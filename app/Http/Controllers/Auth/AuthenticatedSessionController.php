<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // === THIS IS THE FINAL FIX ===
        // We add our role-based redirection logic directly here,
        // which is the method Breeze uses after a successful login.

        $role = Auth::user()->role;

        switch ($role) {
            case 'admin':
                return redirect()->intended(route('admin.users.index'));
            case 'teacher':
                return redirect()->intended(route('teacher.dashboard'));
            case 'student':
                return redirect()->intended(route('student.dashboard'));
            default:
                // As a safe fallback, redirect to the generic home route.
                return redirect()->intended('/home');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}