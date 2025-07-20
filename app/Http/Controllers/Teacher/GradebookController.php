<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\ClassSection;
use App\Models\Subject;
use App\Models\Result; // <-- FIX: Changed -> to \
use App\Models\Assessment; // <-- FIX: Changed -> to \
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB; // If you still use DB facade for pivot checks

class GradebookController extends Controller
{
    /**
     * PAGE 1: Display a list of all Class-Subject combinations assigned to the logged-in teacher.
     */
    public function index(): View
    {
        $teacher = Auth::user();

        // Get all ClassSections.
        // Eager load academicSession.
        // Eager load *all* subjects for these classes, ensuring pivot data (teacher_id) is loaded.
        $allClassesWithSubjects = ClassSection::with([
            'academicSession',
            'subjects' => function ($query) {
                // We are loading ALL subjects linked to these classes,
                // along with their pivot data, which includes teacher_id.
                $query->withPivot('teacher_id'); 
            }
        ])
        ->orderBy('name')
        ->get(); // Get all results first, we will filter them in PHP

        // Now, filter these classes and their subjects in PHP to keep only
        // those subjects taught by the current teacher.
        $classesTaught = new \Illuminate\Database\Eloquent\Collection(); // Initialize an empty Eloquent Collection

        foreach ($allClassesWithSubjects as $classSection) {
            $filteredSubjects = $classSection->subjects->filter(function ($subject) use ($teacher) {
                // Check if the subject has a pivot record and if the teacher_id matches the current teacher
                return $subject->pivot && $subject->pivot->teacher_id === $teacher->id;
            });

            // If this class section has any subjects taught by the current teacher,
            // clone the class section and attach only these filtered subjects to it.
            if ($filteredSubjects->isNotEmpty()) {
                $clonedClassSection = clone $classSection; // Clone to avoid modifying the original collection's relationships
                $clonedClassSection->setRelation('subjects', $filteredSubjects); // Set the filtered subjects
                $classesTaught->push($clonedClassSection);
            }
        }
        
        // Manual pagination for the filtered collection (if you still need it)
        $perPage = 10;
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $classesTaught->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $classesTaught = new \Illuminate\Pagination\LengthAwarePaginator($currentPageItems, $classesTaught->count(), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
        ]);
        
        return view('teacher.gradebook.index', compact('classesTaught'));
    }

    /**
     * PAGE 2: Display a list of assignments for the selected class and subject taught by the logged-in teacher.
     */
    public function showAssessments(ClassSection $classSection, Subject $subject): View
    {
        $teacher = Auth::user();

        // This check should now work correctly, as the relationship loading is confirmed
        $isAssigned = $classSection->subjects()->where('subjects.id', $subject->id)->wherePivot('teacher_id', $teacher->id)->exists();
        if (!$isAssigned) {
            abort(403, 'Unauthorized: You are not assigned to teach this subject in this class.');
        }

        $assignments = Assignment::where('class_section_id', $classSection->id)
                                 ->where('subject_id', $subject->id)
                                 ->where('teacher_id', $teacher->id)
                                 ->with(['classSection', 'subject', 'teacher', 'assessment'])
                                 ->latest()
                                 ->paginate(10);

        return view('teacher.gradebook.assessments', compact('assignments', 'classSection', 'subject'));
    }

    /**
     * PAGE 3: Display the results for a specific assignment.
     */
    public function showResults(Assignment $assignment): View
    {
        $teacher = Auth::user();
        
        $assignment->load('assessment'); 
        
        if (is_null($assignment->assessment)) {
            abort(404, 'Assessment not found for this assignment. Data integrity issue.');
        }

        $assessment = $assignment->assessment; 

        if ($assignment->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized Action: This assignment is not yours.');
        }
        
        if ($assessment->subject_id !== $assignment->subject_id) {
            abort(403, 'Unauthorized Action: Assessment subject mismatch.');
        }

        $assignment->loadMissing('classSection.students', 'subject'); 

        $students = $assignment->classSection->students()->orderBy('name')->get();

        $results = Result::where('assessment_id', $assessment->id) 
                         ->whereIn('user_id', $students->pluck('id'))
                         ->get()
                         ->keyBy('user_id');

        return view('teacher.gradebook.results', compact('assignment', 'assessment', 'students', 'results'));
    }
}