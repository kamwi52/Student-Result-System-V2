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
use ZipArchive;

class GenerateBulkReportCards implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ClassSection $class;
    protected array $studentIds;
    protected int $termId; // <-- ADDED
    protected User $requestingUser;

    /**
     * Create a new job instance.
     */
    public function __construct(ClassSection $class, array $studentIds, int $termId, User $requestingUser)
    {
        $this->class = $class;
        $this->studentIds = $studentIds;
        $this->termId = $termId; // <-- ADDED
        $this->requestingUser = $requestingUser;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $classSlug = str($this->class->name)->slug();
        $timestamp = now()->format('Y-m-d-His');
        $zipFileName = "reports/teacher-{$this->requestingUser->id}/reports-{$classSlug}-{$timestamp}.zip";
        $tempDirectory = "temp-reports/{$classSlug}-{$timestamp}";

        try {
            // Eager load the class relationships we'll need for every student
            $this->class->load('academicSession', 'gradingScale.grades');
            
            $studentsToProcess = User::whereIn('id', $this->studentIds)->get();

            Storage::disk('private')->makeDirectory($tempDirectory);

            foreach ($studentsToProcess as $student) {
                // === FIX: Use the proven logic from the Admin's job to get results ===
                $resultsForTerm = Result::where('user_id', $student->id)
                    ->whereHas('assessment', function ($query) {
                        $query->where('term_id', $this->termId);
                    })
                    ->with(['assessment.subject']) // Eager load for performance
                    ->get();
                
                // Group results by subject for the report card view
                $reportData = $resultsForTerm->groupBy('assessment.subject.name')->map(function ($subjectResults) {
                    return [
                        'results' => $subjectResults,
                        'final_grade' => $subjectResults->avg('score')
                    ];
                });
                // === END OF LOGIC FIX ===

                $pdfData = [
                    'student' => $student,
                    'reportData' => $reportData,
                    'academicSession' => $this->class->academicSession->name ?? 'N/A',
                    'gradingScale' => $this->class->gradingScale
                ];

                $pdf = PDF::loadView('pdf.report-card', $pdfData);
                $studentSlug = str($student->name)->slug();
                $filename = "{$tempDirectory}/report-{$studentSlug}-{$student->id}.pdf";
                Storage::disk('private')->put($filename, $pdf->output());
            }

            // Create the Zip file
            $zip = new ZipArchive;
            $zipFilePath = Storage::disk('private')->path($zipFileName);

            if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
                $files = Storage::disk('private')->files($tempDirectory);
                foreach ($files as $file) {
                    $zip->addFile(Storage::disk('private')->path($file), basename($file));
                }
                $zip->close();
            }

            $this->requestingUser->notify(new ReportGeneratedNotification($zipFileName, false));

        } catch (Throwable $e) {
            Log::error("Bulk Report Job Failed for User ID {$this->requestingUser->id}: " . $e->getMessage());
            $this->requestingUser->notify(new ReportGeneratedNotification(null, true, $e->getMessage()));
        } finally {
            if (isset($tempDirectory)) {
                Storage::disk('private')->deleteDirectory($tempDirectory);
            }
        }
    }
}