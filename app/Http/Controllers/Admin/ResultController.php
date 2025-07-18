<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\ClassSection;
use App\Models\Assignment; // Make sure this is imported (formerly Assessment)
use App\Models\User;
use App\Models\AcademicSession; // Make sure this is imported (for ClassSection display)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Arr; // For Arr::except if needed

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Eager load necessary relationships for display in the table
        $results = Result::with(['student', 'classSection', 'assignment.subject', 'assignment.classSection'])
                         ->latest()
                         ->paginate(20);
        return view('admin.results.index', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     * === THIS IS THE CRITICAL METHOD ===
     */
    public function create(): View
    {
        $students = User::where('role', 'student')->orderBy('name')->get();
        // Load academic session with class for clear display in the dropdown
        $classSections = ClassSection::with('academicSession')->orderBy('name')->get();
        
        // Fetch all assignments (our assessments), with their subject and class for clear display
        // This is the variable that the view expects to be defined
        $assignments = Assignment::with(['subject', 'classSection'])->orderBy('name')->get(); 

        // Ensure 'assignments' is passed to the view via compact()
        return view('admin.results.create', compact('students', 'classSections', 'assignments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'assignment_id' => 'required|exists:assignments,id',
            'score' => 'required|numeric|min:0', // Adjust min/max as per your needs
            'remark' => 'nullable|string|max:1000', // Singular 'remark'
        ]);

        Result::create($validated);

        return redirect()->route('admin.results.index')->with('success', 'Result added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Result $result): View
    {
        $students = User::where('role', 'student')->orderBy('name')->get();
        $classSections = ClassSection::with('academicSession')->orderBy('name')->get();
        $assignments = Assignment::with(['subject', 'classSection'])->orderBy('name')->get(); // Fetch for edit form

        // Eager load relationships for the current result
        $result->load(['student', 'classSection', 'assignment']);

        return view('admin.results.edit', compact('result', 'students', 'classSections', 'assignments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Result $result)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'assignment_id' => 'required|exists:assignments,id',
            'score' => 'required|numeric|min:0',
            'remark' => 'nullable|string|max:1000',
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
     * Step 1: Show the form to select a class for import.
     */
    public function showImportStep1(): View
    {
        $classSections = ClassSection::orderBy('name')->get(); 
        return view('admin.results.import-step1', compact('classSections'));
    }

    /**
     * Step 2: Show the form to select an assignment (assessment) for the chosen class.
     */
    public function showImportStep2(Request $request): View
    {
        $request->validate(['class_id' => 'required|exists:class_sections,id']);

        $classSection = ClassSection::with('subjects')->findOrFail($request->class_id);
        
        $assignments = Assignment::where('class_section_id', $classSection->id)
            ->with('subject')
            ->get();

        return view('admin.results.import-step2', compact('classSection', 'assignments'));
    }

    /**
     * Step 3: Handle the final file upload and import process.
     */
    public function handleImport(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:class_sections,id',
            'assignment_id' => 'required|exists:assignments,id',
            'results_file' => 'required|file|mimes:csv,txt',
        ]);

        Log::info('--- RESULT IMPORT PROCESS STARTED ---');

        $classId = $request->input('class_id');
        $assignmentId = $request->input('assignment_id');

        $classSection = ClassSection::find($classId);
        $enrolledStudentEmails = $classSection->students()->pluck('email')->flip();

        $studentUsers = User::where('role', 'student')->pluck('id', 'email');

        try {
            $file = $request->file('results_file');
            $file_handle = fopen($file->getRealPath(), 'r');
            if ($file_handle === false) {
                throw new \Exception("Could not open the uploaded file.");
            }

            $header = array_map('trim', fgetcsv($file_handle));
            $requiredColumns = ['student_email', 'score', 'remark'];
            if ($header !== $requiredColumns) {
                fclose($file_handle);
                throw new \Exception("Invalid CSV header. Expected: 'student_email,score,remark'. Found: '" . implode(',', $header) . "'.");
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
                            'assignment_id' => $assignmentId,
                        ],
                        [
                            'class_section_id' => $classId,
                            'score' => $data['score'],
                            'remark' => $data['remark'] ?? null,
                        ]
                    );

                    DB::commit();
                    $success_count++;
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
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }
}