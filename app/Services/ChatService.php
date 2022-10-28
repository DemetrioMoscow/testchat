<?php

namespace App\Services;

use App\Events\ChatMessageSent;
use App\Http\Requests\Api\Chat\SendMessageRequest;
use App\Models\ChatMessage;
use App\Services\Interfaces\ChatServiceInterface;
use Illuminate\Support\Facades\Auth;

class ChatService implements ChatServiceInterface
{
    /**
     * @param SendMessageRequest $request
     * @return ChatMessage
     */
    public function sendMessage(SendMessageRequest $request): ChatMessage
    {
        $chatMessage = new ChatMessage($request->validated());
        $chatMessage->user()->associate(Auth::user());
        $chatMessage->save();

        event(new ChatMessageSent($chatMessage));

        return $chatMessage;
    }
}
