<?php

namespace App\Imports;

use App\Models\User;
use App\Models\ClassSection;
use App\Models\AcademicSession;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // === THIS IS THE FIX: Clean the incoming data ===
        // 1. Trim leading/trailing whitespace and collapse multiple spaces within the name into a single space.
        $cleanedName = preg_replace('/\s+/', ' ', trim($row['name']));
        // 2. Trim any whitespace from the email.
        $cleanedEmail = trim($row['email']);
        // === END FIX ===

        // Find or create the user based on their CLEANED email
        $user = User::updateOrCreate(
            ['email' => $cleanedEmail],
            [
                'name'     => $cleanedName, // Use the cleaned name
                'password' => Hash::make($row['password']),
                'role'     => strtolower($row['role']),
            ]
        );

        // --- AUTO-ENROLLMENT LOGIC ---
        if (strtolower($row['role']) === 'student' && !empty($row['class_name']) && !empty($row['academic_session_name'])) {
            $academicSession = AcademicSession::where('name', $row['academic_session_name'])->first();
            
            if ($academicSession) {
                $classSection = ClassSection::where('name', $row['class_name'])
                                            ->where('academic_session_id', $academicSession->id)
                                            ->first();
                
                if ($classSection) {
                    $classSection->students()->syncWithoutDetaching($user->id);
                }
            }
        }
        
        return $user;
    }

    /**
     * Define the validation rules for each row.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required',
            'role' => ['required', Rule::in(['admin', 'teacher', 'student'])],
            'academic_session_name' => [
                'nullable',
                'required_with:class_name',
                Rule::exists('academic_sessions', 'name'),
            ],
            'class_name' => [
                'nullable',
                Rule::exists('class_sections', 'name'),
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function customValidationMessages()
    {
        return [
            'academic_session_name.exists' => 'The specified Academic Session does not exist in the database.',
            'class_name.exists' => 'The specified Class Name does not exist in the database.',
            'academic_session_name.required_with' => 'The Academic Session is required when enrolling a student in a class.',
        ];
    }
}