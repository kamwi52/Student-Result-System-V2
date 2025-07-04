<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            
            // This is the fix! We tell it to reference the 'classes' table that actually exists.
            $table->foreignId('school_class_id')->constrained('classes')->onDelete('cascade');
            
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamps();

            // The unique key to prevent duplicate entries
            $table->unique(['student_id', 'school_class_id', 'assessment_id'], 'student_class_assessment_unique');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};