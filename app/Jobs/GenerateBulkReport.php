<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Student;
use App\Notifications\ReportGeneratedNotification; // We will create this next
use Throwable;

class GenerateBulkReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600; // 10 minutes

    protected array $studentIds;
    protected User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(array $studentIds, User $user)
    {
        $this->studentIds = $studentIds;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Fetch all student data efficiently with eager loading
        // Customize with() to include all relations your report card needs
        $students = Student::with([
            'user',
            'classSection.academicSession',
            'results.assessment.subject',
            'results.assessment.assessmentType',
            'results.gradingScale'
        ])->whereIn('user_id', $this->studentIds)->get();

        // 2. Generate the PDF
        // You will need a view at 'resources/views/pdf/bulk-report.blade.php'
        // This view will contain a @foreach($students as $student) loop.
        $pdf = Pdf::loadView('pdf.bulk-report', [
            'students' => $students,
            'academicSession' => $students->first()->classSection->academicSession, // Example data
        ]);

        // Set paper size and orientation if needed
        $pdf->setPaper('a4', 'portrait');

        // 3. Save the PDF to a private storage location
        $filename = 'reports/bulk-report-' . $this.user->id . '-' . time() . '.pdf';
        Storage::disk('private')->put($filename, $pdf->output());

        // 4. Notify the user that the report is ready
        // This sends a database notification that can be displayed in your UI
        $this->user->notify(new ReportGeneratedNotification($filename));
    }
    
    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        // Send a notification to the user about the failure.
        $this->user->notify(new ReportGeneratedNotification(null, true, $exception->getMessage()));
    }
}