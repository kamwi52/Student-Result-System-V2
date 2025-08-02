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
    protected int $termId;
    protected User $requestingUser;

    public function __construct(array $studentUserIds, int $classSectionId, int $termId, User $requestingUser)
    {
        $this->studentUserIds = $studentUserIds;
        $this->classSectionId = $classSectionId;
        $this->termId = $termId;
        $this->requestingUser = $requestingUser;
    }

    public function handle(): void
    {
        $classSection = ClassSection::with(['students', 'academicSession'])->findOrFail($this->classSectionId);
        $studentsInClass = $classSection->students->whereIn('id', $this->studentUserIds);
        $reportData = [];

        foreach ($studentsInClass as $student) {
            // === LOGIC FIX: THIS QUERY IS NOW CORRECT ===
            // It now directly checks the `term_id` on the `assessments` table.
            $results = Result::where('user_id', $student->id)
                ->whereHas('assessment', function ($query) {
                    $query->where('term_id', $this->termId);
                })
                ->with(['assessment.subject']) // Eager load for performance
                ->get();

            $totalScore = $results->sum('score');
            $average = $results->count() > 0 ? $results->avg('score') : 0;

            $reportData[] = [
                'student' => $student,
                'results' => $results,
                'total' => $totalScore,
                'average' => $average,
                'rank' => 0, // Placeholder for ranking
            ];
        }

        // Rank the students based on total score
        usort($reportData, fn($a, $b) => $b['total'] <=> $a['total']);
        
        $rank = 0;
        $last_score = -1;
        $students_at_rank = 0;
        foreach ($reportData as $index => &$data) {
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