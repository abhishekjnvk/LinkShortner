<?php

class Url
{
    private PDO $database;

    public function __construct()
    {
        $this->database = DatabaseConnection::get();
    }

    public function create(array $data): int
    {
        $statement = $this->database->prepare(
            'INSERT INTO urls (
                user_id,
                original_url,
                original_url_hash,
                alias,
                redirect_type,
                expires_at,
                created_ip,
                is_active,
                total_clicks,
                unique_clicks,
                created_at,
                updated_at
            ) VALUES (
                :user_id,
                :original_url,
                :original_url_hash,
                :alias,
                :redirect_type,
                :expires_at,
                :created_ip,
                1,
                0,
                0,
                :created_at,
                :updated_at
            )'
        );

        $statement->execute([
            ':user_id' => $data['user_id'],
            ':original_url' => $data['original_url'],
            ':original_url_hash' => hash('sha256', $data['original_url']),
            ':alias' => $data['alias'],
            ':redirect_type' => $data['redirect_type'],
            ':expires_at' => $data['expires_at'],
            ':created_ip' => $data['created_ip'],
            ':created_at' => now(),
            ':updated_at' => now(),
        ]);

        return (int) $this->database->lastInsertId();
    }

    public function findByAlias(string $alias): ?array
    {
        $statement = $this->database->prepare(
            'SELECT urls.*, users.email AS owner_email
             FROM urls
             LEFT JOIN users ON users.id = urls.user_id
             WHERE urls.alias = :alias
             LIMIT 1'
        );
        $statement->execute([':alias' => $alias]);
        $record = $statement->fetch();

        return $record ?: null;
    }

    public function findByOriginal(string $originalUrl): ?array
    {
        $statement = $this->database->prepare(
            'SELECT *
             FROM urls
             WHERE original_url_hash = :original_url_hash
             AND is_active = 1
             AND (expires_at IS NULL OR expires_at > NOW())
             ORDER BY id DESC
             LIMIT 1'
        );
        $statement->execute([':original_url_hash' => hash('sha256', $originalUrl)]);
        $record = $statement->fetch();

        return $record ?: null;
    }

    public function findByOriginalForUser(string $originalUrl, int $userId): ?array
    {
        $statement = $this->database->prepare(
            'SELECT *
             FROM urls
             WHERE original_url_hash = :original_url_hash
             AND user_id = :user_id
             AND is_active = 1
             AND (expires_at IS NULL OR expires_at > NOW())
             ORDER BY id DESC
             LIMIT 1'
        );
        $statement->execute([
            ':original_url_hash' => hash('sha256', $originalUrl),
            ':user_id' => $userId,
        ]);
        $record = $statement->fetch();

        return $record ?: null;
    }

    public function aliasExists(string $alias): bool
    {
        $statement = $this->database->prepare('SELECT COUNT(*) FROM urls WHERE alias = :alias');
        $statement->execute([':alias' => $alias]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function incrementCounters(int $urlId, bool $isUnique): void
    {
        $statement = $this->database->prepare(
            'UPDATE urls
             SET total_clicks = total_clicks + 1,
                 unique_clicks = unique_clicks + :unique_increment,
                 updated_at = :updated_at
             WHERE id = :id'
        );
        $statement->execute([
            ':unique_increment' => $isUnique ? 1 : 0,
            ':updated_at' => now(),
            ':id' => $urlId,
        ]);
    }

    public function isExpired(array $urlRecord): bool
    {
        if (empty($urlRecord['expires_at'])) {
            return false;
        }

        return strtotime($urlRecord['expires_at']) < time();
    }

    public function getTrending(int $limit = 5): array
    {
        $statement = $this->database->prepare(
            'SELECT alias, original_url, total_clicks, unique_clicks
             FROM urls
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
             ORDER BY total_clicks DESC, id DESC
             LIMIT :limit'
        );
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getMostClicked(int $limit = 5): array
    {
        $statement = $this->database->prepare(
            'SELECT alias, original_url, total_clicks, unique_clicks, created_at
             FROM urls
             ORDER BY total_clicks DESC, id DESC
             LIMIT :limit'
        );
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function adminSummary(): array
    {
        $summary = [
            'total_links' => (int) $this->database->query('SELECT COUNT(*) FROM urls')->fetchColumn(),
            'active_links' => (int) $this->database->query('SELECT COUNT(*) FROM urls WHERE is_active = 1 AND (expires_at IS NULL OR expires_at > NOW())')->fetchColumn(),
            'total_clicks' => (int) $this->database->query('SELECT COALESCE(SUM(total_clicks), 0) FROM urls')->fetchColumn(),
            'unique_clicks' => (int) $this->database->query('SELECT COALESCE(SUM(unique_clicks), 0) FROM urls')->fetchColumn(),
        ];

        $summary['expiring_soon'] = (int) $this->database->query(
            'SELECT COUNT(*) FROM urls WHERE expires_at IS NOT NULL AND expires_at BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)'
        )->fetchColumn();

        return $summary;
    }

    public function linksForUserPaginated(int $userId, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        $statement = $this->database->prepare(
            'SELECT urls.*
             FROM urls
             WHERE urls.user_id = :user_id
             ORDER BY urls.created_at DESC
             LIMIT :limit OFFSET :offset'
        );
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function userSummary(int $userId): array
    {
        $statement = $this->database->prepare(
            'SELECT
                COUNT(*) AS link_count,
                COALESCE(SUM(total_clicks), 0) AS total_clicks,
                COALESCE(SUM(unique_clicks), 0) AS unique_clicks,
                SUM(CASE WHEN expires_at IS NOT NULL AND expires_at > NOW() THEN 1 ELSE 0 END) AS scheduled_expirations
             FROM urls
             WHERE user_id = :user_id'
        );
        $statement->execute([':user_id' => $userId]);
        $summary = $statement->fetch() ?: [];

        return [
            'link_count' => (int) ($summary['link_count'] ?? 0),
            'total_clicks' => (int) ($summary['total_clicks'] ?? 0),
            'unique_clicks' => (int) ($summary['unique_clicks'] ?? 0),
            'scheduled_expirations' => (int) ($summary['scheduled_expirations'] ?? 0),
        ];
    }

    public function findOwnedByAlias(string $alias, int $userId): ?array
    {
        $statement = $this->database->prepare(
            'SELECT urls.*
             FROM urls
             WHERE urls.alias = :alias
             AND urls.user_id = :user_id
             LIMIT 1'
        );
        $statement->execute([
            ':alias' => $alias,
            ':user_id' => $userId,
        ]);
        $record = $statement->fetch();

        return $record ?: null;
    }

    public function aliasExistsExcludingId(string $alias, int $excludeId): bool
    {
        $statement = $this->database->prepare(
            'SELECT COUNT(*) FROM urls WHERE alias = :alias AND id != :exclude_id'
        );
        $statement->execute([
            ':alias' => $alias,
            ':exclude_id' => $excludeId,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function updateOwnedLink(int $urlId, int $userId, array $data): void
    {
        $statement = $this->database->prepare(
            'UPDATE urls
             SET alias = :alias,
                 expires_at = :expires_at,
                 updated_at = :updated_at
             WHERE id = :id AND user_id = :user_id'
        );
        $statement->execute([
            ':alias' => $data['alias'],
            ':expires_at' => $data['expires_at'],
            ':updated_at' => now(),
            ':id' => $urlId,
            ':user_id' => $userId,
        ]);
    }

    public function deleteOwnedLink(int $urlId, int $userId): void
    {
        $statement = $this->database->prepare(
            'DELETE FROM urls WHERE id = :id AND user_id = :user_id'
        );
        $statement->execute([
            ':id' => $urlId,
            ':user_id' => $userId,
        ]);
    }
}
