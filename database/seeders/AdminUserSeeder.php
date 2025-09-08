<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Ensure the User model is imported

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder ensures a default administrator account exists in the database.
     * It uses updateOrCreate(), which safely finds an existing user by email
     * or creates a new one if they don't exist, preventing duplicate admin accounts.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                // This is the unique identifier to find the user.
                'email' => 'admin@app.com',
            ],
            [
                // These are the values to set if the user is created or updated.
                'name'     => 'System Administrator',
                'password' => Hash::make('password'), // IMPORTANT: Change 'password' to a strong, secure password.
                'role'     => 'admin',                // This grants administrator privileges.
            ]
        );
    }
}