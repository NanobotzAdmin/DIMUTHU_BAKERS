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
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckInterfacePermission::class,
            'ensure.branch' => \App\Http\Middleware\EnsureBranchSelected::class,
        ]);
        $middleware->redirectUsersTo(function () {
            // If referer exists and is not the login page itself, go back there
            $previous = url()->previous();
            $current = url()->current();
            $loginUrl = route('login');

            if ($previous && $previous !== $current && $previous !== $loginUrl) {
                return $previous;
            }

            // Default fallback
            return route('adminDashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
