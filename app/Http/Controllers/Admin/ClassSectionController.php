<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\Subject; // Make sure this is imported
use App\Models\User; // Make sure this is imported
use App\Models\AcademicSession;
use App\Models\GradingScale;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClassSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Eager load relationships and add a count for enrolled students
        $classes = ClassSection::with(['academicSession', 'subjects'])
                                ->withCount('students')
                                ->latest()
                                ->paginate(10);

        return view('admin.classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $subjects = Subject::orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        $gradingScales = GradingScale::orderBy('name')->get();
        // Teachers are not strictly needed on the create page anymore for assignments,
        // but included for consistency if general class teachers are managed here.
        $teachers = User::where('role', 'teacher')->orderBy('name')->get(); 
        return view('admin.classes.create', compact('subjects', 'academicSessions', 'gradingScales', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'grading_scale_id' => 'required|exists:grading_scales,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $classSection = ClassSection::create(Arr::except($validated, ['subjects']));

        if (isset($validated['subjects'])) {
            $classSection->subjects()->sync($validated['subjects']);
        }

        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully. You can now edit it to assign teachers to subjects.');
    }

    /**
     * Show the form for editing the specified resource.
     * === THIS IS THE CORRECTED METHOD FOR QUALIFIED TEACHERS ===
     */
    public function edit(ClassSection $classSection): View
    {
        // === FIX: Ensure all subjects are fetched and passed ===
        $subjects = Subject::orderBy('name')->get(); 

        // Load teachers with their qualified subjects for dynamic filtering in the view
        $teachers = User::where('role', 'teacher')->with('qualifiedSubjects')->orderBy('name')->get();
        
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        $gradingScales = GradingScale::orderBy('name')->get();

        // Eager load class's assigned subjects (with pivot data) for current selections
        $classSection->load(['subjects' => function($query) {
            $query->withPivot('teacher_id'); // Ensure teacher_id is loaded from pivot table
        }]);

        // Pass all necessary variables to the view
        return view('admin.classes.edit', compact(
            'classSection', 
            'subjects', // Make sure $subjects is in compact()
            'teachers', 
            'academicSessions', 
            'gradingScales'
        ));
    }

    /**
     * Update the specified resource in storage.
     * === THIS METHOD HAS BEEN UPDATED WITH THE FIX ===
     */
    public function update(Request $request, ClassSection $classSection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'grading_scale_id' => 'required|exists:grading_scales,id',
            'subjects' => 'nullable|array', // This will come from the subject multi-select box
            'subjects.*' => 'exists:subjects,id',
            'assignments' => 'nullable|array', // This will come from the teacher dropdowns
            'assignments.*' => 'nullable|exists:users,id',
        ]);
        
        $classSection->update($request->only('name', 'academic_session_id', 'grading_scale_id'));
    
        // --- THIS IS THE CORRECTED LOGIC ---
        // We will build a sync array with teacher IDs first, then sync all at once.
        $syncData = [];
        if (isset($validated['assignments'])) {
            foreach ($validated['assignments'] as $subjectId => $teacherId) {
                // Ensure the subject was actually selected in the 'subjects' array
                if (in_array($subjectId, $validated['subjects'] ?? [])) {
                    // Check if the teacherId is valid (not null, not 0). If not, force it to be null.
                    $validTeacherId = ($teacherId && (int)$teacherId > 0) ? (int)$teacherId : null;
                    $syncData[$subjectId] = ['teacher_id' => $validTeacherId];
                }
            }
        }

        // Now, sync the subjects and their assigned teachers in a single, efficient operation.
        // This handles adding new subjects, removing unchecked subjects, and updating teacher assignments.
        $classSection->subjects()->sync($syncData);
        // --- END CORRECTED LOGIC ---
        
        return redirect()->route('admin.classes.index')->with('success', 'Class and assignments updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassSection $classSection)
    {
        if ($classSection->enrollments()->exists() || $classSection->assignments()->exists()) {
             return redirect()->route('admin.classes.index')
                         ->with('error', 'Cannot delete this class. It has students enrolled or assignments associated with it.');
        }

        $classSection->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully.');
    }

    /**
     * Display the form for uploading a class import file.
     */
    public function showImportForm(): View
    {
        return view('admin.classes.import');
    }

    /**
     * Process the uploaded CSV file to import classes.
     */
    public function handleImport(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            $file = $request->file('import_file');
            $file_handle = fopen($file->getRealPath(), 'r');
            if ($file_handle === false) {
                throw new \Exception("Could not open the uploaded file.");
            }
            
            $header = array_map('trim', fgetcsv($file_handle));
            if (isset($header[0]) && str_starts_with($header[0], "\xef\xbb\xbf")) {
                $header[0] = substr($header[0], 3);
            }
            
            $requiredColumns = ['name', 'academic_session_id', 'grading_scale_id'];
            if (count(array_diff($requiredColumns, $header)) > 0) {
                fclose($file_handle);
                throw new \Exception("Invalid CSV header. File must contain columns: 'name', 'academic_session_id', 'grading_scale_id'.");
            }

            $validSessionIds = AcademicSession::pluck('id')->flip();
            $validGradingScaleIds = GradingScale::pluck('id')->flip();
            $successCount = 0;
            $errorRows = [];
            $rowNumber = 1;

            while (($row = fgetcsv($file_handle, 1000, ",")) !== false) {
                $rowNumber++;
                if (empty(implode('', $row))) continue;
                $data = array_combine($header, array_map('trim', $row));

                try {
                    ClassSection::updateOrCreate(
                        ['name' => $data['name']],
                        [
                            'academic_session_id' => $data['academic_session_id'],
                            'grading_scale_id' => !empty($data['grading_scale_id']) ? $data['grading_scale_id'] : null,
                        ]
                    );
                    $successCount++;
                } catch (\Exception $e) {
                    $errorRows[] = "Row {$rowNumber}: " . $e->getMessage();
                }
            }
            fclose($file_handle);

            $message = "Import process finished. Successfully created or updated {$successCount} classes.";
            return redirect()->route('admin.classes.index')
                             ->with('success', $message)
                             ->with('import_errors', $errorRows);

        } catch (\Exception $e) {
            Log::error('Class Import Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred during import: ' . $e->getMessage());
        }
    }
}