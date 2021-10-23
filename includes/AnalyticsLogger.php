<?php

class AnalyticsLogger
{
    public function createClick(Url $urlModel, Click $clickModel, array $urlRecord): array
    {
        $ipAddress = current_user_ip();
        $userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 255);
        $referrerUrl = substr($_SERVER['HTTP_REFERER'] ?? '', 0, 2000);
        $geoIp = (new GeoIpLookup())->resolve($ipAddress);
        $deviceDetails = (new DeviceParser())->parse($userAgent);
        $visitorId = $this->visitorId();
        $visitorHash = hash('sha256', $visitorId . '|' . $urlRecord['id'] . '|' . $ipAddress . '|' . substr($userAgent, 0, 80));
        $trackingKey = hash('sha256', $visitorId . '|' . microtime(true) . '|' . random_int(1000, 999999));
        $isUnique = !$clickModel->hasVisitorClickedBefore((int) $urlRecord['id'], $visitorHash);

        $countryId = (new CountryCatalog())->firstOrCreate($geoIp['country']);
        $deviceId = (new DeviceCatalog())->firstOrCreate($deviceDetails['device_type']);
        $browserId = (new BrowserCatalog())->firstOrCreate($deviceDetails['browser_name']);
        $referrerId = (new ReferrerCatalog())->firstOrCreate($referrerUrl);

        $queryString = $_SERVER['QUERY_STRING'] ?? '';
        parse_str($queryString, $queryParameters);

        $clickId = $clickModel->create([
            'url_id' => (int) $urlRecord['id'],
            'tracking_key' => $trackingKey,
            'visitor_id' => $visitorId,
            'visitor_hash' => $visitorHash,
            'ip_address' => $ipAddress,
            'country_id' => $countryId,
            'city' => $geoIp['city'],
            'device_id' => $deviceId,
            'browser_id' => $browserId,
            'referrer_id' => $referrerId,
            'operating_system' => $deviceDetails['operating_system'],
            'user_agent' => $userAgent,
            'language' => substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 0, 20),
            'timezone' => '',
            'screen_resolution' => '',
            'is_unique' => $isUnique ? 1 : 0,
            'is_bot' => $deviceDetails['is_bot'] ? 1 : 0,
            'utm_source' => substr($queryParameters['utm_source'] ?? '', 0, 191),
            'utm_medium' => substr($queryParameters['utm_medium'] ?? '', 0, 191),
            'utm_campaign' => substr($queryParameters['utm_campaign'] ?? '', 0, 191),
            'utm_term' => substr($queryParameters['utm_term'] ?? '', 0, 191),
            'utm_content' => substr($queryParameters['utm_content'] ?? '', 0, 191),
        ]);

        $urlModel->incrementCounters((int) $urlRecord['id'], $isUnique);

        return [
            'click_id' => $clickId,
            'tracking_key' => $trackingKey,
            'target_url' => $urlRecord['original_url'],
            'redirect_type' => (int) ($urlRecord['redirect_type'] ?: config('app.redirect_status', 302)),
            'is_bot' => $deviceDetails['is_bot'],
        ];
    }

    public function updateClientMetadata(Click $clickModel, string $trackingKey, array $payload): void
    {
        $fingerprintSource = implode('|', [
            $payload['fingerprint'] ?? '',
            $payload['screen_resolution'] ?? '',
            $payload['language'] ?? '',
            $payload['timezone'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? '',
        ]);

        $clickModel->updateClientMetadata($trackingKey, [
            'screen_resolution' => substr((string) ($payload['screen_resolution'] ?? ''), 0, 20),
            'language' => substr((string) ($payload['language'] ?? ''), 0, 20),
            'timezone' => substr((string) ($payload['timezone'] ?? ''), 0, 64),
            'fingerprint_hash' => $fingerprintSource === '||||' ? null : hash('sha256', $fingerprintSource),
        ]);
    }

    private function visitorId(): string
    {
        if (!empty($_COOKIE['linkshortner_vid'])) {
            return preg_replace('/[^a-zA-Z0-9]/', '', $_COOKIE['linkshortner_vid']) ?: bin2hex(random_bytes(20));
        }

        $visitorId = bin2hex(random_bytes(20));
        setcookie('linkshortner_vid', $visitorId, [
            'expires' => time() + (86400 * 365),
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        return $visitorId;
    }
}
