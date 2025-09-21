<?php

namespace App\Imports;

use App\Models\Result;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ResultsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    protected int $assessmentId;

    public function __construct(int $assessmentId)
    {
        $this->assessmentId = $assessmentId;
    }

    public function model(array $row)
    {
        $student = User::where('email', $row['student_email'])->where('role', 'student')->first();
        
        if (!$student) {
            return null; // This will be caught by the validation rules.
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
        return [
            'student_email' => 'required|email|exists:users,email',
            'score' => 'required|numeric|min:0',
            'comments' => 'nullable|string',
        ];
    }

    /**
     * This method is automatically called when a row fails validation.
     */
    public function onFailure(Failure ...$failures)
    {
        // This is the key. We are now storing the failures in the class itself
        // so the controller can access them after the import is complete.
        $this->failures()->add(...$failures);

        // We also log the error for the developer's records.
        foreach ($failures as $failure) {
            Log::error('Result Import Skipped Row', [
                'row' => $failure->row(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ]);
        }
    }
}