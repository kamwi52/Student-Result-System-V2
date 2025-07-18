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
    Schema::table('results', function (Blueprint $table) {
        $table->foreignId('assignment_id')->constrained()->cascadeOnDelete()->after('class_section_id');
    });
}
public function down(): void
{
    Schema::table('results', function (Blueprint $table) {
        $table->dropForeign(['assignment_id']);
        $table->dropColumn('assignment_id');
    });
}
};
