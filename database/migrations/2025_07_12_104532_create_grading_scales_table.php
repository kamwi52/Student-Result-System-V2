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
    Schema::create('grading_scales', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique(); // e.g., "Standard A-F", "Pass/Fail"
        $table->text('description')->nullable();
        // You might add more columns here later, like grade ranges, but this is a good start.
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grading_scales');
    }
};
