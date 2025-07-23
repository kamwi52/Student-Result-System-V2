<?php

namespace App\Jobs;

use App\Models\ClassSection;
use App\Models\Result;
use App\Models\User;
use App\Notifications\ReportGeneratedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Throwable;

class GenerateRankedReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 1200;

    protected array $studentUserIds;
    protected int $classSectionId;
    protected int $termId; // Renamed from assessmentTypeId
    protected User $requestingUser;

    public function __construct(array $studentUserIds, int $classSectionId, int $termId, User $requestingUser)
    {
        $this->studentUserIds = $studentUserIds;
        $this->classSectionId = $classSectionId;
        $this->termId = $termId; // Renamed
        $this->requestingUser = $requestingUser;
    }

    public function handle(): void
    {
        $classSection = ClassSection::with(['students.user', 'academicSession'])->findOrFail($this->classSectionId);
        $studentsToProcess = $classSection->students->whereIn('user_id', $this->studentUserIds);
        $reportData = [];

        foreach ($studentsToProcess as $student) {
            $results = Result::where('student_id', $student->id)
                ->whereHas('assessment', function ($query) use ($classSection) {
                    // This now looks for the term_id on the assignment related to the assessment
                    $query->where('academic_session_id', $classSection->academic_session_id)
                          ->whereHas('assignment', function ($subQuery) {
                              $subQuery->where('term_id', $this->termId);
                          });
                })
                ->with('assessment.subject')
                ->get();

            $totalScore = $results->sum('score');
            $average = $results->count() > 0 ? $results->avg('score') : 0;

            $reportData[] = [
                'student' => $student,
                'results' => $results,
                'total' => $totalScore,
                'average' => $average,
                'rank' => 0,
            ];
        }

        usort($reportData, fn($a, $b) => $b['total'] <=> $a['total']);
        
        $rank = 0;
        $last_score = -1;
        $students_at_rank = 0;
        foreach ($reportData as &$data) {
            $students_at_rank++;
            if ($data['total'] < $last_score) {
                $rank += $students_at_rank - 1;
                $students_at_rank = 1;
            }
            $data['rank'] = $rank + 1;
            $last_score = $data['total'];
        }

        $pdf = Pdf::loadView('pdf.ranked-bulk-report', [
            'reportData' => $reportData,
            'classSection' => $classSection,
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        $filename = 'reports/ranked-report-' . $classSection->id . '-' . time() . '.pdf';
        Storage::disk('private')->put($filename, $pdf->output());

        $this->requestingUser->notify(new ReportGeneratedNotification($filename));
    }

    public function failed(Throwable $exception): void
    {
        $this->requestingUser->notify(new ReportGeneratedNotification(null, true, $exception->getMessage()));
    }
}