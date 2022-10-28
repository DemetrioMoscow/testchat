<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Events\UserStatusUpdated;
use App\Exceptions\Auth\WrongPasswordException;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    /**
     * @param User $user
     * @param string $tokenName
     * @return string
     */
    public function createToken(User $user, string $tokenName): string
    {
        return $user->createToken($tokenName)->plainTextToken;
    }

    /**
     * @param RegisterRequest $request
     * @return User
     */
    public function register(RegisterRequest $request): User
    {
        $user = new User($request->validated());
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return $user;
    }

    /**
     * @param LoginRequest $request
     * @return User
     * @throws WrongPasswordException
     */
    public function login(LoginRequest $request): User
    {
        /** @var User $user */
        $user = User::query()
            ->where('name', $request->validated('name'))
            ->firstOrFail();

        if (!Auth::validate([
            'id' => $user->id,
            'password' => $request->input('password')
        ])) {
            throw new WrongPasswordException(__('validation.current_password'));
        }

        Auth::login($user, true);

        return $user;
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $this->changeStatus(Auth::user(), UserStatus::Offline);
        Auth::user()->tokens()->delete();
        Auth::guard('web')->logout();
    }

    /**
     * @param User $user
     * @param UserStatus $userStatus
     * @return void
     */
    public function changeStatus(User $user, UserStatus $userStatus): void
    {
        $user->update([
            'status' => $userStatus,
        ]);

        event(new UserStatusUpdated($user));
    }
}
