<?php

class CsrfTokenManager
{
    public static function token(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(self::token()) . '">';
    }

    public static function verify(?string $token): bool
    {
        $sessionToken = $_SESSION['_csrf_token'] ?? '';

        return is_string($token) && $sessionToken !== '' && hash_equals($sessionToken, $token);
    }
}
