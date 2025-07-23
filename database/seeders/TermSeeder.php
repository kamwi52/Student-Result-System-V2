<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Term;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Term::firstOrCreate(['name' => 'Mid Term']);
        Term::firstOrCreate(['name' => 'Final Exam']);
        Term::firstOrCreate(['name' => 'Quiz']);
        Term::firstOrCreate(['name' => 'Homework']);
    }
}