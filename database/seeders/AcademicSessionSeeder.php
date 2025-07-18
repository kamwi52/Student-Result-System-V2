<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcademicSession; // <-- Import the model

class AcademicSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AcademicSession::updateOrCreate(
            ['name' => '2024-2025 Academic Year'],
            [
                'start_date' => '2024-09-01',
                'end_date' => '2025-06-30',
            ]
        );

        AcademicSession::updateOrCreate(
            ['name' => '2025-2026 Academic Year'],
            [
                'start_date' => '2025-09-01',
                'end_date' => '2026-06-30',
            ]
        );
    }
}