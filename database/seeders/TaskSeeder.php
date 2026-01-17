<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $users = User::factory()->count(5)->create();
        }

        $tasks = [
            [
                'title' => 'Prepare project proposal',
                'description' => 'Draft the initial project proposal for client review.',
                'status' => 'pending',
                'priority' => 'high',
                'due_date' => Carbon::now()->addDays(5),
            ],
            [
                'title' => 'Develop authentication module',
                'description' => 'Implement login, registration, and JWT authentication.',
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => Carbon::now()->addDays(10),
            ],
            [
                'title' => 'Create database migrations',
                'description' => 'Define migrations for users, tasks, and activity logs.',
                'status' => 'done',
                'priority' => 'medium',
                'due_date' => Carbon::now()->subDays(2),
            ],
            [
                'title' => 'Write API documentation',
                'description' => 'Use Swagger to document all public API endpoints.',
                'status' => 'pending',
                'priority' => 'medium',
                'due_date' => Carbon::now()->addDays(7),
            ],
            [
                'title' => 'Set up CI/CD pipeline',
                'description' => 'Configure GitHub Actions for automated testing and deployment.',
                'status' => 'in_progress',
                'priority' => 'low',
                'due_date' => Carbon::now()->addDays(14),
            ],
            [
                'title' => 'Conduct code review session',
                'description' => 'Review the team’s code to ensure adherence to standards.',
                'status' => 'pending',
                'priority' => 'medium',
                'due_date' => Carbon::now()->addDays(3),
            ],
            [
                'title' => 'Optimize database queries',
                'description' => 'Identify and optimize slow-performing SQL queries.',
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => Carbon::now()->addDays(8),
            ],
            [
                'title' => 'Prepare project presentation',
                'description' => 'Create slides for client demo including progress and next steps.',
                'status' => 'pending',
                'priority' => 'low',
                'due_date' => Carbon::now()->addDays(12),
            ],
            [
                'title' => 'Fix API response structure',
                'description' => 'Ensure consistent API response format across all endpoints.',
                'status' => 'done',
                'priority' => 'medium',
                'due_date' => Carbon::now()->subDay(),
            ],
            [
                'title' => 'Setup task audit logging',
                'description' => 'Implement automatic logging for task create/update/delete actions.',
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => Carbon::now()->addDays(6),
            ],
            [
                'title' => 'Complete project documentation',
                'description' => 'Write the full documentation for the API and frontend integration.',
                'status' => 'pending',
                'priority' => 'high',
                'due_date' => Carbon::now()->addDays(5),
            ],
            [
                'title' => 'Fix login page issue',
                'description' => 'Resolve the session timeout problem affecting user logins.',
                'status' => 'in_progress',
                'priority' => 'medium',
                'due_date' => Carbon::now()->addDays(2),
            ],
            [
                'title' => 'Prepare weekly meeting slides',
                'description' => 'Create a short presentation for the team sync on Monday.',
                'status' => 'done',
                'priority' => 'low',
                'due_date' => Carbon::now()->subDays(1),
            ],
            [
                'title' => 'Update task API endpoint',
                'description' => 'Add pagination and filtering to the task listing endpoint.',
                'status' => 'pending',
                'priority' => 'medium',
                'due_date' => Carbon::now()->addDays(3),
            ],
            [
                'title' => 'Design dashboard UI',
                'description' => 'Create wireframes for the new dashboard layout.',
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => Carbon::now()->addDays(10),
            ],
        ];

        foreach ($tasks as $task) {
            Task::create(array_merge($task, [
                'user_id' => $users->random()->id,
            ]));
        }
    }
}
