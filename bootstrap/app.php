<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

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
            'force.password.change' => \App\Http\Middleware\ForcePasswordChange::class,
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
    })
    ->withSchedule(function (Schedule $schedule) {
        // Run commission generation on the 1st of every month at midnight
        $schedule->command('agent:generate-commissions')->monthlyOn(1, '00:00');
        // Process queued emails every minute
        $schedule->command('email:send-pending')->everyMinute();
    })
    ->create();
