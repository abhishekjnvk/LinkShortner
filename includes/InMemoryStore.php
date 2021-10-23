<?php

class InMemoryStore
{
    private static array $fallback = [];

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->supportsApcu()) {
            $success = false;
            $value = apcu_fetch($key, $success);
            return $success ? $value : $default;
        }

        $item = self::$fallback[$key] ?? null;
        if (!$item || ($item['expires_at'] !== null && $item['expires_at'] < time())) {
            unset(self::$fallback[$key]);
            return $default;
        }

        return $item['value'];
    }

    public function set(string $key, mixed $value, int $ttl): void
    {
        if ($this->supportsApcu()) {
            apcu_store($key, $value, $ttl);
            return;
        }

        self::$fallback[$key] = [
            'value' => $value,
            'expires_at' => $ttl > 0 ? time() + $ttl : null,
        ];
    }

    public function delete(string $key): void
    {
        if ($this->supportsApcu()) {
            apcu_delete($key);
            return;
        }

        unset(self::$fallback[$key]);
    }

    public function hitWindow(string $key, int $limit, int $windowSeconds): array
    {
        $limit = max(1, $limit);
        $windowSeconds = max(1, $windowSeconds);
        $windowKey = $key . ':' . floor(time() / $windowSeconds);
        $retryAfter = $windowSeconds - (time() % $windowSeconds);

        if ($this->supportsApcu()) {
            if (apcu_add($windowKey, 1, $windowSeconds + 1)) {
                return [
                    'allowed' => true,
                    'count' => 1,
                    'limit' => $limit,
                    'retry_after' => $retryAfter,
                ];
            }

            $count = (int) apcu_inc($windowKey, 1);

            return [
                'allowed' => $count <= $limit,
                'count' => $count,
                'limit' => $limit,
                'retry_after' => $retryAfter,
            ];
        }

        $existing = (int) $this->get($windowKey, 0);
        $count = $existing + 1;
        $this->set($windowKey, $count, $windowSeconds + 1);

        return [
            'allowed' => $count <= $limit,
            'count' => $count,
            'limit' => $limit,
            'retry_after' => $retryAfter,
        ];
    }

    private function supportsApcu(): bool
    {
        if (!function_exists('apcu_fetch')) {
            return false;
        }

        if (function_exists('apcu_enabled')) {
            return apcu_enabled();
        }

        return filter_var(ini_get('apc.enabled'), FILTER_VALIDATE_BOOL);
    }
}
