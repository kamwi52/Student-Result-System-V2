<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Result;
use App\Models\ClassSection;
use App\Models\Assignment; // Import the Assignment model
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View; // Import View

class GradebookController extends Controller
{
    /**
     * PAGE 1: Display a list of all "Class-Subject-Teacher" assignments for the logged-in teacher.
     */
    public function index(): View
    {
        $teacher = Auth::user();
        
        // Get all assignments for the teacher, eager-loading the related class and subject
        $assignments = $teacher->assignments()
                               ->with(['classSection', 'subject']) // Eager load relationships used in the view
                               ->latest()
                               ->paginate(10); // Added pagination for large datasets
        
        return view('teacher.gradebook.index', compact('assignments'));
    }

    /**
     * PAGE 2: Display a list of assessments for the selected assignment.
     * We receive the Assignment model directly from the route.
     */
    public function showAssessments(Assignment $assignment): View
    {
        // Security Check: Ensure the logged-in teacher owns this assignment.
        if ($assignment->teacher_id !== Auth::id()) { // Changed from user_id to teacher_id
            abort(403, 'Unauthorized Action');
        }

        // Eager load the class and subject for display
        $assignment->load('classSection', 'subject');

        // Find all assessments (templates) that match the assignment's subject AND academic session.
        // Assuming Assignment itself is a teaching instance, not an assessment template.
        $assessments = Assessment::where('subject_id', $assignment->subject_id)
            ->where('academic_session_id', $assignment->academicSession->id ?? null) // Use academicSession relationship from Assignment
            ->orderBy('assessment_date', 'desc')
            ->get();
            
        return view('teacher.gradebook.assessments', compact('assignment', 'assessments'));
    }

    /**
     * PAGE 3: Display the results for the selected assignment and assessment.
     */
    public function showResults(Assignment $assignment, Assessment $assessment): View
    {
        // Security Check
        if ($assignment->teacher_id !== Auth::id()) { // Changed from user_id to teacher_id
            abort(403, 'Unauthorized Action');
        }
        
        // Eager load for display
        $assignment->load('classSection', 'subject');

        // Get all students enrolled in the assignment's class.
        $students = $assignment->classSection->students()->orderBy('name')->get();

        // Get all relevant results.
        $results = Result::where('assessment_id', $assessment->id)
            ->whereIn('user_id', $students->pluck('id')) // user_id in results is the student's ID
            ->get()
            ->keyBy('user_id');

        return view('teacher.gradebook.results', compact('assignment', 'assessment', 'students', 'results'));
    }
}