<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $subjects = Subject::latest()->paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects',
            'code' => 'required|string|max:20|unique:subjects',
            'description' => 'nullable|string',
        ]);

        Subject::create($request->all());

        return redirect()->route('admin.subjects.index')->with('success', 'Subject created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject): View
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string',
        ]);

        $subject->update($request->all());

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully.');
    }

    /**
     * Show the form for importing subjects.
     */
    public function showImportForm(): View
    {
        return view('admin.subjects.import');
    }

    public function handleImport(Request $request)
    {
        $request->validate(['subjects_file' => 'required|file|mimes:csv,txt']);
    
        try {
            $file = $request->file('subjects_file');
            $path = $file->getRealPath();
            $records = array_map('str_getcsv', file($path));
    
            if (count($records) < 1) {
                return redirect()->back()->with('import_errors', ['The uploaded file is empty.']);
            }
    
            // Clean the header by trimming whitespace from each column name
            $header = array_map('trim', array_shift($records));
            $requiredColumns = ['name', 'code', 'description'];
    
            if ($header !== $requiredColumns) {
                $expected = implode(', ', $requiredColumns);
                $actual = implode(', ', $header);
                throw new \Exception("Invalid CSV header. Expected: '{$expected}'. Found: '{$actual}'.");
            }
    
            $import_errors = [];
            $success_count = 0;
    
            foreach ($records as $key => $row) {
                if (empty(implode('', $row))) continue; // Skip empty rows
                $rowNumber = $key + 2;
                DB::beginTransaction();
                try {
                    $data = array_combine($header, $row);
                    if (empty($data['name']) || empty($data['code'])) {
                        throw new \Exception("Both 'name' and 'code' fields are required.");
                    }
                    if (Subject::where('name', $data['name'])->orWhere('code', $data['code'])->exists()) {
                        throw new \Exception("A subject with the name '{$data['name']}' or code '{$data['code']}' already exists.");
                    }
                    Subject::create($data);
                    DB::commit();
                    $success_count++;
                } catch (\Exception $e) {
                    DB::rollBack();
                    $import_errors[] = "Row {$rowNumber}: " . $e->getMessage();
                }
            }
            $message = "Import process finished. Successfully created {$success_count} subjects.";
            return redirect()->route('admin.subjects.index')
                             ->with('success', $message)
                             ->with('import_errors', $import_errors);
        } catch (\Exception $e) {
            return redirect()->back()->with('import_errors', ['An unexpected error occurred: ' . $e->getMessage()]);
        }
    }
}