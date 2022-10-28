<?php

namespace App\Services\Interfaces;

use App\Enums\UserStatus;
use App\Exceptions\Auth\WrongPasswordException;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;

interface UserServiceInterface
{
    public function changeStatus(User $user, UserStatus $userStatus): void;

    public function createToken(User $user, string $tokenName): string;

    /**
     * @param LoginRequest $request
     * @return User
     * @throws WrongPasswordException
     */
    public function login(LoginRequest $request): User;

    public function logout(): void;

    public function register(RegisterRequest $request): User;
}
