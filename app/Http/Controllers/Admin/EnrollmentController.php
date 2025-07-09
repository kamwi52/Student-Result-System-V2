<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// We will create these files next
// use App\Events\StudentsEnrolled;
// use App\Exports\EnrolledStudentsExport;
// use Maatwebsite\Excel\Facades\Excel;

class EnrollmentController extends Controller
{
    /**
     * We will implement this authorization later. For now, we use the 'is.admin' middleware.
    public function __construct()
    {
        $this->middleware('can:manage-enrollments');
    }
    */

    /**
     * Display paginated, searchable student enrollment interface
     */
    public function index(ClassSection $classSection)
    {
        // This part is fine, as it's only querying the 'users' table directly.
        $allStudents = User::where('role', 'student')
            ->when(request('search'), function ($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%'.request('search').'%')
                      ->orWhere('email', 'like', '%'.request('search').'%');
                });
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        // === MODIFIED: Specify the 'users.id' to resolve ambiguity ===
        $enrolledStudentIds = $classSection->students()
            ->pluck('users.id') // <-- We specify the table name 'users'
            ->toArray();
        // ===============================================================

        return view('admin.enrollments.index', [
            'classSection' => $classSection->loadCount('students'),
            'allStudents' => $allStudents,
            'enrolledStudentIds' => $enrolledStudentIds,
            'searchTerm' => request('search')
        ]);
    }

    /**
     * Process enrollment updates with transaction safety
     */
    public function store(Request $request, ClassSection $classSection)
    {
        $validated = $request->validate([
            'student_ids' => 'sometimes|array',
            'student_ids.*' => 'exists:users,id' // Removed role check for simplicity for now
        ]);

        DB::transaction(function () use ($classSection, $validated) {
            // The sync method is simpler and sufficient for now
            // It expects an array of user IDs, which is what the form will provide.
            $classSection->students()->sync($validated['student_ids'] ?? []);

            // Event firing can be added later
            // if (!empty($changes['attached']) || !empty($changes['detached'])) {
            //     event(new StudentsEnrolled($classSection, $changes));
            // }
        });

        return redirect()
            ->route('admin.classes.index') // Redirect back to the class list after saving
            ->with('success', "Enrollments for {$classSection->name} have been successfully updated.");
    }
}