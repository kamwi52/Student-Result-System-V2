<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\Assessment;
use App\Models\Result;
use Illuminate\Http\Request;

class GradebookController extends Controller
{
    public function index(ClassSection $class)
    {
        if ($class->user_id !== auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $students = $class->students()->orderBy('name')->get();
        $assessments = Assessment::where('academic_session_id', $class->academic_session_id)->get();
        $results = Result::where('class_section_id', $class->id)
            ->get()
            ->keyBy(function ($item) {
                return $item->user_id . '-' . $item->assessment_id;
            });

        return view('teacher.gradebook.index', compact('class', 'students', 'assessments', 'results'));
    }

    // =========================================================
    //  THIS IS THE NEW METHOD YOU NEED TO ADD
    // =========================================================
    public function store(Request $request)
    {
        $request->validate([
            'class_section_id' => 'required|exists:classes,id',
        ]);

        if ($request->has('results')) {
            foreach ($request->results as $student_id => $assessments) {
                foreach ($assessments as $assessment_id => $marks) {
                    if ($marks !== null) {
                        Result::updateOrCreate(
                            [
                                'user_id' => $student_id,
                                'class_section_id' => $request->class_section_id,
                                'assessment_id' => $assessment_id,
                            ],
                            [
                                'marks_obtained' => $marks,
                            ]
                        );
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Grades saved successfully!');
    }
}