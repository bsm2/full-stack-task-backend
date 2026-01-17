<?php

namespace App\Repositories;

use App\Models\Task;
use App\Notifications\TaskCreated;
use App\Notifications\TaskCompleted;
use Illuminate\Support\Facades\Auth;

class TaskRepository
{
    /**
     * Return a paginated list of tasks with optional filters.
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Collection<int, Task>|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function list(array $params = [])
    {
        $query = Task::query()->with('user');

        if (!empty($params['search'])) {
            $query->search($params['search']);
        }

        if (!empty($params['status'])) {
            $query->filterByStatus($params['status']);
        }

        if (!empty($params['priority'])) {
            $query->filterByPriority($params['priority']);
        }

        if (!empty($params['due_date_start']) || !empty($params['due_date_end'])) {
            $start = $params['due_date_start'] ?? null;
            $end = $params['due_date_end'] ?? null;
            $query->filterByDueDate($start, $end);
        }

        if (!empty($params['user_id'])) {
            $query->where('user_id', $params['user_id']);
        }

        $perPage = isset($params['per_page']) ? (int) $params['per_page'] : 12;

        return $query->sorted()->paginate($perPage);
    }

    public function create(array $data)
    {
        if (!isset($data['user_id'])) {
            $data += ['user_id' => Auth::id()];
        }
        return Task::create($data + ['user_id' => Auth::id()]);
    }

    public function update(Task $task, array $data)
    {
        $original = $task->getOriginal();
        $task->update($data);

        if (isset($data['status']) && $data['status'] === 'done' && $original['status'] !== 'done') {
            $task->user->notify(new TaskCompleted($task));
        }

        return $task;
    }

    public function delete(Task $task)
    {
        return $task->delete();
    }
}
