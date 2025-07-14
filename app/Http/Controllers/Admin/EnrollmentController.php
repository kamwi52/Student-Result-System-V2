<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\User;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Show the form to enroll students in a specific class.
     *
     * @param  \App\Models\ClassSection  $classSection
     * @return \Illuminate\View\View
     */
    public function index(ClassSection $classSection)
    {
        // Get all users with the 'student' role
        $allStudents = User::where('role', 'student')->orderBy('name')->get();

        // Get the IDs of students already enrolled in this class
        $enrolledStudentIds = $classSection->students()->pluck('users.id')->toArray();

        return view('admin.enrollments.index', [
            'classSection' => $classSection,
            'allStudents' => $allStudents,
            'enrolledStudentIds' => $enrolledStudentIds,
        ]);
    }

    /**
     * Store the updated enrollment list for the class.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassSection  $classSection
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, ClassSection $classSection)
    {
        $request->validate([
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        // The sync() method is perfect for this. It adds new students,
        // removes any that were unchecked, and leaves existing ones untouched.
        $classSection->students()->sync($request->input('student_ids', []));

        return redirect()->route('admin.classes.index')->with('success', "Enrollment for '{$classSection->name}' updated successfully.");
    }
}