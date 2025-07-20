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
            // Add the class_section_id column, constrained to the 'class_sections' table.
            // If a Class Section is deleted, its assignments should also be deleted.
            $table->foreignId('class_section_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->after('subject_id'); // Optional: Places it neatly after the last column we added
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // The proper way to remove a foreign key column
            $table->dropForeign(['class_section_id']);
            $table->dropColumn('class_section_id');
        });
    }
};