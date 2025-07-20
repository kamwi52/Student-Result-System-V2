<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation; // <-- ADD THIS
use Maatwebsite\Excel\Row;

class UsersImport implements OnEachRow, WithHeadingRow, WithValidation // <-- AND ADD IT HERE
{
    public function onRow(Row $row)
    {
        $rowData = $row->toArray();
        if (empty($rowData['email'])) { return; }

        User::updateOrCreate(
            ['email' => $rowData['email']],
            [
                'name' => $rowData['name'],
                'password' => !empty($rowData['password']) ? Hash::make($rowData['password']) : User::where('email', $rowData['email'])->value('password'), // Keep old password if new one is blank
                'role' => strtolower($rowData['role'] ?? 'student'),
                'email_verified_at' => now(),
            ]
        );
    }

    /**
     * Define the validation rules for each row.
     * The '*' tells Laravel to apply these rules to each item in the collection.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            // 'email' rule is tricky with updateOrCreate, so we handle it manually.
            // But we can still validate the format.
            'email' => 'required|email',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,teacher,student,Admin,Teacher,Student',
        ];
    }
}