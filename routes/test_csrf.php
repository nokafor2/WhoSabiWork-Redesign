<?php

use Illuminate\Support\Facades\Route;

/**
 * TEMPORARY CSRF TEST ROUTE
 * Add this to your routes/web.php temporarily to test CSRF configuration
 * Remove after verifying CSRF works properly
 */

Route::get('/test-csrf', function () {
    return response()->json([
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId(),
        'session_driver' => config('session.driver'),
        'session_domain' => config('session.domain'),
        'session_secure' => config('session.secure'),
        'session_same_site' => config('session.same_site'),
        'app_url' => config('app.url'),
        'app_env' => config('app.env'),
        'session_path' => config('session.files'),
        'session_writable' => is_writable(config('session.files')),
    ]);
})->name('test.csrf');

Route::post('/test-csrf-post', function () {
    return response()->json([
        'success' => true,
        'message' => 'CSRF token is valid!',
        'timestamp' => now()->toIso8601String(),
    ]);
})->name('test.csrf.post');

