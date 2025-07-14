<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\User;
use App\Models\Assessment;
use App\Models\ClassSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- ADD THIS LINE

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::with(['student', 'assessment.subject', 'classSection'])->latest()->paginate(20);
        return view('admin.results.index', compact('results'));
    }

    public function showImportForm()
    {
        $classes = ClassSection::orderBy('name')->get();
        
        $classAssessmentsMap = [];
        foreach ($classes as $class) {
            $classAssessmentsMap[$class->id] = $class->getAssessments()->map(function($assessment) {
                return [
                    'id' => $assessment->id,
                    'display_name' => $assessment->display_name
                ];
            })->values();
        }

        return view('admin.results.import-form', compact('classes', 'classAssessmentsMap'));
    }

    public function handleImport(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:class_sections,id',
            'assessment_id' => 'required|exists:assessments,id',
            'results_file' => 'required|file|mimes:csv,txt',
        ]);

        $classId = $request->class_id;
        $assessment = Assessment::findOrFail($request->assessment_id);
        $file = $request->file('results_file');
        
        $rows = array_map('str_getcsv', file($file->getPathname()));
        $header = array_map('trim', array_shift($rows));
        
        if (isset($header[0]) && strpos($header[0], "\xef\xbb\xbf") === 0) {
            $header[0] = substr($header[0], 3);
        }

        $requiredHeaders = ['student_email', 'score'];
        if (count(array_intersect($requiredHeaders, $header)) < 2) {
             return redirect()->back()->withInput()->with('import_errors', ['Invalid CSV header. Must contain at least: student_email,score']);
        }

        $successCount = 0;
        $students = User::where('role', 'student')->pluck('id', 'email')->all();

        foreach($rows as $key => $row) {
            $rowNumber = $key + 2;
            $rowData = array_combine($header, $row);

            try {
                DB::beginTransaction(); // Start a transaction for this row

                $studentEmail = trim($rowData['student_email']);
                $score = trim($rowData['score']);
                $remarks = isset($rowData['remarks']) ? trim($rowData['remarks']) : null;

                if (!isset($students[$studentEmail])) {
                    throw new \Exception("Student with email '{$studentEmail}' not found. Please ensure users are imported and enrolled.");
                }
                $studentId = $students[$studentEmail];

                if (!is_numeric($score) || $score < 0 || $score > $assessment->max_marks) {
                    throw new \Exception("Invalid score '{$score}'. Must be a number between 0 and {$assessment->max_marks}.");
                }
                
                Result::updateOrCreate(
                    ['user_id' => $studentId, 'assessment_id' => $assessment->id, 'class_id' => $classId ],
                    ['score' => $score, 'remarks' => $remarks ?? 'Imported via CSV']
                );
                
                $successCount++;
                DB::commit(); // Commit the successful transaction

            } catch (\Exception $e) {
                DB::rollBack(); // Rollback the failed transaction
                
                // THIS IS THE CRITICAL DEBUGGING STEP.
                // It will stop the script and show us the exact error.
                dd($e); 
            }
        }

        $message = "Import process finished. $successCount results were successfully created or updated for assessment: '{$assessment->name}'.";

        return redirect()->route('admin.results.index')->with('success', $message);
    }
}