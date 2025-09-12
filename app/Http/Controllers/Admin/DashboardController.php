<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassSection;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics.
     */
    public function index()
    {
        // KPI Card Data (Chart logic has been removed)
        $studentCount = User::where('role', 'student')->count();
        $teacherCount = User::where('role', 'teacher')->count();
        $classCount = ClassSection::count();

        return view('admin.dashboard', [
            'studentCount' => $studentCount,
            'teacherCount' => $teacherCount,
            'classCount' => $classCount,
        ]);
    }

    // ... (downloadUsersTemplate, downloadClassesTemplate, etc. remain the same) ...

    public function downloadUsersTemplate(): StreamedResponse { /* ... existing code ... */ }
    public function downloadClassesTemplate(): StreamedResponse { /* ... existing code ... */ }
    public function downloadSubjectsTemplate(): StreamedResponse { /* ... existing code ... */ }
    public function downloadResultsTemplate(): StreamedResponse { /* ... existing code ... */ }

    /**
     * === NEW METHOD ===
     * Serves the User Guide PDF for download.
     */
    public function downloadUserGuide()
    {
        $filePath = 'public/User_Guide.pdf';

        // Check if the file exists in the storage.
        if (!Storage::exists($filePath)) {
            abort(404, 'User guide not found.');
        }

        return Storage::download($filePath, 'Student Result System - User Guide.pdf');
    }
}