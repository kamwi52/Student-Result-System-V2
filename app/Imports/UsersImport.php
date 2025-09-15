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
use Maatwebsite\Excel\Concerns\SkipsOnFailure; // 1. Import the "Skip on Failure" trait
use Maatwebsite\Excel\Validators\Failure;    // 2. Import the "Failure" object

// --- We now implement SkipsOnFailure ---
class UsersImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, SkipsOnFailure
{
    private $sessions;

    public function __construct()
    {
        $this->sessions = AcademicSession::all();
    }

    public function model(array $row)
    {
        // Your existing, perfected model logic does not need to change.
        $user = User::updateOrCreate(
            ['email' => trim($row['email'])],
            [
                'name'     => trim($row['name']),
                'password' => Hash::make($row['password']),
                'role'     => strtolower($row['role']),
            ]
        );

        if ($user->role === 'student' && !empty($row['class_name']) && !empty($row['academic_session_name'])) {
            $className = trim($row['class_name']);
            $sessionInput = trim($row['academic_session_name']);
            
            $session = $this->sessions->first(fn($s) => str_contains($s->name, $sessionInput));

            if ($session) {
                $classSection = ClassSection::where('name', $className)->where('academic_session_id', $session->id)->first();
                if ($classSection) {
                    $user->enrollments()->updateOrCreate(
                        ['class_section_id' => $classSection->id, 'user_id' => $user->id],
                        []
                    );
                }
            }
        }

        return $user;
    }

    public function rules(): array
    {
        // Your existing, perfected validation rules do not need to change.
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,teacher,student',
            'academic_session_name' => 'nullable', 
            'class_name' => 'nullable|string',
        ];
    }
    
    // =========================================================================
    // === THE DEFINITIVE FIX: THE FAILURE HANDLER =============================
    // This method is automatically called when a row fails validation.
    // =========================================================================
    public function onFailure(Failure ...$failures)
    {
        // This is the "loud" part. For every row that is skipped,
        // we write a detailed error message to the log file.
        foreach ($failures as $failure) {
            Log::error('User Import Skipped Row', [
                'row_number' => $failure->row(), // The row number from the spreadsheet
                'column' => $failure->attribute(), // The column that failed
                'errors' => $failure->errors(), // The specific error messages
                'row_data' => $failure->values(), // The data from the failed row
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}