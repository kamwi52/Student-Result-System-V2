<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportGeneratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public ?string $filename;
    public bool $hasFailed;
    public ?string $errorMessage;

    public function __construct(?string $filename, bool $hasFailed = false, ?string $errorMessage = null)
    {
        $this->filename = $filename;
        $this->hasFailed = $hasFailed;
        $this->errorMessage = $errorMessage;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        if ($this->hasFailed) {
            return (new MailMessage)
                ->error()
                ->subject('Report Generation Failed')
                ->greeting('Hello ' . $notifiable->name . ',')
                ->line('Unfortunately, the report you requested could not be generated.')
                ->line('Reason: ' . ($this->errorMessage ?? 'An unknown error occurred.'))
                ->line('Please try again or contact an administrator if the problem persists.');
        }

        $printUrl = route('admin.final-reports.print', ['filename' => $this->filename]);

        // =========================================================================
        // === THE DEFINITIVE FIX: CORRECTED THE TYPO FROM 'o' to '.' ============
        // =========================================================================
        return (new MailMessage)
                    ->subject('Your Report is Ready to View')
                    ->greeting('Hello ' . $notifiable->name . ',') // <-- THIS WAS THE BUG. IT IS NOW FIXED.
                    ->line('The bulk student report you requested has been successfully generated.')
                    ->action('View & Print Report', $printUrl)
                    ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        if ($this->hasFailed) {
            return [
                'status' => 'error',
                'title' => 'Report Failed',
                'message' => 'Failed to generate your report. Reason: ' . ($this->errorMessage ?? 'Unknown error'),
                'action_url' => null,
            ];
        }
        
        $printUrl = route('admin.final-reports.print', ['filename' => $this->filename]);

        return [
            'status' => 'success',
            'title' => 'Report Ready',
            'message' => 'Your student report is ready to view and print.',
            'action_url' => $printUrl,
        ];
    }
}