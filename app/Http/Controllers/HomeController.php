<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard or redirect the user based on their role.
     *
     * This method acts as a central hub after login. It checks the role
     * of the authenticated user and redirects them to the appropriate
     * dashboard for their role.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get the currently authenticated user.
        $user = Auth::user();

        // Check the user's role and redirect accordingly.
        if ($user->role === 'admin') {
            // Admins are redirected to the user management page.
            return redirect()->route('admin.users.index');
        }

        if ($user->role === 'teacher') {
            // Teachers are redirected to their specific dashboard.
            return redirect()->route('teacher.dashboard');
        }
        
        // === FIX: Added the missing check for the 'student' role ===
        if ($user->role === 'student') {
            // Students are redirected to their specific dashboard.
            return redirect()->route('student.dashboard');
        }

        // Fallback for any other user role or if no role is set.
        // This will render the generic 'resources/views/dashboard.blade.php' view.
        // It's better to use 'dashboard' as the fallback view name, which is a Laravel standard.
        return view('dashboard');
    }
}