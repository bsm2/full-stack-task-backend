<?php

namespace App\Services;

use App\Models\Task;
use App\Notifications\TaskCreated;
use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function __construct(protected TaskRepository $repo) {}

    public function list($request)
    {
        $params = $request->all();
        $current = request()->user('sanctum');

        if ($current && !$current->hasRole('admin')) {
            $params['user_id'] = $current->id;
        }

        $tasks = $this->repo->list($params);

        return [
            'total' => $tasks->total(),
            'data' => $tasks->items()
        ];
    }

    public function create(array $data)
    {
        $task = $this->repo->create($data);
        $task->user->notify(new TaskCreated($task));
        return $task;
    }

    public function show(Task $task)
    {
        return $task;
    }

    public function update(Task $task, array $data)
    {
        return $this->repo->update($task, $data);
    }

    public function delete(Task $task)
    {
        return $this->repo->delete($task);
    }
}