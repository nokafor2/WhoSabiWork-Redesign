<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => env('APP_ENV') === 'production' 
        ? explode(',', env('CORS_ALLOWED_ORIGINS', 'http://ec2-13-40-186-156.eu-west-2.compute.amazonaws.com,http://13.40.186.156'))
        : ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => env('APP_ENV') === 'production'
        ? explode(',', env('CORS_ALLOWED_HEADERS', 'Origin,Content-Type,Accept,Authorization,X-Requested-With,X-CSRF-TOKEN'))
        : ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => env('CORS_SUPPORTS_CREDENTIALS', env('APP_ENV') === 'production'),

];
