<?php

return [

    'route_prefix' => env('ADMIN_URL_PREFIX', 'admin'),
    'url' => env('ADMIN_URL', 'admin'),
    'enable_encryption' => env('ADMIN_ENABLE_ENCRYPTION', true), // 是否启用加密'

    'login' => [
        // 最大尝试登录次数，超过这个次数将导致账户被锁定
        'max_attempts' => 5,
        // 账户锁定时间，单位为分钟。在锁定时间内，用户无法尝试登录
        'lockout_time' => 15,
        // 登录令牌的过期时间，单位为分钟。登录令牌用于验证用户身份
        'token_expiration' => 60,
    ],

    'encryption' => [
        'rsa_public_key' => storage_path('keys/admin_public.pem'),
        'rsa_private_key' => storage_path('keys/admin_private.pem'),
    ],

    'request' => [
        // 最大请求次数限制，用于防止过多的请求导致服务器负担过重
        'max_requests' => 601,
        // 每个请求的时间限制，单位为秒，用于确保请求不会执行过长时间
        'time_limit' => 300,
        // 请求的过期时间，单位为秒，用于清理过期的请求记录
        'expired_time' => 300,
        // 同一请求的最小间隔时间，单位为秒，用于防止过于频繁的相同请求
        'duplicate_time' => 10,
    ]
];
