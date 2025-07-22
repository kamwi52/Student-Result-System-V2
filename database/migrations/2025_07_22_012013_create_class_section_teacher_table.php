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
        Schema::create('class_section_teacher', function (Blueprint $table) {
            // This is the primary key for the pivot table itself.
            $table->id();

            // This column will store the ID of the class.
            // It's a foreign key that links to the 'class_sections' table.
            $table->foreignId('class_section_id')->constrained()->onDelete('cascade');

            // This column will store the ID of the teacher (a user).
            // It's a foreign key that links to the 'users' table.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Optional: Add timestamps if you want to know when a teacher was assigned.
            // $table->timestamps();

            // Ensure that a teacher can only be assigned to a class once.
            $table->unique(['class_section_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_section_teacher');
    }
};