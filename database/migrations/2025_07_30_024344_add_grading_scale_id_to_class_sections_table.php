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
            // This adds the missing foreign key column.
            // It's placed 'after' another column for organizational neatness.
            $table->foreignId('grading_scale_id')
                  ->nullable() // Making it nullable temporarily to avoid issues with existing rows
                  ->constrained('grading_scales') // This creates the foreign key constraint
                  ->onDelete('set null') // If a grading scale is deleted, set this to NULL
                  ->after('academic_session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_sections', function (Blueprint $table) {
            // To drop a column with a foreign key, you must drop the key first.
            $table->dropForeign(['grading_scale_id']);
            $table->dropColumn('grading_scale_id');
        });
    }
};