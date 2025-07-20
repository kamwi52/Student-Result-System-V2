<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'email_verified_at' => now(), // Mark email as verified
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // === Create the Teacher User ===
        User::create([
            'name' => 'teach1',
            'email' => 'teach1@test.com',
            'email_verified_at' => now(), // Mark email as verified
            'role' => 'teacher',

            'password' => Hash::make('password'),
        ]);

        // === Create the Student User ===
        User::create([
            'name' => 'student1',
            'email' => 'stude@test.com',
            'email_verified_at' => now(), // Mark email as verified
            'role' => 'student',
            'password' => Hash::make('password'),
        ]);

        // You can create more users using factories if you wish
        // User::factory(10)->create();

        // === THE FIX: Call the GradingScaleSeeder ===
        // This will run our new seeder to create the default grading systems.
        $this->call(GradingScaleSeeder::class);
    }
}