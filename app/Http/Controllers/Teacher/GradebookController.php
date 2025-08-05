<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GradebookController extends Controller
{
    /**
     * Display a single list of all Assessments assigned to the logged-in teacher.
     */
    public function index(): View
    {
        $teacherId = Auth::id();

        $assessments = Assessment::whereHas('classSection.subjects', function ($query) use ($teacherId) {
            $query->where('class_section_subject.teacher_id', $teacherId);
        })
        ->with(['classSection', 'subject', 'term'])
        ->latest('assessment_date')
        ->paginate(15);
        
        return view('teacher.gradebook.index', compact('assessments'));
    }

    /**
     * Show the form to enter/view results for a specific ASSESSMENT.
     */
    public function showResults(Assessment $assessment): View
    {
        $teacherId = Auth::id();

        // Security check to ensure this teacher is actually assigned to this assessment
        $isAssigned = DB::table('class_section_subject')
            ->where('class_section_id', $assessment->class_section_id)
            ->where('subject_id', $assessment->subject_id)
            ->where('teacher_id', $teacherId)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'You are not authorized to access this assessment.');
        }

        $assessment->load('classSection.students');
        $students = $assessment->classSection->students()->orderBy('name')->get();

        $results = Result::where('assessment_id', $assessment->id)
                         ->whereIn('user_id', $students->pluck('id'))
                         ->get()->keyBy('user_id');

        return view('teacher.gradebook.results', compact('assessment', 'students', 'results'));
    }
    
    /**
     * Handle the form submission to save/update results manually.
     */
    public function storeResults(Request $request, Assessment $assessment)
    {
        $teacherId = Auth::id();
        $isAssigned = DB::table('class_section_subject')
            ->where('class_section_id', $assessment->class_section_id)
            ->where('subject_id', $assessment->subject_id)
            ->where('teacher_id', $teacherId)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'scores' => 'required|array',
            'scores.*' => ['nullable', 'numeric', 'min:0', 'max:' . $assessment->max_marks],
        ]);

        foreach ($validated['scores'] as $studentId => $score) {
            if (!is_null($score)) {
                Result::updateOrCreate(
                    ['user_id' => $studentId, 'assessment_id' => $assessment->id],
                    ['score' => $score]
                );
            }
        }

        return redirect()->route('teacher.gradebook.index')
            ->with('success', 'Results for "' . $assessment->name . '" have been saved successfully!');
    }

    /**
     * === THIS IS THE NEW METHOD FOR THE IMPORT FEATURE ===
     * Handle the CSV file upload from the results page modal.
     */
    public function handleResultsImport(Request $request, Assessment $assessment)
    {
        $request->validate([
            'results_file' => 'required|file|mimes:csv,txt',
        ]);

        // Security check: Ensure the teacher is authorized for this assessment
        $teacherId = Auth::id();
        $isAssigned = DB::table('class_section_subject')
            ->where('class_section_id', $assessment->class_section_id)
            ->where('subject_id', $assessment->subject_id)
            ->where('teacher_id', $teacherId)
            ->exists();

        if (!$isAssigned) {
            abort(403);
        }

        $file = $request->file('results_file');
        $path = $file->getRealPath();
        $records = array_map('str_getcsv', file($path));

        if (count($records) <= 1) {
            return back()->with('error', 'The file is empty or missing a header row.');
        }

        $header = array_map('trim', array_shift($records));
        if ($header[0] !== 'student_email' || $header[1] !== 'score') {
            return back()->with('error', "Invalid header. Columns must be 'student_email' and 'score'.");
        }
        
        $enrolledStudentEmails = $assessment->classSection->students()->pluck('email', 'id');
        $successCount = 0;

        DB::beginTransaction();
        try {
            foreach ($records as $row) {
                if (empty(implode('', $row))) continue;
                $data = array_combine($header, $row);

                // Find the student's ID by their email address
                $studentId = $enrolledStudentEmails->search($data['student_email']);

                // Only import the result if the student email was found and the score is a number
                if ($studentId && is_numeric($data['score'])) {
                    Result::updateOrCreate(
                        [
                            'user_id' => $studentId,
                            'assessment_id' => $assessment->id,
                        ],
                        [
                            'score' => $data['score'],
                        ]
                    );
                    $successCount++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Teacher Result Import Failed: ' . $e->getMessage());
            return back()->with('error', 'An unexpected error occurred during import.');
        }

        return redirect()->route('teacher.gradebook.results', $assessment)
            ->with('success', "$successCount results were successfully imported!");
    }
}