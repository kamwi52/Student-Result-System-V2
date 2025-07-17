<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\ClassSection;
use App\Models\Assessment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Added for logging
use Illuminate\View\View;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $results = Result::with('student', 'assessment.subject')->latest()->paginate(20);
        return view('admin.results.index', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.results.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Placeholder for future implementation
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Result $result): View
    {
        return view('admin.results.edit', compact('result'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Result $result)
    {
        // Placeholder for future implementation
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Result $result)
    {
        $result->delete();
        return redirect()->route('admin.results.index')->with('success', 'Result deleted successfully.');
    }

    /**
     * Step 1: Show the form to select a class for import.
     */
    public function showImportStep1(): View
    {
        $classes = ClassSection::orderBy('name')->get();
        return view('admin.results.import-step1', compact('classes'));
    }

    /**
     * Step 2: Show the form to select an assessment for the chosen class.
     */
    public function showImportStep2(Request $request): View
    {
        $request->validate(['class_id' => 'required|exists:class_sections,id']);

        $classSection = ClassSection::with('subjects')->findOrFail($request->class_id);
        $subjectIds = $classSection->subjects->pluck('id');

        $assessments = Assessment::whereIn('subject_id', $subjectIds)
            ->where('academic_session_id', $classSection->academic_session_id)
            ->with('subject')
            ->get();

        return view('admin.results.import-step2', compact('classSection', 'assessments'));
    }

    /**
     * Step 3: Handle the final file upload and import process.
     */
    public function handleImport(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:class_sections,id',
            'assessment_id' => 'required|exists:assessments,id',
            'results_file' => 'required|file|mimes:csv,txt',
        ]);

        Log::info('--- RESULT IMPORT PROCESS STARTED ---');

        $classId = $request->input('class_id');
        $assessmentId = $request->input('assessment_id');

        // Get all students enrolled in the selected class for validation
        $classSection = ClassSection::find($classId);
        $enrolledStudentEmails = $classSection->students()->pluck('email')->flip();

        // Get all users who are students, mapping their email to their ID
        $studentUsers = User::where('role', 'student')->pluck('id', 'email');

        try {
            $file = $request->file('results_file');

            // --- More robust file reading ---
            $file_handle = fopen($file->getRealPath(), 'r');
            if ($file_handle === false) {
                throw new \Exception("Could not open the uploaded file.");
            }

            // Read header and validate
            $header = array_map('trim', fgetcsv($file_handle));
            $requiredColumns = ['student_email', 'score', 'remark'];
            if ($header !== $requiredColumns) {
                fclose($file_handle); // Close the file handle before throwing
                throw new \Exception("Invalid CSV header. Expected: 'student_email,score,remark'. Found: '" . implode(',', $header) . "'.");
            }

            $import_errors = [];
            $success_count = 0;
            $rowNumber = 1;

            // Loop through the rest of the file
            while (($row = fgetcsv($file_handle, 1000, ",")) !== false) {
                $rowNumber++;

                // Skip empty rows
                if (empty(implode('', $row))) continue;

                DB::beginTransaction();
                try {
                    $data = array_combine($header, array_map('trim', $row));
                    $studentEmail = $data['student_email'];

                    if (!isset($studentUsers[$studentEmail])) {
                        throw new \Exception("Student with email '{$studentEmail}' not found in the system.");
                    }
                    if (!$enrolledStudentEmails->has($studentEmail)) {
                        throw new \Exception("Student '{$studentEmail}' is not enrolled in the selected class.");
                    }
                    if (!is_numeric($data['score'])) {
                        throw new \Exception("Score '{$data['score']}' is not a valid number.");
                    }

                    Result::updateOrCreate(
                        [
                            'user_id' => $studentUsers[$studentEmail],
                            'assessment_id' => $assessmentId,
                        ],
                        [
                            'score' => $data['score'],
                            'remark' => $data['remark'] ?? null,
                        ]
                    );

                    DB::commit();
                    $success_count++;
                } catch (\Exception $e) {
                    DB::rollBack();
                    $import_errors[] = "Row {$rowNumber}: " . $e->getMessage();
                    Log::error("Row {$rowNumber} - FAILED: " . $e->getMessage());
                }
            }
            fclose($file_handle);

            $message = "Import process finished. Successfully created or updated {$success_count} results.";
            return redirect()->route('admin.results.index')
                             ->with('success', $message)
                             ->with('import_errors', $import_errors);
        } catch (\Exception $e) {
            Log::error('--- RESULT IMPORT PROCESS FAILED: ' . $e->getMessage());
            return redirect()->back()->with('import_errors', ['An unexpected error occurred: ' . $e->getMessage()]);
        }
    }
}