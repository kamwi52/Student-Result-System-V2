<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\User;
use App\Models\Assessment;
use App\Models\ClassSection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    // ... index, create, store methods remain unchanged ...
    public function index()
    {
        $results = Result::with(['student', 'assessment.subject', 'classSection'])->latest()->paginate(20);
        return view('admin.results.index', compact('results'));
    }
    public function create()
    {
        // ...
    }
    public function store(Request $request)
    {
        // ...
    }

    public function showImportForm()
    {
        $assessments = Assessment::with('subject')->get()->map(function ($assessment) {
            $subjectName = $assessment->subject->name ?? 'Unassigned';
            $assessment->display_name = "{$subjectName} - {$assessment->name} (Max: {$assessment->max_marks})";
            return $assessment;
        })->sortBy('display_name');
        return view('admin.results.import-form', compact('assessments'));
    }

    /**
     * Handle the imported results file - FINAL CORRECTED VERSION
     */
    public function handleImport(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'results_file' => 'required|file|mimes:csv,txt',
        ]);

        $assessment = Assessment::with('subject.classSections')->findOrFail($request->assessment_id);
        $file = $request->file('results_file');
        
        // --- THIS IS THE KEY LOGIC FIX ---
        // Find the first class associated with the assessment's subject.
        $classSection = $assessment->subject->classSections()->first();
        if (!$classSection) {
            return redirect()->back()->withInput()->with('import_errors', ["The selected assessment's subject ('{$assessment->subject->name}') is not assigned to any class."]);
        }
        $classId = $classSection->id;
        // ------------------------------------

        $rows = array_map('str_getcsv', file($file->getPathname()));
        $header = array_shift($rows);

        $requiredHeader = ['student_email', 'score'];
        if (array_map('trim', $header) !== $requiredHeader) {
            return redirect()->back()->withInput()->with('import_errors', ['Invalid CSV header. Must be exactly: student_email,score']);
        }

        $errors = [];
        $successCount = 0;
        
        $students = User::where('role', 'student')->pluck('id', 'email')->all();

        foreach($rows as $key => $row) {
            $rowNumber = $key + 2;

            try {
                if (count($row) < 2) throw new \Exception("Invalid format.");
                $studentEmail = trim($row[0]);
                $score = trim($row[1]);
                $remarks = isset($row[2]) ? trim($row[2]) : null;

                if (!isset($students[$studentEmail])) {
                    throw new \Exception("Student with email '{$studentEmail}' not found or is not a student.");
                }
                $studentId = $students[$studentEmail];

                if (!is_numeric($score) || $score < 0 || $score > $assessment->max_marks) {
                    throw new \Exception("Invalid score '{$score}'. Must be between 0 and {$assessment->max_marks}.");
                }
                
                // Now we provide the class_id, fixing the NOT NULL error
                Result::updateOrCreate(
                    ['user_id' => $studentId, 'assessment_id' => $assessment->id],
                    ['score' => $score, 'class_id' => $classId, 'remarks' => $remarks ?? 'Imported via CSV']
                );
                $successCount++;

            } catch (\Exception $e) {
                $errors[] = "Row #{$rowNumber}: " . $e->getMessage();
            }
        }

        $message = "Import process finished. $successCount results were successfully created or updated for assessment: '{$assessment->name}'.";

        if (!empty($errors)) {
            return redirect()->route('admin.results.index')->with('success', $message)->with('import_errors', $errors);
        }

        return redirect()->route('admin.results.index')->with('success', $message);
    }
}