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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();

            // This links each grade to its parent grading scale.
            // If the parent scale is deleted, all its grades are also deleted.
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
        Schema::dropIfExists('grades');
    }
};