<?php

class DeviceParser
{
    public function parse(string $userAgent): array
    {
        $userAgent = trim($userAgent);
        $isBot = (bool) preg_match('/bot|crawl|spider|preview|slurp|wget|curl|monitor|facebookexternalhit|whatsapp|telegram/i', $userAgent);

        return [
            'device_type' => $this->deviceType($userAgent),
            'browser_name' => $this->browserName($userAgent),
            'operating_system' => $this->operatingSystem($userAgent),
            'is_bot' => $isBot,
        ];
    }

    private function deviceType(string $userAgent): string
    {
        if (preg_match('/tablet|ipad/i', $userAgent)) {
            return 'Tablet';
        }

        if (preg_match('/mobile|iphone|android/i', $userAgent)) {
            return 'Mobile';
        }

        return 'Desktop';
    }

    private function browserName(string $userAgent): string
    {
        $map = [
            'Edg/' => 'Edge',
            'OPR/' => 'Opera',
            'Chrome/' => 'Chrome',
            'Firefox/' => 'Firefox',
            'Safari/' => 'Safari',
            'MSIE ' => 'Internet Explorer',
            'Trident/' => 'Internet Explorer',
        ];

        foreach ($map as $needle => $label) {
            if (str_contains($userAgent, $needle)) {
                return $label;
            }
        }

        return 'Unknown';
    }

    private function operatingSystem(string $userAgent): string
    {
        $map = [
            'Windows NT' => 'Windows',
            'Mac OS X' => 'macOS',
            'Android' => 'Android',
            'iPhone' => 'iOS',
            'iPad' => 'iPadOS',
            'Linux' => 'Linux',
        ];

        foreach ($map as $needle => $label) {
            if (str_contains($userAgent, $needle)) {
                return $label;
            }
        }

        return 'Unknown';
    }
}
