<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Get the post-login redirect path based on user role.
     * @return string
     */
    public function redirectTo()
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'admin':
                // For now, let's use the raw URL to be safe
                return '/admin/classes';
                break;
            case 'teacher':
                return '/teacher/dashboard';
                break;
            default:
                return '/home';
                break;
        }
    }
}