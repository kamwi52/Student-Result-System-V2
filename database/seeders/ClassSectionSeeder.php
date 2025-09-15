<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicSession;
use App\Models\ClassSection;
use App\Models\GradingScale; // THIS IS THE DEFINITIVE FIX
use App\Models\Subject;

class ClassSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder will create all classes and assign their corresponding subjects.
     */
    public function run(): void
    {
        // First, we need to get the foundational data.
        $session = AcademicSession::where('name', '2025 Academic Year')->first();
        // --- THE FIX ---
        $gradingScale = GradingScale::where('name', 'Senior Secondary (Exam)')->first();
        $juniorGradingScale = GradingScale::where('name', 'Junior Secondary (Formative)')->first() ?? $gradingScale; // Fallback

        if (!$session || !$gradingScale) {
            $this->command->error('Could not find the required Academic Session or Grading Scale. Please run their seeders first.');
            return;
        }

        $classesAndSubjects = [
            'F1TS' => ['Mathematics', 'Information and Communication Technology', 'Civic Education', 'English', 'Chemistry', 'Physics', 'DESIGN AND TECHNOLOGY'],
            '9A' => ['Social Studies', 'Religious Education', 'Business Studies', 'Computer Studies', 'Intergrated Science', 'English', 'Chitonga', 'Mathematics'],
            '9B' => ['Mathematics', 'Religious Education', 'Computer Studies', 'Home Economics', 'Intergrated Science', 'English', 'Business Studies', 'Social Studies'],
            '9D' => ['Mathematics', 'English', 'Computer Studies', 'Music', 'Social Studies', 'Art and Design', 'Business Studies', 'Intergrated Science', 'Religious Education'],
            '10A' => ['Mathematics', 'English', 'Chemistry (Pure)', 'Religious Education', 'Physics (Pure)', 'Biology', 'Additional Mathematics', 'Civic Education'],
            '10C' => ['English', 'Physics', 'Religious Education', 'History', 'Civic Education', 'Mathematics', 'Chemistry', 'Biology', 'Design and Technology'],
            '10D' => ['Design and Technology', 'Mathematics', 'Geography', 'Physics', 'Biology', 'Civic Education', 'Literature in English', 'Chemistry', 'English', 'Chitonga'],
            '10E' => ['Civic Education', 'Mathematics', 'English', 'Biology', 'Religious Education', 'Food and Nutrition', 'Geography', 'Physics', 'Chemistry'],
            '10F' => ['Chemistry', 'Principles of Accounts', 'Commerce', 'Mathematics', 'English', 'Physics', 'Civic Education', 'History', 'Biology'],
            '10G' => ['Art and Design', 'Religious Education', 'Mathematics', 'Chemistry', 'Biology', 'Civic Education', 'English', 'Computer Studies', 'Music'],
            '10H' => ['Geography', 'Physics', 'Religious Education', 'Civic Education', 'Chemistry', 'Biology', 'English', 'Mathematics', 'Food and Nutrition'],
            '11A' => ['Mathematics', 'Physics (Pure)', 'Civic Education', 'Chemistry (Pure)', 'Religious Education', 'Biology', 'Additional Mathematics', 'English'],
            '11B' => ['Biology', 'Mathematics', 'Geography', 'Principles of Accounts', 'Commerce', 'Chemistry', 'Civic Education', 'English'],
            '11C' => ['Physics', 'Chemistry', 'Biology', 'Civic Education', 'History', 'Mathematics', 'Design and Technology', 'English', 'Religious Education'],
            '11D' => ['Mathematics', 'Physics', 'Chemistry', 'Geography', 'Computer Studies', 'Civic Education', 'English', 'Biology'],
            '11E' => ['Biology', 'Geography', 'Mathematics', 'Chemistry', 'Religious Education', 'Civic Education', 'Food and Nutrition', 'English'],
            '11F' => ['Biology', 'Commerce', 'English', 'Mathematics', 'Chemistry', 'History', 'Principles of Accounts', 'Civic Education', 'Physics'],
            '11G' => ['Mathematics', 'Religious Education', 'Chemistry', 'English', 'Music', 'Civic Education', 'Physics', 'Biology', 'Literature in English', 'Art and Design', 'Chitonga'],
            '11H' => ['English', 'Geography', 'Biology', 'Civic Education', 'Mathematics', 'Physics', 'Chemistry', 'Food and Nutrition', 'Religious Education'],
            '12A' => ['Biology', 'Religious Education', 'Mathematics', 'Civic Education', 'Chemistry (Pure)', 'English', 'Additional Mathematics'],
            '12B' => ['Civic Education', 'Geography', 'Commerce', 'Principles of Accounts', 'English', 'Chemistry', 'Biology', 'Physics', 'Mathematics'],
            '12C' => ['English', 'Mathematics', 'Civic Education', 'Biology', 'Physics', 'History', 'Religious Education', 'Chemistry', 'Design and Technology'],
            '12D' => ['Chemistry', 'Biology', 'English', 'Computer Studies', 'Civic Education', 'Physics', 'Chitonga', 'Geography', 'Mathematics'],
            '12E' => ['Food and Nutrition', 'Geography', 'Civic Education', 'Biology', 'Mathematics', 'English', 'Physics', 'Chemistry', 'Religious Education'],
            '12F' => ['Commerce', 'Physics', 'Principles of Accounts', 'History', 'Civic Education', 'Biology', 'Mathematics', 'Chemistry', 'English'],
            '12G' => ['English', 'Physics', 'Chemistry', 'Religious Education', 'Art and Design', 'Mathematics', 'Civic Education', 'Literature in English', 'Biology', 'Music', 'Geography', 'Food and Nutrition'],
            '12H' => ['Biology', 'Mathematics', 'Food and Nutrition', 'English', 'Chemistry', 'Civic Education', 'Geography', 'Religious Education', 'Physics'],
            'F1BF' => ['Principles of Accounts', 'Civic Education', 'Commerce', 'Physics', 'Mathematics', 'Information and Communication Technology', 'English'],
            'F1HTT' => ['Biology', 'Civic Education', 'Chemistry', 'Travel and Tourism', 'Information and Communication Technology', 'French', 'English', 'Mathematics'],
            'F1NS' => ['Physics', 'English', 'Mathematics', 'Biology', 'Chemistry', 'Information and Communication Technology', 'Civic Education'],
            'F1SS' => ['Mathematics', 'Biology', 'Information and Communication Technology', 'Civic Education', 'Literature in English', 'Religious Education', 'English'],
        ];

        foreach ($classesAndSubjects as $className => $subjectNames) {
            $currentGradingScale = (str_starts_with($className, '9')) ? $juniorGradingScale : $gradingScale;
            $this->command->info("Creating Class: {$className}");
            $classSection = ClassSection::updateOrCreate(
                ['name' => $className, 'academic_session_id' => $session->id],
                ['grading_scale_id' => $currentGradingScale->id]
            );
            $subjectIds = Subject::whereIn('name', $subjectNames)->pluck('id');
            $classSection->subjects()->sync($subjectIds);
        }
    }
}