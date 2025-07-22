<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Result;
use App\Models\ClassSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportCardController extends Controller
{
    /**
     * Apply authentication middleware to all methods in this controller.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Generate a report card for the currently logged-in student.
     */
    public function generateForStudent()
    {
        $student = Auth::user();
        return $this->generateReport($student);
    }

    /**
     * Generate a report card for a specific student (for admins).
     */
    public function generateForAdmin(User $student)
    {
        // Protected by 'is.admin' middleware on the route.
        return $this->generateReport($student);
    }

    /**
     * Generate a report card for a specific student (for teachers).
     */
    public function generateForTeacher(User $student)
    {
        $teacher = Auth::user();

        // Authorize that the teacher can view this student's report.
        if ($student->classSection) {
            $isAuthorized = $student->classSection
                            ->subjects()
                            ->wherePivot('teacher_id', $teacher->id)
                            ->exists();
            if (!$isAuthorized) {
                abort(403, 'You are not authorized to generate a report for this student.');
            }
        } else {
            // Student is not in a class, teacher cannot view.
            abort(403, 'Student is not enrolled in a class.');
        }

        return $this->generateReport($student);
    }

    /**
     * Core logic to generate a PDF report for a single student.
     * This method is kept separate for individual report generation.
     */
    private function generateReport(User $student)
    {
        // Eager load all necessary data for the single report.
        $student->load([
            'classSection.subjects',
            'results.assessment.subject',
            'results.assessment.assignment'
        ]);

        // Group results by subject for the summary table.
        $resultsBySubject = $student->results->groupBy('assessment.subject.name');
        $reportData = collect();

        foreach ($resultsBySubject as $subjectName => $subjectResults) {
            if ($subjectName) {
                $reportData->put($subjectName, [
                    'results' => $subjectResults,
                    'final_grade' => $subjectResults->avg('score')
                ]);
            }
        }

        // Prepare data to pass to the single report card view.
        $dataForPdf = [
            'student' => $student,
            'reportData' => $reportData,
            'academicSession' => $student->classSection->academicSession->name ?? 'N/A'
        ];

        $pdf = PDF::loadView('pdf.report-card', $dataForPdf);
        return $pdf->stream('report-card-' . $student->name . '.pdf');
    }

    /**
     * Generate a multi-page PDF with ranked report cards for an entire class.
     */
    public function generateForClass(ClassSection $classSection)
    {
        // Eager load all necessary relationships for efficiency.
        $classSection->load([
            'students.results', // We need all results to sum them up.
            'subjects',         // For getting teacher IDs.
            'academicSession'
        ]);

        // --- Logic to Calculate Student Ranks ---
        $rankedStudents = collect();

        foreach ($classSection->students as $student) {
            // Calculate the total score for the current student.
            $totalScore = $student->results->sum('score');
            
            // Add the student and their calculated data to our new collection.
            $rankedStudents->push([
                'student' => $student,
                'total_score' => $totalScore
            ]);
        }

        // Sort the collection by total_score in descending order (highest score first).
        // The values() method re-indexes the collection from 0 after sorting.
        $sortedStudents = $rankedStudents->sortByDesc('total_score')->values();

        // Now, map over the sorted collection to add the rank.
        $finalStudentData = $sortedStudents->map(function ($studentData, $key) {
            // The rank is the array index (key) + 1.
            $studentData['rank'] = $key + 1;
            return $studentData;
        });
        // --- End of Ranking Logic ---

        // Prepare the final data array to pass to the class-wide report view.
        $dataForPdf = [
            'classSection' => $classSection,
            'academicSession' => $classSection->academicSession->name ?? 'N/A',
            'finalStudentData' => $finalStudentData, // Pass the new ranked data.
            // Create a map of teachers for easy lookup in the view.
            'teachers' => User::whereIn('id', $classSection->subjects->pluck('pivot.teacher_id')->unique())->get()->keyBy('id'),
        ];
        
        // Load the view and stream the PDF.
        $pdf = PDF::loadView('pdf.report-card-class', $dataForPdf);
        return $pdf->stream('report-cards-' . $classSection->name . '.pdf');
    }
}