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
        $assessments = Assessment::with(['subject', 'assignment', 'assignment.classSection', 'assignment.teacher'])
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
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_marks' => 'required|numeric|min:0',
            'weightage' => 'nullable|numeric|min:0|max:100',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'subject_id' => 'required|exists:subjects,id',
            'assessment_date' => 'required|date',
            'teacher_id' => 'required|exists:users,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'title' => 'required|string|max:255',  // Assignment title
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Create the Assessment
            $assessment = Assessment::create([
                'name' => $validated['name'],
                'subject_id' => $validated['subject_id'],
                'academic_session_id' => $validated['academic_session_id'],
                'max_marks' => $validated['max_marks'],
                'weightage' => $validated['weightage'],
                'assessment_date' => $validated['assessment_date'],
                'class_section_id' => $validated['class_section_id'],
            ]);

            // 2. Create the linked Assignment
            $assignment = new Assignment([
                'title' => $validated['title'],
                'subject_id' => $validated['subject_id'],
                'class_section_id' => $validated['class_section_id'],
                'teacher_id' => $validated['teacher_id'],
                'assessment_id' => $assessment->id,
            ]);
            $assessment->assignment()->save($assignment);
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
     */
    public function update(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_marks' => 'required|numeric|min:0',
            'weightage' => 'nullable|numeric|min:0|max:100',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'subject_id' => 'required|exists:subjects,id',
            'assessment_date' => 'required|date',
            'teacher_id' => 'required|exists:users,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'title' => 'required|string|max:255',  // Assignment title
        ]);

        DB::transaction(function () use ($validated, $assessment) {
            // 1. Update the Assessment
            $assessment->update([
                'name' => $validated['name'],
                'subject_id' => $validated['subject_id'],
                'academic_session_id' => $validated['academic_session_id'],
                'max_marks' => $validated['max_marks'],
                'weightage' => $validated['weightage'],
                'assessment_date' => $validated['assessment_date'],
                'class_section_id' => $validated['class_section_id'],
            ]);

            // 2. Update or Create the linked Assignment
            // Check if the assignment exists.
            if ($assessment->assignment) {
                //Update if it exists.
                $assessment->assignment()->update([
                    'title' => $validated['title'],
                    'subject_id' => $validated['subject_id'],
                    'class_section_id' => $validated['class_section_id'],
                    'teacher_id' => $validated['teacher_id'],
                ]);
            } else {
                //Create if it does not exist.
                $assignment = new Assignment([
                    'title' => $validated['title'],
                    'subject_id' => $validated['subject_id'],
                    'class_section_id' => $validated['class_section_id'],
                    'teacher_id' => $validated['teacher_id'],
                    'assessment_id' => $assessment->id,
                ]);
                $assessment->assignment()->save($assignment);
            }
        });

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assessment $assessment)
    {
        // Because of the HasOne relationship, the linked assignment might need
        // to be deleted manually if you didn't set up cascading deletes in the migration.
        // Assuming you have set `onDelete('cascade')` in your migration, this is fine.
        $assessment->delete();

        return redirect()->route('admin.assessments.index')->with('success', 'Assessment deleted successfully.');
    }
}