<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GradingScale;
use Illuminate\Support\Facades\DB;

class GradingScaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // --- Create the Junior Secondary Grading Scale ---
            // Use firstOrCreate to find or create the main grading scale.
            // The method returns the found or newly created model instance.
            $juniorScale = GradingScale::firstOrCreate(
                ['name' => 'Junior Secondary (Formative)'], // Attribute to find by
                ['description' => 'Standard grading for Grades 8-9 based on percentages.'] // Additional attributes if creating
            );

            // Check if the scale was just created to avoid adding duplicate grades.
            if ($juniorScale->wasRecentlyCreated) {
                $juniorScale->grades()->createMany([
                    ['grade_name' => 'Distinction', 'min_score' => 75, 'max_score' => 100, 'remark' => 'Outstanding Achievement'],
                    ['grade_name' => 'Merit',       'min_score' => 60, 'max_score' => 74,  'remark' => 'Commendable Achievement'],
                    ['grade_name' => 'Credit',      'min_score' => 50, 'max_score' => 59,  'remark' => 'Satisfactory Achievement'],
                    ['grade_name' => 'Pass',        'min_score' => 40, 'max_score' => 49,  'remark' => 'Acceptable Achievement'],
                    ['grade_name' => 'Fail',        'min_score' => 0,  'max_score' => 39,  'remark' => 'Unsatisfactory'],
                ]);
            }

            // --- Create the Senior Secondary Grading Scale ---
            // Use firstOrCreate again for the second scale.
            $seniorScale = GradingScale::firstOrCreate(
                ['name' => 'Senior Secondary (Exam)'], // Attribute to find by
                ['description' => 'Standard grading for Grades 10-12 based on a 1-9 numeric scale.'] // Additional attributes if creating
            );

            // Check if this scale was just created before adding its grades.
            if ($seniorScale->wasRecentlyCreated) {
                // Note: The percentage ranges here are a common interpretation.
                // You can adjust these min/max scores as needed.
                $seniorScale->grades()->createMany([
                    ['grade_name' => '1', 'min_score' => 80, 'max_score' => 100, 'remark' => 'Distinction'],
                    ['grade_name' => '2', 'min_score' => 75, 'max_score' => 79,  'remark' => 'Distinction'],
                    ['grade_name' => '3', 'min_score' => 70, 'max_score' => 74,  'remark' => 'Merit'],
                    ['grade_name' => '4', 'min_score' => 65, 'max_score' => 69,  'remark' => 'Merit'],
                    ['grade_name' => '5', 'min_score' => 60, 'max_score' => 64,  'remark' => 'Credit'],
                    ['grade_name' => '6', 'min_score' => 50, 'max_score' => 59,  'remark' => 'Credit'],
                    ['grade_name' => '7', 'min_score' => 45, 'max_score' => 49,  'remark' => 'Pass'],
                    ['grade_name' => '8', 'min_score' => 40, 'max_score' => 44,  'remark' => 'Pass'],
                    ['grade_name' => '9', 'min_score' => 0,  'max_score' => 39,  'remark' => 'Unsatisfactory'],
                ]);
            }
        });
    }
}