<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration introduces a dedicated 'assignments' table to link a
     * teacher to a specific subject within a specific class, and removes
     * the old, limiting 'teacher_id' from the class_sections table.
     */
    public function up(): void
    {
        // First, create the new 'assignments' table
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys to the other tables
            $table->foreignId('class_section_id')->constrained('class_sections')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('user_id')->comment('This is the teacher ID')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();

            // Add a unique constraint to prevent assigning the same teacher to the
            // same subject in the same class more than once.
            $table->unique(['class_section_id', 'subject_id'], 'class_subject_unique_assignment');
        });

        // Next, drop the old foreign key and column from the class_sections table
        Schema::table('class_sections', function (Blueprint $table) {
            // First, drop the foreign key constraint if it exists
            // The name 'class_sections_teacher_id_foreign' is a Laravel convention.
            // If your key has a different name, you may need to adjust this.
            $table->dropForeign(['teacher_id']);
            
            // Then, drop the column itself
            $table->dropColumn('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     * This method allows us to undo the changes if needed.
     */
    public function down(): void
    {
        // First, add back the old column to the class_sections table
        Schema::table('class_sections', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
        });

        // Then, drop the new 'assignments' table
        Schema::dropIfExists('assignments');
    }
};