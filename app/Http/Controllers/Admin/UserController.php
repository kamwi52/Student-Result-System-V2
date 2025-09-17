<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessUserImportJob;
use App\Models\User;
use App\Models\ClassSection; // <-- 1. Import the ClassSection model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource, now with class filtering.
     */
    public function index(Request $request): View
    {
        $perPageOptions = [10, 25, 50, 100];
        $perPage = in_array($request->input('per_page', 10), $perPageOptions) ? $request->input('per_page', 10) : 10;

        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(fn($q) => $q->where('name', 'LIKE', "%{$searchTerm}%")->orWhere('email', 'LIKE', "%{$searchTerm}%"));
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->whereRaw('LOWER(role) = ?', [strtolower($request->input('role'))]);
        }

        // =========================================================================
        // === THE DEFINITIVE FIX: ADDED CLASS FILTERING LOGIC =====================
        // =========================================================================
        if ($request->filled('class_section_id')) {
            $classId = $request->input('class_section_id');
            // This powerful query joins the users table with the enrollments table
            // and filters for users who have an enrollment record for the selected class.
            $query->whereHas('enrollments', function ($q) use ($classId) {
                $q->where('class_section_id', $classId);
            });
        }
        
        // --- 2. Fetch all classes to populate the filter dropdown ---
        $classes = ClassSection::with('academicSession')->orderBy('name')->get();
        
        $users = $query->latest()->paginate($perPage);

        // --- 3. Pass the new $classes variable to the view ---
        return view('admin.users.index', compact('users', 'perPage', 'perPageOptions', 'classes'));
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
        $request->validate(['user_ids' => 'required|array', 'user_ids.*' => 'exists:users,id']);
        $idsToDelete = collect($request->input('user_ids'))->reject(fn($id) => $id == 1 || $id == auth()->id())->toArray();
        $deletedCount = count($idsToDelete);
        $skippedCount = count($request->input('user_ids')) - $deletedCount;
        if ($deletedCount > 0) { User::destroy($idsToDelete); }
        $message = "{$deletedCount} user(s) deleted successfully.";
        if ($skippedCount > 0) { $message .= " {$skippedCount} protected user(s) were not deleted."; }
        return back()->with('success', $message);
    }
}