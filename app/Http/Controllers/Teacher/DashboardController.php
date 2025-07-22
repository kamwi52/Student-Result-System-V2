<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassSection; // <-- Make sure this is imported

class DashboardController extends Controller
{
    /**
     * Show the teacher's dashboard.
     *
     * This method will now fetch all classes assigned to the teacher and the
     * specific subjects they teach within those classes.
     */
    public function index()
    {
        // Get the currently authenticated teacher
        $teacher = Auth::user();

        // This is the key query to get the teacher's assignments.
        // It's efficient and uses constrained eager loading.
        $assignedClasses = ClassSection::query()
            // 1. Find only the classes where this teacher is assigned to at least one subject.
            ->whereHas('subjects', function ($query) use ($teacher) {
                // The 'subjects' relationship looks at the 'class_section_subject' pivot table.
                // We are checking the 'teacher_id' column on that pivot table.
                $query->where('teacher_id', $teacher->id);
            })
            // 2. Eager load the subjects, but ONLY the ones this teacher teaches in each class.
            ->with(['subjects' => function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            }])
            // 3. Eager load the count of students in each class for display.
            ->withCount('students')
            ->orderBy('name')
            ->get();

        // Pass the collection of assigned classes to the view
        return view('teacher.dashboard', compact('assignedClasses'));
    }
}