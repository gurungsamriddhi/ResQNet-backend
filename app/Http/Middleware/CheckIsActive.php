<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->is_active) {
            // Log out the inactive user immediately so they don't hold a session
            Auth::logout();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your account is currently under review by our team or is inactive. We will email you once approved.'
                ], 403);
            }

            return redirect()->route('login')->withErrors([
                'email' => 'Your account is currently under review by our team or is inactive. We will email you once approved.',
            ]);
        }

        return $next($request);
    }
}
