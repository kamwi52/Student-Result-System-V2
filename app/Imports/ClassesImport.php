<?php

namespace App\Imports;

// --- THIS SECTION IS THE DEFINITIVE FIX FOR THE CRASH ---
use App\Models\ClassSection;
use App\Models\AcademicSession;
use App\Models\GradingSystem;
use App\Models\Subject;
// --------------------------------------------------------

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ClassesImport implements ToModel, WithHeadingRow, WithChunkReading
{
    private $sessions;
    private $gradingSystems;
    private $subjects;

    public function __construct()
    {
        // Pre-load data from the database for better performance
        $this->sessions = AcademicSession::all()->keyBy('name');
        $this->gradingSystems = GradingSystem::all()->keyBy('name');
        $this->subjects = Subject::all()->keyBy('name');
    }

    public function model(array $row)
    {
        $className = trim($row['name']);
        $sessionName = trim($row['academic_session']);
        $gradingSystemName = trim($row['grading_system']);
        $subjectNames = array_map('trim', explode('|', $row['subjects']));

        $session = $this->sessions->get($sessionName);
        $gradingSystem = $this->gradingSystems->get($gradingSystemName);
        
        $foundSubjects = $this->subjects->whereIn('name', $subjectNames);
        $subjectIds = $foundSubjects->pluck('id')->toArray();

        // This validation will now work and write a helpful message in your log file if data mismatches.
        if (!$session || !$gradingSystem || $foundSubjects->count() !== count($subjectNames)) {
            Log::warning('Skipping row in class import due to data mismatch.', [
                'row_data' => $row,
                'session_name_from_csv' => $sessionName,
                'session_found_in_db' => $session ? 'YES' : 'NO! MISMATCH!',
                'grading_system_from_csv' => $gradingSystemName,
                'grading_system_found_in_db' => $gradingSystem ? 'YES' : 'NO! MISMATCH!',
                'subjects_found_match' => ($foundSubjects->count() === count($subjectNames)) ? 'YES' : 'NO! MISMATCH!',
                'subjects_not_found' => implode(', ', array_diff($subjectNames, $foundSubjects->keys()->toArray()))
            ]);
            return null; 
        }

        $classSection = null;
        DB::transaction(function () use ($className, $session, $gradingSystem, $subjectIds, &$classSection) {
            $classSection = ClassSection::updateOrCreate(
                ['name' => $className, 'academic_session_id' => $session->id],
                ['grading_system_id' => $gradingSystem->id]
            );
            $classSection->subjects()->sync($subjectIds);
        });

        return $classSection;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}