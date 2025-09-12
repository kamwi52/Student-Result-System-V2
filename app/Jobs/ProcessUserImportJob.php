<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Imports\UsersImport; // <-- It uses your existing "Rule Book"
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Validators\ValidationException;

class ProcessUserImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Here, the background worker uses your UsersImport rules to process the file.
            Excel::import(new UsersImport, $this->filePath);

        } catch (ValidationException $e) {
            $errors = [];
            foreach ($e->failures() as $failure) {
                $errors[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            Log::error('User Import Validation Failed: ', $errors);

        } catch (\Exception $e) {
            Log::error('An unexpected error occurred during user import.', [
                'file' => $this->filePath,
                'error' => $e->getMessage()
            ]);
        }
    }
}