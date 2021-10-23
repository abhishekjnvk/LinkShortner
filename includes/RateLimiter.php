<?php

class RateLimiter
{
    private InMemoryStore $store;

    public function __construct()
    {
        $this->store = new InMemoryStore();
    }

    public function ensureWithinLimit(string $key, string $scope = 'create', ?bool $authenticated = null): void
    {
        $result = $this->hit($key, $scope, $authenticated);

        if ($result['allowed']) {
            return;
        }

        throw new RateLimitExceededException(
            'Rate limit exceeded. Please wait before trying again.',
            (int) $result['retry_after'],
            (int) $result['limit']
        );
    }

    public function hit(string $key, string $scope = 'create', ?bool $authenticated = null): array
    {
        $authenticated = $authenticated ?? is_authenticated();
        $settings = config('app.rate_limit.' . $scope, []);
        $limit = max(1, (int) ($authenticated ? ($settings['authenticated'] ?? $settings['guest'] ?? 100) : ($settings['guest'] ?? 100)));
        $window = max(1, (int) ($settings['window_seconds'] ?? 60));

        return $this->store->hitWindow('rate:' . $scope . ':' . hash('sha256', $key), $limit, $window);
    }
}
