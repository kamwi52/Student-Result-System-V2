<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradingScale;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GradingScaleController extends Controller
{
    /**
     * Display a listing of the grading scales.
     */
    public function index(): View
    {
        // === THE FIX IS HERE ===
        // We change ->get() to ->paginate() to fetch the results in chunks.
        // This returns a Paginator instance, which the view's ->links() method can use.
        $scales = GradingScale::withCount('grades')->latest()->paginate(10); // Using 10 items per page

        return view('admin.grading-scales.index', compact('scales'));
    }

    /**
     * Show the form for creating a new grading scale.
     */
    public function create(): View
    {
        return view('admin.grading-scales.create');
    }

    /**
     * Store a newly created grading scale and its grades in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:grading_scales',
            'grades' => 'required|array|min:1',
            'grades.*.grade_name' => 'required|string|max:20',
            'grades.*.min_score' => 'required|integer|min:0|max:100',
            'grades.*.max_score' => 'required|integer|min:0|max:100|gte:grades.*.min_score',
            'grades.*.remark' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $scale = GradingScale::create(['name' => $validated['name']]);
            $scale->grades()->createMany($validated['grades']);
        });

        return redirect()->route('admin.grading-scales.index')->with('success', 'Grading scale created successfully.');
    }

    /**
     * Show the form for editing the specified grading scale.
     */
    public function edit(GradingScale $gradingScale): View
    {
        $gradingScale->load('grades'); // Eager load the grades for the form
        return view('admin.grading-scales.edit', compact('gradingScale'));
    }

    /**
     * Update the specified grading scale in storage.
     */
    public function update(Request $request, GradingScale $gradingScale)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:grading_scales,name,' . $gradingScale->id,
            'grades' => 'required|array|min:1',
            'grades.*.id' => 'nullable|integer|exists:grades,id',
            'grades.*.grade_name' => 'required|string|max:20',
            'grades.*.min_score' => 'required|integer|min:0|max:100',
            'grades.*.max_score' => 'required|integer|min:0|max:100|gte:grades.*.min_score',
            'grades.*.remark' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $gradingScale) {
            $gradingScale->update(['name' => $validated['name']]);
            
            // Get IDs of grades submitted to delete any that were removed from the form
            $submittedGradeIds = collect($validated['grades'])->pluck('id')->filter();
            $gradingScale->grades()->whereNotIn('id', $submittedGradeIds)->delete();

            // Update existing grades or create new ones
            foreach ($validated['grades'] as $gradeData) {
                $gradingScale->grades()->updateOrCreate(
                    ['id' => $gradeData['id'] ?? null], // Match by ID or create if new
                    $gradeData
                );
            }
        });

        return redirect()->route('admin.grading-scales.index')->with('success', 'Grading scale updated successfully.');
    }

    /**
     * Remove the specified grading scale from storage.
     */
    public function destroy(GradingScale $gradingScale)
    {
        $gradingScale->delete(); // The onDelete('cascade') in the migration will handle deleting the grades.
        return redirect()->route('admin.grading-scales.index')->with('success', 'Grading scale deleted successfully.');
    }
}