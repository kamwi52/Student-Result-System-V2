<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Assessment;
use App\Models\ClassSection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $results = Result::with(['student', 'assessment.subject'])->latest()->paginate(10);
        return view('admin.results.index', compact('results'));
    }

    // ... (Your create, store, edit, update, destroy methods are likely here and can remain)

    /**
     * === NEW/EDITED: Show Step 1 of the import process (Class Selection). ===
     */
    public function showImportStep1(): View
    {
        $classSections = ClassSection::with('academicSession')->orderBy('name')->get();
        return view('admin.results.import-step1', compact('classSections'));
    }

    /**
     * === NEW/EDITED: Handle the submission from Step 1 and redirect to Step 2. ===
     */
    public function handleImportStep1(Request $request)
    {
        $validated = $request->validate([
            'class_section_id' => 'required|exists:class_sections,id',
        ]);

        // Redirect to the second step, passing the chosen class ID in the URL.
        return redirect()->route('admin.results.import.show_step2', ['classSection' => $validated['class_section_id']]);
    }

    /**
     * === NEW/EDITED: Show Step 2 of the import process (Assessment & File Upload). ===
     */
    public function showImportStep2(ClassSection $classSection): View
    {
        // Find all assessments that are linked to the selected class.
        $assessments = Assessment::where('class_section_id', $classSection->id)
                                 ->with('subject')
                                 ->orderBy('name')
                                 ->get();
        
        return view('admin.results.import-step2', compact('classSection', 'assessments'));
    }

    /**
     * === NEW/EDITED: Process the final CSV upload for results. ===
     */
    public function processImport(Request $request, ClassSection $classSection)
    {
        $validated = $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $records = array_map('str_getcsv', file($path));

        if (count($records) <= 1) {
            return redirect()->back()->with('error', 'The file is empty or invalid.');
        }

        $header = array_map('trim', array_shift($records));
        $requiredColumns = ['student_email', 'score'];

        if (count(array_diff($requiredColumns, $header)) > 0) {
            return redirect()->back()->with('error', 'Invalid CSV header. Must contain: student_email, score');
        }

        $importErrors = [];
        $successCount = 0;
        $assessment = Assessment::find($validated['assessment_id']);

        DB::beginTransaction();
        try {
            foreach ($records as $key => $row) {
                $rowNumber = $key + 2;
                if (empty(implode('', $row))) continue;
                
                $data = array_combine($header, $row);

                $student = User::where('email', $data['student_email'])->where('role', 'student')->first();
                if (!$student) {
                    $importErrors[] = "Row {$rowNumber}: Student with email '{$data['student_email']}' not found.";
                    continue;
                }
                
                // Use updateOrCreate to prevent duplicate results.
                // It will find a result for this student in this assessment and update it,
                // or it will create a new one if it doesn't exist.
                Result::updateOrCreate(
                    [
                        'user_id' => $student->id,
                        'assessment_id' => $assessment->id,
                    ],
                    [
                        'score' => $data['score'],
                        // You can add a 'remark' column to your CSV and table if needed
                        // 'remark' => $data['remark'] ?? null, 
                    ]
                );
                $successCount++;
            }

            if (!empty($importErrors)) {
                DB::rollBack();
                return redirect()->back()->with('import_errors', $importErrors);
            }

            DB::commit();
            return redirect()->route('admin.results.index')->with('success', "Import complete! {$successCount} results were created or updated.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Result Import Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred during the import.');
        }
    }
}