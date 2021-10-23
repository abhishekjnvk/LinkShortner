<?php

function config(?string $key = null, mixed $default = null): mixed
{
    $config = $GLOBALS['app_config'] ?? [];
    if ($key === null) {
        return $config;
    }

    $segments = explode('.', $key);
    $value = $config;

    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }

        $value = $value[$segment];
    }

    return $value;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function app_url(string $path = ''): string
{
    $baseUrl = rtrim(config('app.url'), '/');
    $path = ltrim($path, '/');

    return $path === '' ? $baseUrl : $baseUrl . '/' . $path;
}

function asset_url(string $path): string
{
    return app_url('assets/' . ltrim($path, '/'));
}

function request_method(): string
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
}

function is_post_request(): bool
{
    return request_method() === 'POST';
}

function request_path(): string
{
    $uri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?') ?: '/';
    $basePath = parse_url(config('app.url'), PHP_URL_PATH) ?: '';

    if ($basePath !== '' && str_starts_with($uri, $basePath)) {
        $uri = substr($uri, strlen($basePath)) ?: '/';
    }

    return '/' . ltrim($uri, '/');
}

function redirect_to(string $path, int $status = 302): never
{
    header('Location: ' . (str_starts_with($path, 'http') ? $path : app_url($path)), true, $status);
    exit;
}

function old(string $key, mixed $default = ''): mixed
{
    $oldInput = FlashBag::peek('old_input') ?: [];

    return $oldInput[$key] ?? $default;
}

function remember_input(array $input): void
{
    FlashBag::set('old_input', $input);
}

function clear_old_input(): void
{
    FlashBag::forget('old_input');
}

function flash_status(?string $type = null): mixed
{
    $status = FlashBag::get('status');
    if ($type === null || !is_array($status)) {
        return $status;
    }

    return $status[$type] ?? null;
}

function current_user_ip(): string
{
    $trustedHeaders = config('security.trusted_proxy_headers', []);
    foreach ($trustedHeaders as $header) {
        if (empty($_SERVER[$header])) {
            continue;
        }

        $candidate = trim(explode(',', $_SERVER[$header])[0]);
        if (filter_var($candidate, FILTER_VALIDATE_IP)) {
            return $candidate;
        }
    }

    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function now(): string
{
    return date('Y-m-d H:i:s');
}

function format_datetime(?string $datetime, string $format = 'd M Y, h:i A'): string
{
    if (!$datetime) {
        return 'N/A';
    }

    return date($format, strtotime($datetime));
}

function human_number(int|float $value): string
{
    if ($value >= 1000000) {
        return number_format($value / 1000000, 1) . 'M';
    }

    if ($value >= 1000) {
        return number_format($value / 1000, 1) . 'K';
    }

    return number_format($value);
}

function selected_if(string $left, string $right): string
{
    return $left === $right ? 'selected' : '';
}

function checked_if(bool $condition): string
{
    return $condition ? 'checked' : '';
}

function request_input(string $key, mixed $default = null): mixed
{
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}

function query_string_without(array $keys): string
{
    $query = $_GET;
    foreach ($keys as $key) {
        unset($query[$key]);
    }

    $encoded = http_build_query($query);

    return $encoded === '' ? '' : '?' . $encoded;
}

function auth(): AuthManager
{
    return AuthManager::instance();
}

function current_user(): ?array
{
    return auth()->user();
}

function auth_user_id(): ?int
{
    return auth()->id();
}

function is_authenticated(): bool
{
    return auth()->check();
}

function sanitize_internal_path(?string $path, string $default = '/'): string
{
    $path = trim((string) $path);
    if ($path === '' || str_contains($path, '://') || str_starts_with($path, '//')) {
        return $default;
    }

    return str_starts_with($path, '/') ? $path : '/' . ltrim($path, '/');
}

function current_request_target(): string
{
    $query = $_SERVER['QUERY_STRING'] ?? '';
    return request_path() . ($query !== '' ? '?' . $query : '');
}

function icon(string $name, string $class = 'h-5 w-5'): string
{
    $icons = [
        'arrow-path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992V4.356m-2.674 11.214A9 9 0 1 1 21 12M7.977 14.652H3.75v4.227"/>',
        'arrow-right' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0-6.75-6.75M19.5 12l-6.75 6.75"/>',
        'arrow-right-on-rectangle' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-7.5A2.25 2.25 0 0 0 3.75 5.25v13.5A2.25 2.25 0 0 0 6 21h7.5a2.25 2.25 0 0 0 2.25-2.25V15m-3-3h8.25m0 0-3-3m3 3-3 3"/>',
        'chart-bar' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125V21h18v-7.875M7.5 16.5V9.75m4.5 6.75V3.75m4.5 12.75v-4.5"/>',
        'check-circle' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m6 2.25a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
        'clock' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2.25m5.25-2.25a9.75 9.75 0 1 1-19.5 0 9.75 9.75 0 0 1 19.5 0Z"/>',
        'computer-desktop' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5v11.25H3.75V4.5Zm0 11.25L6 19.5h12l2.25-3.75"/>',
        'copy' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3A2.25 2.25 0 0 1 13.5 22.5h-9A2.25 2.25 0 0 1 2.25 20.25v-9A2.25 2.25 0 0 1 4.5 9h3m8.25 8.25h3A2.25 2.25 0 0 0 21 15V6A2.25 2.25 0 0 0 18.75 3.75h-9A2.25 2.25 0 0 0 7.5 6v3m8.25 8.25H9.75A2.25 2.25 0 0 1 7.5 15V9m8.25 8.25V9A2.25 2.25 0 0 0 13.5 6.75H7.5"/>',
        'device-phone-mobile' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5h3A2.25 2.25 0 0 1 15.75 3.75v16.5A2.25 2.25 0 0 1 13.5 22.5h-3a2.25 2.25 0 0 1-2.25-2.25V3.75A2.25 2.25 0 0 1 10.5 1.5Z"/>',
        'envelope' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21.75 7.5v9A2.25 2.25 0 0 1 19.5 18.75h-15A2.25 2.25 0 0 1 2.25 16.5v-9m19.5 0A2.25 2.25 0 0 0 19.5 5.25h-15A2.25 2.25 0 0 0 2.25 7.5m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0l-7.5-4.615A2.25 2.25 0 0 1 2.25 7.743V7.5"/>',
        'exclamation-triangle' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008Zm8.257 3.099c.765 1.36-.213 3.026-1.746 3.026H5.49c-1.533 0-2.51-1.666-1.746-3.026L10.256 3.62c.766-1.36 2.72-1.36 3.486 0l6.515 11.979Z"/>',
        'globe-alt' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 2.25c5.385 0 9.75 4.365 9.75 9.75S17.385 21.75 12 21.75 2.25 17.385 2.25 12 6.615 2.25 12 2.25Zm0 0c2.481 0 4.5 4.365 4.5 9.75s-2.019 9.75-4.5 9.75-4.5-4.365-4.5-9.75 2.019-9.75 4.5-9.75Zm-8.858 6.75h17.716M3.142 15h17.716"/>',
        'home' => '<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955a1.125 1.125 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125A1.125 1.125 0 0 0 5.625 21h4.5v-6.75h3.75V21h4.5a1.125 1.125 0 0 0 1.125-1.125V9.75"/>',
        'link' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 6.364 6.364l-1.757 1.757a4.5 4.5 0 0 1-6.364 0m1.757-9.192-1.757 1.757a4.5 4.5 0 0 0 0 6.364m-1.757 1.757a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757a4.5 4.5 0 0 1 6.364 0"/>',
        'map-pin' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>',
        'moon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0 1 12 21.75 9.75 9.75 0 1 1 8.998 2.248 7.5 7.5 0 0 0 21.752 15.002Z"/>',
        'pencil-square' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.121 2.121 0 1 1 3 3L7.5 18.848l-4.5 1.5 1.5-4.5L16.862 3.487ZM19.5 21H4.5A1.5 1.5 0 0 1 3 19.5v-15A1.5 1.5 0 0 1 4.5 3h8.25"/>',
        'shield-check' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m6.283-3.136A2.25 2.25 0 0 1 22.5 8.638V12a9.75 9.75 0 0 1-4.286 8.084l-3.464 2.31a2.25 2.25 0 0 1-2.5 0l-3.464-2.31A9.75 9.75 0 0 1 4.5 12V8.638a2.25 2.25 0 0 1 1.217-2.024l5.25-2.625a2.25 2.25 0 0 1 2.066 0l5.25 2.625Z"/>',
        'shield-exclamation' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008Zm7.5-7.862V12a9.75 9.75 0 0 1-4.286 8.084l-2.214 1.476a2.25 2.25 0 0 1-2.5 0l-2.214-1.476A9.75 9.75 0 0 1 4.5 12V8.638a2.25 2.25 0 0 1 1.217-2.024l5.25-2.625a2.25 2.25 0 0 1 2.066 0l5.25 2.625A2.25 2.25 0 0 1 19.5 8.638Z"/>',
        'sparkles' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18l-.813-2.096a4.5 4.5 0 0 0-2.091-2.091L4 13l2.096-.813a4.5 4.5 0 0 0 2.091-2.091L9 8l.813 2.096a4.5 4.5 0 0 0 2.091 2.091L14 13l-2.096.813a4.5 4.5 0 0 0-2.091 2.091ZM18.259 8.715 18 9.75l-.259-1.035a2.25 2.25 0 0 0-1.456-1.456L15.25 7l1.035-.259a2.25 2.25 0 0 0 1.456-1.456L18 4.25l.259 1.035a2.25 2.25 0 0 0 1.456 1.456L20.75 7l-1.035.259a2.25 2.25 0 0 0-1.456 1.456ZM16.894 20.567 16.5 21.75l-.394-1.183a1.875 1.875 0 0 0-1.173-1.173L13.75 19l1.183-.394a1.875 1.875 0 0 0 1.173-1.173l.394-1.183.394 1.183a1.875 1.875 0 0 0 1.173 1.173L19.25 19l-1.183.394a1.875 1.875 0 0 0-1.173 1.173Z"/>',
        'sun' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1.5m0 15V21m9-9h-1.5M4.5 12H3m15.364 6.364-1.06-1.06M6.696 6.696 5.636 5.636m12.728 0-1.06 1.06M6.696 17.304l-1.06 1.06M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>',
        'trash' => '<path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084A2.25 2.25 0 0 1 5.84 19.673L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.875A2.25 2.25 0 0 0 13.5 2.625h-3a2.25 2.25 0 0 0-2.25 2.25V5.25m7.5 0h-7.5"/>',
        'user-circle' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A9.75 9.75 0 1 0 6.018 18.725m11.964 0a9.713 9.713 0 0 1-5.982 2.025 9.713 9.713 0 0 1-5.982-2.025m11.964 0a8.966 8.966 0 0 0-11.964 0M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-1.372 3 3 0 0 0-4.682-2.72m.94 3.094a11.959 11.959 0 0 1-8.998 0m8.998 0A5.25 5.25 0 0 0 12 12.75a5.25 5.25 0 0 0-5.999 4.972m8.998 0A9.094 9.094 0 0 1 5.258 17.35a3 3 0 0 1 4.681-2.72m0 0A5.239 5.239 0 0 1 9 9.75a5.25 5.25 0 0 1 10.5 0 5.239 5.239 0 0 1-.939 3.002M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>',
    ];

    $path = $icons[$name] ?? $icons['sparkles'];

    return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="' . e($class) . '">' . $path . '</svg>';
}

function device_icon_name(?string $device): string
{
    return match (strtolower((string) $device)) {
        'mobile' => 'device-phone-mobile',
        'tablet' => 'computer-desktop',
        default => 'computer-desktop',
    };
}

function browser_icon_name(?string $browser): string
{
    $browser = strtolower((string) $browser);

    return str_contains($browser, 'safari') || str_contains($browser, 'chrome') || str_contains($browser, 'edge') || str_contains($browser, 'firefox')
        ? 'globe-alt'
        : 'sparkles';
}

function truncate(string $text, int $length = 100, string $append = '...'): string
{
    if (strlen($text) <= $length) {
        return $text;
    }

    return substr($text, 0, $length) . $append;
}
