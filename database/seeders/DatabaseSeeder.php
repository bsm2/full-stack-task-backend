<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');

        $user = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
        ]);

        $user->assignRole('user');

        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'User@123',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => 'User@123',
            ],
            [
                'name' => 'Ahmed Ali',
                'email' => 'ahmed@example.com',
                'password' => 'User@123',
            ],
            [
                'name' => 'Sara Johnson',
                'email' => 'sara@example.com',
                'password' => 'User@123',
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
            $user->assignRole('user');
        }

        $this->call([
            TaskSeeder::class,
        ]);
    }
}
