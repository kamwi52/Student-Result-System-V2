<?php

namespace App\Imports;

use App\Models\Assessment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow; // <-- Add this new concern

class AssessmentsImport implements ToModel, WithStartRow // <-- Implement WithStartRow
{
    private int $academicSessionId;

    public function __construct(int $academicSessionId)
    {
        $this->academicSessionId = $academicSessionId;
    }

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Now we access columns by their index number, which is more reliable.
        // Column A is index 0, Column B is index 1, etc.
        return new Assessment([
            'name'                => $row[0], // First column
            'max_marks'           => $row[1], // Second column
            'weightage'           => $row[2], // Third column
            'academic_session_id' => $this->academicSessionId,
        ]);
    }

    /**
     * This tells the importer to start reading from the 2nd row, skipping the header.
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}