<?php

namespace App\Imports;

use App\Models\Result;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure; // 1. Import the "Skip on Failure" trait
use Maatwebsite\Excel\Validators\Failure;    // 2. Import the "Failure" object
use Illuminate\Support\Facades\Log;           // 3. Import the Log facade

// --- We now implement SkipsOnFailure ---
class ResultsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    protected int $assessmentId;

    public function __construct(int $assessmentId)
    {
        $this->assessmentId = $assessmentId;
    }

    public function model(array $row)
    {
        $student = User::where('email', $row['student_email'])->where('role', 'student')->first();

        // This safeguard is still good practice.
        if (!$student) {
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

    public function rules(): array
    {
        // The validation rules remain strict and correct.
        return [
            'student_email' => 'required|email|exists:users,email',
            'score' => 'required|numeric|min:0',
            'comments' => 'nullable|string',
        ];
    }

    // =========================================================================
    // === THE DEFINITIVE FIX: THE FAILURE HANDLER =============================
    // This method is automatically called when a row fails validation.
    // =========================================================================
    public function onFailure(Failure ...$failures)
    {
        // For every row that is skipped, we write a detailed error to the log file.
        foreach ($failures as $failure) {
            Log::error('Result Import Skipped Row', [
                'row_number' => $failure->row(),
                'column' => $failure->attribute(),
                'errors' => $failure->errors(),
                'row_data' => $failure->values(),
            ]);
        }
    }
}.