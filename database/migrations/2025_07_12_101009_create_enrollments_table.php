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
        Schema::create('enrollments', function (Blueprint $table) {
            // This links to the users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // THIS IS THE MOST LIKELY SOURCE OF THE ERROR.
            // It MUST constrain to the 'class_sections' table.
            $table->foreignId('class_section_id')->constrained('class_sections')->onDelete('cascade');

            // This ensures a student can't be enrolled in the same class twice.
            $table->primary(['user_id', 'class_section_id']);
            
            // Timestamps are good practice but optional for a pivot table.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};