<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Result;
use App\Models\ClassSection;
use App\Models\Assignment; // Import the new Assignment model
use Illuminate\Support\Facades\Auth;

class GradebookController extends Controller
{
    /**
     * PAGE 1: Display a list of all "Class-Subject" assignments for the teacher.
     */
    public function index()
    {
        $teacher = Auth::user();
        
        // Get all assignments for the teacher, eager-loading the related class and subject
        $assignments = $teacher->assignments()->with(['classSection', 'subject'])->get();
        
        return view('teacher.gradebook.index', compact('assignments'));
    }

    /**
     * PAGE 2: Display a list of assessments for the selected assignment.
     * We receive the Assignment model directly from the route.
     */
    public function showAssessments(Assignment $assignment)
    {
        // Security Check: Ensure the logged-in teacher owns this assignment.
        if ($assignment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized Action');
        }

        // Eager load the class and subject for display
        $assignment->load('classSection', 'subject');

        // Find all assessments that match the assignment's subject AND the class's academic session.
        $assessments = Assessment::where('subject_id', $assignment->subject_id)
            ->where('academic_session_id', $assignment->classSection->academic_session_id)
            ->orderBy('assessment_date', 'desc')
            ->get();
            
        return view('teacher.gradebook.assessments', compact('assignment', 'assessments'));
    }

    /**
     * PAGE 3: Display the results for the selected assignment and assessment.
     */
    public function showResults(Assignment $assignment, Assessment $assessment)
    {
        // Security Check
        if ($assignment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized Action');
        }
        
        // Eager load for display
        $assignment->load('classSection', 'subject');

        // Get all students enrolled in the assignment's class.
        $students = $assignment->classSection->students()->orderBy('name')->get();

        // Get all relevant results.
        $results = Result::where('assessment_id', $assessment->id)
            ->whereIn('user_id', $students->pluck('id'))
            ->get()
            ->keyBy('user_id');

        return view('teacher.gradebook.results', compact('assignment', 'assessment', 'students', 'results'));
    }
}