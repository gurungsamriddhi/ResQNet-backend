<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AppUrl
{
    /**
     * When enabled, HTTP requests use the incoming Host / forwarded headers
     * instead of APP_URL from .env (ideal for ngrok, LAN IP testing, etc.).
     */
    public static function isDynamic(): bool
    {
        return filter_var(config('app.dynamic_url'), FILTER_VALIDATE_BOOL);
    }

    /**
     * Resolve the application root URL from the current HTTP request.
     */
    public static function rootFromRequest(?Request $request = null): ?string
    {
        $request ??= request();

        if (!$request) {
            return null;
        }

        $forwardedHost = $request->headers->get('X-Forwarded-Host');
        if (is_string($forwardedHost) && $forwardedHost !== '') {
            $host = trim(explode(',', $forwardedHost)[0]);
            $scheme = $request->headers->get('X-Forwarded-Proto', 'https');

            return rtrim($scheme . '://' . $host, '/');
        }

        $host = $request->getHost();
        if ($host === '') {
            return null;
        }

        return rtrim($request->getSchemeAndHttpHost(), '/');
    }

    /**
     * Apply request-derived URLs to Laravel's URL generator and disk config.
     */
    public static function apply(?Request $request = null): void
    {
        if (!static::isDynamic()) {
            return;
        }

        $request ??= request();
        $root = static::rootFromRequest($request);

        if (!$root) {
            return;
        }

        config(['app.url' => $root]);
        config(['filesystems.disks.public.url' => $root . '/storage']);

        URL::forceRootUrl($root);

        if (str_starts_with($root, 'https://')) {
            URL::forceScheme('https');
        }
    }

    /**
     * Current root URL — request-aware when dynamic mode is on.
     */
    public static function root(): string
    {
        if (static::isDynamic()) {
            $fromRequest = static::rootFromRequest();
            if ($fromRequest) {
                return $fromRequest;
            }
        }

        return rtrim((string) config('app.url'), '/');
    }

    /**
     * Ensure a URL is absolute — required for mobile clients (React Native Image).
     */
    public static function absolute(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        static::apply(request());

        return url($path);
    }
}
