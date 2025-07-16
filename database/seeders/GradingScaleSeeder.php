<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradingScale;
use App\Models\Grade;

class GradingScaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This will create or update the official ECZ grading scales.
     */
    public function run(): void
    {
        // --- 1. ECZ Grade 7 Grading Scale ---
        $grade7Scale = GradingScale::updateOrCreate(
            ['name' => 'ECZ Grade 7 Grading']
        );
        
        // Define the grades for the Grade 7 scale
        $grade7Grades = [
            ['grade_name' => 'ONE',   'min_score' => 112, 'max_score' => 150, 'remark' => 'Excellent'],
            ['grade_name' => 'TWO',   'min_score' => 90,  'max_score' => 111, 'remark' => 'Very Good'],
            ['grade_name' => 'THREE', 'min_score' => 75,  'max_score' => 89,  'remark' => 'Good'],
            ['grade_name' => 'FOUR',  'min_score' => 40,  'max_score' => 74,  'remark' => 'Average'],
            ['grade_name' => 'F',     'min_score' => 0,   'max_score' => 39,  'remark' => 'Below Average'],
        ];
        
        // Create or update each grade within this scale
        foreach ($grade7Grades as $grade) {
            Grade::updateOrCreate(
                ['grading_scale_id' => $grade7Scale->id, 'grade_name' => $grade['grade_name']],
                $grade
            );
        }

        // --- 2. ECZ Grade 9 Grading Scale ---
        $grade9Scale = GradingScale::updateOrCreate(
            ['name' => 'ECZ Grade 9 Grading']
        );

        $grade9Grades = [
            ['grade_name' => 'ONE',   'min_score' => 75, 'max_score' => 100, 'remark' => 'Distinction'],
            ['grade_name' => 'TWO',   'min_score' => 60, 'max_score' => 74,  'remark' => 'Merit'],
            ['grade_name' => 'THREE', 'min_score' => 50, 'max_score' => 59,  'remark' => 'Credit'],
            ['grade_name' => 'FOUR',  'min_score' => 40, 'max_score' => 49,  'remark' => 'Pass'],
            ['grade_name' => 'F',     'min_score' => 0,  'max_score' => 39,  'remark' => 'Fail'],
        ];

        foreach ($grade9Grades as $grade) {
            Grade::updateOrCreate(
                ['grading_scale_id' => $grade9Scale->id, 'grade_name' => $grade['grade_name']],
                $grade
            );
        }

        // --- 3. ECZ Grade 12 & GCE Grading Scale ---
        $grade12Scale = GradingScale::updateOrCreate(
            ['name' => 'ECZ Grade 12 & GCE Grading']
        );

        $grade12Grades = [
            ['grade_name' => '1', 'min_score' => 75, 'max_score' => 100, 'remark' => 'Distinction'],
            ['grade_name' => '2', 'min_score' => 70, 'max_score' => 74,  'remark' => 'Distinction'],
            ['grade_name' => '3', 'min_score' => 65, 'max_score' => 69,  'remark' => 'Merit'],
            ['grade_name' => '4', 'min_score' => 60, 'max_score' => 64,  'remark' => 'Merit'],
            ['grade_name' => '5', 'min_score' => 55, 'max_score' => 59,  'remark' => 'Credit'],
            ['grade_name' => '6', 'min_score' => 50, 'max_score' => 54,  'remark' => 'Credit'],
            ['grade_name' => '7', 'min_score' => 45, 'max_score' => 49,  'remark' => 'Satisfactory'],
            ['grade_name' => '8', 'min_score' => 40, 'max_score' => 44,  'remark' => 'Satisfactory'],
            ['grade_name' => '9', 'min_score' => 0,  'max_score' => 39,  'remark' => 'Unsatisfactorily'],
        ];

        foreach ($grade12Grades as $grade) {
            Grade::updateOrCreate(
                ['grading_scale_id' => $grade12Scale->id, 'grade_name' => $grade['grade_name']],
                $grade
            );
        }

        // Note: The 'Absent' (X) status is typically handled at the application level
        // (e.g. a NULL score in the results table) rather than as a numeric grade.
    }
}