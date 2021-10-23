<?php

class ReferrerCatalog
{
    private PDO $database;

    public function __construct()
    {
        $this->database = DatabaseConnection::get();
    }

    public function firstOrCreate(string $referrerUrl): int
    {
        $referrerUrl = $referrerUrl !== '' ? $referrerUrl : 'Direct / Unknown';
        $domain = parse_url($referrerUrl, PHP_URL_HOST) ?: 'direct';

        $statement = $this->database->prepare(
            'SELECT id
             FROM referrers
             WHERE referrer_label = :label AND referrer_domain = :domain
             LIMIT 1'
        );
        $statement->execute([
            ':label' => $referrerUrl,
            ':domain' => $domain,
        ]);
        $existing = $statement->fetchColumn();
        if ($existing) {
            return (int) $existing;
        }

        $insert = $this->database->prepare(
            'INSERT INTO referrers (referrer_label, referrer_domain, created_at)
             VALUES (:label, :domain, :created_at)'
        );
        $insert->execute([
            ':label' => $referrerUrl,
            ':domain' => $domain,
            ':created_at' => now(),
        ]);

        return (int) $this->database->lastInsertId();
    }
}
