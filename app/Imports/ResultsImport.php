<?php

namespace App\Imports;

use App\Models\Result;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ResultsImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $class_id;
    protected $assessment_id;

    /**
     * We pass the class and assessment IDs to the constructor
     * so we can use them when creating the result records.
     */
    public function __construct(int $class_id, int $assessment_id)
    {
        $this->class_id = $class_id;
        $this->assessment_id = $assessment_id;
    }

    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            // We only process rows that have a score entered.
            if (isset($row['score']) && !is_null($row['score'])) {
                Result::updateOrCreate(
                    [
                        'user_id' => $row['student_id'],
                        'class_id' => $this->class_id,
                        'assessment_id' => $this->assessment_id,
                    ],
                    [
                        'score' => $row['score'],
                        'remarks' => $row['remarks'] ?? null,
                    ]
                );
            }
        }
    }

    /**
     * Define the validation rules for each row.
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:users,student_id',
            'score' => 'nullable|numeric|min:0|max:100',
        ];
    }
}