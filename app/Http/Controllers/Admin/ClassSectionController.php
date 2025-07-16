<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\Subject;
use App\Models\AcademicSession;
use App\Models\GradingScale;
use App\Models\User;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClassSectionController extends Controller
{
    public function index(): View
    {
        $classes = ClassSection::with(['subjects', 'assignments.teacher', 'academicSession'])
                               ->withCount('students')
                               ->latest()
                               ->paginate(10);
        return view('admin.classes.index', compact('classes'));
    }

    public function create(): View
    {
        $subjects = Subject::orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        $gradingScales = GradingScale::orderBy('name')->get();
        return view('admin.classes.create', compact('subjects', 'academicSessions', 'gradingScales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:class_sections,name',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'grading_scale_id' => 'nullable|exists:grading_scales,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);
        $classSection = ClassSection::create($validated);
        if (!empty($validated['subjects'])) {
            $classSection->subjects()->attach($validated['subjects']);
        }
        return redirect()->route('admin.classes.edit', $classSection->id)
                         ->with('success', 'Class created successfully. Now you can assign teachers to each subject.');
    }
    
    public function edit(ClassSection $classSection): View
    {
        $classSection->load(['subjects', 'assignments.teacher']);
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        $gradingScales = GradingScale::orderBy('name')->get();
        return view('admin.classes.edit', compact('classSection', 'teachers', 'academicSessions', 'gradingScales'));
    }

    public function update(Request $request, ClassSection $classSection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:class_sections,name,' . $classSection->id,
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'grading_scale_id' => 'nullable|exists:grading_scales,id',
            'assignments' => 'nullable|array',
        ]);
        DB::transaction(function () use ($request, $classSection, $validated) {
            $classSection->update($validated);
            $classSection->assignments()->delete();
            if ($request->has('assignments')) {
                foreach ($request->assignments as $subject_id => $user_id) {
                    if (!empty($user_id)) {
                        Assignment::create([
                            'class_section_id' => $classSection->id,
                            'subject_id' => $subject_id,
                            'user_id' => $user_id,
                        ]);
                    }
                }
            }
        });
        return redirect()->route('admin.classes.index')->with('success', 'Class and teacher assignments updated successfully.');
    }

    public function destroy(ClassSection $classSection)
    {
        $classSection->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully.');
    }
    
    public function showImportForm(): View
    {
        return view('admin.classes.import');
    }

    /**
     * === CORRECTED VERSION ===
     * Handle the imported classes file with simpler, more direct logic.
     */
    public function handleImport(Request $request)
    {
        $request->validate(['classes_file' => 'required|file|mimes:csv,txt']);
        Log::info('--- CLASS IMPORT PROCESS STARTED ---');

        try {
            $file = $request->file('classes_file');
            $path = $file->getRealPath();
            $records = array_map('str_getcsv', file($path));

            if (count($records) < 1) {
                return redirect()->back()->with('import_errors', ['The uploaded file is empty or invalid.']);
            }

            $header = array_map('trim', array_shift($records));
            $requiredColumns = ['name', 'academic_session_name', 'subjects'];
            if ($header !== $requiredColumns) {
                throw new \Exception("Invalid CSV header. Expected: 'name,academic_session_name,subjects'.");
            }

            $import_errors = [];
            $success_count = 0;
            $academicSessions = AcademicSession::pluck('id', 'name')->all();
            $allSubjects = Subject::pluck('id', 'name')->all();

            foreach ($records as $key => $row) {
                $rowNumber = $key + 2;
                usleep(10000); // Prevents SQLite "database is locked" errors
                DB::beginTransaction();

                try {
                    // === THE FIX: Directly map row data by its position (index) ===
                    $className = trim($row[0]);
                    $sessionName = trim($row[1]);
                    $subjectNamesStr = trim($row[2]);
                    // =============================================================

                    if (empty($className)) throw new \Exception("The 'name' field cannot be empty.");
                    if (ClassSection::where('name', $className)->exists()) throw new \Exception("A class with the name '{$className}' already exists.");
                    if (!isset($academicSessions[$sessionName])) throw new \Exception("Academic session named '{$sessionName}' not found. Please ensure it exists and matches the CSV exactly.");
                    
                    $classSection = ClassSection::create([
                        'name' => $className,
                        'academic_session_id' => $academicSessions[$sessionName],
                    ]);
                    
                    if (!empty($subjectNamesStr)) {
                        $subjectNamesArray = explode('|', $subjectNamesStr);
                        $subjectIdsToAttach = [];
                        foreach ($subjectNamesArray as $subjectName) {
                            $trimmedSubjectName = trim($subjectName);
                            if (isset($allSubjects[$trimmedSubjectName])) {
                                $subjectIdsToAttach[] = $allSubjects[$trimmedSubjectName];
                            } else {
                                throw new \Exception("Subject '{$trimmedSubjectName}' was not found in the database.");
                            }
                        }
                        if (!empty($subjectIdsToAttach)) {
                            $classSection->subjects()->attach($subjectIdsToAttach);
                        }
                    }
                    DB::commit();
                    $success_count++;
                } catch (\Exception $e) {
                    DB::rollBack();
                    $import_errors[] = "Row $rowNumber: " . $e->getMessage();
                    Log::error("Row $rowNumber - FAILED: " . $e->getMessage());
                }
            }
            $message = "Import process finished. Successfully created {$success_count} classes.";
            return redirect()->route('admin.classes.index')->with('success', $message)->with('import_errors', $import_errors);
        } catch (\Exception $e) {
            Log::error('--- CLASS IMPORT PROCESS FAILED: ' . $e->getMessage());
            return redirect()->back()->with('import_errors', ['An unexpected error occurred: ' . $e->getMessage()]);
        }
    }
}