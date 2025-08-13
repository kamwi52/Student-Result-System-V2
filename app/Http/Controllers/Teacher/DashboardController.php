<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassSection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the teacher's dashboard.
     *
     * This method fetches all class sections where the logged-in teacher is
     * assigned to at least one subject. It then eager loads the specific
     * subjects they teach and a count of enrolled students for display.
     */
    public function index(): View
    {
        // Get the ID of the currently authenticated teacher.
        $teacherId = Auth::id();

        // Find all ClassSections where the teacher has an assignment.
        $assignedClasses = ClassSection::query()
            ->whereHas('subjects', function ($query) use ($teacherId) {
                $query->where('class_section_subject.teacher_id', $teacherId);
            })
            // Eager load the related data we need for the view.
            ->with([
                // Load ONLY the subjects that this specific teacher is assigned to.
                'subjects' => function ($query) use ($teacherId) {
                    $query->where('class_section_subject.teacher_id', $teacherId);
                }
            ])
            // Eager load the COUNT of students for each class for efficiency.
            ->withCount('students')
            ->get();

        // Pass the prepared collection of assigned classes to the dashboard view.
        return view('teacher.dashboard', compact('assignedClasses'));
    }
}