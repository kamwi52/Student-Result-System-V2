<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $assessments = Assessment::with('academicSession')->latest()->paginate(10);
        return view('admin.assessments.index', compact('assessments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        return view('admin.assessments.create', compact('academicSessions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_marks' => 'required|integer|min:1',
            'weightage' => 'required|integer|min:0|max:100',
            'academic_session_id' => 'required|exists:academic_sessions,id',
        ]);

        Assessment::create($validated);

        return to_route('admin.assessments.index')->with('success', 'Assessment created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assessment $assessment): View
    {
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        return view('admin.assessments.edit', compact('assessment', 'academicSessions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_marks' => 'required|integer|min:1',
            'weightage' => 'required|integer|min:0|max:100',
            'academic_session_id' => 'required|exists:academic_sessions,id',
        ]);

        $assessment->update($validated);

        return to_route('admin.assessments.index')->with('success', 'Assessment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Assessment  $assessment
     */
    public function destroy(Assessment $assessment)
    {
        $assessment->delete();
        return to_route('admin.assessments.index')->with('success', 'Assessment deleted successfully.');
    }
}