<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassSection;

class DashboardController extends Controller
{
    /**
     * Show the teacher's dashboard.
     *
     * This method fetches all class sections where the logged-in teacher is
     * assigned to at least one subject. It then eager loads the specific
     * subjects they teach within those classes, along with the academic session info.
     * This provides all the necessary data for the teacher's dashboard view.
     */
    public function index()
    {
        // Get the ID of the currently authenticated teacher.
        $teacherId = Auth::id();

        // This is the key query to get all the teacher's assignments.
        $assignedClasses = ClassSection::query()
            // 1. Find only the ClassSections where the teacher has an assignment
            //    in the `class_section_subject` pivot table.
            ->whereHas('subjects', function ($query) use ($teacherId) {
                $query->where('class_section_subject.teacher_id', $teacherId);
            })
            // 2. Eager load the related data we need for the view to be efficient.
            ->with([
                // Load the academic session for each class (e.g., "2024-2025")
                'academicSession',
                // Load the subjects, but constrain this to ONLY the subjects
                // that this specific teacher is assigned to in each class.
                'subjects' => function ($query) use ($teacherId) {
                    $query->where('class_section_subject.teacher_id', $teacherId);
                }
            ])
            ->get();

        // Pass the prepared collection of assigned classes to the dashboard view.
        return view('teacher.dashboard', compact('assignedClasses'));
    }
}