<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\ClassSection;
use App\Models\Assessment; // Corrected from Assignment to Assessment based on context
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $results = Result::with(['student', 'assessment.subject', 'assessment.classSection'])
            ->latest()
            ->paginate(20);
        return view('admin.results.index', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $students = User::where('role', 'student')->orderBy('name')->get();
        $assessments = Assessment::with(['subject', 'classSection'])->orderBy('name')->get();
        $classSections = ClassSection::with('academicSession')->orderBy('name')->get();

        // --- EDITED: Changed assignments to assessments  ---
        return view('admin.results.create', compact('students', 'assessments', 'classSections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'assessment_id' => 'required|exists:assessments,id',
            'score' => 'required|numeric|min:0',
            'comments' => 'nullable|string|max:1000',
        ]);

        // Check if a result already exists for this user and assessment
        $existingResult = Result::where('user_id', $validated['user_id'])
            ->where('assessment_id', $validated['assessment_id'])
            ->first();

        if ($existingResult) {
            // A result already exists, so display an error message
            return back()->withErrors(['message' => 'You have already submitted a result for this assessment.']);
        } else {
            // No existing result, so create a new one
            Result::create($validated);
            return redirect()->route('admin.results.index')->with('success', 'Result added successfully.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Result $result): View
    {
        $students = User::where('role', 'student')->orderBy('name')->get();
        $assessments = Assessment::with(['subject', 'classSection'])->orderBy('name')->get();
        $classSections = ClassSection::with('academicSession')->orderBy('name')->get();

        $result->load(['student', 'assessment']);
        // --- EDITED: Changed assignments to assessments for edit too ---
        return view('admin.results.edit', compact('result', 'students', 'assessments', 'classSections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Result $result)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'assessment_id' => 'required|exists:assessments,id',
            'score' => 'required|numeric|min:0',
            'comments' => 'nullable|string|max:1000',
        ]);
        $result->update($validated);
        return redirect()->route('admin.results.index')->with('success', 'Result updated successfully.');
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
     * Step 1: Show the form to select an assessment for import.
     */
    public function showImportStep1(): View
    {
        $assessments = Assessment::with(['classSection', 'subject'])->orderBy('name')->get();
        return view('admin.results.import-step1', compact('assessments'));
    }

    /**
     * Step 2: This method is now handled by the form in step 1 and the handleImport method.
     * We can keep it for future expansion or remove it if a single form is sufficient.
     * For now, the logic is combined into handleImport.
     */
    public function showImportStep2(Request $request)
    {
        return redirect()->route('admin.results.import.step1');
    }

    /**
     * Step 3: Handle the final file upload and import process.
     */
    public function handleImport(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'file' => 'required|file|mimes:csv,txt',
        ]);

        Log::info('--- RESULT IMPORT PROCESS STARTED ---');

        $assessment = Assessment::with('classSection.students')->findOrFail($request->assessment_id);
        $enrolledStudentEmails = $assessment->classSection->students->pluck('email')->flip();

        try {
            $file = $request->file('file');
            $file_handle = fopen($file->getRealPath(), 'r');
            if ($file_handle === false) {
                throw new \Exception("Could not open the uploaded file.");
            }

            $header = array_map('trim', fgetcsv($file_handle));
            $requiredColumns = ['student_email', 'score', 'comments'];
            if ($header !== $requiredColumns) {
                fclose($file_handle);
                throw new \Exception("Invalid CSV header. Expected: 'student_email,score,comments'. Found: '" . implode(',', $header) . "'.");
            }

            $import_errors = [];
            $success_count = 0;
            $rowNumber = 1;

            while (($row = fgetcsv($file_handle, 1000, ",")) !== false) {
                $rowNumber++;
                if (empty(implode('', $row))) continue;
                $data = array_combine($header, array_map('trim', $row));

                DB::beginTransaction();
                try {
                    $student = User::where('email', $data['student_email'])->first();

                    if (!$student) {
                        throw new \Exception("Student with email '{$data['student_email']}' not found.");
                    }
                    if (!$enrolledStudentEmails->has($data['student_email'])) {
                        throw new \Exception("Student '{$data['student_email']}' is not enrolled in the required class '{$assessment->classSection->name}'.");
                    }
                    if (!is_numeric($data['score'])) {
                        throw new \Exception("Score '{$data['score']}' is not a valid number.");
                    }

                    // Check if a result already exists for this user and assessment in the import
                    $existingResult = Result::where('user_id', $student->id)
                        ->where('assessment_id', $assessment->id)
                        ->first();
                    if ($existingResult) {
                        $existingResult->update([
                            'score' => $data['score'],
                            'comments' => $data['comments'] ?? null
                        ]);
                        $success_count++;
                    } else {


                        Result::updateOrCreate(
                            ['user_id' => $student->id, 'assessment_id' => $assessment->id],
                            ['score' => $data['score'], 'comments' => $data['comments'] ?? null]
                        );
                        $success_count++;
                    }

                    DB::commit();

                } catch (\Exception $e) {
                    DB::rollBack();
                    $import_errors[] = "Row {$rowNumber}: " . $e->getMessage();
                    Log::error("Result Import Row {$rowNumber} - FAILED: " . $e->getMessage());
                }
            }
            fclose($file_handle);

            $message = "Import process finished. Successfully created or updated {$success_count} results.";
            return redirect()->route('admin.results.index')
                ->with('success', $message)
                ->with('import_errors', $import_errors);
        } catch (\Exception $e) {
            Log::error('--- RESULT IMPORT PROCESS FAILED: ' . $e->getMessage());
            return redirect()->back()->with('import_error', 'An unexpected error occurred: '.$e->getMessage())->withInput();
        }
    }
}