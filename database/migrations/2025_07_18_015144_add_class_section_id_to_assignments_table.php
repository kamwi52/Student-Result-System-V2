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
            // Adding 'class_section_id' as a foreign key.
            // It's nullable to allow existing assignment records to remain valid.
            // constrained() automatically links to 'class_sections' table.
            // cascadeOnDelete() means if a class is deleted, assignments linked to it are also deleted.
            $table->foreignId('class_section_id')
                  ->nullable() // <-- THIS IS CRUCIAL TO AVOID THE "NOT NULL" ERROR
                  ->constrained()
                  ->cascadeOnDelete()
                  ->after('name'); // Place it after the 'name' column for organization
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // To reverse:
            // 1. Drop the foreign key constraint first.
            // 2. Then, drop the column itself.
            $table->dropForeign(['class_section_id']);
            $table->dropColumn('class_section_id');
        });
    }
};