<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the teacher's dashboard.
     *
     * This method fetches all classes assigned to the currently
     * logged-in teacher and displays them in the dashboard view.
     * It eager-loads related subject and academic session data,
     * and also gets a count of students enrolled in each class.
     */
    public function index(): View
    {
        // Get the currently authenticated user (the teacher)
        $teacher = Auth::user();

        // Fetch the classes assigned to this teacher
        // The relationship should be defined in the User model
        $classes = $teacher->classes() // Assumes a 'classes' relationship on the User model
                           ->with(['subject', 'academicSession']) // Eager-load for efficiency
                           ->withCount('students') // Get the number of enrolled students
                           ->paginate(10); // Paginate the results

        // Return the view and pass the classes data to it
        return view('teacher.dashboard', compact('classes'));
    }
}