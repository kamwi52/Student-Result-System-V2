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
        // This is the standard naming convention for a pivot table.
        Schema::create('class_section_user', function (Blueprint $table) {
            
            // First foreign key for the user
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Second foreign key for the class_section
            // This now correctly references the 'class_sections' table.
            $table->foreignId('class_section_id')->constrained('class_sections')->onDelete('cascade');

            // Set the primary key to be the combination of the two foreign keys.
            $table->primary(['user_id', 'class_section_id']);
            
            // No need for id() or timestamps() on a simple pivot table.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_section_user');
    }
};