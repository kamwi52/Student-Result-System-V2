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
        Schema::table('class_section_subject', function (Blueprint $table) {
            $table->timestamps(); // This adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_section_subject', function (Blueprint $table) {
            $table->dropTimestamps(); // This removes them if you roll back
        });
    }
};