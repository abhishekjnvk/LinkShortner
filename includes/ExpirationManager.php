<?php

class ExpirationManager
{
    public function resolve(string $preset, string $customDate): ?string
    {
        return match ($preset) {
            'never' => null,
            '1_day' => date('Y-m-d H:i:s', strtotime('+1 day')),
            '7_days' => date('Y-m-d H:i:s', strtotime('+7 days')),
            '30_days' => date('Y-m-d H:i:s', strtotime('+30 days')),
            '90_days' => date('Y-m-d H:i:s', strtotime('+90 days')),
            'custom' => $this->validateCustomDate($customDate),
            default => throw new InvalidArgumentException('Please select a valid expiration option.'),
        };
    }

    public function presetFor(?string $expiresAt): string
    {
        return $expiresAt ? 'custom' : 'never';
    }

    private function validateCustomDate(string $customDate): string
    {
        $customDate = trim($customDate);
        if ($customDate === '') {
            throw new InvalidArgumentException('Please choose a custom expiration date and time.');
        }

        $expiresTimestamp = strtotime($customDate);
        if ($expiresTimestamp === false) {
            throw new InvalidArgumentException('Please provide a valid expiration date and time.');
        }

        if ($expiresTimestamp <= time()) {
            throw new InvalidArgumentException('Expiration must be a future date and time.');
        }

        return date('Y-m-d H:i:s', $expiresTimestamp);
    }
}
