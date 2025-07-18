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
            // Add the new column. We'll make it a 'date' type.
            // ->after('max_marks') is optional but keeps the table organized.
            $table->date('assessment_date')->nullable()->after('max_marks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            // This tells Laravel how to undo the migration
            $table->dropColumn('assessment_date');
        });
    }
};