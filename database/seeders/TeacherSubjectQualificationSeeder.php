<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;    // Import the User model
use App\Models\Subject; // Import the Subject model

class TeacherSubjectQualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find your primary teacher users
        $teacher1 = User::where('email', 'teach1@test.com')->first();
        $mukumbwali = User::where('email', 'mukumbwali@test.com')->first(); // <-- Find Mukumbwali Milimo

        // Find subjects (ensure these names match your Subject seeder or existing data)
        $math = Subject::where('name', 'Mathematics')->first();
        $english = Subject::where('name', 'English')->first();
        $physics = Subject::where('name', 'Physics')->first();
        $biology = Subject::where('name', 'Biology')->first();
        $history = Subject::where('name', 'History')->first();
        $chemistry = Subject::where('name', 'Chemistry')->first(); // Assuming Chemistry exists
        $computerScience = Subject::where('name', 'Computer Science')->first(); // Assuming Computer Science exists
        $geography = Subject::where('name', 'Geography')->first(); // Assuming Geography exists
        $physicalEducation = Subject::where('name', 'Physical Education')->first(); // Assuming PE exists

        // Collect all available subject IDs for easy assignment
        $allSubjectIds = [];
        if ($math) $allSubjectIds[] = $math->id;
        if ($english) $allSubjectIds[] = $english->id;
        if ($physics) $allSubjectIds[] = $physics->id;
        if ($biology) $allSubjectIds[] = $biology->id;
        if ($history) $allSubjectIds[] = $history->id;
        if ($chemistry) $allSubjectIds[] = $chemistry->id;
        if ($computerScience) $allSubjectIds[] = $computerScience->id;
        if ($geography) $allSubjectIds[] = $geography->id;
        if ($physicalEducation) $allSubjectIds[] = $physicalEducation->id;


        // Assign qualifications to teach1 if the teacher exists
        if ($teacher1) {
            // teach1 is qualified for all subjects for comprehensive testing
            $teacher1->qualifiedSubjects()->syncWithoutDetaching($allSubjectIds);
        }

        // === CRUCIAL FIX: Assign qualifications to Mukumbwali Milimo ===
        if ($mukumbwali) {
            // Mukumbwali Milimo is also qualified for all subjects
            $mukumbwali->qualifiedSubjects()->syncWithoutDetaching($allSubjectIds);
        }

        // Example for another teacher if needed
        // $teacher2 = User::where('email', 'teach2@test.com')->first();
        // if ($teacher2) {
        //     // Assign specific subjects to teacher2, e.g., only History
        //     $teacher2->qualifiedSubjects()->syncWithoutDetaching([$history->id]);
        // }
    }
}