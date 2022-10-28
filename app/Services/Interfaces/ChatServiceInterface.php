<?php

namespace App\Services\Interfaces;

use App\Http\Requests\Api\Chat\SendMessageRequest;
use App\Models\ChatMessage;

interface ChatServiceInterface
{
    public function sendMessage(SendMessageRequest $request): ChatMessage;
}
