<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassSection;
use App\Models\Result;

class DashboardController extends Controller
{
    /**
     * Show the student's dashboard with a list of their enrolled classes.
     */
    public function index()
    {
        $student = Auth::user();
        
        // Get all classes the student is enrolled in.
        // Eager-load 'teacher' and 'subjects' for efficient display.
        $enrollments = $student->enrollments()->with('classSection.teacher', 'classSection.subjects')->get();
        
        return view('student.dashboard', compact('enrollments'));
    }

    /**
     * Show all of the student's results for a specific class.
     */
    public function showResults(ClassSection $classSection)
    {
        $student = Auth::user();

        // Security Check: Ensure the student is actually enrolled in the requested class.
        $isEnrolled = $student->enrollments()->where('class_section_id', $classSection->id)->exists();
        if (!$isEnrolled) {
            abort(403, 'You are not enrolled in this class.');
        }

        // Get all subject IDs for the class the student is viewing.
        $subjectIds = $classSection->subjects()->pluck('subjects.id');
        
        // Get all results FOR THIS STUDENT where the assessment's subject
        // is one of the subjects taught in the selected class.
        $results = Result::where('user_id', $student->id)
            ->whereHas('assessment', function ($query) use ($subjectIds) {
                $query->whereIn('subject_id', $subjectIds);
            })
            ->with('assessment.subject') // Eager load assessment and subject details
            ->get()
            ->sortBy('assessment.subject.name'); // Group results by subject for clean display

        return view('student.results', compact('classSection', 'results'));
    }
}