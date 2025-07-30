<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AcademicSession;
use App\Models\Subject;
use App\Models\User;
use App\Models\ClassSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Added for error logging
use Illuminate\View\View;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Simplified the query as direct relationships should exist
        $assessments = Assessment::with(['subject', 'classSection', 'academicSession'])
            ->latest()
            ->paginate(10);

        return view('admin.assessments.index', compact('assessments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        $subjects = Subject::orderBy('name')->get();
        $classSections = ClassSection::orderBy('name')->get();
        return view('admin.assessments.create', compact('academicSessions', 'subjects', 'classSections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_marks' => 'required|numeric|min:0',
            'weightage' => 'nullable|numeric|min:0|max:100',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'subject_id' => 'required|exists:subjects,id',
            'assessment_date' => 'required|date',
            'class_section_id' => 'required|exists:class_sections,id',
            'description' => 'nullable|string',
        ]);

        Assessment::create($validated);

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment created successfully.');
    }

    // ... (edit, update, destroy methods remain the same)

    /**
     * === ADDED: Show the form for importing assessments. ===
     */
    public function showImportForm(): View
    {
        return view('admin.assessments.import');
    }

    /**
     * === ADDED: Handle the import of assessments from a CSV file. ===
     */
    public function handleImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $records = array_map('str_getcsv', file($path));

        if (count($records) <= 1) {
            return redirect()->back()->with('error', 'The uploaded file is empty or contains no data rows.');
        }

        $header = array_map('trim', array_shift($records));
        $requiredColumns = ['assessment_name', 'subject_name', 'class_name', 'academic_session_name', 'max_marks', 'weightage', 'assessment_date'];

        if (count(array_diff($requiredColumns, $header)) > 0) {
            return redirect()->back()->with('error', "Invalid CSV header. Please ensure the columns are: " . implode(', ', $requiredColumns));
        }

        $importErrors = [];
        $successCount = 0;

        DB::beginTransaction();
        try {
            foreach ($records as $key => $row) {
                $rowNumber = $key + 2;
                if (empty(implode('', $row))) continue;
                
                $data = array_combine($header, $row);

                // Find related models by name
                $subject = Subject::where('name', $data['subject_name'])->first();
                $academicSession = AcademicSession::where('name', $data['academic_session_name'])->first();
                $classSection = $academicSession ? ClassSection::where('name', $data['class_name'])->where('academic_session_id', $academicSession->id)->first() : null;

                // Validate that related models were found
                if (!$subject) {
                    $importErrors[] = "Row {$rowNumber}: Subject '{$data['subject_name']}' not found.";
                    continue;
                }
                if (!$academicSession) {
                    $importErrors[] = "Row {$rowNumber}: Academic Session '{$data['academic_session_name']}' not found.";
                    continue;
                }
                if (!$classSection) {
                    $importErrors[] = "Row {$rowNumber}: Class '{$data['class_name']}' not found in the '{$data['academic_session_name']}' session.";
                    continue;
                }

                // Create the assessment
                Assessment::create([
                    'name' => $data['assessment_name'],
                    'subject_id' => $subject->id,
                    'class_section_id' => $classSection->id,
                    'academic_session_id' => $academicSession->id,
                    'max_marks' => $data['max_marks'],
                    'weightage' => $data['weightage'],
                    'assessment_date' => $data['assessment_date'],
                ]);
                $successCount++;
            }

            if (!empty($importErrors)) {
                DB::rollBack();
                return redirect()->back()->with('import_errors', $importErrors);
            }

            DB::commit();
            return redirect()->route('admin.assessments.index')->with('success', "Import complete! Successfully created {$successCount} assessments.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Assessment Import Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred during import. Please check your file.');
        }
    }
}