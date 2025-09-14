<?php

// --- THIS IS THE FIX: Corrected the namespace ---
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ClassSection;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessUserImportJob;
use App\Jobs\ProcessClassImportJob;
use App\Jobs\ProcessSubjectImportJob;
use App\Jobs\ProcessResultImportJob;
use Exception;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics.
     */
    public function index()
    {
        $studentCount = User::where('role', 'student')->count();
        $teacherCount = User::where('role', 'teacher')->count();
        $classCount = ClassSection::count();

        return view('admin.dashboard', [
            'studentCount' => $studentCount,
            'teacherCount' => $teacherCount,
            'classCount'   => $classCount,
        ]);
    }
    
    /**
     * Display the combined import page.
     */
    public function showImportPage()
    {
        return view('admin.imports.index');
    }

    // ===================================================================
    // IMPORT HANDLING METHODS
    // ===================================================================

    public function importUsers(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);
        $requiredHeaders = ['name', 'email', 'password', 'role', 'academic_session_name', 'class_name'];
        
        return $this->handleImport(
            $request->file('file'), 
            $requiredHeaders, 
            ProcessUserImportJob::class, 
            'User'
        );
    }

    public function importClasses(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);
        
        $requiredHeaders = ['name', 'academic_session', 'grading_system', 'subjects'];
        
        return $this->handleImport(
            $request->file('file'), 
            $requiredHeaders, 
            ProcessClassImportJob::class, 
            'Class'
        );
    }

    public function importSubjects(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);
        $requiredHeaders = ['name', 'code', 'description'];
        
        return $this->handleImport(
            $request->file('file'), 
            $requiredHeaders, 
            ProcessSubjectImportJob::class, 
            'Subject'
        );
    }

    public function importResults(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);
        $requiredHeaders = ['student_email', 'score'];
        
        return $this->handleImport(
            $request->file('file'), 
            $requiredHeaders, 
            ProcessResultImportJob::class, 
            'Result'
        );
    }

    private function handleImport($file, array $requiredHeaders, string $jobClass, string $importType)
    {
        try {
            $path = $file->getRealPath();
            $handle = fopen($path, "r");
            $header = fgetcsv($handle);
            fclose($handle);

            if (!$header) {
                throw new Exception("The provided CSV file is empty or improperly formatted.");
            }

            $normalizedHeader = array_map('strtolower', array_map('trim', $header));
            $missingHeaders = array_diff($requiredHeaders, $normalizedHeader);

            if (!empty($missingHeaders)) {
                $errorMessage = "Invalid CSV header. Missing columns: " . implode(', ', $missingHeaders);
                Log::error("{$importType} Import Failed: {$errorMessage}");
                return back()->with('error', $errorMessage);
            }

            $storedPath = $file->store('imports');
            $jobClass::dispatch($storedPath, auth()->id());

            return back()->with('success', "{$importType} import started successfully! You will be notified upon completion.");

        } catch (Exception $e) {
            Log::error("{$importType} Import Failed Unexpectedly: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', "An unexpected error occurred during the {$importType} import. Please check the logs.");
        }
    }

    // ===================================================================
    // TEMPLATE DOWNLOAD METHODS
    // ===================================================================

    public function downloadUsersTemplate(): StreamedResponse
    {
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="users_import_template.csv"'];
        $columns = ['name', 'email', 'password', 'role', 'academic_session_name', 'class_name'];
        $example = ['John Doe', 'john.doe@example.com', 'Password123', 'student', '2023 Academic Year', '10A'];
        $callback = function () use ($columns, $example) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, $example);
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function downloadClassesTemplate(): StreamedResponse
    {
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="classes_import_template.csv"'];
        
        $columns = ['name', 'academic_session', 'grading_system', 'subjects'];
        $example = ['10A', '2023 Academic Year', 'Senior Secondary (Exam)', 'English|Mathematics|History'];

        $callback = function () use ($columns, $example) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, $example);
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function downloadSubjectsTemplate(): StreamedResponse
    {
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="subjects_import_template.csv"'];
        $columns = ['name', 'code', 'description'];
        $example = ['Mathematics', 'MATH101', 'Core mathematics focusing on algebra and geometry.'];
        $callback = function () use ($columns, $example) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, $example);
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function downloadResultsTemplate(): StreamedResponse
    {
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="results_import_template.csv"'];
        $columns = ['student_email', 'score'];
        $example = ['student.email@example.com', '85'];
        $callback = function () use ($columns, $example) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, $example);
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function downloadUserGuide()
    {
        $filePath = 'public/User_Guide.pdf';
        if (!Storage::exists($filePath)) {
            abort(404, 'User guide file not found. Please upload it to storage/app/public/');
        }
        return Storage::download($filePath, 'Student Result System - User Guide.pdf');
    }
}