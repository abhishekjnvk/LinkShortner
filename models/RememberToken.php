<?php

class RememberToken
{
    private PDO $database;

    public function __construct()
    {
        $this->database = DatabaseConnection::get();
    }

    public function create(int $userId): array
    {
        $selector = bin2hex(random_bytes(12));
        $validator = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+' . config('security.remember_days', 30) . ' days'));

        $statement = $this->database->prepare(
            'INSERT INTO remember_tokens (
                user_id,
                selector,
                token_hash,
                expires_at,
                user_agent,
                created_ip,
                created_at
            ) VALUES (
                :user_id,
                :selector,
                :token_hash,
                :expires_at,
                :user_agent,
                :created_ip,
                :created_at
            )'
        );
        $statement->execute([
            ':user_id' => $userId,
            ':selector' => $selector,
            ':token_hash' => hash('sha256', $validator),
            ':expires_at' => $expiresAt,
            ':user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown', 0, 255),
            ':created_ip' => current_user_ip(),
            ':created_at' => now(),
        ]);

        return [
            'cookie' => $selector . ':' . $validator,
            'expires_at' => $expiresAt,
        ];
    }

    public function findValidToken(string $selector): ?array
    {
        $statement = $this->database->prepare(
            'SELECT * FROM remember_tokens
             WHERE selector = :selector
             AND expires_at > NOW()
             LIMIT 1'
        );
        $statement->execute([':selector' => $selector]);
        $record = $statement->fetch();

        return $record ?: null;
    }

    public function deleteBySelector(string $selector): void
    {
        $statement = $this->database->prepare('DELETE FROM remember_tokens WHERE selector = :selector');
        $statement->execute([':selector' => $selector]);
    }
}
