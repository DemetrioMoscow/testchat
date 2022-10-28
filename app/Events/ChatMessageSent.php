<?php

namespace App\Events;

use App\Http\Resources\Api\ChatMessage\ChatMessageResource;
use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public ChatMessage $chatMessage)
    {
    }

    /**
     * @return array
     */
    public function broadcastWith(): array
    {
        return ChatMessageResource::make($this->chatMessage)->response()->getData(true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): PresenceChannel|array
    {
        return new PresenceChannel('chat');
    }
}
