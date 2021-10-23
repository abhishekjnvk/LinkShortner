<?php

class GeoIpLookup
{
    private string $cachePath = '/Applications/MAMP/htdocs/Linkshortner/storage/cache/geoip';

    public function resolve(string $ipAddress): array
    {
        if ($ipAddress === '127.0.0.1' || $ipAddress === '::1' || $ipAddress === '0.0.0.0') {
            return ['country' => 'Localhost', 'city' => 'Localhost'];
        }

        // 1. Try Cache
        $cached = $this->getCached($ipAddress);
        if ($cached) {
            return $cached;
        }

        // 2. Try Current Logic (Server variables/Local GeoIP)
        $country = $_SERVER['GEOIP_COUNTRY_NAME'] ?? null;
        $city = $_SERVER['GEOIP_CITY'] ?? null;

        if (function_exists('geoip_country_name_by_name') && !$country) {
            $country = @geoip_country_name_by_name($ipAddress) ?: null;
        }

        if (function_exists('geoip_record_by_name') && !$city) {
            $record = @geoip_record_by_name($ipAddress) ?: [];
            $city = $record['city'] ?? null;
            $country = $country ?: ($record['country_name'] ?? null);
        }

        $result = [
            'country' => $country ?: 'Unknown',
            'city' => $city ?: 'Unknown',
        ];

        // 3. Try Fallback APIs if "Unknown"
        if ($result['country'] === 'Unknown' || $result['city'] === 'Unknown') {
            $fallback = $this->resolveFallback($ipAddress);
            if ($fallback) {
                $result = $fallback;
            }
        }

        // 4. Cache and Return
        if ($result['country'] !== 'Unknown') {
            $this->saveToCache($ipAddress, $result);
        }

        return $result;
    }

    private function resolveFallback(string $ipAddress): ?array
    {
        // Try IpWhoIs.app
        $data = $this->fetchJson("https://ipwhois.app/json/{$ipAddress}");
        if ($data && !empty($data['success'])) {
            return [
                'country' => $data['country'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
            ];
        }

        // Try Ip-Api.com
        $data = $this->fetchJson("http://ip-api.com/json/{$ipAddress}");
        if ($data && ($data['status'] ?? '') === 'success') {
            return [
                'country' => $data['country'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
            ];
        }

        // Try IpWho.is
        $data = $this->fetchJson("http://ipwho.is/{$ipAddress}");
        if ($data && !empty($data['success'])) {
            return [
                'country' => $data['country'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
            ];
        }

        return null;
    }

    private function fetchJson(string $url): ?array
    {
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 2,
                'ignore_errors' => true,
                'user_agent' => 'Linkshortner/1.0 (GeoIpLookup)'
            ]
        ]);

        $response = @file_get_contents($url, false, $ctx);
        if (!$response) {
            return null;
        }

        return json_decode($response, true);
    }

    private function getCached(string $ipAddress): ?array
    {
        $file = $this->getCacheFile($ipAddress);
        if (!file_exists($file)) {
            return null;
        }

        // Cache for 7 days
        if (time() - filemtime($file) > 86400 * 7) {
            @unlink($file);
            return null;
        }

        $data = json_decode((string) @file_get_contents($file), true);
        return is_array($data) ? $data : null;
    }

    private function saveToCache(string $ipAddress, array $data): void
    {
        $file = $this->getCacheFile($ipAddress);
        $dir = dirname($file);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        @file_put_contents($file, json_encode($data));
    }

    private function getCacheFile(string $ipAddress): string
    {
        return $this->cachePath . '/' . md5($ipAddress) . '.json';
    }
}
