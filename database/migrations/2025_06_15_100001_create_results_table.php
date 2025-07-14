<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration defines the blueprint for the `results` table.
     * Each foreign key MUST point to the correct parent table.
     */
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();

            // This result belongs to a user.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // This result is for a specific assessment.
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');

            // This result was submitted in a specific class.
            // This is the most likely source of the error. It MUST point to `class_sections`.
            $table->foreignId('class_id')->constrained('class_sections')->onDelete('cascade');
            
            $table->decimal('score', 8, 2); // Allows for scores like 95.50
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Optional: Ensure a student can only have one result per assessment.
            // This also improves lookup performance.
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