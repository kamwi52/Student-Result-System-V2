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
        Schema::create('subject_user', function (Blueprint $table) {
            $table->id();
            // Foreign key to the 'users' table (for teachers)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // Foreign key to the 'subjects' table
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->timestamps(); // Adds created_at and updated_at columns

            // Ensure a teacher is only linked to a subject once
            $table->unique(['user_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_user');
    }
};