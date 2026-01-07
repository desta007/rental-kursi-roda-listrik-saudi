<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Conditional redirect based on route prefix
        $middleware->redirectGuestsTo(function ($request) {
            // If accessing admin routes, redirect to admin login
            if ($request->is('admin/*') || $request->is('admin')) {
                return route('admin.login');
            }
            // Otherwise redirect to customer login
            return route('web.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
