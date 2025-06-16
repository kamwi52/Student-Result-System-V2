<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::latest()->paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        // No longer needs to fetch teachers
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        // No longer validates user_id
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:subjects',
            'description' => 'nullable|string',
        ]);

        Subject::create($validated);

        // Uses the correct route name for redirect
        return redirect()->route('admin.subjects.index')->with('success', 'Subject created successfully!');
    }

    public function show(Subject $subject)
    {
        //
    }

    public function edit(Subject $subject)
    {
        // No longer needs to fetch teachers
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        // No longer validates user_id
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:subjects,code,'.$subject->id,
            'description' => 'nullable|string',
        ]);

        $subject->update($validated);

        // Uses the correct route name for redirect
        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully!');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        // Uses the correct route name for redirect
        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully!');
    }
}