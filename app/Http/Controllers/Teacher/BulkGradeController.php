<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class BulkGradeController extends Controller
{
    public function edit(Assignment $assignment)
    {
        if (auth()->user()->id !== $assignment->teacher_id) {
            abort(403, 'UNAUTHORIZED');
        }

        $assignment->load([
            'subject',
            'results.student',
            'classSection' => function ($query) {
                $query->with('gradingScale.grades');
            }
        ]);

        return view('teacher.grades.bulk-edit', compact('assignment'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        if (auth()->user()->id !== $assignment->teacher_id) {
            abort(403, 'UNAUTHORIZED');
        }

        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*.score' => 'nullable|numeric|min:0|max:100',
        ]);

        $assignment->load('classSection.gradingScale.grades');
        $gradesForScale = $assignment->classSection->gradingScale->grades ?? null;

        DB::transaction(function () use ($validated, $assignment, $gradesForScale) {
            foreach ($validated['grades'] as $resultId => $data) {
                $score = $data['score'] ?? null;
                $remark = $this->getRemarkForScore($score, $gradesForScale);

                $result = Result::find($resultId);
                if ($result && $result->assessment_id === $assignment->assessment_id) {
                    $result->update([
                        'score' => $score,
                        'comments' => $remark,
                    ]);
                }
            }
        });

        return redirect()->route('teacher.assignments.results', $assignment->id)
                         ->with('success', 'Grades have been updated successfully.');
    }

    private function getRemarkForScore($score, $grades): ?string
    {
        if (is_null($score) || is_null($grades)) {
            return null;
        }

        foreach ($grades as $grade) {
            if ($score >= $grade->min_score && $score <= $grade->max_score) {
                return $grade->remark;
            }
        }
        return null;
    }
}