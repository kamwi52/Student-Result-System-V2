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
        Schema::table('assignments', function (Blueprint $table) {
            // Add the teacher_id column, constrained to the 'users' table.
            // An assignment might not have a teacher, so it can be nullable.
            // If a teacher's user account is deleted, the assignment's teacher_id will be set to null.
            $table->foreignId('teacher_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->after('class_section_id'); // Optional: Places it neatly after the last column we added
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // The proper way to remove a foreign key column
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }
};