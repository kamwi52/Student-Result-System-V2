<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This will drop the 'student_id' column if it exists.
     */
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            // Check if the column exists before trying to drop it, to avoid errors on repeated runs.
            if (Schema::hasColumn('results', 'student_id')) {

                // If a foreign key constraint exists on this column, you must drop it first.
                // We'll try to drop a conventionally named foreign key.
                // If this fails, you may need to check your DB for the exact constraint name.
                // However, often just dropping the column is enough if the constraint name is standard.
                try {
                    // Laravel 10+ can infer the foreign key name
                    $table->dropForeign(['student_id']);
                } catch (\Exception $e) {
                    // If dropping the foreign key fails (e.g., non-standard name),
                    // the dropColumn might still work depending on the DB driver,
                    // or you might need to find the exact constraint name in your DB schema.
                    // For now, we proceed to drop the column.
                }

                $table->dropColumn('student_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * This will re-add the 'student_id' column if the migration is rolled back.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            // Re-add the column if we roll back. Make it nullable to prevent future issues.
            if (!Schema::hasColumn('results', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable()->after('id');
            }
        });
    }
};