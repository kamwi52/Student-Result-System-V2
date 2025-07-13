<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Assessment;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
// Note: The below are for the original Laravel Excel import, which we are leaving in place
use App\Imports\AssessmentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;


class AssessmentController extends Controller
{
    // All your existing methods (index, create, store, etc.) are here...
    public function index(): View
    {
        $assessments = Assessment::with(['academicSession', 'subject'])->latest()->paginate(10);
        return view('admin.assessments.index', compact('assessments'));
    }
    public function create(): View
    {
        $subjects = Subject::orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        return view('admin.assessments.create', compact('subjects', 'academicSessions'));
    }
    public function store(Request $request)
    {
        // ...
    }
    public function edit(Assessment $assessment): View
    {
        // ...
    }
    public function update(Request $request, Assessment $assessment)
    {
        // ...
    }
    public function destroy(Assessment $assessment)
    {
        // ...
    }
    public function showImportForm()
    {
        return view('admin.assessments.import-form');
    }
    
    /**
     * Handle the simple imported assessments file - FINAL ROBUST VERSION
     */
    public function handleSimpleImport(Request $request)
    {
        $request->validate(['assessments_file' => 'required|file|mimes:csv']);
        $file = $request->file('assessments_file');
        $rows = array_map('str_getcsv', file($file->getPathname()));
        $header = array_shift($rows);

        $requiredColumns = ['assessment_name', 'subject_name', 'academic_session_name', 'max_marks', 'weightage_percent'];
        if (array_map('trim', $header) !== $requiredColumns) { // Trim header for safety
            return redirect()->back()->with('import_errors', ['Invalid CSV header. Must be: assessment_name,subject_name,academic_session_name,max_marks,weightage_percent']);
        }

        $import_errors = [];
        $success_count = 0;
        
        // --- KEY FIX IS HERE: TRIM THE DATABASE VALUES ---
        $academicSessions = AcademicSession::all()->pluck('id', 'name')->mapWithKeys(function ($id, $name) {
            return [trim($name) => $id];
        })->all();
        $allSubjects = Subject::all()->pluck('id', 'name')->mapWithKeys(function ($id, $name) {
            return [trim($name) => $id];
        })->all();
        // ------------------------------------------------

        foreach ($rows as $key => $row) {
            $rowNumber = $key + 2;

            DB::beginTransaction();
            try {
                if (count($row) !== count($requiredColumns)) throw new \Exception("Incorrect number of columns.");
                list($assessment_name, $subject_name, $academic_session_name, $max_marks, $weightage_percent) = array_map('trim', $row);

                if (empty($assessment_name)) throw new \Exception("The 'assessment_name' is required.");
                if (empty($subject_name)) throw new \Exception("The 'subject_name' is required.");
                if (empty($academic_session_name)) throw new \Exception("The 'academic_session_name' is required.");
                if (!is_numeric($max_marks)) throw new \Exception("The 'max_marks' must be a number.");
                if (!is_numeric($weightage_percent)) throw new \Exception("The 'weightage_percent' must be a number.");

                $subject_id = $allSubjects[$subject_name] ?? null;
                if (!$subject_id) throw new \Exception("Subject '$subject_name' not found in database.");
                
                $academic_session_id = $academicSessions[$academic_session_name] ?? null;
                if (!$academic_session_id) throw new \Exception("Academic session '$academic_session_name' not found in database.");

                Assessment::create([
                    'name' => $assessment_name,
                    'subject_id' => $subject_id,
                    'academic_session_id' => $academic_session_id,
                    'max_marks' => $max_marks,
                    'weightage' => $weightage_percent,
                ]);
                
                DB::commit();
                $success_count++;

            } catch (\Exception $e) {
                DB::rollBack();
                $import_errors[] = "Row $rowNumber: " . $e->getMessage();
            }
        }

        $message = "Import process finished. Successfully created $success_count assessments.";
        
        if (!empty($import_errors)) {
            return redirect()->route('admin.assessments.index')
                             ->with('success', $message)
                             ->with('import_errors', $import_errors);
        }
        
        return redirect()->route('admin.assessments.index')->with('success', $message);
    }

    /**
     * Handle the original imported assessments file (using Laravel Excel).
     */
    public function handleImport(Request $request)
    {
         // ... old import logic
    }

    /**
     * Export assessments to CSV.
     */
    public function export()
    {
        // ... export logic
    }
}