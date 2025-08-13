<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class ReportGeneratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public ?string $filename;
    public bool $hasFailed;
    public ?string $errorMessage;

    /**
     * Create a new notification instance.
     *
     * @param string|null $filename The path to the saved PDF file. Null on failure.
     * @param bool $hasFailed True if the job failed.
     * @param string|null $errorMessage The exception message on failure.
     */
    public function __construct(?string $filename, bool $hasFailed = false, ?string $errorMessage = null)
    {
        $this->filename = $filename;
        $this->hasFailed = $hasFailed;
        $this->errorMessage = $errorMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // We will send this notification via email and store it in the database.
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        if ($this->hasFailed) {
            return (new MailMessage)
                ->error() // This gives it a red theme
                ->subject('Report Generation Failed')
                ->greeting('Hello ' . $notifiable->name . ',')
                ->line('Unfortunately, the report you requested could not be generated.')
                ->line('Reason: ' . ($this->errorMessage ?? 'An unknown error occurred.'))
                ->line('Please try again or contact an administrator if the problem persists.');
        }

        // === FIX: The route name has been corrected ===
        $downloadUrl = URL::temporarySignedRoute(
            'reports.download.generated', // <-- REMOVED 'admin.' prefix
            now()->addHours(24),
            ['filename' => $this->filename]
        );

        return (new MailMessage)
                    ->subject('Your Report is Ready for Download')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('The bulk student report you requested has been successfully generated.')
                    ->action('Download Report', $downloadUrl)
                    ->line('This download link will expire in 24 hours.')
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification (for the database).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if ($this->hasFailed) {
            return [
                'status' => 'error',
                'title' => 'Report Failed',
                'message' => 'Failed to generate your report. Reason: ' . ($this->errorMessage ?? 'Unknown error'),
                'action_url' => null, // No action to take on failure
            ];
        }
        
        // === FIX: The route name has been corrected ===
        $downloadUrl = URL::temporarySignedRoute(
            'reports.download.generated', // <-- REMOVED 'admin.' prefix
            now()->addHours(24),
            ['filename' => $this->filename]
        );

        return [
            'status' => 'success',
            'title' => 'Report Ready',
            'message' => 'Your student report is ready for download.',
            'action_url' => $downloadUrl, // This URL is used by the notification bell link
        ];
    }
}