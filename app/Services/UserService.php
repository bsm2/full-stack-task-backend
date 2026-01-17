<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function list()
    {
        return $this->repo->list();
    }

    public function findById($id)
    {
        return $this->repo->findById($id);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update(User $user, array $data)
    {
        return $this->repo->update($user, $data);
    }

    public function delete(User $user)
    {
        return $this->repo->delete($user);
    }
}