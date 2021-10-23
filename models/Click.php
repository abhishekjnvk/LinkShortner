<?php

class Click
{
    private PDO $database;

    public function __construct()
    {
        $this->database = DatabaseConnection::get();
    }

    public function create(array $data): int
    {
        $statement = $this->database->prepare(
            'INSERT INTO clicks (
                url_id,
                tracking_key,
                visitor_id,
                visitor_hash,
                fingerprint_hash,
                ip_address,
                country_id,
                city,
                device_id,
                browser_id,
                referrer_id,
                operating_system,
                user_agent,
                screen_resolution,
                language,
                timezone,
                is_unique,
                is_bot,
                utm_source,
                utm_medium,
                utm_campaign,
                utm_term,
                utm_content,
                clicked_at
            ) VALUES (
                :url_id,
                :tracking_key,
                :visitor_id,
                :visitor_hash,
                NULL,
                :ip_address,
                :country_id,
                :city,
                :device_id,
                :browser_id,
                :referrer_id,
                :operating_system,
                :user_agent,
                :screen_resolution,
                :language,
                :timezone,
                :is_unique,
                :is_bot,
                :utm_source,
                :utm_medium,
                :utm_campaign,
                :utm_term,
                :utm_content,
                :clicked_at
            )'
        );
        $statement->execute([
            ':url_id' => $data['url_id'],
            ':tracking_key' => $data['tracking_key'],
            ':visitor_id' => $data['visitor_id'],
            ':visitor_hash' => $data['visitor_hash'],
            ':ip_address' => $data['ip_address'],
            ':country_id' => $data['country_id'],
            ':city' => $data['city'],
            ':device_id' => $data['device_id'],
            ':browser_id' => $data['browser_id'],
            ':referrer_id' => $data['referrer_id'],
            ':operating_system' => $data['operating_system'],
            ':user_agent' => $data['user_agent'],
            ':screen_resolution' => $data['screen_resolution'],
            ':language' => $data['language'],
            ':timezone' => $data['timezone'],
            ':is_unique' => $data['is_unique'],
            ':is_bot' => $data['is_bot'],
            ':utm_source' => $data['utm_source'],
            ':utm_medium' => $data['utm_medium'],
            ':utm_campaign' => $data['utm_campaign'],
            ':utm_term' => $data['utm_term'],
            ':utm_content' => $data['utm_content'],
            ':clicked_at' => now(),
        ]);

        return (int) $this->database->lastInsertId();
    }

    public function hasVisitorClickedBefore(int $urlId, string $visitorHash): bool
    {
        $statement = $this->database->prepare(
            'SELECT COUNT(*) FROM clicks WHERE url_id = :url_id AND visitor_hash = :visitor_hash'
        );
        $statement->execute([
            ':url_id' => $urlId,
            ':visitor_hash' => $visitorHash,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function updateClientMetadata(string $trackingKey, array $data): void
    {
        $statement = $this->database->prepare(
            'UPDATE clicks
             SET screen_resolution = COALESCE(NULLIF(:screen_resolution, \'\'), screen_resolution),
                 language = COALESCE(NULLIF(:language, \'\'), language),
                 timezone = COALESCE(NULLIF(:timezone, \'\'), timezone),
                 fingerprint_hash = COALESCE(:fingerprint_hash, fingerprint_hash)
             WHERE tracking_key = :tracking_key'
        );
        $statement->execute([
            ':screen_resolution' => $data['screen_resolution'],
            ':language' => $data['language'],
            ':timezone' => $data['timezone'],
            ':fingerprint_hash' => $data['fingerprint_hash'],
            ':tracking_key' => $trackingKey,
        ]);
    }

    public function stats(int $urlId, ?string $from = null, ?string $to = null): array
    {
        $filters = $this->dateFilterClause($from, $to);
        $statement = $this->database->prepare(
            'SELECT
                COUNT(*) AS total_clicks,
                SUM(is_unique = 1) AS unique_clicks,
                SUM(is_bot = 1) AS bot_clicks,
                COUNT(DISTINCT country_id) AS countries_reached
             FROM clicks
             WHERE url_id = :url_id ' . $filters['sql']
        );
        $statement->execute(array_merge([':url_id' => $urlId], $filters['params']));

        return $statement->fetch() ?: [
            'total_clicks' => 0,
            'unique_clicks' => 0,
            'bot_clicks' => 0,
            'countries_reached' => 0,
        ];
    }

    public function timeline(int $urlId, ?string $from = null, ?string $to = null): array
    {
        $filters = $this->dateFilterClause($from, $to);
        $statement = $this->database->prepare(
            'SELECT DATE(clicked_at) AS label, COUNT(*) AS clicks
             FROM clicks
             WHERE url_id = :url_id ' . $filters['sql'] . '
             GROUP BY DATE(clicked_at)
             ORDER BY DATE(clicked_at) ASC'
        );
        $statement->execute(array_merge([':url_id' => $urlId], $filters['params']));

        return $statement->fetchAll();
    }

    public function byCountry(int $urlId, ?string $from = null, ?string $to = null): array
    {
        return $this->dimensionBreakdown($urlId, 'countries', 'country_id', 'country_name', $from, $to);
    }

    public function byDevice(int $urlId, ?string $from = null, ?string $to = null): array
    {
        return $this->dimensionBreakdown($urlId, 'devices', 'device_id', 'device_name', $from, $to);
    }

    public function byBrowser(int $urlId, ?string $from = null, ?string $to = null): array
    {
        return $this->dimensionBreakdown($urlId, 'browsers', 'browser_id', 'browser_name', $from, $to);
    }

    public function topReferrers(int $urlId, ?string $from = null, ?string $to = null): array
    {
        $filters = $this->dateFilterClause($from, $to);
        $statement = $this->database->prepare(
            'SELECT referrers.referrer_label AS label, referrers.referrer_domain AS domain, COUNT(*) AS clicks
             FROM clicks
             INNER JOIN referrers ON referrers.id = clicks.referrer_id
             WHERE clicks.url_id = :url_id ' . $filters['sql'] . '
             GROUP BY clicks.referrer_id
             ORDER BY clicks DESC
             LIMIT 8'
        );
        $statement->execute(array_merge([':url_id' => $urlId], $filters['params']));

        return $statement->fetchAll();
    }

    public function recentVisitors(int $urlId, ?string $from = null, ?string $to = null, int $limit = 10, int $offset = 0): array
    {
        $filters = $this->dateFilterClause($from, $to);
        $statement = $this->database->prepare(
            'SELECT
                clicked_at,
                ip_address,
                city,
                countries.country_name,
                devices.device_name,
                browsers.browser_name,
                operating_system,
                language,
                timezone,
                is_unique,
                is_bot,
                referrers.referrer_label
             FROM clicks
             LEFT JOIN countries ON countries.id = clicks.country_id
             LEFT JOIN devices ON devices.id = clicks.device_id
             LEFT JOIN browsers ON browsers.id = clicks.browser_id
             LEFT JOIN referrers ON referrers.id = clicks.referrer_id
             WHERE clicks.url_id = :url_id ' . $filters['sql'] . '
             ORDER BY clicked_at DESC
             LIMIT :limit OFFSET :offset'
        );
        $statement->bindValue(':url_id', $urlId, PDO::PARAM_INT);
        foreach ($filters['params'] as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function recentVisitorsCount(int $urlId, ?string $from = null, ?string $to = null): int
    {
        $filters = $this->dateFilterClause($from, $to);
        $statement = $this->database->prepare(
            'SELECT COUNT(*) FROM clicks WHERE url_id = :url_id ' . $filters['sql']
        );
        $statement->execute(array_merge([':url_id' => $urlId], $filters['params']));

        return (int) $statement->fetchColumn();
    }

    public function topLocations(int $urlId, ?string $from = null, ?string $to = null): array
    {
        $filters = $this->dateFilterClause($from, $to);
        $statement = $this->database->prepare(
            'SELECT countries.country_name, city, COUNT(*) AS clicks
             FROM clicks
             LEFT JOIN countries ON countries.id = clicks.country_id
             WHERE clicks.url_id = :url_id ' . $filters['sql'] . '
             GROUP BY countries.country_name, city
             ORDER BY clicks DESC
             LIMIT 8'
        );
        $statement->execute(array_merge([':url_id' => $urlId], $filters['params']));

        return $statement->fetchAll();
    }

    public function hourlyHeatmap(int $urlId, ?string $from = null, ?string $to = null): array
    {
        $filters = $this->dateFilterClause($from, $to);
        $statement = $this->database->prepare(
            'SELECT DAYOFWEEK(clicked_at) AS weekday, HOUR(clicked_at) AS hour_value, COUNT(*) AS clicks
             FROM clicks
             WHERE url_id = :url_id ' . $filters['sql'] . '
             GROUP BY DAYOFWEEK(clicked_at), HOUR(clicked_at)
             ORDER BY weekday ASC, hour_value ASC'
        );
        $statement->execute(array_merge([':url_id' => $urlId], $filters['params']));

        return $statement->fetchAll();
    }

    public function dailyTrend(int $urlId): array
    {
        $statement = $this->database->prepare(
            'SELECT DATE(clicked_at) AS label, COUNT(*) AS clicks
             FROM clicks
             WHERE url_id = :url_id
             AND clicked_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY DATE(clicked_at)
             ORDER BY DATE(clicked_at) ASC'
        );
        $statement->execute([':url_id' => $urlId]);

        return $statement->fetchAll();
    }

    public function exportRows(int $urlId, ?string $from = null, ?string $to = null): array
    {
        $filters = $this->dateFilterClause($from, $to);
        $statement = $this->database->prepare(
            'SELECT
                clicked_at,
                ip_address,
                city,
                countries.country_name,
                devices.device_name,
                browsers.browser_name,
                operating_system,
                language,
                timezone,
                screen_resolution,
                is_unique,
                is_bot,
                referrers.referrer_label,
                utm_source,
                utm_medium,
                utm_campaign,
                utm_term,
                utm_content
             FROM clicks
             LEFT JOIN countries ON countries.id = clicks.country_id
             LEFT JOIN devices ON devices.id = clicks.device_id
             LEFT JOIN browsers ON browsers.id = clicks.browser_id
             LEFT JOIN referrers ON referrers.id = clicks.referrer_id
             WHERE clicks.url_id = :url_id ' . $filters['sql'] . '
             ORDER BY clicked_at DESC'
        );
        $statement->execute(array_merge([':url_id' => $urlId], $filters['params']));

        return $statement->fetchAll();
    }

    public function adminTopLinks(int $limit = 10): array
    {
        $statement = $this->database->prepare(
            'SELECT alias, original_url, total_clicks, unique_clicks, created_at, expires_at
             FROM urls
             ORDER BY total_clicks DESC, id DESC
             LIMIT :limit'
        );
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    private function dimensionBreakdown(
        int $urlId,
        string $table,
        string $foreignKey,
        string $labelColumn,
        ?string $from,
        ?string $to
    ): array {
        $filters = $this->dateFilterClause($from, $to);
        $statement = $this->database->prepare(
            "SELECT {$table}.{$labelColumn} AS label, COUNT(*) AS clicks
             FROM clicks
             INNER JOIN {$table} ON {$table}.id = clicks.{$foreignKey}
             WHERE clicks.url_id = :url_id {$filters['sql']}
             GROUP BY clicks.{$foreignKey}
             ORDER BY clicks DESC
             LIMIT 8"
        );
        $statement->execute(array_merge([':url_id' => $urlId], $filters['params']));

        return $statement->fetchAll();
    }

    private function dateFilterClause(?string $from, ?string $to): array
    {
        $sql = '';
        $params = [];

        if ($from) {
            $sql .= ' AND clicked_at >= :from_date';
            $params[':from_date'] = $from . ' 00:00:00';
        }

        if ($to) {
            $sql .= ' AND clicked_at <= :to_date';
            $params[':to_date'] = $to . ' 23:59:59';
        }

        return ['sql' => $sql, 'params' => $params];
    }
}
