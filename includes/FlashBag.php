<?php

class FlashBag
{
    public static function set(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        if (!isset($_SESSION['_flash'][$key])) {
            return $default;
        }

        $value = $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);

        return $value;
    }

    public static function peek(string $key, mixed $default = null): mixed
    {
        return $_SESSION['_flash'][$key] ?? $default;
    }

    public static function forget(string $key): void
    {
        unset($_SESSION['_flash'][$key]);
    }
}
