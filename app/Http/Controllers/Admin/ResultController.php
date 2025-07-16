<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\ClassSection;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $results = Result::with('student', 'assessment.subject')->latest()->paginate(20);
        return view('admin.results.index', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.results.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Placeholder for future implementation
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Result $result): View
    {
        return view('admin.results.edit', compact('result'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Result $result)
    {
        // Placeholder for future implementation
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
     * === THE FIX: Correctly eager-load all necessary data ===
     * Show the form for importing results.
     */
    public function showImportForm(): View
    {
        // 1. Get all classes and, for each class, also get its related subjects.
        $classes = ClassSection::with('subjects')->orderBy('name')->get();

        // 2. Get all assessments and, for each assessment, also get its related subject.
        $assessments = Assessment::with('subject')->get();

        // 3. Pass both complete collections to the view.
        return view('admin.results.import', compact('classes', 'assessments'));
    }

    /**
     * Handle the import of results from a CSV file.
     */
    public function handleImport(Request $request)
    {
        // Placeholder for future implementation
        return redirect()->route('admin.results.index')->with('success', 'Results imported successfully.');
    }
}