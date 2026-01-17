<?php

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'admin']);
    $this->user = User::factory()->create()->assignRole('admin');;
    $this->actingAs($this->user);

    $this->task = Task::factory()->create([
        'title' => 'Run reports',
        'status' => 'done',
        'priority' => 'low',
        'due_date' => '2025-10-30',
        'user_id' => $this->user->id,
    ]);

    Task::factory()->create([
        'title' => 'Running analytics',
        'status' => 'done',
        'priority' => 'low',
        'due_date' => '2025-10-30',
        'user_id' => $this->user->id,
    ]);

    Task::factory()->create([
        'title' => 'Write docs',
        'status' => 'pending',
        'priority' => 'high',
        'due_date' => '2025-11-01',
        'user_id' => $this->user->id,
    ]);
});

it('search tasks by keyword', function () {
    $response = $this->getJson('/api/tasks?search=run');

    $response->assertStatus(200)
        ->assertJsonCount(0, 'data.data');
});

it('returns empty array for unmatched search', function () {
    $response = $this->getJson('/api/tasks?search=nonexistent&status=done&priority=low&due_date_start=2025-10-29&due_date_end=2025-10-31');

    $response->assertStatus(200)
        ->assertJson(['data' => []]);
});

it('filters tasks by status only', function () {
    $response = $this->getJson('/api/tasks?status=pending');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data.data');
});

it('filters tasks by priority only', function () {
    $response = $this->getJson('/api/tasks?priority=high');
    $response->assertStatus(200)
        ->assertJsonCount(1, 'data.data');
});

it('filters tasks by due date range', function () {
    $response = $this->getJson('/api/tasks?due_date_start=2025-10-29&due_date_end=2025-10-31');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data.data');
});

it('can create a new task', function () {
    $payload = [
        'title' => 'New Task',
        'description' => 'Task description',
        'status' => 'pending',
        'priority' => 'high',
        'due_date' => '2025-10-31',
    ];

    $response = $this->postJson('/api/tasks', $payload);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'title' => 'New Task',
            'description' => 'Task description',
            'status' => 'pending',
            'priority' => 'high',
        ]);

    $this->assertDatabaseHas('tasks', [
        'title' => 'New Task',
        'description' => 'Task description',
        'status' => 'pending',
        'priority' => 'high',
    ]);
});

it('can update an existing task', function () {
    $payload = [
        'title' => 'Updated Task',
        'description' => 'Updated description',
        'status' => 'done',
        'priority' => 'low',
        'due_date' => '2025-11-01',
    ];

    $response = $this->putJson("/api/tasks/{$this->task->id}", $payload);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'title' => 'Updated Task',
            'description' => 'Updated description',
            'status' => 'done',
            'priority' => 'low',
        ]);

    $this->assertDatabaseHas('tasks', [
        'id' => $this->task->id,
        'title' => 'Updated Task',
        'description' => 'Updated description',
        'status' => 'done',
        'priority' => 'low',
    ]);
});