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
            
            // Link to the user (student)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade'); // Automatically deletes result if user is deleted

            // Link to the assessment
            $table->foreignId('assessment_id')
                  ->constrained('assessments')
                  ->onDelete('cascade'); // Automatically deletes result if assessment is deleted

            $table->decimal('score', 5, 2);
            $table->text('remark')->nullable();
            $table->timestamps();

            // Ensure a student can't have two results for the same assessment
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