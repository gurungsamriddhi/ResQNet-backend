<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust ngrok / reverse-proxy forwarded headers (Host, Proto, etc.)
        $middleware->trustProxies(at: '*');

        // Use incoming request host for URLs in local/ngrok — no .env swapping needed
        $middleware->prepend(\App\Http\Middleware\ForceRequestRootUrl::class);

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

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (\Illuminate\Broadcasting\BroadcastException $e) {
            \Log::warning('Broadcast failed (non-fatal): ' . $e->getMessage());
            return response()->json([], 200);
        });
    })->create();
