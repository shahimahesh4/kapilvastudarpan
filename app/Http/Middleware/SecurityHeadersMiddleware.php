<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=()');
        $csp = implode('; ', [
            "default-src 'self'",
            // Scripts: self (Vite/Livewire bundles) + jsdelivr (SweetAlert2 lazy-loaded)
            // 'unsafe-inline' is required by Alpine.js x-data/x-on handlers and inline <script> blocks
            // 'unsafe-eval' is required by Alpine.js & Livewire 3 for dynamic expression evaluation
            "script-src 'self' https://cdn.jsdelivr.net 'unsafe-inline' 'unsafe-eval'",
            // Styles: self (Vite CSS) + inline styles used by Alpine/Livewire
            "style-src 'self' 'unsafe-inline'",
            // Images: self + data URIs (base64 avatars/placeholders) + storage CDN if applicable
            "img-src 'self' data: https:",
            // Fonts: self only (no external font CDNs in use)
            "font-src 'self' data:",
            // Connections: self (Livewire Ajax/WS, search, API calls)
            "connect-src 'self'",
            // Media (audio/video): self only
            "media-src 'self'",
            // Frames: self (no iframes embedded from external sources)
            "frame-src 'self'",
            // Workers: self (no service workers from third parties)
            "worker-src 'self' blob:",
            // Manifest
            "manifest-src 'self'",
            // No plugins
            "object-src 'none'",
            // Restrict <base> tag
            "base-uri 'self'",
            // Restrict form submissions
            "form-action 'self'",
            // Prevent clickjacking via CSP (complements X-Frame-Options)
            "frame-ancestors 'self'",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
