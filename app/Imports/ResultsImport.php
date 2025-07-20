<?php

namespace App\Imports;

use App\Models\Result;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ResultsImport implements ToModel, WithHeadingRow, WithValidation
{
    protected int $assessmentId;

    /**
     * Store the assessment ID when the class is instantiated.
     */
    public function __construct(int $assessmentId)
    {
        $this->assessmentId = $assessmentId;
    }

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $student = User::where('email', $row['student_email'])->where('role', 'student')->first();

        if (!$student) {
            // The validation rule will catch this, but this is a safeguard.
            return null;
        }

        return Result::updateOrCreate(
            [
                'assessment_id' => $this->assessmentId,
                'user_id'       => $student->id,
            ],
            [
                'score'         => $row['score'],
                'comments'      => $row['comments'] ?? null,
            ]
        );
    }

    /**
     * Define the validation rules for each row.
     */
    public function rules(): array
    {
        return [
            // Ensure student_email exists in the users table
            'student_email' => 'required|email|exists:users,email',
            // Ensure score is numeric
            'score' => 'required|numeric|min:0',
            // Comments are optional
            'comments' => 'nullable|string',
        ];
    }
}