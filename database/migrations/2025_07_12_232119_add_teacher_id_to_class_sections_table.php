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
        Schema::table('class_sections', function (Blueprint $table) {
            // We add the foreign key for the teacher.
            // It's nullable in case a class can exist without a teacher temporarily.
            // The 'after' method is just for organization in the database table.
            $table->foreignId('teacher_id')->nullable()->constrained('users')->after('academic_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_sections', function (Blueprint $table) {
            // This will properly remove the column and its foreign key.
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }
};