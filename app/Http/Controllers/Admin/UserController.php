<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessUserImportJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * === THIS IS THE UPDATED METHOD ===
     * Display a listing of the resource, with search, filter, and dynamic pagination.
     */
    public function index(Request $request): View
    {
        // Define the valid options for the "per page" selector
        $perPageOptions = [10, 25, 50, 100];
        
        // Get the 'per_page' value from the request, default to 10.
        // Also, validate that the requested value is one of our allowed options.
        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, $perPageOptions)) {
            $perPage = 10;
        }

        $query = User::query();

        // Filter by search term
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by role (case-insensitive)
        if ($request->filled('role')) {
            $query->whereRaw('LOWER(role) = ?', [strtolower($request->input('role'))]);
        }

        // Use the dynamic $perPage variable for pagination
        $users = $query->latest()->paginate($perPage);

        // Pass the users and the current perPage value to the view
        return view('admin.users.index', compact('users', 'perPage', 'perPageOptions'));
    }

    // ... all other methods (create, store, edit, etc.) remain exactly the same ...

    public function create(): View
    {
        return view('admin.users.create');
    }

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
            'email_verified_at' => now(),
        ]);

        return to_route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $roles = ['admin' => 'Admin', 'teacher' => 'Teacher', 'student' => 'Student'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

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

    public function destroy(User $user)
    {
        if ($user->id === 1 || $user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete the super admin or yourself.');
        }

        $user->delete();
        return to_route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function showImportForm(): View
    {
        return view('admin.users.import');
    }

    public function handleImport(Request $request)
    {
        $request->validate([ 'file' => 'required|mimes:xlsx,csv,xls', ]);
        $filePath = $request->file('file')->store('imports');
        ProcessUserImportJob::dispatch($filePath);
        return to_route('admin.users.index')->with('success', 'Your file has been uploaded and will be processed in the background.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $idsToDelete = $request->input('user_ids');

        $filteredIds = collect($idsToDelete)->reject(function ($id) {
            return $id == 1 || $id == auth()->id();
        })->toArray();
        
        $deletedCount = count($filteredIds);
        $skippedCount = count($idsToDelete) - $deletedCount;

        if ($deletedCount > 0) {
            User::destroy($filteredIds);
        }

        $message = "{$deletedCount} user(s) deleted successfully.";
        if ($skippedCount > 0) {
            $message .= " {$skippedCount} protected user(s) were not deleted.";
        }

        return back()->with('success', $message);
    }
}