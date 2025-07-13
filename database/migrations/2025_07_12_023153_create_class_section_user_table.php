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
        // This creates our missing pivot table
        Schema::create('class_section_user', function (Blueprint $table) {
            // This is not an auto-incrementing ID. It's a composite key.
            // We don't need a separate 'id' column.

            // The foreign key for the User model
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // The foreign key for the ClassSection model
            $table->foreignId('class_section_id')->constrained()->onDelete('cascade');

            // Define a composite primary key. This also ensures a teacher
            // cannot be assigned to the same class more than once.
            $table->primary(['user_id', 'class_section_id']);
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