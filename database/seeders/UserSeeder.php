<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Manager
        User::create([
            'name' => 'Manager One',
            'email' => 'manager@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'manager',
        ]);

        // Normal Users
        User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'User Three',
            'email' => 'user3@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
        ]);
    }
}
