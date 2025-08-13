<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Result;
use App\Models\ClassSection;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class GradebookController extends Controller
{
    /**
     * This is the definitive fix for the 404 error.
     * The `select('assessments.*')` line prevents the ID conflict from the join.
     */
    public function index(): View
    {
        $teacherId = Auth::id();

        $assessments = Assessment::query()
            ->join('class_section_subject', function ($join) use ($teacherId) {
                $join->on('class_section_subject.class_section_id', '=', 'assessments.class_section_id')
                     ->on('class_section_subject.subject_id', '=', 'assessments.subject_id')
                     ->where('class_section_subject.teacher_id', '=', $teacherId);
            })
            ->with(['classSection', 'subject', 'term'])
            // THIS LINE IS THE FIX. IT PREVENTS THE ID CONFLICT.
            ->select('assessments.*') 
            ->latest('assessments.created_at')
            ->paginate(15);
        
        return view('teacher.gradebook.index', compact('assessments'));
    }

    /**
     * Displays a list of assessments for a specific subject within a specific class.
     */
    public function showAssessments(ClassSection $classSection, Subject $subject): View
    {
        $teacherId = Auth::id();
        $isAssigned = DB::table('class_section_subject')
            ->where('class_section_id', $classSection->id)
            ->where('subject_id', $subject->id)
            ->where('teacher_id', $teacherId)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'You are not authorized to access this gradebook.');
        }

        $assessments = Assessment::where('class_section_id', $classSection->id)
                                 ->where('subject_id', $subject->id)
                                 ->withCount('results')
                                 ->latest('created_at')
                                 ->paginate(15);

        return view('teacher.gradebook.index', compact('assessments', 'classSection', 'subject'));
    }

    /**
     * Show the form to enter/view results for a specific ASSESSMENT.
     */
    public function showResults(Assessment $assessment): View
    {
        $teacherId = Auth::id();
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

        return redirect()->route('teacher.gradebook.assessments', ['classSection' => $assessment->classSection, 'subject' => $assessment->subject])
            ->with('success', 'Results for "' . $assessment->name . '" have been saved successfully!');
    }

    /**
     * Handle the CSV file upload from the results page modal.
     */
    public function handleResultsImport(Request $request, Assessment $assessment)
    {
        $request->validate([
            'results_file' => 'required|file|mimes:csv,txt',
        ]);

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

                $studentId = $enrolledStudentEmails->search($data['student_email']);

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
    
    /**
     * Generates a PDF summary of results for a single assessment.
     */
    public function printSummary(Assessment $assessment)
    {
        $teacherId = Auth::id();
        $isAssigned = DB::table('class_section_subject')
            ->where('class_section_id', $assessment->class_section_id)
            ->where('subject_id', $assessment->subject_id)
            ->where('teacher_id', $teacherId)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'You are not authorized to print a summary for this assessment.');
        }

        $assessment->load([
            'classSection.students' => function ($query) {
                $query->orderBy('name', 'asc');
            },
            'subject',
            'term'
        ]);
        
        $results = Result::where('assessment_id', $assessment->id)
                         ->whereIn('user_id', $assessment->classSection->students->pluck('id'))
                         ->get()
                         ->keyBy('user_id');

        $dataForPdf = [
            'assessment' => $assessment,
            'students' => $assessment->classSection->students,
            'results' => $results
        ];

        $pdf = Pdf::loadView('pdf.marks-summary', $dataForPdf);
        return $pdf->stream('marks-summary-' . str($assessment->name)->slug() . '.pdf');
    }
}