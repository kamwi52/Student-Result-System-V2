<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Subject;
use App\Models\AcademicSession;
use App\Models\User;
use App\Models\ClassSection; // <-- IMPORT THIS MODEL
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssessmentController extends Controller
{
    // ... (your existing methods) ...

    /**
     * Display a listing of the resource (now Assignments).
     */
    public function index(): View
    {
        $assessments = Assignment::with(['subject', 'academicSession', 'teacher', 'classSection']) // Eager load classSection too
                                ->latest()
                                ->paginate(10);
        return view('admin.assessments.index', compact('assessments'));
    }

    /**
     * Show the form for creating a new resource (Assignment).
     */
    public function create(): View
    {
        $subjects = Subject::orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $classSections = ClassSection::orderBy('name')->get(); // <-- Fetch all classes

        return view('admin.assessments.create', compact('subjects', 'academicSessions', 'teachers', 'classSections')); // <-- Pass classes
    }

    /**
     * Store a newly created resource (Assignment) in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'max_marks' => 'required|numeric|min:0',
            'weightage' => 'nullable|numeric|min:0|max:100',
            'assessment_date' => 'required|date',
            'teacher_id' => 'required|exists:users,id',
            'class_section_id' => 'required|exists:class_sections,id', // <-- ADD THIS VALIDATION
        ]);

        Assignment::create($validated); 

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment created successfully.');
    }

    /**
     * Show the form for editing the specified resource (Assignment).
     */
    public function edit(Assignment $assessment): View
    {
        $subjects = Subject::orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $classSections = ClassSection::orderBy('name')->get(); // <-- Fetch all classes

        return view('admin.assessments.edit', compact('assessment', 'subjects', 'academicSessions', 'teachers', 'classSections')); // <-- Pass classes
    }

    /**
     * Update the specified resource (Assignment) in storage.
     */
    public function update(Request $request, Assignment $assessment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'max_marks' => 'required|numeric|min:0',
            'weightage' => 'nullable|numeric|min:0|max:100',
            'assessment_date' => 'required|date',
            'teacher_id' => 'required|exists:users,id',
            'class_section_id' => 'required|exists:class_sections,id', // <-- ADD THIS VALIDATION
        ]);

        $assessment->update($validated);

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment updated successfully.');
    }

    // ... (Import methods: showImportForm, handleImport) ...
    public function handleImport(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            $file = $request->file('import_file');
            $path = $file->getRealPath();
            
            $file_handle = fopen($path, 'r');
            if ($file_handle === false) {
                throw new \Exception("Could not open the uploaded file.");
            }

            // Updated required columns for import, assuming CSV now includes class_section_id
            $header = array_map('trim', fgetcsv($file_handle));
            $requiredColumns = ['name', 'subject_id', 'academic_session_id', 'max_marks', 'weightage', 'assessment_date', 'teacher_id', 'class_section_id']; // <-- ADD class_section_id
            if (count(array_diff($requiredColumns, $header)) > 0) {
                fclose($file_handle);
                throw new \Exception("Invalid CSV header. Expected: 'name,subject_id,academic_session_id,max_marks,weightage,assessment_date,teacher_id,class_section_id'.");
            }

            $validSubjectIds = Subject::pluck('id')->flip();
            $validSessionIds = AcademicSession::pluck('id')->flip();
            $validTeacherIds = User::where('role', 'teacher')->pluck('id')->flip();
            $validClassIds = ClassSection::pluck('id')->flip(); // <-- NEW VALIDATION FOR CLASSES

            $successCount = 0;
            $errorRows = [];
            $rowNumber = 1;

            while (($row = fgetcsv($file_handle, 1000, ",")) !== false) {
                $rowNumber++;
                if (empty(implode('', $row))) continue;
                $data = array_combine($header, array_map('trim', $row));

                try {
                    if (empty($data['name'])) throw new \Exception("Name is empty.");
                    if (!$validSubjectIds->has($data['subject_id'])) throw new \Exception("Subject ID '{$data['subject_id']}' not found.");
                    if (!$validSessionIds->has($data['academic_session_id'])) throw new \Exception("Academic Session ID '{$data['academic_session_id']}' not found.");
                    if (!is_numeric($data['max_marks']) || $data['max_marks'] < 0) throw new \Exception("Max Marks must be a non-negative number.");
                    if (!empty($data['weightage']) && (!is_numeric($data['weightage']) || $data['weightage'] < 0 || $data['weightage'] > 100)) throw new \Exception("Weightage must be a number between 0 and 100.");
                    if (empty($data['assessment_date'])) throw new \Exception("Assessment Date is empty.");
                    if (!$validTeacherIds->has($data['teacher_id'])) throw new \Exception("Teacher ID '{$data['teacher_id']}' not found or is not a teacher.");
                    if (!$validClassIds->has($data['class_section_id'])) throw new \Exception("Class Section ID '{$data['class_section_id']}' not found."); // <-- NEW CLASS VALIDATION

                    DB::transaction(function () use ($data) {
                        Assignment::create([
                            'name' => $data['name'],
                            'subject_id' => $data['subject_id'],
                            'academic_session_id' => $data['academic_session_id'],
                            'max_marks' => $data['max_marks'],
                            'weightage' => !empty($data['weightage']) ? $data['weightage'] : null,
                            'assessment_date' => $data['assessment_date'],
                            'teacher_id' => $data['teacher_id'],
                            'class_section_id' => $data['class_section_id'], // <-- ADD THIS TO CREATE
                        ]);
                    });
                    $successCount++;
                } catch (\Exception $e) {
                    $errorRows[] = "Row {$rowNumber}: " . $e->getMessage();
                }
            }
            fclose($file_handle);

            $message = "Import finished. Successfully imported {$successCount} assessments.";
            return redirect()->route('admin.assessments.index')
                             ->with('success', $message)
                             ->with('import_errors', $errorRows);

        } catch (\Exception $e) {
            Log::error('Assessment Import Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }
}