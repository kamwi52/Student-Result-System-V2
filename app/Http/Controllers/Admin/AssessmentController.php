<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AcademicSession;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index() {
        $assessments = Assessment::with('academicSession')->latest()->paginate(10);
        return view('admin.assessments.index', compact('assessments'));
    }

    public function create() {
        $academic_sessions = AcademicSession::orderBy('name', 'desc')->get();
        return view('admin.assessments.create', compact('academic_sessions'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_marks' => 'required|integer|min:1',
            'weightage' => 'required|numeric|min:0|max:1',
            'academic_session_id' => 'required|exists:academic_sessions,id',
        ]);
        Assessment::create($validated);
        return redirect()->route('admin.assessments.index')->with('success', 'Assessment created successfully.');
    }

    public function edit(Assessment $assessment) {
        $academic_sessions = AcademicSession::orderBy('name', 'desc')->get();
        return view('admin.assessments.edit', compact('assessment', 'academic_sessions'));
    }

    public function update(Request $request, Assessment $assessment) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_marks' => 'required|integer|min:1',
            'weightage' => 'required|numeric|min:0|max:1',
            'academic_session_id' => 'required|exists:academic_sessions,id',
        ]);
        $assessment->update($validated);
        return redirect()->route('admin.assessments.index')->with('success', 'Assessment updated successfully.');
    }

    public function destroy(Assessment $assessment) {
        $assessment->delete();
        return redirect()->route('admin.assessments.index')->with('success', 'Assessment deleted successfully.');
    }
}