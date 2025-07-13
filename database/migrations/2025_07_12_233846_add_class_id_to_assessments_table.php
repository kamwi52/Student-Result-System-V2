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
        Schema::table('assessments', function (Blueprint $table) {
            // This adds the new 'class_id' column to your table.
            // 'after('subject_id')' is optional but keeps the table organized.
            // 'nullable()' makes it optional so old records don't cause errors.
            $table->foreignId('class_id')->nullable()->constrained('class_sections')->after('subject_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            // This tells Laravel how to remove the column if you ever need to reverse the migration.
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });
    }
};