<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and if their role is 'student'
        if (auth()->check() && auth()->user()->role === 'student') {
            // If they are a student, allow the request to continue
            return $next($request);
        }

        // If not, block access with a "Forbidden" error
        abort(403, 'Unauthorized');
    }
}