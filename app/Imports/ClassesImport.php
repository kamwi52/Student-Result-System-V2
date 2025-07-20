<?php

namespace App\Imports;

use App\Models\ClassSection;
use App\Models\AcademicSession;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClassesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Find the current active academic session to associate the new class with.
        // You can adjust this logic if you want to specify the session in the CSV.
        $activeSession = AcademicSession::where('is_active', true)->first();

        // If no active session, you might want to skip or throw an error
        if (!$activeSession) {
            return null; 
        }

        return new ClassSection([
            // This assumes your spreadsheet has a column named 'name'
            'name' => $row['name'],
            'academic_session_id' => $activeSession->id,
        ]);
    }
}