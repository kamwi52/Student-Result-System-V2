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
            // Add the foreign key for the class section
            // It's constrained and set to cascadeOnDelete (if class is deleted, results are too)
            // It's NOT NULL because our controller validation requires it for new results.
            $table->foreignId('class_section_id')->constrained('class_sections')->cascadeOnDelete()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropForeign(['class_section_id']); // Drop the foreign key constraint
            $table->dropColumn('class_section_id');    // Drop the column itself
        });
    }
};