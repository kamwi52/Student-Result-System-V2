<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassSection;
use App\Models\Assessment;
use App\Models\Assignment;
use App\Models\Result;
use Illuminate\View\View; // Ensure View is imported if not already

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
     * It receives an Assignment and Assessment from the route.
     */
    public function show(Assignment $assignment, Assessment $assessment): View
    {
        $teacher = Auth::user();

        // Security Check: Ensure the logged-in teacher owns this assignment.
        // The fix: Changed $assignment->user_id to $assignment->teacher_id
        if ($assignment->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized Action: You are not assigned to this assignment.');
        }
        
        // Security Check: Ensure the assessment belongs to the same subject as the assignment
        if ($assessment->subject_id !== $assignment->subject_id) {
            abort(403, 'Unauthorized Action: Assessment subject mismatch for this assignment.');
        }

        // Eager load related data for display
        $assignment->load('classSection', 'subject');
        
        // Get all students enrolled in the assignment's class.
        $students = $assignment->classSection->students()->orderBy('name')->get(); // Added orderBy('name')->get()

        // Fetch existing results for this assessment and the students in this class.
        $existingResults = Result::where('assignment_id', $assignment->id) // Filter by specific assignment
                                 ->whereIn('user_id', $students->pluck('id'))
                                 ->get()
                                 ->keyBy('user_id'); // Key by user_id for easy lookup

        return view('teacher.grades.bulk-show', compact('assignment', 'assessment', 'students', 'existingResults'));
    }

    /**
     * Store or update the grades for multiple students.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'assignment_id' => 'required|exists:assignments,id', 
            'scores' => 'required|array',
            'scores.*' => 'nullable|numeric|min:0|max:100', // Assuming scores are between 0 and 100
            'remarks' => 'nullable|array', // Added remarks field
            'remarks.*' => 'nullable|string|max:255', // Validation for remarks
        ]);

        $assessmentId = $validated['assessment_id'];
        $assignmentId = $validated['assignment_id'];
        $teacher = Auth::user();

        // Retrieve assignment and assessment to perform security checks
        $assignment = Assignment::find($assignmentId);
        $assessment = Assessment::find($assessmentId);

        // Re-perform security checks from the show method for submission
        if (!$assignment || $assignment->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized Action: Assignment not found or not yours.');
        }
        if (!$assessment || $assessment->subject_id !== $assignment->subject_id) {
            abort(403, 'Unauthorized Action: Assessment subject mismatch on submission.');
        }
        
        // Find the grading scale via the assignment's class section
        // Eager load gradingScale and its grades.
        $classSection = $assignment->classSection()->with('gradingScale.grades')->first();

        foreach ($validated['scores'] as $studentId => $score) {
            // Trim score input to remove trailing zeroes if it's a float display issue
            $score = ($score !== null) ? round((float) $score, 2) : null; 
            
            // Get remark for this student
            $remark = $validated['remarks'][$studentId] ?? null;
            if ($remark) {
                $remark = trim($remark); // Trim whitespace
            }

            // If a score is provided (not null and not empty string after trim)
            if ($score !== null && $score !== '') {
                // Determine remark based on grading scale if set
                if ($classSection && $classSection->gradingScale) {
                    // Assuming getGradeFromScore is a method on your GradingScale model
                    $grade = $classSection->gradingScale->grades->first(function ($g) use ($score) {
                        return $score >= $g->min_score && $score <= $g->max_score;
                    });
                    if ($grade && $grade->remark) {
                        $remark = $grade->remark;
                    }
                }
                
                Result::updateOrCreate(
                    [
                        'user_id' => $studentId, 
                        'assessment_id' => $assessmentId,
                        'assignment_id' => $assignmentId, // Crucial: Link result to specific assignment
                        'class_section_id' => $assignment->class_section_id // Crucial: Link result to specific class
                    ],
                    [
                        'score' => $score, 
                        'remark' => $remark,
                        'teacher_id' => $teacher->id // Add teacher_id to results
                    ]
                );
            } else {
                // If score is null/empty, delete the result if it exists (or set to null, depends on your logic)
                Result::where('user_id', $studentId)
                      ->where('assessment_id', $assessmentId)
                      ->where('assignment_id', $assignmentId)
                      ->delete(); // Or update(['score' => null, 'remark' => null])
            }
        }

        return redirect()->route('teacher.gradebook.results', [
            'assignment' => $assignmentId,
            'assessment' => $assessmentId,
        ])->with('success', 'Grades saved successfully.');
    }
}