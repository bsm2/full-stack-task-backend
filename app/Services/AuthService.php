<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class AuthService
{

    /**
     * Attempt to authenticate a user
     * @param array $credentials
     * @throws \Illuminate\Auth\AuthenticationException
     * @return array{roles: mixed, token: string, user: User}
     */
    public function login(array $credentials): array
    {
        $user = User::firstWhere('email', $credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new AuthenticationException('Invalid credentials');
        }

        $token = $user->load('roles')->createToken('auth-token')->plainTextToken;
        return [
            'token' => $token,
            'user'  => $user
        ];
    }

    /**
     * Register a new user
     */
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $user->assignRole('user');

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $user,
            'roles' => $user->roles
        ];
    }

    /**
     * Logout the user by revoking the current token
     */
    public function logout(User $user): void
    {
        // currentAccessToken() may return null in some contexts; guard it
        /** @var \Laravel\Sanctum\PersonalAccessToken|null $token */
        $token = $user->currentAccessToken();
        if ($token) {
            $token->delete();
        }
    }
}
