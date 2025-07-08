<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

// We implement WithHeadingRow to easily access columns by their name
class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // This method is called for every row in the spreadsheet.
        // It maps the column names from the spreadsheet (e.g., 'name', 'email')
        // to the fields in the User model.
        return new User([
            'name'     => $row['name'],
            'email'    => $row['email'],
            'password' => Hash::make($row['password']), // We must hash the password for security
            'role'     => $row['role'] ?? 'student', // Default to 'student' if the role column is missing
        ]);
    }
}