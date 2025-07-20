<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            // Add the foreign key column after 'subject_id'
            $table->foreignId('class_section_id')->nullable()->after('subject_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropForeign(['class_section_id']);
            $table->dropColumn('class_section_id');
        });
    }
};