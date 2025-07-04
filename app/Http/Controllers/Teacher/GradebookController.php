<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Result;
use App\Models\SchoolClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradebookController extends Controller
{
    /**
     * Shows the form for editing the grades for a specific class.
     */
    public function edit(SchoolClass $class): View
    {
        $class->load('students');
        $students = $class->students()->orderBy('name')->get();
        $assessments = Assessment::orderBy('name')->get();
        $grades = Result::where('school_class_id', $class->id)
            ->get()
            ->keyBy(function ($item) {
                return $item['student_id'] . '-' . $item['assessment_id'];
            })
            ->map(function ($item) {
                return $item['score'];
            });

        return view('teacher.gradebook.edit', [
            'class'       => $class,
            'students'    => $students,
            'assessments' => $assessments,
            'grades'      => $grades,
        ]);
    }


    /**
     * Store or update the grades for the specified class.
     * This is the "Big Boss" method that does the heavy lifting.
     */
    public function store(Request $request, SchoolClass $class): RedirectResponse
    {
        // 1. VALIDATION: Ensure the data is in the correct format.
        $request->validate([
            'grades' => ['required', 'array'],
            // Ensure every grade submitted is for a real student and a real assessment
            'grades.*.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);


        // 2. LOOP AND SAVE: Iterate through every grade submitted from the form.
        foreach ($request->grades as $studentId => $assessments) {
            foreach ($assessments as $assessmentId => $score) {
                // Use updateOrCreate to be efficient.
                // It will CREATE a new record if one doesn't exist,
                // or UPDATE the existing one if it does.
                Result::updateOrCreate(
                    [
                        // The unique keys to find the record
                        'student_id'        => $studentId,
                        'school_class_id'   => $class->id,
                        'assessment_id'     => $assessmentId,
                    ],
                    [
                        // The value to save. Use null if the input box was empty.
                        'score' => empty($score) ? null : $score,
                    ]
                );
            }
        }

        // 3. REDIRECT WITH SUCCESS: Send the teacher back to their dashboard.
        // The 'with' method flashes a success message to the session.
        return redirect()->route('teacher.dashboard')
                         ->with('success', 'Grades for ' . $class->name . ' have been saved successfully!');
    }
}