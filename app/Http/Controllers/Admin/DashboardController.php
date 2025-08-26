<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassSection;
use ArielMejiaDev\LarapexCharts\LarapexChart; // Make sure you ran "composer require arielmejiadev/larapex-charts"

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
            ->setSubtitle('Shows the number of students enrolled in each class.')
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
}