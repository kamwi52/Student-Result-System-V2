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
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ResultsImport;
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

    // === START: IMPORT WORKFLOW METHODS ===

    /**
     * Show Step 1 of the import process (Class Selection).
     */
    public function showImportStep1(): View
    {
        $classSections = ClassSection::with('academicSession')->orderBy('name')->get();
        return view('admin.results.import-step1', compact('classSections'));
    }

    /**
     * === THIS IS THE CORRECTED METHOD ===
     * Handle submission from Step 1 and prepare data for Step 2.
     */
    public function prepareImportStep2(Request $request)
    {
        $validated = $request->validate([
            'class_section_id' => 'required|exists:class_sections,id',
        ]);

        $classSection = ClassSection::findOrFail($validated['class_section_id']);
        
        // === THE FIX IS HERE ===
        // The query now correctly filters assessments by the selected class_section_id,
        // ensuring only relevant assessments for that class are shown.
        $assessments = Assessment::where('class_section_id', $classSection->id)
            ->with('subject') // Eager load subject for display
            ->orderBy('name')
            ->get();

        return view('admin.results.import-step2', compact('classSection', 'assessments'));
    }

    /**
     * Process the final file upload from Step 2.
     */
    public function handleImport(Request $request)
    {
        $validated = $request->validate([
            'class_section_id' => 'required|exists:class_sections,id',
            'assessment_id' => 'required|exists:assessments,id',
            'file' => 'required|file|mimes:csv,txt,xlsx,xls',
        ]);

        try {
            Excel::import(new ResultsImport($validated['assessment_id']), $request->file('file'));
        } catch (\Exception $e) {
            Log::error('Result Import Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred during import. Please check file format and content. Details: ' . $e->getMessage());
        }

        return redirect()->route('admin.results.index')->with('success', 'Import successful! Results have been created or updated.');
    }

    // === END: IMPORT WORKFLOW METHODS ===
}