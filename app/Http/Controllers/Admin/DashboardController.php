<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassSection;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Symfony\Component\HttpFoundation\StreamedResponse; // Import this class

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics and charts.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // --- 1. KPI Card Data ---
        $studentCount = User::where('role', 'student')->count();
        $teacherCount = User::where('role', 'teacher')->count();
        $classCount = ClassSection::count();

        // --- 2. Students per Class Chart ---
        $classes = ClassSection::withCount('students')->get();
        
        $studentsPerClassChart = (new LarapexChart)->barChart()
            ->setTitle('Student Distribution per Class')
            // ->setSubtitle('Shows the number of students enrolled in each class.') // Subtitle can be redundant
            ->addData('Students', $classes->pluck('students_count')->toArray())
            ->setXAxis($classes->pluck('name')->toArray())
            ->setColors(['#3B82F6']); // Using a blue color


        return view('admin.dashboard', [
            'studentCount' => $studentCount,
            'teacherCount' => $teacherCount,
            'classCount' => $classCount,
            'studentsPerClassChart' => $studentsPerClassChart,
        ]);
    }

    /**
     * === NEW METHOD ===
     * Generates and downloads a CSV template for importing users.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadUsersTemplate(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users_import_template.csv"',
        ];

        $columns = ['name', 'email', 'password', 'role', 'academic_session_name', 'class_name'];
        $example = ['John Doe', 'john.doe@example.com', 'Password123', 'student', '2025-2026', 'Grade 9A'];

        $callback = function() use ($columns, $example) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); // Header row
            fputcsv($file, $example); // Example row
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * === NEW METHOD ===
     * Generates and downloads a CSV template for importing classes.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadClassesTemplate(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="classes_import_template.csv"',
        ];

        $columns = ['name', 'academic_session_name', 'grading_scale_name', 'subjects'];
        $example = ['Grade 9A', '2025-2026', 'Standard A-F', 'Mathematics|Science|History'];

        $callback = function() use ($columns, $example) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); // Header row
            fputcsv($file, $example); // Example row
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}