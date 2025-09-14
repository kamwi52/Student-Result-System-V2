<?php

namespace App\Imports;

use App\Models\User;
use App\Models\ClassSection;
use App\Models\AcademicSession;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{
    private $sessions;

    public function __construct()
    {
        $this->sessions = AcademicSession::all()->keyBy('name');
    }

    public function model(array $row)
    {
        // Step 1: Create or Update the User
        $user = User::updateOrCreate(
            ['email' => $row['email']],
            [
                'name'     => $row['name'],
                'password' => Hash::make($row['password']),
                'role'     => strtolower($row['role']),
            ]
        );

        // Step 2: Handle Enrollment
        if ($user->role === 'student' && !empty($row['class_name']) && !empty($row['academic_session_name'])) {
            
            $className = trim($row['class_name']);
            $sessionName = trim($row['academic_session_name']);
            
            $session = $this->sessions->get($sessionName);

            if (!$session) {
                Log::warning('ENROLLMENT FAILED: The Academic Session from the CSV does not exist.', [
                    'student_email' => $user->email,
                    'session_name_from_csv' => $sessionName,
                ]);
                return $user;
            }

            $classSection = ClassSection::where('name', $className)
                ->where('academic_session_id', $session->id)
                ->first();

            if ($classSection) {
                // =========================================================================
                // === THIS IS THE FINAL FIX: Corrected `student_id` to `user_id` ==========
                // This aligns the code with your actual database schema and fixes the crash.
                // =========================================================================
                $user->enrollments()->updateOrCreate(
                    [
                        'class_section_id' => $classSection->id,
                        'user_id' => $user->id, // THIS WAS THE BUG. IT IS NOW FIXED.
                    ],
                    [] 
                );
            } else {
                Log::warning('ENROLLMENT FAILED: The Class Name was not found within the specified Academic Session.', [
                    'student_email' => $user->email,
                    'class_name_from_csv' => $className,
                    'session_name_from_csv' => $sessionName,
                ]);
            }
        }

        return $user;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,teacher,student',
            'academic_session_name' => 'nullable|string',
            'class_name' => 'nullable|string',
        ];
    }
    
    public function chunkSize(): int
    {
        return 100;
    }
}