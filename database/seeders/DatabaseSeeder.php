<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Foundational data must come first
            AcademicSessionSeeder::class,
            TermSeeder::class,
            GradingScaleSeeder::class,
            SubjectSeeder::class, // The full curriculum
            
            // Dependent data comes next
            ClassSectionSeeder::class, // Creates classes and assigns subjects
            UserSeeder::class, // Creates users and enrolls them
        ]);
    }
}