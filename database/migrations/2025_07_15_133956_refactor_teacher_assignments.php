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
        // This migration's job is to remove the old one-to-many column.
        Schema::table('class_sections', function (Blueprint $table) {
            // First, drop the foreign key constraint.
            // The array format is needed for SQLite.
            $table->dropForeign(['teacher_id']);
            
            // Then, drop the column itself.
            $table->dropColumn('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // The 'down' method should add the column back if we ever need to rollback.
        Schema::table('class_sections', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->constrained('users');
        });
    }
};