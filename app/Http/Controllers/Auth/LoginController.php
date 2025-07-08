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
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
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
     * Get the post-login redirect path.
     * This method contains our custom role-based redirect logic.
     */
    protected function redirectTo()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return route('admin.users.index'); // Admins go to User Management
        }

        if ($user->role === 'teacher') {
            return route('teacher.dashboard'); // Teachers go to their dashboard
        }

        // Default for students or any other role
        return '/home';
    }
}