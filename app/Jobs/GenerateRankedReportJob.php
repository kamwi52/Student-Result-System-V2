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
use Illuminate\Support\Facades\Log;
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
        try {
            $classSection = ClassSection::with(['academicSession', 'gradingScale.grades'])->findOrFail($this->classSectionId);
            
            $allResults = Result::whereIn('user_id', $this->studentUserIds)
                ->whereHas('assessment', function ($query) {
                    $query->where('term_id', $this->termId);
                })
                ->with('assessment.subject')
                ->get();

            $resultsBySubject = $allResults->groupBy('assessment.subject_id');
            $subjectRanks = [];
            $subjectStudentCounts = [];

            foreach ($resultsBySubject as $subjectId => $results) {
                $sortedResults = $results->sortByDesc('score')->values();
                $subjectStudentCounts[$subjectId] = $sortedResults->count();

                $rank = 0;
                $last_score = -1;
                foreach ($sortedResults as $index => $result) {
                    if ($result->score < $last_score) { $rank = $index + 1; }
                    if ($last_score === -1) { $rank = 1; }
                    $subjectRanks[$result->id] = $rank;
                    $last_score = $result->score;
                }
            }

            $reportData = [];
            $students = User::whereIn('id', $this->studentUserIds)->get();

            foreach ($students as $student) {
                $studentResults = $allResults->where('user_id', $student->id);
                foreach ($studentResults as $result) {
                    $result->subject_rank = $subjectRanks[$result->id] ?? null;
                }
                
                $totalScore = $studentResults->sum('score');
                $average = $studentResults->count() > 0 ? $studentResults->avg('score') : 0;
                
                // === FIX: THE DETAILED COMMENT LOGIC IS RESTORED HERE ===
                $commentParts = [];
                if ($average >= 90) { $commentParts[] = 'An exceptional performance, demonstrating mastery of the subjects.'; } 
                elseif ($average >= 80) { $commentParts[] = 'An outstanding overall performance. Truly excellent work.'; } 
                elseif ($average >= 70) { $commentParts[] = 'A very good and consistent performance across all subjects.'; } 
                elseif ($average >= 60) { $commentParts[] = 'A good, solid performance this term. Keep up the effort.'; } 
                elseif ($average >= 50) { $commentParts[] = 'A satisfactory performance. Consistent effort will lead to further improvement.'; } 
                else if ($studentResults->count() > 0) { $commentParts[] = 'There are areas requiring significant improvement across the board.'; }

                if ($studentResults->count() > 1) {
                    $sortedResults = $studentResults->sortBy('score');
                    $lowestScoreResult = $sortedResults->first();
                    $highestScoreResult = $sortedResults->last();

                    if ($highestScoreResult->score >= 80) {
                        $commentParts[] = 'Particularly strong work was noted in ' . $highestScoreResult->assessment->subject->name . '.';
                    }
                    if ($lowestScoreResult->score < 60) {
                        $commentParts[] = 'Future focus could be placed on improving results in ' . $lowestScoreResult->assessment->subject->name . '.';
                    }
                }
                
                $systemComment = $studentResults->count() > 0 ? implode(' ', $commentParts) : 'No results available to generate a comment.';
                // === END OF FIX ===

                $reportData[] = [
                    'student' => $student,
                    'results' => $studentResults,
                    'total' => $totalScore,
                    'average' => $average,
                    'system_comment' => $systemComment,
                    'subject_student_counts' => $subjectStudentCounts,
                ];
            }

            // (Overall ranking logic remains the same)
            usort($reportData, fn($a, $b) => $b['total'] <=> $a['total']);
            $rank = 0; $last_score = -1;
            foreach ($reportData as $index => &$data) {
                if ($data['total'] < $last_score) { $rank = $index + 1; }
                if ($last_score === -1) { $rank = 1; }
                $data['rank'] = $rank;
                $last_score = $data['total'];
            }

            $pdf = Pdf::loadView('pdf.ranked-bulk-report', [
                'reportData' => $reportData,
                'classSection' => $classSection,
            ]);
            
            $pdf->setPaper('a4', 'portrait');
            $filename = 'reports/ranked-report-' . $classSection->id . '-' . time() . '.pdf';
            Storage::disk('private')->put($filename, $pdf->output());

            $this->requestingUser->notify(new ReportGeneratedNotification($filename, false));

        } catch (Throwable $exception) {
            Log::error("Ranked Report Job Failed for User ID {$this->requestingUser->id}: " . $exception->getMessage() . ' in ' . $exception->getFile() . ' on line ' . $exception->getLine());
            $this->failed($exception);
        }
    }

    public function failed(Throwable $exception): void
    {
        $this->requestingUser->notify(new ReportGeneratedNotification(null, true, $exception->getMessage()));
    }
}