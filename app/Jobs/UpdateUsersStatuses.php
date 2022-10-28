<?php

namespace App\Jobs;

use App\Enums\UserStatus;
use App\Repositories\Interfaces\ChatRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateUsersStatuses
{
    use Dispatchable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function handle(
        ChatRepositoryInterface $chatRepository,
        UserServiceInterface    $userService,
    )
    {
        $chatRepository->getUsersTimedOut()?->each(function ($user) use ($userService) {
            $userService->changeStatus($user, UserStatus::Offline);
        });
    }
}
