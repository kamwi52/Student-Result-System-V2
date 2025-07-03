<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AcademicSession;
use Illuminate\Http\Request;
// Add these at the top with your other 'use' statements
use App\Imports\AssessmentsImport;
use Maatwebsite\Excel\Facades\Excel;


class AssessmentController extends Controller
{
    public function index() {
        $assessments = Assessment::with('academicSession')->latest()->paginate(10);
        return view('admin.assessments.index', compact('assessments'));
    }

    public function create() {
        $academic_sessions = AcademicSession::orderBy('name', 'desc')->get();
        return view('admin.assessments.create', compact('academic_sessions'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_marks' => 'required|integer|min:1',
            'weightage' => 'required|numeric|min:0|max:1',
            'academic_session_id' => 'required|exists:academic_sessions,id',
        ]);
        Assessment::create($validated);
        return redirect()->route('admin.assessments.index')->with('success', 'Assessment created successfully.');
    }

    public function edit(Assessment $assessment) {
        $academic_sessions = AcademicSession::orderBy('name', 'desc')->get();
        return view('admin.assessments.edit', compact('assessment', 'academic_sessions'));
    }

    public function update(Request $request, Assessment $assessment) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_marks' => 'required|integer|min:1',
            'weightage' => 'required|numeric|min:0|max:1',
            'academic_session_id' => 'required|exists:academic_sessions,id',
        ]);
        $assessment->update($validated);
        return redirect()->route('admin.assessments.index')->with('success', 'Assessment updated successfully.');
    }

    public function destroy(Assessment $assessment) {
        $assessment->delete();
        return redirect()->route('admin.assessments.index')->with('success', 'Assessment deleted successfully.');
    }
    // Inside the AssessmentController class

// ... your existing index(), create(), store(), etc. methods ...

/**
 * Handle the import of assessments from a spreadsheet.
 */
public function import(Request $request)
{
    // 1. Validate the uploaded file
    $request->validate([
        'import_file' => 'required|mimes:csv,xlsx'
    ]);

    // 2. Find the current academic session
    // Get the most recently created academic session as the "current" one.
$currentSession = AcademicSession::latest()->firstOrFail();

    // 3. Get the uploaded file
    $file = $request->file('import_file');

    // 4. Process the file using our Import class
    // We pass the current session ID to the constructor
    Excel::import(new AssessmentsImport($currentSession->id), $file);

    // 5. Redirect back with a success message
    return redirect()->route('admin.assessments.index')->with('success', 'Assessments have been imported successfully!');
}
}