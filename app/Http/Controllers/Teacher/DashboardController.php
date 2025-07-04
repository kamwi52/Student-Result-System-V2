<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// This is the only class that should be in this file.
class DashboardController extends Controller
{
    /**
     * Display the teacher's dashboard.
     */
    public function index()
    {
        $teacher = Auth::user();

        // This line will work correctly once we fix the User model in the next step.
        $classes = $teacher->taughtClasses()
            ->with(['subject', 'academicSession'])
            ->withCount('students')
            ->orderBy('name')
            ->get();

        $stats = [
            'total_classes' => $classes->count(),
            'total_students' => $classes->sum('students_count'),
        ];

        return view('teacher.dashboard', [
            'classes' => $classes,
            'stats' => $stats,
        ]);
    }
}