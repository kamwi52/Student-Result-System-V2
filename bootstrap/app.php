<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // THIS IS THE CRITICAL ADDITION THAT SOLVES THE "Target class does not exist" ERROR
        $middleware->alias([
            'is.admin' => \App\Http\Middleware\IsAdmin::class,
            'is.teacher' => \App\Http\Middleware\IsTeacher::class,
            // 'is.student' => \App\Http\Middleware\IsStudent::class, // Ready for the future
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();