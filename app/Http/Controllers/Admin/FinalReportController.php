<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateRankedReportJob;
use App\Models\Term; // <-- CHANGED from AssessmentType
use App\Models\ClassSection;
use Illuminate\Http\Request;

class FinalReportController extends Controller
{
    /**
     * Step 1: Display the main selection page.
     * The user selects a Class and a Term.
     */
    public function index()
    {
        $classes = ClassSection::with('academicSession')->orderBy('name')->get();
        // CHANGED from AssessmentType to Term
        $terms = Term::orderBy('name')->get(); 

        // CHANGED from assessmentTypes to terms
        return view('admin.final-reports.index', compact('classes', 'terms'));
    }

    /**
     * Step 2: Show the student list for the selected class.
     * The user will confirm which students to include.
     */
    public function showStudents(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:class_sections,id',
            'term_id'  => 'required|exists:terms,id', // <-- CHANGED from assessment_type_id
        ]);
        
        $classSection = ClassSection::findOrFail($validated['class_id']);
        // CHANGED from AssessmentType to Term
        $term = Term::findOrFail($validated['term_id']); 
        $students = $classSection->students()->select('users.id', 'users.name')->orderBy('users.name')->get();
        
        // CHANGED from assessmentType to term
        return view('admin.final-reports.show-students', compact('classSection', 'term', 'students'));
    }

    /**
     * Step 3: Handle the form submission and dispatch the job.
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'required|exists:users,id',
            'class_id'    => 'required|exists:class_sections,id',
            'term_id'     => 'required|exists:terms,id', // <-- CHANGED from assessment_type_id
        ]);

        // Dispatch a new, dedicated job for this complex task
        GenerateRankedReportJob::dispatch(
            $validated['student_ids'],
            $validated['class_id'],
            $validated['term_id'], // <-- CHANGED from assessment_type_id
            auth()->user()
        );

        return redirect()->route('admin.final-reports.index')
            ->with('status', 'Your ranked report cards are being generated! This may take a few minutes. You will be notified when they are ready.');
    }
}