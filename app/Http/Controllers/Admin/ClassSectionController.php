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
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:class_sections,name',
            'teacher_id' => 'nullable|exists:users,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'grading_scale_id' => 'nullable|exists:grading_scales,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $classSection = ClassSection::create($validated);
        
        if (!empty($validated['subjects'])) {
            $classSection->subjects()->attach($validated['subjects']);
        }

        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully.');
    }
    
    public function edit(ClassSection $classSection): View
    {
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        $gradingScales = class_exists(GradingScale::class) ? GradingScale::orderBy('name')->get() : collect();

        return view('admin.classes.edit', compact(
            'classSection', 
            'subjects', 
            'teachers', 
            'academicSessions', 
            'gradingScales'
        ));
    }

    public function update(Request $request, ClassSection $classSection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:class_sections,name,' . $classSection->id,
            'teacher_id' => 'nullable|exists:users,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'grading_scale_id' => 'nullable|exists:grading_scales,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);
        
        $classSection->subjects()->sync($request->input('subjects', []));

        $classSection->update($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(ClassSection $classSection)
    {
        $classSection->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully.');
    }

    // --- YOUR EXISTING IMPORT METHODS (UNCHANGED) ---
    // public function showImportForm() { /* ... */ }
    // public function handleImport(Request $request) { /* ... */ }
    // public function showEnrollForm() { /* ... */ }
    // public function handleEnrollmentImport(Request $request) { /* ... */ }
}