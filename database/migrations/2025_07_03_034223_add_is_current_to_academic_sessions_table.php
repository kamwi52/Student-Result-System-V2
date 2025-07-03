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
    Schema::table('academic_sessions', function (Blueprint $table) {
        // Add a boolean column, default it to false (0)
        $table->boolean('is_current')->default(false)->after('name');
    });
}

public function down(): void
{
    Schema::table('academic_sessions', function (Blueprint $table) {
        $table->dropColumn('is_current');
    });
}
};
