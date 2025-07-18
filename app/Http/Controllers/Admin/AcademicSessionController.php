<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession; // Make sure this model is imported
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // For redirect responses
use Illuminate\Validation\Rule; // For unique validation rules

class AcademicSessionController extends Controller
{
    /**
     * Display a listing of the academic sessions.
     */
    public function index(): View
    {
        $academicSessions = AcademicSession::latest()->paginate(10);
        return view('admin.academic-sessions.index', compact('academicSessions'));
    }

    /**
     * Show the form for creating a new academic session.
     */
    public function create(): View
    {
        return view('admin.academic-sessions.create');
    }

    /**
     * Store a newly created academic session in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:academic_sessions,name',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_current' => 'boolean', // This will be absent if checkbox is unchecked
        ]);

        // Handle 'is_current' checkbox: set to false if not present in request
        $validated['is_current'] = $request->has('is_current') && $validated['is_current'];

        // Ensure only one academic session is 'current'
        if ($validated['is_current']) {
            AcademicSession::where('is_current', true)->update(['is_current' => false]);
        } else {
            // If this is the very first session being created, make it current by default
            // unless explicitly unchecked and there are no other sessions.
            if (AcademicSession::count() === 0) {
                $validated['is_current'] = true;
            }
        }

        AcademicSession::create($validated);

        return redirect()->route('admin.academic-sessions.index')->with('success', 'Academic session created successfully.');
    }

    /**
     * Show the form for editing the specified academic session.
     */
    public function edit(AcademicSession $academicSession): View
    {
        return view('admin.academic-sessions.edit', compact('academicSession'));
    }

    /**
     * Update the specified academic session in storage.
     */
    public function update(Request $request, AcademicSession $academicSession): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('academic_sessions')->ignore($academicSession->id),
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_current' => 'boolean',
        ]);
        
        $validated['is_current'] = $request->has('is_current'); // Ensure it's false if checkbox not present

        // Ensure only one academic session is 'current'
        if ($validated['is_current']) {
            AcademicSession::where('is_current', true)
                           ->where('id', '!=', $academicSession->id)
                           ->update(['is_current' => false]);
        } 
        // Prevent unmarking the only current session if others exist
        elseif (AcademicSession::where('is_current', true)->count() === 1 && $academicSession->is_current && !$validated['is_current']) {
            return redirect()->back()->with('error', 'Cannot unmark the only current academic session. Please mark another as current first.');
        }

        $academicSession->update($validated);

        return redirect()->route('admin.academic-sessions.index')->with('success', 'Academic session updated successfully.');
    }

    /**
     * Remove the specified academic session from storage.
     */
    public function destroy(AcademicSession $academicSession): RedirectResponse
    {
        // Prevent deletion if there are associated classes or assignments
        if ($academicSession->classSections()->exists() || $academicSession->assignments()->exists()) {
            return redirect()->route('admin.academic-sessions.index')
                             ->with('error', 'Cannot delete this academic session. It has associated classes or assignments.');
        }
        // Prevent deletion if it's currently marked as current and there are other sessions
        if ($academicSession->is_current && AcademicSession::count() > 1) {
            return redirect()->route('admin.academic-sessions.index')
                             ->with('error', 'Cannot delete the current academic session. Please mark another as current first.');
        }
        // Prevent deletion if it's the only academic session in the system
        if (AcademicSession::count() === 1) {
            return redirect()->route('admin.academic-sessions.index')
                             ->with('error', 'Cannot delete the only academic session. Create another first.');
        }

        $academicSession->delete();
        return redirect()->route('admin.academic-sessions.index')->with('success', 'Academic session deleted successfully.');
    }
}