<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassSection;
use App\Models\Assessment;
use App\Models\Assignment; // Import Assignment model
use App\Models\Result;

class BulkGradeController extends Controller
{
    /**
     * This old entry point now just redirects to the better workflow.
     */
    public function create()
    {
        return redirect()->route('teacher.gradebook.index');
    }

    /**
     * Show the main grade entry form (the grid).
     * It now receives an Assignment and Assessment from the route.
     */
    public function show(Assignment $assignment, Assessment $assessment)
    {
        // Security Check: Ensure the logged-in teacher owns this assignment.
        if ($assignment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized Action');
        }
        
        // Eager load related data for display
        $assignment->load('classSection', 'subject');
        
        // Get all students enrolled in the assignment's class.
        $students = $assignment->classSection->students;

        $existingResults = Result::where('assessment_id', $assessment->id)
                                 ->whereIn('user_id', $students->pluck('id'))
                                 ->get()->keyBy('user_id');

        return view('teacher.grades.bulk-show', compact('assignment', 'assessment', 'students', 'existingResults'));
    }

    /**
     * Store or update the grades for multiple students.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'assignment_id' => 'required|exists:assignments,id', // Used for finding grading scale & redirecting
            'scores' => 'required|array',
            'scores.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $assessmentId = $validated['assessment_id'];
        $assignment = Assignment::find($validated['assignment_id']);
        
        // Find the grading scale via the assignment's class section
        $classSection = $assignment->classSection()->with('gradingScale.grades')->first();

        foreach ($validated['scores'] as $studentId => $score) {
            if (!is_null($score)) {
                $remark = null; // Default remark
                if ($classSection && $classSection->gradingScale) {
                    $grade = $classSection->gradingScale->getGradeFromScore($score);
                    if ($grade && $grade->remark) {
                        $remark = $grade->remark;
                    }
                }
                
                Result::updateOrCreate(
                    ['user_id' => $studentId, 'assessment_id' => $assessmentId],
                    ['score' => $score, 'remark' => $remark]
                );
            }
        }

        // Redirect back to the results page for that assignment for a seamless experience.
        return redirect()->route('teacher.gradebook.results', [
            'assignment' => $validated['assignment_id'],
            'assessment' => $assessmentId,
        ])->with('success', 'Grades saved successfully.');
    }
    
    // Your other methods like downloadTemplate can stay, but may need refactoring
    // if you intend to use them with the new assignment-based system.
}