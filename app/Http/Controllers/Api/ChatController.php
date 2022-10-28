<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Chat\SendMessageRequest;
use App\Http\Resources\Api\ChatMessage\ChatMessageResource;
use App\Http\Resources\Api\User\UserResource;
use App\Repositories\Interfaces\ChatRepositoryInterface;
use App\Services\Interfaces\ChatServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct(
        private readonly ChatRepositoryInterface $chatRepository,
        private readonly ChatServiceInterface $chatService,
        private readonly UserServiceInterface $userService,
    )
    {
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function init(): \Illuminate\Http\JsonResponse
    {
        $this->userService->changeStatus(Auth::user(), UserStatus::Online);

        $chatMessages = $this->chatRepository->getLastMessages();
        $usersOnline = $this->chatRepository->getUsersOnline();

        return response()->json([
            'messages' => ChatMessageResource::collection($chatMessages),
            'users' => UserResource::collection($usersOnline)
        ]);
    }

    /**
     * @param SendMessageRequest $request
     * @return ChatMessageResource
     */
    public function sendMessage(SendMessageRequest $request): ChatMessageResource
    {
        $chatMessage = $this->chatService->sendMessage($request);
        return ChatMessageResource::make($chatMessage);
    }
}
