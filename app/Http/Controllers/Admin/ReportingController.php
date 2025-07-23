<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateBulkReport;
use App\Models\Assessment;
use App\Models\ClassSection;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportingController extends Controller
{
    /**
     * Admin Report Flow: Step 2
     * Display all assessments associated with a specific class section.
     */
    public function showAssessments(ClassSection $classSection)
    {
        // Find all assessments that belong to the subjects taught in this class.
        // This assumes a relationship exists on ClassSection to get its subjects.
        $assessments = Assessment::whereIn('subject_id', $classSection->subjects->pluck('id'))
                            ->with('subject', 'assessmentType')
                            ->orderBy('date', 'desc')
                            ->get();

        return view('admin.reports.show-assessments', compact('classSection', 'assessments'));
    }

    /**
     * Admin Report Flow: Step 3
     * Display student results for a specific assessment.
     */
    public function showResults(Assessment $assessment)
    {
        // Eager load the data needed for the view to prevent N+1 query problems
        $results = Result::where('assessment_id', $assessment->id)
                        ->with('student.user', 'gradingScale')
                        ->get();
        
        return view('admin.reports.show-results', compact('assessment', 'results'));
    }


    /**
     * Handle the form submission for generating bulk reports.
     * This method is called by the form on the show-results page.
     */
    public function generateBulkReport(Request $request)
    {
        $validated = $request->validate([
            'student_ids'   => 'required|array|min:1',
            'student_ids.*' => 'required|exists:users,id',
        ]);

        GenerateBulkReport::dispatch($validated['student_ids'], auth()->user());

        // Redirect back with a success message
        return redirect()->back()
            ->with('status', 'Your reports are being generated! You will be notified when they are ready.');
    }

    /**
     * Handle the secure download of a generated report.
     * This action is triggered by the link in the notification.
     */
    public function downloadReport(Request $request)
    {
        $filename = $request->query('filename');

        if (str_contains($filename, '..') || !str_starts_with($filename, 'reports/')) {
            abort(403, 'Invalid file path specified.');
        }

        if (! Storage::disk('private')->exists($filename)) {
            abort(404, 'The requested report could not be found. It may have been deleted or expired.');
        }

        return Storage::disk('private')->download($filename);
    }
}