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
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, SkipsOnFailure
{
    private $sessions;

    public function __construct()
    {
        $this->sessions = AcademicSession::all();
    }

    public function model(array $row)
    {
        // Step 1: Create or Update the User
        $user = User::updateOrCreate(
            ['email' => trim($row['email'])],
            [
                'name'     => trim($row['name']),
                'password' => Hash::make($row['password']),
                'role'     => strtolower($row['role']),
            ]
        );

        // =========================================================================
        // === THIS IS THE DEFINITIVE FIX ==========================================
        // This is the final, bulletproof enrollment logic. It uses the direct
        // 'enrollments()' relationship, which is the correct way.
        // =========================================================================
        if ($user->role === 'student' && !empty($row['class_name']) && !empty($row['academic_session_name'])) {
            
            $className = trim($row['class_name']);
            $sessionInput = trim($row['academic_session_name']);
            
            $session = $this->sessions->first(fn($s) => str_contains($s->name, $sessionInput));

            if ($session) {
                $classSection = ClassSection::where('name', $className)->where('academic_session_id', $session->id)->first();

                if ($classSection) {
                    // This is the corrected, direct enrollment creation.
                    $user->enrollments()->updateOrCreate(
                        [
                            'class_section_id' => $classSection->id,
                            'user_id' => $user->id,
                        ],
                        []
                    );
                } else {
                    Log::warning('ENROLLMENT FAILED: Class not found in session.', ['email' => $user->email, 'class' => $className, 'session' => $session->name]);
                }
            } else {
                Log::warning('ENROLLMENT FAILED: Session not found.', ['email' => $user->email, 'session' => $sessionInput]);
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
            'academic_session_name' => 'nullable', 
            'class_name' => 'nullable|string',
        ];
    }
    
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            Log::error('User Import Skipped Row', [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}