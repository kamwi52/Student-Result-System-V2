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
        // Use Schema::table() to modify an existing table
        Schema::table('class_sections', function (Blueprint $table) {
            $table->foreignId('teacher_id')
                  ->nullable() // Allows the "Unassigned" option
                  ->constrained('users') // Links to the 'id' on the 'users' table
                  ->onDelete('set null'); // If a teacher is deleted, set this class's teacher_id to NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_sections', function (Blueprint $table) {
            // Important to properly define how to drop the foreign key
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }
};