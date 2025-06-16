<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\Subject;
use App\Models\User;
use App\Models\AcademicSession;
use Illuminate\Http\Request;

class ClassSectionController extends Controller
{
    public function index()
    {
        $class_sections = ClassSection::with(['subject', 'teacher', 'academicSession'])->latest()->paginate(10);
        return view('admin.classes.index', compact('class_sections'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $academic_sessions = AcademicSession::orderBy('name', 'desc')->get();
        return view('admin.classes.create', compact('subjects', 'teachers', 'academic_sessions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'user_id' => 'required|exists:users,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
        ]);
        ClassSection::create($validated);
        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully.');
    }

    public function show(ClassSection $class)
    {
        //
    }

    public function edit(ClassSection $class)
    {
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $academic_sessions = AcademicSession::orderBy('name', 'desc')->get();
        return view('admin.classes.edit', compact('class', 'subjects', 'teachers', 'academic_sessions'));
    }

    public function update(Request $request, ClassSection $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'user_id' => 'required|exists:users,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
        ]);
        $class->update($validated);
        // Corrected the redirect route here
        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(ClassSection $class)
    {
        $class->delete();
        // Corrected the redirect route here
        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully.');
    }
}