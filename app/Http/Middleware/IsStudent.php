<?php

/**
 * THIS IS A DIAGNOSTIC FILE.
 * If you see the message 'DIAGNOSTIC TEST PASSED' in your browser,
 * it means the application is successfully loading this file.
 * If you still see 'Target class does not exist', the deployment is broken.
 */
die('DIAGNOSTIC TEST PASSED: The IsStudent.php file was successfully loaded.');

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        // This code will not be reached during the test.
        return $next($request);
    }
}