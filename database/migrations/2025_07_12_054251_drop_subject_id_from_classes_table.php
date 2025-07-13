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
        Schema::table('classes', function (Blueprint $table) {
            // Drop the foreign key constraint first
            // Use dropConstrainedForeignId for columns created with foreignId()->constrained()
            $table->dropConstrainedForeignId('subject_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            // If you ever need to rollback, this will re-add the column.
            // Note: Re-adding a NOT NULL column might cause issues if data already exists
            // without a subject_id. For production, you might want to consider nullable
            // or a default value on rollback, or just remove the down() logic if you're sure.
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
        });
    }
};