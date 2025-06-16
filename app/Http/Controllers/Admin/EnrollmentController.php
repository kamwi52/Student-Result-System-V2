<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassSection;
use App\Models\User;

class EnrollmentController extends Controller
{
    /**
     * Display the enrollment management page.
     */
    public function index(Request $request)
    {
        $classes = ClassSection::with(['subject', 'teacher'])->get();
        $students = User::where('role', 'student')->orderBy('name', 'asc')->get();

        $selectedClass = null;
        $enrolledStudentIds = [];

        if ($request->has('class_id') && $request->class_id != '') {
            $selectedClass = ClassSection::with('students')->find($request->class_id);
            if ($selectedClass) {
                $enrolledStudentIds = $selectedClass->students->pluck('id')->toArray();
            }
        }

        return view('admin.enrollments.index', compact('classes', 'students', 'selectedClass', 'enrolledStudentIds'));
    }

    /**
     * Store the updated enrollment information.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'student_ids' => 'sometimes|array'
        ]);

        $class = ClassSection::findOrFail($request->class_id);

        $studentIds = $request->input('student_ids', []);

        $class->students()->sync($studentIds);

        // ====================================================
        // THIS IS THE CORRECTED REDIRECT
        // ====================================================
        return redirect()->route('admin.enrollments.index', ['class_id' => $class->id])
                         ->with('success', 'Enrollments updated successfully!');
    }
}