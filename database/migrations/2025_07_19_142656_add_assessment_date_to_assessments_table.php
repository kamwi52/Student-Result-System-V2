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
            // Add a 'date' type column. It can be nullable if a date is not always required.
            // The ->after() method is optional but keeps your schema organized.
            $table->date('assessment_date')->nullable()->after('weightage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            // This will remove the column if you ever roll back the migration.
            $table->dropColumn('assessment_date');
        });
    }
};