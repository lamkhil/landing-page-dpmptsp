<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Skip on Filament admin routes if assets break under stricter CSP — they ship their own headers.
        $isAdmin = str_starts_with($request->path(), 'admin');

        $headers = [
            'X-Content-Type-Options'  => 'nosniff',
            'X-Frame-Options'         => 'SAMEORIGIN',
            'Referrer-Policy'         => 'strict-origin-when-cross-origin',
            'Permissions-Policy'      => 'camera=(), microphone=(), geolocation=(self), interest-cohort=()',
            'Cross-Origin-Opener-Policy' => 'same-origin',
        ];

        if (app()->environment('production')) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains; preload';
        }

        if (! $isAdmin) {
            // Public-page CSP — kept loose enough for inline Alpine + ApexCharts dynamic import + Bunny font CDN.
            $headers['Content-Security-Policy'] = implode('; ', [
                "default-src 'self'",
                "img-src 'self' data: https:",
                "font-src 'self' https://fonts.bunny.net data:",
                "style-src 'self' 'unsafe-inline' https://fonts.bunny.net",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
                "connect-src 'self'",
                "frame-src 'self' https://www.google.com https://www.youtube.com",
                "frame-ancestors 'self'",
                "base-uri 'self'",
                "form-action 'self'",
            ]);
        }

        foreach ($headers as $name => $value) {
            $response->headers->set($name, $value);
        }

        return $response;
    }
}
