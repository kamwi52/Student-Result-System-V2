<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Define the roles to pass to the create view as well (optional, but good practice if create also uses roles)
        // In your provided create view, roles were not a dropdown, so it might not be strictly needed there yet.
        // If you add a role dropdown to the create form later, uncomment these lines:
        /*
        $roles = [
            'admin' => 'Admin',
            'teacher' => 'Teacher',
            'student' => 'Student',
        ];
        return view('admin.users.create', compact('roles'));
        */

        // Keeping the original return for create based on your provided view
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,teacher,student'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return to_route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        // === MODIFIED: Define the roles array and pass it to the view ===
        $roles = [
            'admin' => 'Admin',
            'teacher' => 'Teacher',
            'student' => 'Student',
        ];

        return view('admin.users.edit', compact('user', 'roles')); // Pass both 'user' and 'roles'
        // ================================================================
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:admin,teacher,student'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return to_route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === 1 || $user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete this user.');
        }

        $user->delete();
        return to_route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    // =======================================================
    // == ADDED METHODS FOR BULK USER IMPORT
    // =======================================================

    /**
     * Show the form for importing users.
     */
    public function showImportForm(): View
    {
        return view('admin.users.import');
    }

    /**
     * Handle the import of users from a spreadsheet.
     */
    public function handleImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return to_route('admin.users.index')->with('success', 'Users imported successfully.');
    }
}