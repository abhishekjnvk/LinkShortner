<?php

class User
{
    private PDO $database;

    public function __construct()
    {
        $this->database = DatabaseConnection::get();
    }

    public function create(string $email, string $passwordHash): int
    {
        $statement = $this->database->prepare(
            'INSERT INTO users (email, password_hash, created_at, updated_at)
             VALUES (:email, :password_hash, :created_at, :updated_at)'
        );
        $statement->execute([
            ':email' => strtolower($email),
            ':password_hash' => $passwordHash,
            ':created_at' => now(),
            ':updated_at' => now(),
        ]);

        return (int) $this->database->lastInsertId();
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->database->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->execute([':email' => strtolower($email)]);
        $record = $statement->fetch();

        return $record ?: null;
    }

    public function findById(int $id): ?array
    {
        $statement = $this->database->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $statement->execute([':id' => $id]);
        $record = $statement->fetch();

        return $record ?: null;
    }

    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }
}
