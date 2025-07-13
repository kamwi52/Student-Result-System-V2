<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the student's academic results dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Get the currently authenticated user (the student)
        $student = Auth::user();

        // 2. Fetch the student's results, eager loading related data for efficiency.
        // We need the subject and the academic session for each result.
        $results = $student->results()
                           ->with(['subject', 'academicSession'])
                           ->get();

        // 3. Group the results by the academic session.
        // This creates a collection where each key is an academic_session_id
        // and the value is a collection of results for that session.
        $resultsBySession = $results->groupBy('academic_session_id');

        // 4. We'll pass this grouped data to the view.
        // The view will then loop through each session and display its results.
        // The calculation of averages and grades will happen in the view for clarity,
        // but could also be done here.

        return view('student.dashboard', [
            'resultsBySession' => $resultsBySession,
        ]);
    }
}