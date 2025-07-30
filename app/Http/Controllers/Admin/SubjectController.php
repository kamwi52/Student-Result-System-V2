<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\User; // <-- ADDED: To fetch teacher data
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Exception;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     * === UPDATED: Now counts assigned teachers for the index page ===
     */
    public function index(): View
    {
        $subjects = Subject::withCount('teachers')->latest()->paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     * === UPDATED: Fetches teachers for the creation form ===
     */
    public function create(): View
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        return view('admin.subjects.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     * === UPDATED: Handles teacher assignments on creation ===
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects',
            'code' => 'required|string|max:20|unique:subjects',
            'description' => 'nullable|string',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:users,id',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $subject = Subject::create($request->only('name', 'code', 'description'));

            if (!empty($validated['teachers'])) {
                $subject->teachers()->sync($validated['teachers']);
            }
        });

        return redirect()->route('admin.subjects.index')->with('success', 'Subject created and teachers assigned successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     * === UPDATED: Fetches teachers and current assignments for the edit form ===
     */
    public function edit(Subject $subject): View
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $subject->load('teachers'); // Eager load existing assignments

        return view('admin.subjects.edit', compact('subject', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     * === UPDATED: Handles teacher assignments on update ===
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:users,id',
        ]);

        DB::transaction(function () use ($request, $subject) {
            $subject->update($request->only('name', 'code', 'description'));

            // Sync will automatically add/remove teachers based on the checkboxes.
            $subject->teachers()->sync($request->input('teachers', []));
        });

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

    /**
     * Handle the import of subjects from a spreadsheet.
     */
    public function handleImport(Request $request)
    {
        $request->validate(['subjects_file' => 'required|file|mimes:csv,txt']);
    
        try {
            $file = $request->file('subjects_file');
            $path = $file->getRealPath();
            $records = array_map('str_getcsv', file($path));
    
            if (count($records) < 1) {
                return redirect()->back()->with('import_error', 'The uploaded file is empty or invalid.');
            }
    
            $header = array_map('trim', array_shift($records));
            $requiredColumns = ['name', 'code', 'description'];
    
            if ($header !== $requiredColumns) {
                $expected = implode(', ', $requiredColumns);
                $actual = implode(', ', $header);
                throw new Exception("Invalid CSV header. Expected: '{$expected}'. Found: '{$actual}'.");
            }
    
            $import_errors = [];
            $success_count = 0;
    
            foreach ($records as $key => $row) {
                if (count($row) !== count($header) || empty(implode('', $row))) {
                    continue; 
                }
                $rowNumber = $key + 2;
                
                DB::beginTransaction();
                try {
                    $data = array_combine($header, $row);
                    
                    if (empty($data['name']) || empty($data['code'])) {
                        throw new Exception("Both 'name' and 'code' fields are required.");
                    }
                    if (Subject::where('name', $data['name'])->orWhere('code', $data['code'])->exists()) {
                        throw new Exception("A subject with the name '{$data['name']}' or code '{$data['code']}' already exists.");
                    }

                    Subject::create($data);
                    DB::commit();
                    $success_count++;
                } catch (Exception $e) {
                    DB::rollBack();
                    $import_errors[] = "Row {$rowNumber}: " . $e->getMessage();
                }
            }
            
            $message = "Import process finished. Successfully created {$success_count} subjects.";
            
            return redirect()->route('admin.subjects.index')
                             ->with('success', $message)
                             ->with('import_errors', $import_errors);
                             
        } catch (Exception $e) {
            return redirect()->back()->with('import_error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }
}
