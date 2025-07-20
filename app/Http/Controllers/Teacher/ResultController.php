<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    /**
     * Show the form for editing a single result.
     */
    public function edit(Result $result)
    {
        // Authorize the action first.
        $this->authorize('update', $result);

        // FIX #1: Eager-load the nested relationship.
        // This loads the result, its student, its assessment, AND the assignment
        // that belongs to that assessment. This makes it available in the view.
        $result->load('student', 'assessment.assignment');

        return view('teacher.results.edit', compact('result'));
    }

    /**
     * Update a single result.
     */
    public function update(Request $request, Result $result)
    {
        // Authorize the action first.
        $this->authorize('update', $result);

        // Validate input.
        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable|string|max:1000',
        ]);

        $result->update($validated);

        // FIX #2: Load the relationship before the redirect to get the assignment ID.
        $result->load('assessment.assignment');

        // Now this redirect will work correctly.
        return redirect()->route('teacher.assignments.results', $result->assessment->assignment->id)
                         ->with('success', 'Result updated successfully.');
    }
}