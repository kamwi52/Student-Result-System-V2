<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\Assessment;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Import DB Facade for transactions

class GradebookController extends Controller
{
    /**
     * Show the form for editing grades for a specific class.
     * Renamed from index() to edit() for Laravel convention.
     */
    public function edit(ClassSection $class) // <-- Route Model Binding injects the class
    {
        // 1. AUTHORIZATION: Use the policy to check permissions.
        $this->authorize('view', $class);

        // 2. EFFICIENT DATA LOADING: Use eager loading.
        $class->load(['students' => fn($query) => $query->orderBy('name'), 'academicSession']);
        $assessments = Assessment::where('academic_session_id', $class->academic_session_id)->get();

        // Your clever keyBy logic is perfect, let's keep it.
        $results = Result::where('class_section_id', $class->id)
            ->get()
            ->keyBy(fn($item) => $item->user_id . '-' . $item->assessment_id);

        // Renamed view to 'edit' to match the method name.
        return view('teacher.gradebook.edit', compact('class', 'assessments', 'results'));
    }

    /**
     * Store or update the grades for a specific class.
     */
    public function store(Request $request, ClassSection $class) // <-- Route Model Binding here too!
    {
        // 1. AUTHORIZATION: Ensure the teacher is authorized to update this specific class.
        $this->authorize('view', $class); // We can reuse the 'view' permission for now.

        // 2. VALIDATION: No need to validate class_section_id, it's from the URL.
        $request->validate([
            'results' => 'present|array', // Ensures 'results' exists, even if empty
            'results.*.*' => 'nullable|numeric|min:0|max:100' // Validates every single score
        ]);

        // 3. DATABASE LOGIC: Wrap in a transaction for safety.
        DB::transaction(function () use ($request, $class) {
            foreach ($request->results as $student_id => $assessments) {
                foreach ($assessments as $assessment_id => $marks) {
                    if ($marks !== null && $marks !== '') {
                        Result::updateOrCreate(
                            [
                                'user_id' => $student_id,
                                'class_section_id' => $class->id, // Use the ID from the model
                                'assessment_id' => $assessment_id,
                            ],
                            [
                                'marks_obtained' => $marks,
                            ]
                        );
                    } else {
                        // Optional: If a score is cleared, delete the result from the DB.
                        Result::where('user_id', $student_id)
                              ->where('class_section_id', $class->id)
                              ->where('assessment_id', $assessment_id)
                              ->delete();
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Grades saved successfully!');
    }
}