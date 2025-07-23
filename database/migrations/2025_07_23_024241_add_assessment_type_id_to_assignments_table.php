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
            // Add the new column. It should be nullable if some assignments might not have a type.
            // We'll place it after the 'assessment_id' column for organization.
            $table->foreignId('assessment_type_id')->nullable()->after('assessment_id')->constrained('assessment_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // To drop a foreign key, you must drop the constraint first, then the column.
            // The constraint name is typically 'table_column_foreign'.
            $table->dropForeign(['assessment_type_id']);
            $table->dropColumn('assessment_type_id');
        });
    }
};