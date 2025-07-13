<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Added for logging

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::latest()->paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects',
            'code' => 'required|string|max:255|unique:subjects',
            'description' => 'nullable|string',
        ]);

        Subject::create($validated);
        return redirect()->route('admin.subjects.index')->with('success', 'Subject created successfully!');
    }

    public function show(Subject $subject)
    {
        //
    }

    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,'.$subject->id,
            'code' => 'required|string|max:255|unique:subjects,code,'.$subject->id,
            'description' => 'nullable|string',
        ]);

        $subject->update($validated);
        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully!');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully!');
    }

    // --- NEW METHODS FOR IMPORT ---

    /**
     * Show the form for importing subjects.
     */
    public function showImportForm()
    {
        return view('admin.subjects.import-form');
    }

    /**
     * Handle the imported subjects file.
     */
    public function handleImport(Request $request)
    {
        $request->validate([
            'subjects_file' => 'required|file|mimes:csv',
        ]);

        $file = $request->file('subjects_file');
        $filePath = $file->getPathname();

        $rows = array_map('str_getcsv', file($filePath));
        $header = array_shift($rows); // Get and remove the header row

        $requiredColumns = ['name', 'code', 'description'];
        if ($header !== $requiredColumns) {
            return redirect()->back()->with('import_errors', ['Invalid CSV header. Please ensure columns are exactly: name,code,description']);
        }

        $import_errors = [];
        $existingSubjects = Subject::pluck('code', 'name')->toArray();

        try {
            foreach ($rows as $key => $row) {
                $rowNumber = $key + 2;

                if (count($row) != 3) {
                   $import_errors[] =  'Row '. $rowNumber .': Incorrect number of columns. Expected 3, got ' . count($row); 
                   continue;
                }

                list($name, $code, $description) = array_map('trim', $row);

                // Validation
                if (empty($name)) $import_errors[] = 'Row '. $rowNumber . ': The name field is required.';
                if (empty($code)) $import_errors[] = 'Row '. $rowNumber . ': The code field is required.';
                if (isset($existingSubjects[$name])) $import_errors[] = 'Row '. $rowNumber . ': The subject name "' . $name . '" already exists.';
                if (in_array($code, $existingSubjects)) $import_errors[] = 'Row '. $rowNumber . ': The subject code "' . $code . '" already exists.';
                
                if (!empty($import_errors)) continue;

                // Create the subject
                Subject::create([
                    'name' => $name,
                    'code' => $code,
                    'description' => $description,
                ]);
            }

            if (count($import_errors) > 0) {
              return redirect()->back()->with('import_errors',  $import_errors)->withInput();
            }

            return redirect()->route('admin.subjects.index')->with('success', 'Subjects imported successfully!');
        
        } catch (\Exception $e) {
            Log::error('Subject Import Exception: '.$e->getMessage());
            return redirect()->back()->with('import_errors', ['An unexpected error occurred. Please check the logs.'])->withInput();
        }
    }
}