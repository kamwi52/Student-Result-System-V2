<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\ClassSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    /**
     * Show the form for editing a single result.
     */
    public function edit(Result $result)
    {
        // Security Check: Ensure the logged-in teacher is assigned to the class
        // where this assessment was given.
        $assessment = $result->load('assessment.subject.classes')->assessment;
        $teacherClassIds = Auth::user()->classes()->pluck('id');
        
        $isAuthorized = $assessment->subject->classes->whereIn('id', $teacherClassIds)->isNotEmpty();

        if (!$isAuthorized) {
            abort(403, 'Unauthorized Action');
        }

        // We need the classSection for the "Back" button URL.
        // We can get it from the relationships we just loaded.
        $classSection = $assessment->subject->classes->whereIn('id', $teacherClassIds)->first();

        return view('teacher.results.edit', compact('result', 'classSection'));
    }

    /**
     * Update the specified result in storage.
     */
    public function update(Request $request, Result $result)
    {
        // Reuse the same security check from the edit method.
        $assessment = $result->load('assessment.subject')->assessment;
        $teacherClassIds = Auth::user()->classes()->pluck('id');
        $isAuthorized = $assessment->subject->classes->whereIn('id', $teacherClassIds)->isNotEmpty();

        if (!$isAuthorized) {
            abort(403, 'Unauthorized Action');
        }

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'remark' => 'nullable|string|max:255',
            'class_section_id' => 'required|exists:class_sections,id' // For redirecting
        ]);

        $result->update($validated);

        // Redirect back to the main results page
        return redirect()->route('teacher.gradebook.results', [
            'classSection' => $validated['class_section_id'],
            'assessment' => $result->assessment_id
        ])->with('success', 'Grade updated successfully.');
    }
}