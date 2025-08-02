<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AcademicSession;
use App\Models\Subject;
use App\Models\Term;
use App\Models\ClassSection;
use App\Models\User; // <-- Added User model for edit method
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $assessments = Assessment::with(['subject', 'classSection', 'academicSession', 'term'])
            ->latest()
            ->paginate(10);
        return view('admin.assessments.index', compact('assessments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        $subjects = Subject::orderBy('name')->get();
        $classSections = ClassSection::orderBy('name')->get();
        $terms = Term::orderBy('name')->get();
        return view('admin.assessments.create', compact('academicSessions', 'subjects', 'classSections', 'terms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_marks' => 'required|numeric|min:0',
            'weightage' => 'nullable|numeric|min:0|max:100',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'term_id' => 'required|exists:terms,id',
            'assessment_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Assessment::create($validated);
        return redirect()->route('admin.assessments.index')->with('success', 'Assessment created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assessment $assessment): View
    {
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        $subjects = Subject::orderBy('name')->get();
        $classSections = ClassSection::with('academicSession')->orderBy('name')->get();
        $terms = Term::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get(); // Added for consistency
        return view('admin.assessments.edit', compact('assessment', 'academicSessions', 'subjects', 'classSections', 'terms', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_marks' => 'required|numeric|min:0',
            'weightage' => 'nullable|numeric|min:0|max:100',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'term_id' => 'required|exists:terms,id',
            'assessment_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $assessment->update($validated);
        return redirect()->route('admin.assessments.index')->with('success', 'Assessment updated successfully.');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assessment $assessment)
    {
        $assessment->delete();
        return redirect()->route('admin.assessments.index')->with('success', 'Assessment deleted successfully.');
    }
    
    /**
     * === THIS IS THE MISSING METHOD ===
     * Show the form for bulk creating assessments for a class.
     */
    public function showBulkCreateForm(): View
    {
        $classSections = ClassSection::with('subjects')->orderBy('name')->get();
        $terms = Term::orderBy('name')->get();
        return view('admin.assessments.create-bulk', compact('classSections', 'terms'));
    }

    /**
     * === THIS IS THE OTHER MISSING METHOD ===
     * Handle the submission of the bulk create form.
     */
    public function handleBulkCreate(Request $request)
    {
        $validated = $request->validate([
            'class_section_id' => 'required|exists:class_sections,id',
            'term_id' => 'required|exists:terms,id',
            'subject_ids' => 'required|array|min:1',
            'subject_ids.*' => 'exists:subjects,id',
            'base_name' => 'required|string|max:255',
            'max_marks' => 'required|integer|min:1',
            'weightage' => 'required|integer|min:0|max:100',
            'assessment_date' => 'required|date',
        ]);

        $classSection = ClassSection::findOrFail($validated['class_section_id']);
        $subjects = Subject::whereIn('id', $validated['subject_ids'])->get();
        $createdCount = 0;

        DB::beginTransaction();
        try {
            foreach ($subjects as $subject) {
                Assessment::create([
                    'name' => $validated['base_name'] . ' (' . $subject->name . ')',
                    'class_section_id' => $classSection->id,
                    'academic_session_id' => $classSection->academic_session_id,
                    'subject_id' => $subject->id,
                    'term_id' => $validated['term_id'],
                    'max_marks' => $validated['max_marks'],
                    'weightage' => $validated['weightage'],
                    'assessment_date' => $validated['assessment_date'],
                ]);
                $createdCount++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk Assessment Creation Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred and no assessments were created. Please check the logs.');
        }

        return redirect()->route('admin.assessments.index')
            ->with('success', "$createdCount assessments were created successfully!");
    }
}