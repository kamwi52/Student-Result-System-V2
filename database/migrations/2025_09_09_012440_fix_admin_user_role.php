<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // CRITICAL: Import the DB facade
use App\Models\User; // CRITICAL: Import the User model

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration will find the user with the email 'admin@app.com'
     * and forcefully update their role to 'admin'.
     */
    public function up(): void
    {
        // Use a raw DB update for maximum reliability.
        // This finds the user and sets the role directly.
        DB::table('users')
            ->where('email', 'admin@app.com')
            ->update(['role' => 'admin']);
    }

    /**
     * Reverse the migrations.
     * (We don't need to reverse this, but the method must exist)
     */
    public function down(): void
    {
        //
    }
};