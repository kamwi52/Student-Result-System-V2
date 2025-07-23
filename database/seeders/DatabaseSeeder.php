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
        User::firstOrCreate(
            ['email' => 'east@test.com'],
            ['name' => 'east', 'email_verified_at' => now(), 'role' => 'admin', 'password' => Hash::make('password')]
        );
        User::firstOrCreate(
            ['email' => 'teach1@test.com'],
            ['name' => 'teach1', 'email_verified_at' => now(), 'role' => 'teacher', 'password' => Hash::make('password')]
        );
        User::firstOrCreate(
            ['email' => 'stude@test.com'],
            ['name' => 'student1', 'email_verified_at' => now(), 'role' => 'student', 'password' => Hash::make('password')]
        );
        
        $this->call([
            GradingScaleSeeder::class,
            TermSeeder::class, // Renamed from AssessmentTypeSeeder
        ]);
    }
}