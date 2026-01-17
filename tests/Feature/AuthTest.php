<?php

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'user']);
});

it('can register a new user', function () {
    $payload = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Nshbhwsq@123',
        'password_confirmation' => 'Nshbhwsq@123',
    ];

    $response = $this->postJson('/api/register', $payload);
    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => ['token', 'user' => ['id', 'name', 'email', 'created_at', 'updated_at'], 'roles'],
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);
});

it('can login with valid credentials', function () {
    $user = User::factory()->create([
        'password' => 'password',
    ]);

    $payload = [
        'email' => $user->email,
        'password' => 'password',
    ];

    $response = $this->postJson('/api/login', $payload);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => ['token', 'user' => ['id', 'name', 'email', 'created_at', 'updated_at']],

        ]);
});

it('fails to login with invalid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $payload = [
        'email' => $user->email,
        'password' => 'wrongpassword',
    ];

    $response = $this->postJson('/api/login', $payload);

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Invalid credentials',
        ]);
});

it('can logout an authenticated user', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum');

    $response = $this->postJson('/api/logout');

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Logged out successfully',
        ]);
});