<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Assignment;
use App\Models\ClassSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class ResultController extends Controller
{
    /**
     * Show the form for editing a single result.
     * MODIFIED: Receives an Assignment for context.
     */
    public function edit(Assignment $assignment, Result $result)
    {
        // Security Check 1: Ensure the logged-in teacher owns this assignment.
        if ($assignment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized Action');
        }

        // Security Check 2: Make sure the result being edited actually belongs to this assignment's subject.
        if ($result->assessment->subject_id !== $assignment->subject_id) {
            abort(403, 'Result does not match assignment subject.');
        }

        return view('teacher.results.edit', compact('assignment', 'result'));
    }

    /**
     * Update the specified result in storage.
     */
    public function update(Request $request, Result $result)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            // remark is now auto-generated, so no longer in validation
            'assignment_id' => 'required|exists:assignments,id' // For security and redirect
        ]);

        $assignment = Assignment::find($validated['assignment_id']);

        // Security Check
        if ($assignment->user_id !== Auth::id() || $result->assessment->subject_id !== $assignment->subject_id) {
            abort(403, 'Unauthorized Action');
        }
        
        $score = $validated['score'];
        $remark = null;

        // Auto-remark logic
        $classSection = $assignment->classSection()->with('gradingScale.grades')->first();
        if ($classSection && $classSection->gradingScale) {
            $grade = $classSection->gradingScale->getGradeFromScore($score);
            if ($grade) {
                $remark = $grade->remark;
            }
        }
        
        $result->update([
            'score' => $score,
            'remark' => $remark,
        ]);

        // Redirect back to the main results page for the assignment
        return redirect()->route('teacher.gradebook.results', [
            'assignment' => $validated['assignment_id'],
            'assessment' => $result->assessment_id
        ])->with('success', 'Grade updated successfully.');
    }
}