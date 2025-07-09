<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Assessment;
use App\Models\ClassSection;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GradeController extends Controller
{
    /**
     * Show the form for entering grades for a specific class.
     *
     * @param  \App\Models\ClassSection  $classSection
     * @return \Illuminate\View\View
     */
    public function enterGrades(Request $request, ClassSection $classSection): View
    {
        // Get the current academic session based on the class
        $academicSessionId = $classSection->academic_session_id;

        // Fetch all assessments for that academic session
        $assessments = Assessment::where('academic_session_id', $academicSessionId)
                                 ->orderBy('name')
                                 ->get();

        // Get the ID of the assessment we are currently viewing/entering grades for
        // It comes from the query string, e.g., ?assessment_id=1
        $selectedAssessmentId = $request->query('assessment_id');
        $selectedAssessment = $selectedAssessmentId ? Assessment::find($selectedAssessmentId) : null;

        // Get all students enrolled in the class, ordered by name
        $students = $classSection->students()->orderBy('name')->get();

        // Pre-fetch existing results for these students for the selected assessment
        // This is for displaying already saved scores in the form
        $existingResults = Result::where('class_id', $classSection->id)
                                 ->where('assessment_id', $selectedAssessmentId)
                                 ->pluck('score', 'user_id'); // Creates an array like [student_id => score]

        return view('teacher.grades.enter', compact(
            'classSection',
            'assessments',
            'selectedAssessment',
            'students',
            'existingResults'
        ));
    }

    /**
     * Store or update the grades for the class and assessment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassSection  $classSection
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeGrades(Request $request, ClassSection $classSection)
    {
        $validated = $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'scores' => 'required|array',
            // Validate that each score is for a valid student and is a numeric value
            'scores.*.user_id' => 'required|exists:users,id',
            'scores.*.score' => 'nullable|numeric|min:0',
        ]);

        $assessment = Assessment::find($validated['assessment_id']);

        // Use a database transaction to ensure all grades are saved or none are.
        DB::transaction(function () use ($validated, $classSection, $assessment) {
            foreach ($validated['scores'] as $studentScoreData) {
                // Skip if the score is not entered (is null)
                if (is_null($studentScoreData['score'])) {
                    continue;
                }

                // Use updateOrCreate to either update an existing result or create a new one.
                // This prevents duplicate entries.
                Result::updateOrCreate(
                    [
                        'user_id' => $studentScoreData['user_id'],
                        'class_id' => $classSection->id,
                        'assessment_id' => $assessment->id,
                    ],
                    [
                        'score' => $studentScoreData['score'],
                        // You could add a 'remarks' field here if you add it to the form
                    ]
                );
            }
        });

        // Redirect back to the same page with a success message
        return redirect()
            ->route('teacher.grades.enter', ['classSection' => $classSection->id, 'assessment_id' => $assessment->id])
            ->with('success', 'Grades have been saved successfully!');
    }
}