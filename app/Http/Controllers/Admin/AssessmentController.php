<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Assignment;
use App\Models\AcademicSession;
use App\Models\Subject;
use App\Models\User;
use App\Models\ClassSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $assessments = Assessment::with(['subject', 'assignment.classSection', 'assignment.teacher'])
                                 ->latest()
                                 ->paginate(10);
        return view('admin.assessments.index', compact('assessments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $academicSessions = AcademicSession::where('is_current', true)->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $classSections = ClassSection::orderBy('name')->get();
        return view('admin.assessments.create', compact('academicSessions', 'subjects', 'teachers', 'classSections'));
    }

    /**
     * Store a newly created resource in storage.
     * REDESIGNED to create both Assessment and Assignment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_marks' => 'required|numeric|min:0',
            'weightage' => 'required|numeric|min:0|max:100',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'subject_id' => 'required|exists:subjects,id',
            'assessment_date' => 'required|date',
            'teacher_id' => 'nullable|exists:users,id',
            'class_section_id' => 'required|exists:class_sections,id',
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Create the Assessment
            $assessment = Assessment::create($validated);

            // 2. Prepare data and create the linked Assignment
            $assignmentData = array_merge($validated, ['assessment_id' => $assessment->id]);
            Assignment::create($assignmentData);
        });

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment created successfully.');
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assessment $assessment): View
    {
        $assessment->load('assignment'); // Load the linked assignment
        $academicSessions = AcademicSession::all();
        $subjects = Subject::orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $classSections = ClassSection::orderBy('name')->get();

        return view('admin.assessments.edit', compact('assessment', 'academicSessions', 'subjects', 'teachers', 'classSections'));
    }

    /**
     * Update the specified resource in storage.
     * REDESIGNED to update both Assessment and Assignment.
     */
    public function update(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_marks' => 'required|numeric|min:0',
            'weightage' => 'required|numeric|min:0|max:100',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'subject_id' => 'required|exists:subjects,id',
            'assessment_date' => 'required|date',
            'teacher_id' => 'nullable|exists:users,id',
            'class_section_id' => 'required|exists:class_sections,id',
        ]);

        DB::transaction(function () use ($validated, $assessment) {
            // 1. Update the Assessment
            $assessment->update($validated);

            // 2. Update the linked Assignment
            // If an assignment doesn't exist for some reason, create it.
            $assessment->assignment()->updateOrCreate(
                ['assessment_id' => $assessment->id],
                $validated
            );
        });

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * REDESIGNED to delete both Assessment and linked Assignment.
     */
    public function destroy(Assessment $assessment)
    {
        // Deleting the Assessment will automatically delete the linked Assignment
        // because of the onDelete('cascade') we set in the migration.
        $assessment->delete();

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment deleted successfully.');
    }

    // ... your import methods can stay as they are, but you will need to adjust them
    // to create both an Assessment and an Assignment for each imported row.
}