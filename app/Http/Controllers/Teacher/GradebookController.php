<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Result;
use App\Models\ClassSection;
use Illuminate\Support\Facades\Auth;

class GradebookController extends Controller
{
    /**
     * PAGE 1: Display a list of all classes assigned to the teacher.
     */
    public function index()
    {
        $classes = Auth::user()->classes()->with('subjects')->get();
        return view('teacher.gradebook.index', compact('classes'));
    }

    /**
     * PAGE 2: Display a list of assessments for the selected class.
     */
    public function showAssessments(ClassSection $classSection)
    {
        // Security Check
        if ($classSection->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized Action');
        }

        // Get all subject IDs for the class
        $subjectIds = $classSection->subjects()->pluck('subjects.id');

        // Find all assessments that match those subject IDs
        $assessments = Assessment::whereIn('subject_id', $subjectIds)
            ->orderBy('assessment_date', 'desc')
            ->get();
            
        return view('teacher.gradebook.assessments', compact('classSection', 'assessments'));
    }

    /**
     * PAGE 3: Display the results for the selected class and assessment.
     */
    public function showResults(ClassSection $classSection, Assessment $assessment)
    {
        // Security Check
        if ($classSection->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized Action');
        }

        // Get all students enrolled in the class.
        $students = $classSection->students()->orderBy('name')->get();

        // Get all relevant results in a single query.
        $results = Result::where('assessment_id', $assessment->id)
            ->whereIn('user_id', $students->pluck('id'))
            ->get()
            ->keyBy('user_id'); // Key by user_id for easy lookup in the view.

        return view('teacher.gradebook.results', compact('classSection', 'assessment', 'students', 'results'));
    }
}