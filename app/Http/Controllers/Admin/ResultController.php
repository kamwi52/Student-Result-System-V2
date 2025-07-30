<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\ClassSection;
use App\Models\Assessment;
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
        $results = Result::with(['student', 'assessment.subject', 'assessment.classSection'])
            ->latest()
            ->paginate(20);
        return view('admin.results.index', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $students = User::where('role', 'student')->orderBy('name')->get();
        $assessments = Assessment::with(['subject', 'classSection'])->orderBy('name')->get();
        return view('admin.results.create', compact('students', 'assessments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'assessment_id' => 'required|exists:assessments,id',
            'score' => 'required|numeric|min:0',
            'comments' => 'nullable|string|max:1000',
        ]);

        if (Result::where('user_id', $validated['user_id'])->where('assessment_id', $validated['assessment_id'])->exists()) {
            return back()->with('error', 'A result for this student and assessment already exists. Please edit the existing result.');
        }

        Result::create($validated);
        return redirect()->route('admin.results.index')->with('success', 'Result added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Result $result): View
    {
        $students = User::where('role', 'student')->orderBy('name')->get();
        $assessments = Assessment::with(['subject', 'classSection'])->orderBy('name')->get();
        $result->load(['student', 'assessment']);
        return view('admin.results.edit', compact('result', 'students', 'assessments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Result $result)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'assessment_id' => 'required|exists:assessments,id',
            'score' => 'required|numeric|min:0',
            'comments' => 'nullable|string|max:1000',
        ]);
        $result->update($validated);
        return redirect()->route('admin.results.index')->with('success', 'Result updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Result $result)
    {
        $result->delete();
        return redirect()->route('admin.results.index')->with('success', 'Result deleted successfully.');
    }

    /**
     * === CORRECTED: Show Step 1 of the import process (Class Selection). ===
     */
    public function showImportStep1(): View
    {
        $classSections = ClassSection::with('academicSession')->orderBy('name')->get();
        return view('admin.results.import-step1', compact('classSections'));
    }

    /**
     * === CORRECTED: Handle the submission from Step 1 and redirect to Step 2. ===
     */
    public function handleImportStep1(Request $request)
    {
        $validated = $request->validate([
            'class_section_id' => 'required|exists:class_sections,id',
        ]);

        return redirect()->route('admin.results.import.show_step2', ['classSection' => $validated['class_section_id']]);
    }

    /**
     * === CORRECTED: Show Step 2 of the import process (Assessment & File Upload). ===
     */
    public function showImportStep2(ClassSection $classSection): View
    {
        $assessments = Assessment::where('class_section_id', $classSection->id)
                                 ->with('subject')
                                 ->orderBy('name')
                                 ->get();
        
        return view('admin.results.import-step2', compact('classSection', 'assessments'));
    }

    /**
     * === CORRECTED: Process the final CSV upload for results. ===
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
        $requiredColumns = ['student_email', 'score']; // Optional: add 'comments'

        if (count(array_diff($requiredColumns, $header)) > 0) {
            return redirect()->back()->with('error', 'Invalid CSV header. Must contain at least: student_email, score');
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
                
                Result::updateOrCreate(
                    [ 'user_id' => $student->id, 'assessment_id' => $assessment->id ],
                    [ 'score' => $data['score'], 'comments' => $data['comments'] ?? null ]
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