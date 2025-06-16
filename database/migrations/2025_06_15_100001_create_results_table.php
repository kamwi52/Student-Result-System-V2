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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('class_section_id')->constrained('classes')->onDelete('cascade');
            
            // ADD THIS LINE BACK IN
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            
            $table->integer('marks_obtained');
            $table->timestamps();

            // UPDATE THE UNIQUE KEY TO INCLUDE THE ASSESSMENT ID
            $table->unique(['user_id', 'class_section_id', 'assessment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};