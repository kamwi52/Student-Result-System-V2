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
        // === Create or Update the Admin User ===
        User::updateOrCreate(
            ['email' => 'east@test.com'], // The unique attribute to find the user by
            [
                'name' => 'east',
                'email_verified_at' => now(),
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        // === Create or Update the Teacher User ===
        User::updateOrCreate(
            ['email' => 'teach1@test.com'], // The unique attribute to find the user by
            [
                'name' => 'teach1',
                'email_verified_at' => now(),
                'role' => 'teacher',
                'password' => Hash::make('password'),
            ]
        );

        // === Create or Update the Student User ===
        User::updateOrCreate(
            ['email' => 'stude@test.com'], // The unique attribute to find the user by
            [
                'name' => 'student1',
                'email_verified_at' => now(),
                'role' => 'student',
                'password' => Hash::make('password'),
            ]
        );

        // === Call Other Seeders ===
        $this->call([
            GradingScaleSeeder::class,
            AcademicSessionSeeder::class,
        ]);
    }
}