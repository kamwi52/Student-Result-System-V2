<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   // In UserController.php
public function index()
{
    // We can fetch all users, maybe the ones that aren't admins,
    // or all of them. Let's get all for now.
    $users = \App\Models\User::latest()->paginate(10);

    return view('admin.users.index', compact('users'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(\App\Models\User $user) // Added full namespace for clarity
{
    // Laravel automatically finds the user by their ID from the URL
    return view('admin.users.edit', compact('user'));
}

    /**
     * Update the specified resource in storage.
     */
    // in UserController.php

public function update(\Illuminate\Http\Request $request, \App\Models\User $user)
{
    // Validate the incoming role
    $request->validate([
        'role' => 'required|in:admin,teacher,student', // Must be one of these three values
    ]);

    // Update the user's role
    $user->role = $request->role;
    $user->save();

    // THIS IS THE CORRECTED LINE
return redirect()->route('admin.users.index')->with('success', 'User role updated successfully!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
