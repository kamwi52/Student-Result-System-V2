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
        // This table links a User (teacher) to a Subject they are qualified for.
        Schema::create('subject_user', function (Blueprint $table) {
            // Foreign key for the user (teacher)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Foreign key for the subject
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');

            // Set the primary key to be the combination of the two foreign keys
            // This prevents a teacher from being linked to the same subject twice.
            $table->primary(['user_id', 'subject_id']);
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