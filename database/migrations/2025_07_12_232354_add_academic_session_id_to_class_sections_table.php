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
        Schema::table('class_sections', function (Blueprint $table) {
            // Add the foreign key for the academic session.
            // We'll place it after the 'name' column for organization.
            $table->foreignId('academic_session_id')->nullable()->constrained('academic_sessions')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_sections', function (Blueprint $table) {
            $table->dropForeign(['academic_session_id']);
            $table->dropColumn('academic_session_id');
        });
    }
};