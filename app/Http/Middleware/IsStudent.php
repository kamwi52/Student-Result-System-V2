<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsStudent
{
    /**
     * Handle an incoming request.
     *
     * This function checks if the authenticated user has the 'student' role.
     * If the user is not logged in or does not have the correct role,
     * it will abort the request with a 403 Unauthorized error.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if a user is authenticated.
        if (!Auth::check()) {
            // If not logged in, redirect to the login page.
            return redirect('login');
        }

        // 2. Check if the authenticated user's role is 'student'.
        // This assumes you have a 'role' column on your 'users' table.
        // Adjust 'student' to match the value you use in your database (e.g., '3', 'student', etc.).
        if (Auth::user()->role !== 'student') {
            // If the user is not a student, deny access.
            abort(403, 'Unauthorized Action');
        }

        // 3. If the user is an authenticated student, allow the request to proceed.
        return $next($request);
    }
}