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
            // Add the subject_id column, constrained to the 'subjects' table.
            // If a Subject is deleted, all its assignments will be deleted too.
            $table->foreignId('subject_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->after('id'); // Optional: Places the column neatly after the id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // The proper way to remove a foreign key column
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
        });
    }
};