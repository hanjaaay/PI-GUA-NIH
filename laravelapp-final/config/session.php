<?php

use Illuminate\Support\Str;

return [



    'driver' => env('SESSION_DRIVER', 'database'),



    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Session Encryption
    |--------------------------------------------------------------------------
    |
    | This option allows you to easily specify that all of your session data
    | should be encrypted before it's stored. All encryption is performed
    | automatically by Laravel and you may use the session like normal.
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', false),



    'files' => storage_path('framework/sessions'),



    'connection' => env('SESSION_CONNECTION'),



    'table' => env('SESSION_TABLE', 'sessions'),



    'store' => env('SESSION_STORE'),



    'lottery' => [2, 100],

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Name
    |--------------------------------------------------------------------------
    |
    | Here you may change the name of the session cookie that is created by
    | the framework. Typically, you should not need to change this value
    | since doing so does not grant a meaningful security improvement.
    |
    */

    'cookie' => env(
        'SESSION_COOKIE',
        Str::snake((string) env('APP_NAME', 'laravel')).'_session'
    ),

    /*
    |--------------------------------------------------------------------------
    Session Cookie Path
    |--------------------------------------------------------------------------
    |
    | The session cookie path determines the path for which the cookie will
    | be regarded as available. Typically, this will be the root path of
    | your application, but you're free to change this when necessary.
    |
    */

    'path' => env('SESSION_PATH', '/'),



    'domain' => env('SESSION_DOMAIN'),



    'secure' => env('SESSION_SECURE_COOKIE'),



    'http_only' => env('SESSION_HTTP_ONLY', true),



    'same_site' => env('SESSION_SAME_SITE', 'lax'),



    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];
