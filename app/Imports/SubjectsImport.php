<?php

namespace App\Imports;

use App\Models\Subject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubjectsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // This assumes your Excel file has columns named 'name' and 'code'.
        // Adjust these keys if your column names are different.
        return new Subject([
            'name'     => $row['name'],
            'code'     => $row['code'], 
        ]);
    }
}