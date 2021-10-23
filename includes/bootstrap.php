<?php

$configFile = __DIR__ . '/../config/config.php';
$GLOBALS['app_config'] = require $configFile;

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../config/database.php';

date_default_timezone_set(config('app.timezone'));

$sessionName = config('security.session_name');
if (session_status() === PHP_SESSION_NONE) {
    session_name($sessionName);
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ]);
    session_start();
}

spl_autoload_register(static function (string $className): void {
    $directories = [
        __DIR__,
        __DIR__ . '/../config',
        __DIR__ . '/../controllers',
        __DIR__ . '/../models',
    ];

    foreach ($directories as $directory) {
        $path = $directory . '/' . $className . '.php';
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

AuthManager::boot();
