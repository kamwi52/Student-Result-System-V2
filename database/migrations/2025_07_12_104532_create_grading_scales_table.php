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
        // STEP 1: Create the parent 'grading_scales' table FIRST.
        Schema::create('grading_scales', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'Standard Letter Grades', 'Pass/Fail'
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // STEP 2: Now that 'grading_scales' exists, create the 'grades' table that links to it.
        Schema::create('grades', function (Blueprint $table) {
            $table->id();

            // This links each grade to its parent grading scale.
            // This will now work because the 'grading_scales' table exists.
            $table->foreignId('grading_scale_id')->constrained('grading_scales')->onDelete('cascade');

            $table->string('grade_name');       // The name of the grade, e.g., 'A+', 'B', 'Pass'
            $table->unsignedInteger('min_score');   // The minimum score for this grade, e.g., 90
            $table->unsignedInteger('max_score');   // The maximum score for this grade, e.g., 100
            $table->string('remark')->nullable(); // The auto-generated remark, e.g., 'Excellent'
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in the reverse order of creation to respect foreign keys.
        Schema::dropIfExists('grades');
        Schema::dropIfExists('grading_scales');
    }
};