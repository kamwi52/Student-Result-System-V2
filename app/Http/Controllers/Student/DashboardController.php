<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the student's dashboard with their results.
     */
    public function index(): View
    {
        // Get the currently authenticated user (the student)
        $student = Auth::user();

        // Fetch all results for this student
        // Eager-load the related data to prevent N+1 query problems in the view
        $results = Result::where('user_id', $student->id)
            ->with(['classSection.subject', 'assessment'])
            ->latest() // Order by most recent results first
            ->paginate(15);

        return view('student.dashboard', compact('results'));
    }
}