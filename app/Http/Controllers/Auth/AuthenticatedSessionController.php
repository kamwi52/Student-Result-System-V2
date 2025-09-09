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

        $role = Auth::user()->role;

        // === THIS IS THE DEFINITIVE FIX ===
        // We have removed `.intended()` to make the redirects absolute and non-negotiable.
        // The user will ALWAYS be sent to the correct dashboard for their role.

        switch ($role) {
            case 'admin':
                return redirect(route('admin.users.index'));
            case 'teacher':
                return redirect(route('teacher.dashboard'));
            case 'student':
                return redirect(route('student.dashboard'));
            default:
                // As a safe fallback, redirect to the generic home route.
                return redirect('/home');
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