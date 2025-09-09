<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder aggressively ensures the admin user exists and has the correct role.
     * It will find an existing user by email and forcefully update their role to 'admin',
     * or it will create a new admin user if one does not exist.
     */
    public function run(): void
    {
        // Find the user by email, or prepare to create a new one.
        $admin = User::firstOrNew(['email' => 'admin@app.com']);

        // Set or forcefully overwrite the user's attributes.
        $admin->name = 'System Administrator';
        $admin->password = Hash::make('password'); // This will reset the password on every deploy.
        $admin->role = 'admin'; // This is the critical line that forces the role.

        // Save the record to the database.
        $admin->save();
    }
}