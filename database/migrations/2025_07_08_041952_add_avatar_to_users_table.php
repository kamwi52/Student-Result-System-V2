<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // This adds the 'avatar' column to the 'users' table after the 'email' column.
            $table->string('avatar')->nullable()->after('email');
        });
    } // <--- THIS IS THE CRITICAL CLOSING BRACE THAT WAS MISSING

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // This tells Laravel how to undo the migration (by dropping the column).
            $table->dropColumn('avatar');
        });
    }
};