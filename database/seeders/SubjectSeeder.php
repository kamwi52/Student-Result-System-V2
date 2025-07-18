<?php
// Ensure NO BLANK LINES OR WHITESPACE before the <?php tag
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject; // Make sure the Subject model is imported

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Using updateOrCreate ensures that if you run the seeder multiple times,
        // it won't create duplicate subjects, but will update existing ones.
        Subject::updateOrCreate(['name' => 'Mathematics'], ['code' => 'MATH101']);
        Subject::updateOrCreate(['name' => 'English'], ['code' => 'ENG101']);
        Subject::updateOrCreate(['name' => 'Physics'], ['code' => 'PHY101']);
        Subject::updateOrCreate(['name' => 'Chemistry'], ['code' => 'CHEM101']);
        Subject::updateOrCreate(['name' => 'Biology'], ['code' => 'BIO101']);
        Subject::updateOrCreate(['name' => 'History'], ['code' => 'HIST101']);
        Subject::updateOrCreate(['name' => 'Geography'], ['code' => 'GEO101']);
        Subject::updateOrCreate(['name' => 'Computer Science'], ['code' => 'CS101']);
        Subject::updateOrCreate(['name' => 'Physical Education'], ['code' => 'PE101']);
    }
}