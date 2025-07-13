<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassSection;
use App\Models\Assessment;
use App\Models\Result;
use App\Imports\ResultsImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class BulkGradeController extends Controller
{
    /**
     * Show the form for selecting a class and assessment.
     */
    public function create()
    {
        // Get the classes assigned to the currently logged-in teacher
        $teacherClasses = Auth::user()->classSections()->orderBy('name')->get();
        $assessments = Assessment::with('subject')->latest()->get();

        return view('teacher.grades.bulk-create', compact('teacherClasses', 'assessments'));
    }

    /**
     * Show the main grade entry form with all students for the selected class.
     */
    public function show(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:class_sections,id',
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $classId = $request->input('class_id');
        $assessmentId = $request->input('assessment_id');

        // Verify the teacher is assigned to the selected class
        if (!Auth::user()->classSections()->where('id', $classId)->exists()) {
            return redirect()->back()->with('error', 'You are not authorized to access this class.');
        }

        $classSection = ClassSection::findOrFail($classId);
        $assessment = Assessment::with('subject')->findOrFail($assessmentId);

        // Get all students enrolled in this class
        $students = $classSection->enrollments()->with('user')->get()->pluck('user');

        // Pre-fetch existing results for these students for this assessment to populate form
        $existingResults = Result::where('class_id', $classId)
                                 ->where('assessment_id', $assessmentId)
                                 ->whereIn('user_id', $students->pluck('id'))
                                 ->pluck('score', 'user_id');

        return view('teacher.grades.bulk-show', compact('classSection', 'assessment', 'students', 'existingResults'));
    }

    /**
     * Store or update the grades for multiple students.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:class_sections,id',
            'assessment_id' => 'required|exists:assessments,id',
            'scores' => 'required|array',
            'scores.*' => 'nullable|numeric|min:0|max:100', // Validate each score in the array
        ]);

        $classId = $request->input('class_id');
        $assessmentId = $request->input('assessment_id');

        foreach ($request->scores as $studentId => $score) {
            // Only save if a score was actually entered
            if (!is_null($score)) {
                Result::updateOrCreate(
                    [
                        'user_id' => $studentId,
                        'class_id' => $classId,
                        'assessment_id' => $assessmentId,
                    ],
                    [
                        'score' => $score,
                    ]
                );
            }
        }

        return redirect()->route('teacher.grades.bulk.create')->with('success', 'Grades have been saved successfully!');
    }

    /**
     * Show the form for uploading the grades file.
     * It receives the class and assessment from the previous selection page.
     */
    public function showImportForm(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:class_sections,id',
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $classSection = ClassSection::findOrFail($request->class_id);
        $assessment = Assessment::with('subject')->findOrFail($request->assessment_id);

        return view('teacher.grades.import-form', compact('classSection', 'assessment'));
    }

    /**
     * Handle the uploaded grades file.
     */
    public function handleImport(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:class_sections,id',
            'assessment_id' => 'required|exists:assessments,id',
            'results_file' => 'required|file|mimes:csv,xlsx,xls',
        ]);

        $classId = $request->input('class_id');
        $assessmentId = $request->input('assessment_id');

        try {
            Excel::import(new ResultsImport($classId, $assessmentId), $request->file('results_file'));
        } catch (ValidationException $e) {
            // This catches validation errors from our ResultsImport class
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Row " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            return redirect()->back()->with('import_errors', $errorMessages);
        }

        return redirect()->route('teacher.grades.bulk.create')->with('success', 'Grades have been imported successfully!');
    }

    /**
     * Generate and download a CSV template for a given class.
     */
    public function downloadTemplate(Request $request)
    {
        $request->validate(['class_id' => 'required|exists:class_sections,id']);
        $classSection = ClassSection::with('enrollments.user')->findOrFail($request->class_id);
        $students = $classSection->enrollments->pluck('user');

        $filename = "grade_template_{$classSection->name}.csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            // Add column headers
            fputcsv($file, ['student_id', 'student_name', 'score', 'remarks']);

            // Add student data
            foreach ($students as $student) {
                fputcsv($file, [$student->student_id, $student->name, '', '']);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}