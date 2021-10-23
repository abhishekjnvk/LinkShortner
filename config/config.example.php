<?php

return [
    'app' => [
        'name' => 'Linkshortner',
        'url' => 'http://localhost:8888/Linkshortner',
        'timezone' => 'Asia/Kolkata',
        'redirect_status' => 302,
        'allow_private_destinations' => false,
        'cache' => [
            'url_lookup_ttl' => 300,
        ],
        'rate_limit' => [
            'create' => [
                'guest' => 10,
                'authenticated' => 25,
                'window_seconds' => 900,
            ],
            'visit' => [
                'guest' => 100,
                'authenticated' => 180,
                'window_seconds' => 60,
            ],
        ],
    ],
    'database' => [
        'host' => '127.0.0.1',
        'port' => 8889,
        'name' => 'linkshortner',
        'user' => 'root',
        'pass' => 'root',
        'charset' => 'utf8mb4',
    ],
    'security' => [
        'session_name' => 'linkshortner_session',
        'trusted_proxy_headers' => ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP'],
        'remember_cookie' => 'linkshortner_remember',
        'remember_days' => 30,
    ],
];
