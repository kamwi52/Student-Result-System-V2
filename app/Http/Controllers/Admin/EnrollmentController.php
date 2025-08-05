<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\User;
use App\Models\AcademicSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * === THIS METHOD IS UPGRADED WITH FILTERING LOGIC ===
     * Show the form to manually enroll students in a specific class.
     * It now supports filtering students by their previous class.
     */
    public function index(Request $request, ClassSection $classSection)
    {
        // Get the ID of the class we are enrolling INTO (the destination)
        $destinationClassId = $classSection->id;

        // Fetch all academic sessions and their classes to build the filter dropdown.
        // We exclude the current class itself from the filter options.
        $sessions = AcademicSession::with(['classSections' => function ($query) use ($destinationClassId) {
            $query->where('id', '!=', $destinationClassId);
        }])->orderBy('name', 'desc')->get();

        // Check if the user has applied a filter from the dropdown
        $sourceClassId = $request->input('source_class_id');
        
        // Start the base query for users with the 'student' role
        $studentQuery = User::where('role', 'student');

        // If a source class ID is present in the URL, modify the query
        if ($sourceClassId) {
            // Get the IDs of students who are currently enrolled in the SOURCE class
            $sourceStudentIds = DB::table('class_section_user')
                                  ->where('class_section_id', $sourceClassId)
                                  ->pluck('user_id');
            
            // Limit the main student query to only include students from that source class
            $studentQuery->whereIn('id', $sourceStudentIds);
        }

        // Execute the query to get the final list of students to display
        $allStudents = $studentQuery->orderBy('name')->get();

        // Get the IDs of students already enrolled in our DESTINATION class
        // This is used to pre-check the correct boxes in the view
        $enrolledStudentIds = $classSection->students()->pluck('users.id')->toArray();

        // Return the view with all the necessary data
        return view('admin.enrollments.index', [
            'classSection' => $classSection,           // The class we are enrolling INTO
            'allStudents' => $allStudents,              // The (possibly filtered) list of students
            'enrolledStudentIds' => $enrolledStudentIds,  // List of currently enrolled student IDs
            'sessions' => $sessions,                    // Data for the filter dropdown
            'sourceClassId' => $sourceClassId,          // The currently selected filter ID
        ]);
    }

    /**
     * Store the manually updated enrollment list for the class from the index page.
     */
    public function store(Request $request, ClassSection $classSection)
    {
        $request->validate([
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        $classSection->students()->sync($request->input('student_ids', []));

        return redirect()->route('admin.classes.index')->with('success', "Enrollment for '{$classSection->name}' updated successfully.");
    }
    
    /**
     * Show the server-side bulk enrollment management form.
     */
    public function showBulkManageForm(Request $request)
    {
        $classSections = ClassSection::with('academicSession')->orderBy('name')->get();
        
        $selectedClass = null;
        $enrolledStudents = collect();
        $unenrolledStudents = collect();

        if ($request->filled('class_section_id')) {
            $validated = $request->validate(['class_section_id' => 'required|exists:class_sections,id']);
            $selectedClass = ClassSection::findOrFail($validated['class_section_id']);
            
            $enrolledStudentIds = $selectedClass->students()->pluck('users.id')->toArray();
            
            $unenrolledStudents = User::where('role', 'student')
                                        ->whereNotIn('id', $enrolledStudentIds)
                                        ->orderBy('name')
                                        ->get(['id', 'name']);
            
            $enrolledStudents = $selectedClass->students()->orderBy('name')->get();
        }

        return view('admin.enrollments.manage-bulk', compact('classSections', 'selectedClass', 'enrolledStudents', 'unenrolledStudents'));
    }

    /**
     * Handle the submission from the bulk enrollment form.
     */
    public function handleBulkManage(Request $request)
    {
        $validated = $request->validate([
            'class_section_id' => 'required|exists:class_sections,id',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        $classSection = ClassSection::findOrFail($validated['class_section_id']);
        
        $classSection->students()->sync($validated['student_ids'] ?? []);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Enrollments for ' . $classSection->name . ' have been updated successfully!');
    }
}