<?php

namespace App\Http\Controllers;

use App\Models\ClassSection;
use App\Models\User;
use App\Models\Assessment;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF; // <-- Import the PDF facade

class ReportCardController extends Controller
{
    public function download(ClassSection $class, User $student)
    {
        // --- Authorization Check ---
        // Allow if the user is an admin
        // Allow if the user is the student themselves
        // Allow if the user is the teacher of the class
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->id !== $student->id && $user->id !== $class->user_id) {
            abort(403, 'Unauthorized Action');
        }

        // --- Data Fetching ---
        $results_data = $this->getResultsDataForStudent($class, $student);

        // --- PDF Generation ---
        $pdf = PDF::loadView('pdf.report_card', [
            'student' => $student,
            'class' => $class,
            'results_data' => $results_data,
        ]);

        // --- Stream Download ---
        $filename = 'Report-Card-' . $student->name . '-' . $class->subject->name . '.pdf';
        return $pdf->download($filename);
    }

    // Helper function to get all results and calculations for a student in a class
    private function getResultsDataForStudent(ClassSection $class, User $student)
    {
        $assessments = Assessment::where('academic_session_id', $class->academic_session_id)->get();
        $results = Result::where('user_id', $student->id)->where('class_section_id', $class->id)->get();
        $total_weighted_marks = 0;

        foreach ($assessments as $assessment) {
            $result_for_assessment = $results->firstWhere('assessment_id', $assessment->id);
            if ($result_for_assessment && $assessment->max_marks > 0) {
                $score_ratio = $result_for_assessment->marks_obtained / $assessment->max_marks;
                $total_weighted_marks += $score_ratio * $assessment->weightage;
            }
        }
        
        $final_percentage = round($total_weighted_marks * 100, 2);

        return [
            'assessments' => $assessments,
            'results' => $results,
            'final_percentage' => $final_percentage,
            'final_letter_grade' => (new HomeController)->getLetterGrade($final_percentage),
        ];
    }
}