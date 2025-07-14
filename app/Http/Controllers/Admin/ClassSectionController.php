<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\Subject;
use App\Models\AcademicSession;
use App\Models\GradingScale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ClassSectionController extends Controller
{
    // All your existing methods (index, create, store, etc.) are here
    public function index(): View
    {
        $classes = ClassSection::with(['subjects', 'teacher', 'academicSession'])->withCount('students')->latest()->paginate(10);
        return view('admin.classes.index', compact('classes'));
    }

    public function create(): View
    {
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        $gradingScales = class_exists(GradingScale::class) ? GradingScale::orderBy('name')->get() : collect();
        return view('admin.classes.create', compact('subjects', 'teachers', 'academicSessions','gradingScales'));
    }

    public function store(Request $request)
    {
        // ... store logic remains the same ...
    }

    public function edit(ClassSection $class)
    {
        // ... edit logic remains the same ...
    }

    public function update(Request $request, ClassSection $class)
    {
        // ... update logic remains the same ...
    }

    public function destroy(ClassSection $class)
    {
        // ... destroy logic remains the same ...
    }

    public function showImportForm()
    {
        return view('admin.classes.import-form');
    }
    
    /**
     * Handle the imported classes file - FINAL ROBUST VERSION WITH LOGGING
     */
    public function handleImport(Request $request)
    {
        Log::info('--- CLASS IMPORT PROCESS STARTED ---');
        try {
            if (!$request->hasFile('classes_file')) {
                throw new \Exception('No file was uploaded.');
            }
            $file = $request->file('classes_file');
            if (!$file->isValid()) {
                throw new \Exception('The uploaded file is not valid.');
            }
            Log::info('File validation passed.');

            $rows = array_map('str_getcsv', file($file->getPathname()));
            $header = array_shift($rows);
            Log::info('File read successfully. Found ' . count($rows) . ' data rows.');

            $requiredColumns = ['name', 'teacher_email', 'academic_session_name', 'subjects'];
            if (array_map('trim', $header) !== $requiredColumns) {
                throw new \Exception('Invalid CSV header. Must be exactly: name,teacher_email,academic_session_name,subjects');
            }
            Log::info('Header check passed.');

            $import_errors = [];
            $success_count = 0;
            
            $teachers = User::where('role', 'teacher')->pluck('id', 'email')->all();
            $academicSessions = AcademicSession::pluck('id', 'name')->all();
            $allSubjects = Subject::pluck('id', 'name')->all();
            Log::info('Pre-loaded ' . count($teachers) . ' teachers, ' . count($academicSessions) . ' sessions, and ' . count($allSubjects) . ' subjects.');

            foreach ($rows as $key => $row) {
                $rowNumber = $key + 2;
                DB::beginTransaction();
                try {
                    if (count($row) !== count($requiredColumns)) throw new \Exception("Incorrect number of columns.");
                    list($name, $teacher_email, $academic_session_name, $subject_names_str) = array_map('trim', $row);
                    if (empty($name)) throw new \Exception("The 'name' field is required.");
                    if (ClassSection::where('name', $name)->exists()) throw new \Exception("A class with the name '$name' already exists.");
                    $teacher_id = $teachers[$teacher_email] ?? null;
                    if (!$teacher_id) throw new \Exception("Teacher with email '$teacher_email' not found.");
                    $academic_session_id = $academicSessions[$academic_session_name] ?? null;
                    if (!$academic_session_id) throw new \Exception("Academic session '$academic_session_name' not found.");
                    $classSection = ClassSection::create(['name' => $name,'teacher_id' => $teacher_id,'academic_session_id' => $academic_session_id,]);
                    if (!empty($subject_names_str)) {
                        $subjectIdsToAttach = [];
                        $subjectNamesArray = explode('|', $subject_names_str);
                        foreach ($subjectNamesArray as $subjectName) {
                            $trimmedSubjectName = trim($subjectName);
                            if (isset($allSubjects[$trimmedSubjectName])) {
                                $subjectIdsToAttach[] = $allSubjects[$trimmedSubjectName];
                            } elseif (!empty($trimmedSubjectName)) {
                                throw new \Exception("Subject '$trimmedSubjectName' was not found.");
                            }
                        }
                        if (!empty($subjectIdsToAttach)) {
                            $classSection->subjects()->attach($subjectIdsToAttach);
                        }
                    }
                    DB::commit();
                    $success_count++;
                    Log::info("Row $rowNumber - SUCCESS: Class '$name' created.");
                } catch (\Exception $e) {
                    DB::rollBack();
                    $errorMessage = $e->getMessage();
                    $import_errors[] = "Row $rowNumber: " . $errorMessage;
                    Log::error("Row $rowNumber - FAILED: " . $errorMessage);
                }
            }

            $message = "Import process finished. Successfully created $success_count classes.";
            
            if (!empty($import_errors)) {
                return redirect()->route('admin.classes.index')->with('success', $message)->with('import_errors', $import_errors);
            }
            return redirect()->route('admin.classes.index')->with('success', $message);

        } catch (\Exception $e) {
            Log::error('--- CLASS IMPORT PROCESS FAILED: ' . $e->getMessage());
            return redirect()->back()->with('import_errors', ['An unexpected error occurred: ' . $e->getMessage()]);
        }
    }
    // Inside app/Http/Controllers/Admin/ClassSectionController.php

// ADD THESE TWO NEW METHODS

/**
 * Display the student enrollment form.
 */
public function showEnrollForm()
{
    return view('admin.classes.enroll-form');
}

/**
 * Handle the bulk student enrollment from a CSV file.
 */
public function handleEnrollmentImport(Request $request)
{
    $request->validate([
        'enrollments_file' => 'required|file|mimes:csv,txt',
    ]);

    $file = $request->file('enrollments_file');
    $rows = array_map('str_getcsv', file($file->getPathname()));
    $header = array_map('trim', array_shift($rows));

    $requiredHeader = ['student_email', 'class_name'];
    if ($header !== $requiredHeader) {
        return redirect()->back()->with('import_errors', ['Invalid CSV header. Must be: student_email,class_name']);
    }

    $errors = [];
    $successCount = 0;
    
    // Cache lookups to avoid querying in a loop
    $students = User::where('role', 'student')->pluck('id', 'email');
    $classes = ClassSection::pluck('id', 'name');

    foreach ($rows as $key => $row) {
        $rowNumber = $key + 2;
        $data = array_combine($header, $row);

        $studentEmail = trim($data['student_email']);
        $className = trim($data['class_name']);

        if (!isset($students[$studentEmail])) {
            $errors[] = "Row #{$rowNumber}: Student with email '{$studentEmail}' not found or is not a student.";
            continue;
        }

        if (!isset($classes[$className])) {
            $errors[] = "Row #{$rowNumber}: Class with name '{$className}' not found.";
            continue;
        }

        $studentId = $students[$studentEmail];
        $classId = $classes[$className];

        try {
            // Use a raw DB insert for the pivot table for efficiency
            // and to avoid model events if not needed.
            DB::table('enrollments')->updateOrInsert(
                ['user_id' => $studentId, 'class_section_id' => $classId],
                ['user_id' => $studentId, 'class_section_id' => $classId]
            );
            $successCount++;
        } catch (\Exception $e) {
            $errors[] = "Row #{$rowNumber}: Could not enroll student '{$studentEmail}' in class '{$className}'. Error: " . $e->getMessage();
        }
    }

    $message = "Import process finished. {$successCount} enrollments were successfully created or updated.";

    return redirect()->back()->with('success', $message)->with('import_errors', $errors);
}
}