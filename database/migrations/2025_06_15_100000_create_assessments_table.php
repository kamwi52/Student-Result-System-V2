<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This creates the 'assessments' table, which will store the different
     * types of tests or assignments (e.g., Mid-Term Exam, Final Exam).
     */
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // e.g., "Mid-Term Exam", "Quiz 1"
            $table->unsignedInteger('max_marks'); // The maximum possible score for this assessment
            $table->unsignedInteger('weightage'); // The percentage weight towards the final grade
            $table->timestamps(); // created_at and updated_at

            // An assessment belongs to an academic session
            // This ensures Mid-Terms for 2024 are separate from Mid-Terms for 2025
            $table->foreignId('academic_session_id')
                  ->constrained('academic_sessions')
                  ->onDelete('cascade'); // If a session is deleted, delete its assessments
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};