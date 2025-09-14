<?php

namespace App\Imports;

use App\Models\ClassSection;
use App\Models\AcademicSession;
use App\Models\GradingSystem;
use App\Models\Subject;
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
        // Pre-load all data once, keyed by name for extreme speed.
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

        // Direct, case-sensitive lookup from our cached collections.
        $session = $this->sessions->get($sessionName);
        $gradingSystem = $this->gradingSystems->get($gradingSystemName);
        
        $foundSubjects = $this->subjects->whereIn('name', $subjectNames);
        $subjectIds = $foundSubjects->pluck('id')->toArray();

        // =========================================================================
        // === THE ULTRA-LOUD VALIDATION BLOCK =====================================
        // =========================================================================
        if (!$session || !$gradingSystem || $foundSubjects->count() !== count($subjectNames)) {
            // Find the exact subject names that were not found in the database.
            $notFoundSubjects = implode(', ', array_diff($subjectNames, $foundSubjects->keys()->toArray()));

            Log::warning('CLASS IMPORT FAILURE: Skipping row due to data mismatch.', [
                'row_data' => $row,
                'session_check' => $session ? 'OK' : "FAILED: Could not find Academic Session named '{$sessionName}'",
                'grading_system_check' => $gradingSystem ? 'OK' : "FAILED: Could not find Grading System named '{$gradingSystemName}'",
                'subjects_check' => ($foundSubjects->count() === count($subjectNames)) ? 'OK' : "FAILED: Could not find these subjects: [{$notFoundSubjects}]"
            ]);
            return null; // Silently skip the row, but leave a loud footprint in the log.
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