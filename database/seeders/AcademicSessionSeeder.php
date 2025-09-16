<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicSession;

class AcademicSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =========================================================================
        // === THE DEFINITIVE FIX ================================================
        // This seeder now creates only ONE session and ensures it is active.
        // It first sets all other sessions to inactive to guarantee only one is active.
        // =========================================================================

        // Step 1: Deactivate all existing academic sessions to prevent conflicts.
        AcademicSession::query()->update(['is_active' => false]);

        // Step 2: Create or update the '2025 Academic Year' and set it as the only active session.
        AcademicSession::updateOrCreate(
            ['name' => '2025 Academic Year'], // The unique name to find or create
            [
                'start_date' => '2025-01-01',
                'end_date'   => '2025-12-31',
                'is_active'  => true, // This is the critical part
            ]
        );

        $this->command->info('Academic session "2025 Academic Year" has been created and set as active.');
    }
}