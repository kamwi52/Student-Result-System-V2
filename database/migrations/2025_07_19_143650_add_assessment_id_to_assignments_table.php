<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method is intentionally left blank because the column already exists.
     */
    public function up(): void
    {
        // This method is intentionally left blank.
        // DO NOT put any code here for now.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // You might need to drop foreign keys before dropping the column on rollback
            // $table->dropForeign(['assessment_id']);
            $table->dropColumn('assessment_id');
        });
    }
};