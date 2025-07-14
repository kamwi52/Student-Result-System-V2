<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class UsersImport implements OnEachRow, WithHeadingRow
{
    /**
     * This method is called for each row, giving us a Row object.
     * We will manually perform the update or create logic here.
     * This is the most reliable method for this task.
     *
     * @param Row $row
     */
    public function onRow(Row $row)
    {
        // Get the row data as an array
        $rowData = $row->toArray();

        // Skip the row if the email is missing, which prevents errors from blank lines in the CSV.
        if (empty($rowData['email'])) {
            return;
        }

        // Use updateOrCreate to find a user by their email.
        // If they exist, it updates them. If not, it creates them.
        User::updateOrCreate(
            [
                // This is the unique key to find the user by.
                'email' => $rowData['email'],
            ],
            [
                // These are the values to set on the new or updated user.
                'name' => $rowData['name'],
                'password' => Hash::make($rowData['password']),
                'role' => $rowData['role'] ?? 'student',
                'email_verified_at' => now(), // This ensures all imported/updated users can log in.
            ]
        );
    }
}