<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function index()
    {
        return $this->userService->list();
    }

    public function store(StoreUserRequest $request)
    {
        return $this->userService->create($request->validated());
    }

    public function show(User $user)
    {
        return $this->userService->findById($user->id);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        return $this->userService->update($user, $request->validated());
    }

    public function destroy(User $user)
    {
        return $this->userService->delete($user);
    }
}
