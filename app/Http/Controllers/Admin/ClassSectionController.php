<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\ClassSection;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   // In app/Http/Controllers/Admin/ClassSectionController.php

public function index(): View
{
    // === MODIFIED: Added withCount('students') to the query ===
    $classes = ClassSection::with(['subject', 'teacher', 'academicSession'])
        ->withCount('students') // <-- THIS IS THE NEW LINE
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
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name')->get();
        return view('admin.classes.create', compact('subjects', 'teachers', 'academicSessions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
        ]);
        ClassSection::create($request->all());
        return to_route('admin.classes.index')->with('success', 'Class created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassSection $class): View
    {
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name')->get();
        return view('admin.classes.edit', compact('class', 'subjects', 'teachers', 'academicSessions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassSection $class)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
        ]);
        $class->update($request->all());
        return to_route('admin.classes.index')->with('success', 'Class updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassSection $class)
    {
        $class->delete();
        return to_route('admin.classes.index')->with('success', 'Class deleted successfully.');
    }
}