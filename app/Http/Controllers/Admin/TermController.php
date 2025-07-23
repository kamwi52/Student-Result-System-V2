<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // === THIS IS THE FIX ===
        // 1. Fetch all terms from the database.
        $terms = Term::latest()->paginate(10); // Get the latest terms and paginate the results.

        // 2. Return a view and pass the fetched data to it.
        return view('admin.terms.index', compact('terms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.terms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:terms,name',
        ]);

        Term::create($validated);

        return redirect()->route('admin.terms.index')->with('success', 'Term created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Term $term)
    {
        // Typically not needed for a simple management page, can redirect to edit.
        return redirect()->route('admin.terms.edit', $term);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Term $term)
    {
        return view('admin.terms.edit', compact('term'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Term $term)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:terms,name,' . $term->id,
        ]);

        $term->update($validated);

        return redirect()->route('admin.terms.index')->with('success', 'Term updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Term $term)
    {
        $term->delete();
        return redirect()->route('admin.terms.index')->with('success', 'Term deleted successfully.');
    }
}