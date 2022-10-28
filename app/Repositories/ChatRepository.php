<?php

namespace App\Repositories;

use App\Models\ChatMessage;
use App\Models\User;
use App\Repositories\Interfaces\ChatRepositoryInterface;
use Illuminate\Support\Collection;
use Pusher\Pusher;

class ChatRepository implements ChatRepositoryInterface
{
    /**
     * @param int $count
     * @return Collection
     */
    public function getLastMessages(int $count = 30): Collection
    {
        return ChatMessage::query()
            ->with('user')
            ->latest()
            ->limit($count)
            ->get();
    }

    /**
     * @return Collection
     */
    public function getUsersOnline(): Collection
    {
        return User::query()
            ->online()
            ->get();
    }

    /**
     * @return Collection|null
     */
    public function getUsersTimedOut(): Collection|null
    {
        try {
            $connection = config('broadcasting.connections.pusher');
            $pusher = new Pusher(
                $connection['key'],
                $connection['secret'],
                $connection['app_id'],
                $connection['options'] ?? []
            );

            $userIds = collect($pusher->get_users_info('presence-chat')->users)->pluck('id');

            return User::query()
                ->online()
                ->whereNotIn('id', $userIds)
                ->get();

        } catch (\Exception $e) {
            return null;
        }
    }
}
