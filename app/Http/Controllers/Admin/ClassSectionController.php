<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\Subject;
use App\Models\User;
use App\Models\AcademicSession;
use App\Models\GradingScale;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse; // <-- Added
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Throwable;

class ClassSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = ClassSection::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'LIKE', "%{$searchTerm}%");
        }

        $classes = $query->with(['academicSession', 'subjects'])
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
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        return view('admin.classes.create', compact('subjects', 'academicSessions', 'gradingScales', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('class_sections')->where(function ($query) use ($request) {
                    return $query->where('academic_session_id', $request->academic_session_id);
                }),
            ],
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
     */
    public function edit(ClassSection $classSection): View
    {
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->with('qualifiedSubjects')->orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        $gradingScales = GradingScale::orderBy('name')->get();
        $classSection->load(['subjects' => function($query) {
            $query->withPivot('teacher_id');
        }]);

        return view('admin.classes.edit', compact(
            'classSection', 'subjects', 'teachers', 'academicSessions', 'gradingScales'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassSection $classSection)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('class_sections')->where(function ($query) use ($request) {
                    return $query->where('academic_session_id', $request->academic_session_id);
                })->ignore($classSection->id),
            ],
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'grading_scale_id' => 'required|exists:grading_scales,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'assignments' => 'nullable|array',
            'assignments.*' => 'nullable|exists:users,id',
        ]);
        
        $classSection->update($request->only('name', 'academic_session_id', 'grading_scale_id'));
    
        $syncData = [];
        if (isset($validated['assignments'])) {
            foreach ($validated['assignments'] as $subjectId => $teacherId) {
                if (in_array($subjectId, $validated['subjects'] ?? [])) {
                    $validTeacherId = ($teacherId && (int)$teacherId > 0) ? (int)$teacherId : null;
                    $syncData[$subjectId] = ['teacher_id' => $validTeacherId];
                }
            }
        }
        $classSection->subjects()->sync($syncData);
        
        return redirect()->route('admin.classes.index')->with('success', 'Class and assignments updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassSection $classSection)
    {
        if ($classSection->enrollments()->exists() || $classSection->subjects()->exists()) {
             return redirect()->route('admin.classes.index')
                         ->with('error', 'Cannot delete class. It has students enrolled or subjects assigned.');
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
        $request->validate([ 'classes_file' => 'required|file|mimes:csv,txt' ]);

        try {
            // ... (Your existing import logic) ...
            return redirect()->route('admin.classes.index')->with('success', 'Import successful!');
        } catch (Throwable $e) {
            Log::error('Class Import Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'A critical error occurred during import.');
        }
    }

    /**
     * Display the view for the hardcoded POST test.
     */
    public function showPostTest(): View
    {
        return view('admin.classes.test-import');
    }

    /**
     * Handle the hardcoded POST test.
     */
    public function handlePostTest(Request $request)
    {
        try {
            // ... (Your existing test logic) ...
        } catch (Throwable $e) {
            // ... (error handling) ...
        }
    }
    
    /**
     * === THIS IS THE NEW METHOD ===
     * Responds to an AJAX request with a JSON list of subjects for the given class.
     */
    public function getSubjectsJson(ClassSection $classSection): JsonResponse
    {
        return response()->json($classSection->subjects()->orderBy('name')->get());
    }
}