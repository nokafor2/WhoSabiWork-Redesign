<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/logout'
    ];

    /**
     * Handle CSRF token verification with debugging for ALB setup.
     */
    protected function tokensMatch($request)
    {
        $token = $this->getTokenFromRequest($request);
        
        // Debug CSRF token information in production for troubleshooting
        if (config('app.env') === 'production' && !$token) {
            Log::warning('CSRF Token Missing', [
                'url' => $request->url(),
                'method' => $request->method(),
                'headers' => [
                    'X-CSRF-TOKEN' => $request->header('X-CSRF-TOKEN'),
                    'X-XSRF-TOKEN' => $request->header('X-XSRF-TOKEN'),
                    'X-Forwarded-For' => $request->header('X-Forwarded-For'),
                    'X-Forwarded-Proto' => $request->header('X-Forwarded-Proto'),
                ],
                'session_token' => $request->session()->token(),
            ]);
        }

        return parent::tokensMatch($request);
    }
}
