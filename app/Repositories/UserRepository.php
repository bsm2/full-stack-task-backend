<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function list($includeRoles = true)
    {
        $query = User::query();

        if ($includeRoles) {
            $query->with('roles');
        }

        return $query->latest()->paginate();
    }

    public function findById($id)
    {
        return User::with('roles')->findOrFail($id);
    }

    public function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user->load('roles');
    }

    public function update(User $user, array $data)
    {
        $user->update(array_filter($data));

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user->load('roles');
    }

    public function delete(User $user)
    {
        return $user->delete();
    }
}
