<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Assessment;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
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
        $request->validate([
            'name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'max_marks' => 'required|integer|min:1',
            'assessment_date' => 'required|date',
        ]);
        Assessment::create($request->all());
        return redirect()->route('admin.assessments.index')->with('success', 'Assessment created successfully.');
    }

    public function edit(Assessment $assessment): View
    {
        $subjects = Subject::orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name', 'desc')->get();
        return view('admin.assessments.edit', compact('assessment', 'subjects', 'academicSessions'));
    }

    public function update(Request $request, Assessment $assessment)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'max_marks' => 'required|integer|min:1',
            'assessment_date' => 'required|date',
        ]);
        $assessment->update($request->all());
        return redirect()->route('admin.assessments.index')->with('success', 'Assessment updated successfully.');
    }

    public function destroy(Assessment $assessment)
    {
        $assessment->delete();
        return redirect()->route('admin.assessments.index')->with('success', 'Assessment deleted successfully.');
    }

    /**
     * Show the form for importing assessments.
     */
    public function showImportForm(): View
    {
        // === THE FIX: Use the correct view name ===
        return view('admin.assessments.import');
    }
    
    /**
     * Handle the imported assessments file.
     * Note: Renamed from handleSimpleImport to handleImport to match routes.
     */
    public function handleImport(Request $request)
    {
        $request->validate(['assessments_file' => 'required|file|mimes:csv,txt']);
        
        try {
            $file = $request->file('assessments_file');
            $path = $file->getRealPath();
            $records = array_map('str_getcsv', file($path));

            if (count($records) < 1) {
                return redirect()->back()->with('import_errors', ['The uploaded file is empty.']);
            }

            $header = array_map('trim', array_shift($records));
            $requiredColumns = ['name', 'subject_name', 'academic_session_name', 'max_marks', 'assessment_date'];
            
            if ($header !== $requiredColumns) {
                $expected = implode(', ', $requiredColumns);
                $actual = implode(', ', $header);
                throw new \Exception("Invalid CSV header. Expected: '{$expected}'. Found: '{$actual}'.");
            }
            
            $import_errors = [];
            $success_count = 0;
            
            $academicSessions = AcademicSession::pluck('id', 'name')->all();
            $allSubjects = Subject::pluck('id', 'name')->all();

            foreach ($records as $key => $row) {
                $rowNumber = $key + 2;
                DB::beginTransaction();
                try {
                    $data = array_combine($header, array_map('trim', $row));

                    if (empty($data['name'])) throw new \Exception("The 'name' is required.");
                    if (!isset($allSubjects[$data['subject_name']])) throw new \Exception("Subject '{$data['subject_name']}' not found.");
                    if (!isset($academicSessions[$data['academic_session_name']])) throw new \Exception("Academic session '{$data['academic_session_name']}' not found.");
                    if (!is_numeric($data['max_marks'])) throw new \Exception("'max_marks' must be a number.");
                    
                    Assessment::create([
                        'name' => $data['name'],
                        'subject_id' => $allSubjects[$data['subject_name']],
                        'academic_session_id' => $academicSessions[$data['academic_session_name']],
                        'max_marks' => $data['max_marks'],
                        'assessment_date' => $data['assessment_date'],
                    ]);
                    
                    DB::commit();
                    $success_count++;
                } catch (\Exception $e) {
                    DB::rollBack();
                    $import_errors[] = "Row {$rowNumber}: " . $e->getMessage();
                }
            }

            $message = "Import process finished. Successfully created {$success_count} assessments.";
            return redirect()->route('admin.assessments.index')->with('success', $message)->with('import_errors', $import_errors);
        } catch (\Exception $e) {
            return redirect()->back()->with('import_errors', ['An unexpected error occurred: ' . $e->getMessage()]);
        }
    }
}