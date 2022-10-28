<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Auth\WrongPasswordException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Services\Interfaces\UserServiceInterface;

class AuthController extends Controller
{
    public function __construct(
        private readonly UserServiceInterface $userService
    )
    {
    }

    /**
     * @param LoginRequest $request
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): UserResource|\Illuminate\Http\JsonResponse
    {
        try {
            $user = $this->userService->login($request);
            $token = $this->userService->createToken($user, $request->header('User-Agent', 'api'));

            return UserResource::make($user)
                ->additional([
                    'token' => $token,
                ]);
        } catch (WrongPasswordException $e) {
            return response()->json([
                'errors' => [
                    'password' => [
                        $e->getMessage()
                    ],
                ],
            ], 422);
        }
    }

    /**
     * @param RegisterRequest $request
     * @return UserResource
     */
    public function register(RegisterRequest $request): UserResource
    {
        $user = $this->userService->register($request);
        $token = $this->userService->createToken($user, $request->header('User-Agent', 'api'));

        return UserResource::make($user)
            ->additional([
                'token' => $token,
            ]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function logout(): \Illuminate\Http\Response
    {
        $this->userService->logout();

        return response()->noContent();
    }
}
