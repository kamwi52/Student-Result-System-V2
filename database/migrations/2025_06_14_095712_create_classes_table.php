<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('academic_session_id')->constrained('academic_sessions')->onDelete('cascade');
            // The old, problematic user_id is intentionally left out.
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('classes');
    }
};