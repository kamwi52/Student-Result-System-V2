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
    // ... create() method is here ...
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

        // === THIS IS THE INTERROGATION ===
        // This command will halt the application and display the authenticated user's data.
        // We will see, without question, what role the system is assigning to you at login.
        dd(Auth::user());

        // The code below this line will not be executed during the test.
        $role = Auth::user()->role;

        switch ($role) {
            case 'admin':
                return redirect(route('admin.users.index'));
            case 'teacher':
                return redirect(route('teacher.dashboard'));
            case 'student':
                return redirect(route('student.dashboard'));
            default:
                return redirect('/home');
        }
    }

    // ... destroy() method is here ...
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}