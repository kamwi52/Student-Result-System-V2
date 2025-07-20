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
        Schema::table('class_section_subject', function (Blueprint $table) {
            // Add the teacher_id column. It can be null because a subject might be unassigned.
            // It's constrained to the 'users' table.
            // If a teacher is deleted, the assignment becomes NULL instead of breaking.
            $table->foreignId('teacher_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_section_subject', function (Blueprint $table) {
            // This is the proper way to remove a foreign key column
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }
};