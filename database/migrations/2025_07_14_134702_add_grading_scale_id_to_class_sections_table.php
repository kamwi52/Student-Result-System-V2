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
            // Add the new column
            $table->foreignId('grading_scale_id')
                  ->nullable() // Make it optional
                  ->constrained('grading_scales') // Links to the 'id' on the 'grading_scales' table
                  ->onDelete('set null'); // If a grading scale is deleted, set this column to NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_sections', function (Blueprint $table) {
            // This allows you to undo the migration
            $table->dropForeign(['grading_scale_id']);
            $table->dropColumn('grading_scale_id');
        });
    }
};