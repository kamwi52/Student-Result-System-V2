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
     * The old entry point for selecting a class.
     * This now redirects to the new, better starting point.
     */
    public function create()
    {
        return redirect()->route('teacher.gradebook.index');
    }

    /**
     * Show the main grade entry form (the grid).
     * MODIFIED: It now receives models directly from the route parameters,
     * making it accessible from the "Edit All Grades" button.
     */
    public function show(ClassSection $classSection, Assessment $assessment)
    {
        // Security Check: Ensure the teacher is authorized for this class.
        if ($classSection->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized Action');
        }

        $students = $classSection->enrollments;
        
        $existingResults = Result::where('assessment_id', 'like', $assessment->id)
                                 ->whereIn('user_id', $students->pluck('id'))
                                 ->get()->keyBy('user_id');

        return view('teacher.grades.bulk-show', compact('classSection', 'assessment', 'students', 'existingResults'));
    }

    /**
     * Store or update the grades for multiple students from the grid view.
     * This logic is correct and remains unchanged.
     */
    public function store(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'scores' => 'required|array',
            'remarks' => 'nullable|array',
            'scores.*' => 'nullable|numeric|min:0|max:100',
            'remarks.*' => 'nullable|string|max:255',
        ]);

        $assessmentId = $request->input('assessment_id');

        foreach ($request->scores as $studentId => $score) {
            if (!is_null($score)) {
                $remark = $request->remarks[$studentId] ?? null;
                Result::updateOrCreate(
                    ['user_id' => $studentId, 'assessment_id' => $assessmentId],
                    ['score' => $score, 'remark' => $remark]
                );
            }
        }

        return redirect()->route('teacher.dashboard')->with('success', 'Grades have been saved successfully!');
    }

    // --- YOUR EXISTING IMPORT/EXPORT FEATURES (UNCHANGED) ---
    // These methods are important and are kept exactly as you had them.

    public function showImportForm(Request $request)
    {
        $request->validate([ 'class_id' => 'required|exists:class_sections,id', 'assessment_id' => 'required|exists:assessments,id',]);
        $classSection = ClassSection::findOrFail($request->class_id);
        $assessment = Assessment::with('subject')->findOrFail($request->assessment_id);
        return view('teacher.grades.import-form', compact('classSection', 'assessment'));
    }

    public function handleImport(Request $request)
    {
        $request->validate([ 'class_id' => 'required|exists:class_sections,id', 'assessment_id' => 'required|exists:assessments,id', 'results_file' => 'required|file|mimes:csv,xlsx,xls', ]);
        $classId = $request->input('class_id');
        $assessmentId = $request->input('assessment_id');
        try {
            Excel::import(new ResultsImport($classId, $assessmentId), $request->file('results_file'));
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Row " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            return redirect()->back()->with('import_errors', $errorMessages);
        }
        return redirect()->route('teacher.grades.bulk.create')->with('success', 'Grades have been imported successfully!');
    }

    public function downloadTemplate(Request $request)
    {
        $request->validate(['class_id' => 'required|exists:class_sections,id']);
        $classSection = ClassSection::with('enrollments')->findOrFail($request->class_id);
        $students = $classSection->enrollments;
        $filename = "grade_template_{$classSection->name}.csv";
        $headers = [ 'Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\"",];
        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['student_id', 'student_name', 'score', 'remarks']);
            foreach ($students as $student) {
                fputcsv($file, [$student->student_id, $student->name, '', '']);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}