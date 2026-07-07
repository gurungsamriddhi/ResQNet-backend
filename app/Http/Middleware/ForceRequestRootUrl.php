<?php

namespace App\Http\Middleware;

use App\Support\AppUrl;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceRequestRootUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        AppUrl::apply($request);

        return $next($request);
    }
}
