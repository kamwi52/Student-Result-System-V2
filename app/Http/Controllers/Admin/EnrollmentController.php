<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\User;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    // ... your existing index() and store() methods ...

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
            
            // NOTE: We now fetch the full student object for the enrolled list
            $enrolledStudents = $selectedClass->students()->orderBy('name')->get();
        }

        return view('admin.enrollments.manage-bulk', compact('classSections', 'selectedClass', 'enrolledStudents', 'unenrolledStudents'));
    }

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