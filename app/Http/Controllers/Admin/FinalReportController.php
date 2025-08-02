<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateRankedReportJob;
use App\Models\Term;
use App\Models\ClassSection;
use Illuminate\Http\Request;

class FinalReportController extends Controller
{
    /**
     * Step 1: Display the main selection page.
     */
    public function index()
    {
        $classes = ClassSection::with('academicSession')->orderBy('name')->get();
        $terms = Term::orderBy('name')->get(); 
        return view('admin.final-reports.index', compact('classes', 'terms'));
    }

    /**
     * Step 2: Show the student list for the selected class.
     */
    public function showStudents(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:class_sections,id',
            'term_id'  => 'required|exists:terms,id',
        ]);
        
        $classSection = ClassSection::findOrFail($validated['class_id']);
        $term = Term::findOrFail($validated['term_id']); 
        $students = $classSection->students()->select('users.id', 'users.name')->orderBy('users.name')->get();
        
        return view('admin.final-reports.show-students', compact('classSection', 'term', 'students'));
    }

    /**
     * Step 3: Handle the BULK form submission and dispatch the job.
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'required|exists:users,id',
            'class_id'    => 'required|exists:class_sections,id',
            'term_id'     => 'required|exists:terms,id',
        ]);

        GenerateRankedReportJob::dispatch(
            $validated['student_ids'],
            $validated['class_id'],
            $validated['term_id'],
            auth()->user()
        );

        return redirect()->route('admin.final-reports.index')
            ->with('success', 'Your ranked report cards are being generated! You will be notified when they are ready.');
    }

    /**
     * === THIS IS THE NEW METHOD ===
     * Step 3 (Single): Handle the single student generation link.
     */
    public function generateSingle(Request $request, int $student_id, int $class_id, int $term_id)
    {
        // We still use the same job, but we pass the student ID as an array with one element.
        GenerateRankedReportJob::dispatch(
            [$student_id], // Pass the student ID as an array
            $class_id,
            $term_id,
            auth()->user()
        );

        // Redirect back to the student list page with a success message.
        return redirect()->route('admin.final-reports.show-students', ['class_id' => $class_id, 'term_id' => $term_id])
            ->with('success', 'The report is being generated! You will be notified when it is ready.');
    }
}