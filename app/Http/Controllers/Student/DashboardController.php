<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassSection;
use App\Models\Result;

class DashboardController extends Controller
{
    public function __construct()
    {
        // First, ensure the user is authenticated.
        $this->middleware('auth');

        // THEN, run a check to ensure the authenticated user is a student.
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'student') {
                abort(403, 'Unauthorized Action.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $student = Auth::user();
        $enrollments = $student->enrollments()->with('classSection.teachers', 'classSection.subjects')->get();
        return view('student.dashboard', compact('enrollments'));
    }

    public function showResults(ClassSection $classSection)
    {
        $student = Auth::user();
        $isEnrolled = $student->enrollments()->where('class_section_id', $classSection->id)->exists();
        if (!$isEnrolled) {
            abort(403, 'You are not enrolled in this class.');
        }
        $subjectIds = $classSection->subjects()->pluck('subjects.id');
        $results = Result::where('user_id', $student->id)
            ->whereHas('assessment', function ($query) use ($subjectIds) {
                $query->whereIn('subject_id', $subjectIds);
            })
            ->with('assessment.subject')
            ->get()
            ->sortBy('assessment.subject.name');
        return view('student.results', compact('classSection', 'results'));
    }
}