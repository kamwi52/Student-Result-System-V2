<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\Subject;
use App\Models\AcademicSession;
use App\Models\GradingScale;
use App\Models\User;
use App\Models\Assignment; // Import the new Assignment model
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ClassSectionController extends Controller
{
    public function index(): View
    {
        // Add `assignments` to the `with` clause to eager-load the data
        $classes = ClassSection::with(['subjects', 'assignments.teacher', 'academicSession'])
                               ->withCount('students')
                               ->latest()
                               ->paginate(10);
        return view('admin.classes.index', compact('classes'));
    }

    public function create(): View
    {
        // No changes needed here, but kept for completeness
        $subjects = Subject::orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        $gradingScales = GradingScale::orderBy('name')->get();
        return view('admin.classes.create', compact('subjects', 'academicSessions', 'gradingScales'));
    }

    public function store(Request $request)
    {
        // When a class is created, we now redirect to the edit page to assign teachers
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
        // Fetch all the data needed for the new, advanced edit form
        $classSection->load(['subjects', 'assignments.teacher']);
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        $gradingScales = GradingScale::orderBy('name')->get();
        
        return view('admin.classes.edit', compact(
            'classSection', 'teachers', 'academicSessions', 'gradingScales'
        ));
    }

    public function update(Request $request, ClassSection $classSection)
    {
        // This method now contains the logic to save the teacher assignments
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:class_sections,name,' . $classSection->id,
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'grading_scale_id' => 'nullable|exists:grading_scales,id',
            'assignments' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $classSection, $validated) {
            $classSection->update($validated);

            // Clear all previous assignments for this class to start fresh
            $classSection->assignments()->delete();

            // Create new assignments based on the submitted form data
            if ($request->has('assignments')) {
                foreach ($request->assignments as $subject_id => $user_id) {
                    // Only create an assignment if a teacher was actually selected
                    if (!empty($user_id)) {
                        Assignment::create([
                            'class_section_id' => $classSection->id,
                            'subject_id' => $subject_id,
                            'user_id' => $user_id, // This is the teacher's ID
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.classes.index')->with('success', 'Class and teacher assignments updated successfully.');
    }

    // Your destroy and import methods can remain here...
    public function destroy(ClassSection $classSection) { /* ... */ }
    public function showImportForm() { /* ... */ }
    public function handleImport(Request $request) { /* ... */ }
}