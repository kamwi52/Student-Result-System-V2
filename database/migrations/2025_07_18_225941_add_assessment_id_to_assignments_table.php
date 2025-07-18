<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // Add the assessment_id column. It should be unique because of the 1-to-1 relationship.
            // It links to the 'assessments' table and will cascade delete.
            $table->foreignId('assessment_id')->unique()->constrained('assessments')->onDelete('cascade')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['assessment_id']);
            $table->dropColumn('assessment_id');
        });
    }
};