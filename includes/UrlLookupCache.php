<?php

class UrlLookupCache
{
    private InMemoryStore $store;

    public function __construct()
    {
        $this->store = new InMemoryStore();
    }

    public function get(string $alias): ?array
    {
        return $this->store->get($this->key($alias));
    }

    public function put(string $alias, array $urlRecord): void
    {
        $payload = [
            'id' => $urlRecord['id'],
            'user_id' => $urlRecord['user_id'] ?? null,
            'original_url' => $urlRecord['original_url'],
            'alias' => $urlRecord['alias'],
            'redirect_type' => $urlRecord['redirect_type'],
            'expires_at' => $urlRecord['expires_at'],
            'is_active' => $urlRecord['is_active'],
        ];

        $this->store->set($this->key($alias), $payload, (int) config('app.cache.url_lookup_ttl', 300));
    }

    public function forget(string $alias): void
    {
        $this->store->delete($this->key($alias));
    }

    private function key(string $alias): string
    {
        return 'url_lookup:' . strtolower($alias);
    }
}
