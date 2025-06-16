<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assessment;
use App\Models\Result;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        // For non-students, just show a simple home page.
        if ($user->role !== 'student') {
            return view('home');
        }

        $student = $user;
        $enrolled_classes = $student->enrolledClasses()->with(['subject', 'teacher'])->get();
        $results_data = [];

        // Loop through each class the student is enrolled in
        foreach ($enrolled_classes as $class) {
            // Get all assessments for that class's academic session
            $assessments = Assessment::where('academic_session_id', $class->academic_session_id)->get();
            if ($assessments->isEmpty()) {
                continue; // Skip if no assessments are set up for this class's session
            }

            // Get all of the student's results for this specific class
            $results = Result::where('user_id', $student->id)
                                ->where('class_section_id', $class->id)
                                ->get();

            $total_weighted_marks = 0;

            // Calculate the student's final percentage
            foreach ($assessments as $assessment) {
                // Find the result for this specific assessment
                $result_for_assessment = $results->firstWhere('assessment_id', $assessment->id);

                if ($result_for_assessment && $assessment->max_marks > 0) {
                    // Calculate the score as a ratio (e.g., 85/100 = 0.85)
                    $score_ratio = $result_for_assessment->marks_obtained / $assessment->max_marks;
                    // Apply the weightage (e.g., 0.85 * 0.30)
                    $total_weighted_marks += $score_ratio * $assessment->weightage;
                }
            }
            
            // Convert to a final percentage (e.g., 0.885 -> 88.5)
            $final_percentage = round($total_weighted_marks * 100, 2);

            // Store all the calculated data for this class, keyed by the class ID
            $results_data[$class->id] = [
                'assessments' => $assessments,
                'results' => $results,
                'final_percentage' => $final_percentage,
                'final_letter_grade' => $this->getLetterGrade($final_percentage),
            ];
        }

        return view('home', compact('enrolled_classes', 'results_data'));
    }

    /**
     * Helper function to convert a percentage to a letter grade.
     * @param float $percentage
     * @return string
     */
    public function getLetterGrade(float $percentage): string
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 85) return 'A';
        if ($percentage >= 80) return 'A-';
        if ($percentage >= 75) return 'B+';
        if ($percentage >= 70) return 'B';
        if ($percentage >= 65) return 'B-';
        if ($percentage >= 60) return 'C+';
        if ($percentage >= 50) return 'C';
        if ($percentage >= 40) return 'D';
        return 'F';
    }
}