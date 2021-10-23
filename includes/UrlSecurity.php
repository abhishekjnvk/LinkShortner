<?php

class UrlSecurity
{
    private array $reservedAliases = [
        'analytics',
        'track',
        'shorten',
        'admin',
        'assets',
        'public',
        'setup',
        'api',
    ];

    public function normalizeDestination(string $url): string
    {
        $url = trim($url);
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . $url;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Please enter a valid HTTP or HTTPS URL.');
        }

        $parts = parse_url($url);
        $scheme = strtolower($parts['scheme'] ?? '');
        if (!in_array($scheme, ['http', 'https'], true)) {
            throw new InvalidArgumentException('Only HTTP and HTTPS destinations are allowed.');
        }

        // if (!empty($parts['user']) || !empty($parts['pass'])) {
        //     throw new InvalidArgumentException('URLs with embedded credentials are not allowed.');
        // }

        $host = $parts['host'] ?? '';
        if ($host === '') {
            throw new InvalidArgumentException('The destination URL must include a host.');
        }

        if (!config('app.allow_private_destinations', false) && $this->isPrivateHost($host)) {
            throw new InvalidArgumentException('Private, localhost, and internal network URLs are blocked.');
        }

        return $url;
    }

    public function normalizeAlias(?string $alias): ?string
    {
        if ($alias === null || trim($alias) === '') {
            return null;
        }

        $alias = strtolower(trim($alias));
        $alias = preg_replace('/[^a-z0-9\-_]/', '', $alias) ?: '';

        if (strlen($alias) < 4 || strlen($alias) > 64) {
            throw new InvalidArgumentException('Custom alias must be between 4 and 64 characters.');
        }

        if (in_array($alias, $this->reservedAliases, true)) {
            throw new InvalidArgumentException('That alias is reserved. Please choose another one.');
        }

        return $alias;
    }

    private function isPrivateHost(string $host): bool
    {
        if (in_array($host, ['localhost', '127.0.0.1', '::1'], true)) {
            return true;
        }

        if (filter_var($host, FILTER_VALIDATE_IP) === false) {
            $resolved = gethostbyname($host);
            if ($resolved === $host) {
                return false;
            }

            $host = $resolved;
        }

        return !filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }
}