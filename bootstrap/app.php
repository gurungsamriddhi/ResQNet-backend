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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'is_active' => \App\Http\Middleware\CheckIsActive::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->redirectTo(
            guests: function (\Illuminate\Http\Request $request) {
                if ($request->is('admin') || $request->is('admin/*')) {
                    return route('admin.login');
                }
                return route('organization.login');
            },
            users: function (\Illuminate\Http\Request $request) {
                if ($request->is('admin') || $request->is('admin/*')) {
                    return route('admin.dashboard');
                }
                return route('organization.dashboard');
            }
        );

        $middleware->redirectTo(
            guests: fn() => route('organization.login'), // Where unauthenticated users go

            users: function (\Illuminate\Http\Request $request) {
                $user = \Illuminate\Support\Facades\Auth::user();

                if ($user) {
                    // If they are a Superadmin, seamlessly route them to the admin panel
                    if ($user->hasRole('super_admin')) {
                        return route('admin.dashboard');
                    }

                    // If they are an Organization owner/staff, send them to their dashboard
                    if ($user->hasRole('organization')) {
                        return route('organization.dashboard');
                    }
                }

                // return route('dashboard'); // Fallback for standard users
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
