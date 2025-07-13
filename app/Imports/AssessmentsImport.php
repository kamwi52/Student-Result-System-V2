<?php

namespace App\Imports;

use App\Models\Assessment;
use App\Models\Subject;
use App\Models\AcademicSession;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AssessmentsImport implements ToModel, WithHeadingRow, WithValidation
{
    private $subjects;
    private $sessions;

    public function __construct()
    {
        // Cache subjects and sessions to avoid repeated DB calls inside the loop
        $this->subjects = Subject::pluck('id', 'name');
        $this->sessions = AcademicSession::pluck('id', 'name');
    }

    public function model(array $row)
    {
        return new Assessment([
            'name'     => $row['assessment_name'],
            'subject_id' => $this->subjects[$row['subject_name']],
            'academic_session_id' => $this->sessions[$row['academic_session_name']],
            'max_marks' => $row['max_marks'],
            'weightage' => $row['weightage_percent'],
        ]);
    }

    public function rules(): array
    {
        return [
            'assessment_name' => 'required|string',
            'subject_name' => 'required|exists:subjects,name',
            'academic_session_name' => 'required|exists:academic_sessions,name',
            'max_marks' => 'required|integer',
            'weightage_percent' => 'required|integer',
        ];
    }
}