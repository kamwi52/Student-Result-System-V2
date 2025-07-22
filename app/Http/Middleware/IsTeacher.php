<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if a user is logged in.
        // 2. Check if the logged-in user's role is 'teacher'.
        if ($request->user() && $request->user()->role === 'teacher') {
            // If both are true, allow the request to proceed.
            return $next($request);
        }

        // Otherwise, stop the request and show a 403 Forbidden error.
        abort(403, 'UNAUTHORIZED ACTION.');
    }
}