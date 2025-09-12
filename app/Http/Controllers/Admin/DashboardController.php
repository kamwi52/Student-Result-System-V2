<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassSection;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics and charts.
     */
    public function index()
    {
        $studentCount = User::where('role', 'student')->count();
        $teacherCount = User::where('role', 'teacher')->count();
        $classCount = ClassSection::count();

        $classes = ClassSection::withCount('students')->get();
        
        $studentsPerClassChart = (new LarapexChart)->barChart()
            ->setTitle('Student Distribution per Class')
            ->addData('Students', $classes->pluck('students_count')->toArray())
            ->setXAxis($classes->pluck('name')->toArray())
            ->setColors(['#3B82F6']);

        return view('admin.dashboard', [
            'studentCount' => $studentCount,
            'teacherCount' => $teacherCount,
            'classCount' => $classCount,
            'studentsPerClassChart' => $studentsPerClassChart,
        ]);
    }

    /**
     * Generates and downloads a CSV template for importing users.
     */
    public function downloadUsersTemplate(): StreamedResponse
    {
        $headers = [ 'Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="users_import_template.csv"', ];
        $columns = ['name', 'email', 'password', 'role', 'academic_session_name', 'class_name'];
        $example = ['John Doe', 'john.doe@example.com', 'Password123', 'student', '2025-2026', 'Grade 9A'];
        $callback = function() use ($columns, $example) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); fputcsv($file, $example); fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generates and downloads a CSV template for importing classes.
     */
    public function downloadClassesTemplate(): StreamedResponse
    {
        $headers = [ 'Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="classes_import_template.csv"', ];
        $columns = ['name', 'academic_session_name', 'grading_scale_name', 'subjects'];
        $example = ['Grade 9A', '2025-2026', 'Standard A-F', 'Mathematics|Science|History'];
        $callback = function() use ($columns, $example) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); fputcsv($file, $example); fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generates and downloads a CSV template for importing subjects.
     */
    public function downloadSubjectsTemplate(): StreamedResponse
    {
        $headers = [ 'Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="subjects_import_template.csv"', ];
        $columns = ['name', 'code', 'description'];
        $example = ['Mathematics', 'MATH101', 'Core mathematics focusing on algebra and geometry.'];
        $callback = function() use ($columns, $example) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); fputcsv($file, $example); fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * === NEW METHOD ===
     * Generates and downloads a CSV template for importing results.
     */
    public function downloadResultsTemplate(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="results_import_template.csv"',
        ];

        $columns = ['student_email', 'score'];
        $example = ['student.email@example.com', '85'];

        $callback = function() use ($columns, $example) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, $example);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}