<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the post-authentication redirect path.
     *
     * This method overrides the default redirection logic. It is the single
     * source of truth for where a user goes after logging in.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'admin':
                // Admins are sent directly to the user management page.
                return route('admin.users.index');
            case 'teacher':
                return route('teacher.dashboard');
            case 'student':
                return route('student.dashboard');
            default:
                // For security, if a user has no role or an unknown role,
                // log them out and redirect to the login page.
                Auth::logout();
                return '/login';
        }
    }
}