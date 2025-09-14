<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcademicSession; // Your import is correct

class AcademicSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This session is created but marked as not active.
        AcademicSession::updateOrCreate(
            ['name' => '2024-2025 Academic Year'],
            [
                'start_date' => '2024-09-01',
                'end_date'   => '2025-06-30',
                'is_active'  => false, 
            ]
        );

        // =========================================================================
        // === THE DEFINITIVE IMPROVEMENT ========================================
        // We are now explicitly setting this session as the active one.
        // =========================================================================
        AcademicSession::updateOrCreate(
            ['name' => '2025 Academic Year'], // Use the exact name from your CSVs
            [
                'start_date' => '2025-09-01',
                'end_date'   => '2026-06-30',
                'is_active'  => true, // This is the critical addition
            ]
        );
    }
}