<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Import the Hash facade

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // === Create the Admin User ===
        User::create([
            'name' => 'east',
            'email' => 'east@test.com',
            'role' => 'admin', // Set the role explicitly
            'password' => Hash::make('password'), // Use a secure password
        ]);

        // === Create the Teacher User ===
        User::create([
            'name' => 'teach1',
            'email' => 'teach1@test.com',
            'role' => 'teacher', // Set the role explicitly
            'password' => Hash::make('password'),
        ]);

        // === Create the Student User ===
        User::create([
            'name' => 'student1',
            'email' => 'stude@test.com',
            'role' => 'student', // Set the role explicitly
            'password' => Hash::make('password'),
        ]);

        // You can create more users using factories if you wish
        // User::factory(10)->create();
    }
}