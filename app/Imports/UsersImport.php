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
        // Find or create the user based on their email
        $user = User::updateOrCreate(
            ['email' => $row['email']],
            [
                'name'     => $row['name'],
                'password' => Hash::make($row['password']),
                'role'     => strtolower($row['role']),
            ]
        );

        // --- AUTO-ENROLLMENT LOGIC ---
        // If the user is a student and class details are provided, enroll them.
        if (strtolower($row['role']) === 'student' && !empty($row['class_name']) && !empty($row['academic_session_name'])) {
            $academicSession = AcademicSession::where('name', $row['academic_session_name'])->first();
            
            // Important: We proceed only if the session exists. Validation rules below will catch the error if it doesn't.
            if ($academicSession) {
                $classSection = ClassSection::where('name', $row['class_name'])
                                            ->where('academic_session_id', $academicSession->id)
                                            ->first();
                
                // If the class is found, attach the student.
                if ($classSection) {
                    $classSection->students()->syncWithoutDetaching($user->id);
                }
            }
        }
        // --- END OF AUTO-ENROLLMENT LOGIC ---
        
        return $user;
    }

    /**
     * Define the validation rules for each row.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email', // 'unique' check is handled by updateOrCreate
            'password' => 'required',
            'role' => ['required', Rule::in(['admin', 'teacher', 'student'])],
            
            // These rules will check if the provided names exist in the database.
            // 'nullable' allows them to be empty for non-student roles.
            'academic_session_name' => [
                'nullable',
                'required_with:class_name', // Required if class_name is present
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
     *
     * @return array
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