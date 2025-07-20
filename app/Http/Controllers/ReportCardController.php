<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Result;
use App\Models\ClassSection; // <-- ADD THIS LINE
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportCardController extends Controller
{
    public function generateReport(User $student)
    {
        $results = $student->results()->with(['assessment.assignment.subject'])->get();
        $student->load('classSection');
        $resultsBySubject = $results->groupBy('assessment.assignment.subject.name');

        $reportData = [];
        foreach ($resultsBySubject as $subjectName => $subjectResults) {
            if ($subjectName) {
                $reportData[$subjectName] = [
                    'results' => $subjectResults,
                    'final_grade' => $subjectResults->avg('score')
                ];
            }
        }

        $dataForPdf = [
            'student' => $student,
            'reportData' => $reportData,
            'academicSession' => 'Mid Term 2024'
        ];

        $pdf = PDF::loadView('pdf.report-card', $dataForPdf);
        return $pdf->stream('report-card-'.$student->id.'.pdf');
    }

    public function generateForAdmin(User $student)
    {
        return $this->generateReport($student);
    }

    public function generateForTeacher(User $student)
    {
        return $this->generateReport($student);
    }

    public function generateForStudent()
    {
        $student = Auth::user();
        return $this->generateReport($student);
    }

    /**
     * === NEW METHOD: Generate a multi-page PDF for an entire class ===
     */
    public function generateForClass(ClassSection $classSection)
    {
        $classSection->load('students');
        $allReportsData = [];

        foreach ($classSection->students as $student) {
            $results = $student->results()->with(['assessment.assignment.subject'])->get();
            $resultsBySubject = $results->groupBy('assessment.assignment.subject.name');
            
            $reportData = [];
            foreach ($resultsBySubject as $subjectName => $subjectResults) {
                if ($subjectName) {
                    $reportData[$subjectName] = [
                        'results' => $subjectResults,
                        'final_grade' => $subjectResults->avg('score')
                    ];
                }
            }

            $allReportsData[] = [
                'student' => $student,
                'reportData' => $reportData
            ];
        }

        $dataForPdf = [
            'allReportsData' => $allReportsData,
            'classSectionName' => $classSection->name,
            'academicSession' => 'Mid Term 2024'
        ];

        $pdf = PDF::loadView('pdf.report-card-class', $dataForPdf);
        return $pdf->stream('report-cards-' . $classSection->name . '.pdf');
    }
}