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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            // Student ID (User)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Assessment ID
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            // Score
            $table->integer('score')->nullable(); // Score can be null if not yet graded
            // Comments (formerly remarks)
            $table->text('comments')->nullable();
            // Teacher ID (who recorded the result)
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            // Class Section ID
            $table->foreignId('class_section_id')->nullable()->constrained()->onDelete('cascade'); // Make class_section_id nullable

            $table->timestamps();

            // Ensure a student can only have one result per assessment
            $table->unique(['user_id', 'assessment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};