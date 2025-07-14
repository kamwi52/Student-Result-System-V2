<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\User;
use App\Models\Assessment;
use App\Models\ClassSection;
use Illuminate\Http\Request;

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

        // --- THIS IS THE FIX ---
        // Some CSV files (from Excel) have a hidden BOM character at the start.
        // This code detects and removes it from the first header column.
        if (isset($header[0]) && strpos($header[0], "\xef\xbb\xbf") === 0) {
            $header[0] = substr($header[0], 3);
        }
        // --- END OF FIX ---

        $requiredHeaders = ['student_email', 'score'];
        if (count(array_intersect($requiredHeaders, $header)) < 2) {
             return redirect()->back()->withInput()->with('import_errors', ['Invalid CSV header. Must contain at least: student_email,score']);
        }

        $errors = [];
        $successCount = 0;
        
        $students = User::where('role', 'student')->pluck('id', 'email')->all();

        foreach($rows as $key => $row) {
            $rowNumber = $key + 2;
            $rowData = array_combine($header, $row);

            try {
                $studentEmail = trim($rowData['student_email']);
                $score = trim($rowData['score']);
                $remarks = isset($rowData['remarks']) ? trim($rowData['remarks']) : null;

                if (!isset($students[$studentEmail])) {
                    throw new \Exception("Student with email '{$studentEmail}' not found or is not a student.");
                }
                $studentId = $students[$studentEmail];

                if (!is_numeric($score) || $score < 0 || $score > $assessment->max_marks) {
                    throw new \Exception("Invalid score '{$score}'. Must be a number between 0 and {$assessment->max_marks}.");
                }
                
                Result::updateOrCreate(
                    [
                        'user_id' => $studentId, 
                        'assessment_id' => $assessment->id,
                        'class_id' => $classId 
                    ],
                    [
                        'score' => $score, 
                        'remarks' => $remarks ?? 'Imported via CSV'
                    ]
                );
                $successCount++;

            } catch (\Exception $e) {
                $errors[] = "Row #{$rowNumber}: " . $e->getMessage();
            }
        }

        $message = "Import process finished. $successCount results were successfully created or updated for assessment: '{$assessment->name}'.";

        if (!empty($errors)) {
            return redirect()->route('admin.results.index')
                             ->with('success', $message)
                             ->with('import_errors', $errors);
        }

        return redirect()->route('admin.results.index')->with('success', $message);
    }
}