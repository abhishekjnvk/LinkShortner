<?php

return [
    'app' => [
        'name' => 'Linkshortner',
        'url' => getenv('APP_URL') ?: 'http://localhost:8888/Linkshortner',
        'timezone' => getenv('APP_TIMEZONE') ?: 'Asia/Kolkata',
        'redirect_status' => (int) (getenv('APP_REDIRECT_STATUS') ?: 302),
        'allow_private_destinations' => filter_var(getenv('ALLOW_PRIVATE_DESTINATIONS') ?: false, FILTER_VALIDATE_BOOL),
        'cache' => [
            'url_lookup_ttl' => (int) (getenv('URL_LOOKUP_CACHE_TTL') ?: 300),
        ],
        'rate_limit' => [
            'create' => [
                'guest' => (int) (getenv('CREATE_RATE_LIMIT_GUEST') ?: 10),
                'authenticated' => (int) (getenv('CREATE_RATE_LIMIT_AUTH') ?: 25),
                'window_seconds' => (int) (getenv('CREATE_RATE_LIMIT_WINDOW') ?: 900),
            ],
            'visit' => [
                'guest' => (int) (getenv('VISIT_RATE_LIMIT_GUEST') ?: 100),
                'authenticated' => (int) (getenv('VISIT_RATE_LIMIT_AUTH') ?: 180),
                'window_seconds' => (int) (getenv('VISIT_RATE_LIMIT_WINDOW') ?: 60),
            ],
        ],
    ],
    'database' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int) (getenv('DB_PORT') ?: 8889),
        'name' => getenv('DB_NAME') ?: 'linkshortner',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: 'root',
        'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
    ],
    'security' => [
        'session_name' => getenv('SESSION_NAME') ?: 'linkshortner_session',
        'trusted_proxy_headers' => ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP'],
        'remember_cookie' => getenv('REMEMBER_COOKIE') ?: 'linkshortner_remember',
        'remember_days' => (int) (getenv('REMEMBER_DAYS') ?: 30),
    ],
];
