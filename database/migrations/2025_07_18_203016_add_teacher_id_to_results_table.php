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
        Schema::table('results', function (Blueprint $table) {
            // Add the teacher_id column as a foreign key to the users table.
            // It can be nullable if a result might sometimes not be explicitly linked to a teacher,
            // or if a teacher is deleted, their association here is set to null.
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null')->after('class_section_id');
            // Using 'after' is optional but helps keep columns logically grouped.
            // If you don't care about column order, you can omit ->after('class_section_id')
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['teacher_id']);
            // Then drop the column
            $table->dropColumn('teacher_id');
        });
    }
};