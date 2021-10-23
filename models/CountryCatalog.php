<?php

class CountryCatalog
{
    private PDO $database;

    public function __construct()
    {
        $this->database = DatabaseConnection::get();
    }

    public function firstOrCreate(string $name): int
    {
        $statement = $this->database->prepare('SELECT id FROM countries WHERE country_name = :name LIMIT 1');
        $statement->execute([':name' => $name]);
        $existing = $statement->fetchColumn();
        if ($existing) {
            return (int) $existing;
        }

        $insert = $this->database->prepare('INSERT INTO countries (country_name, created_at) VALUES (:name, :created_at)');
        $insert->execute([':name' => $name, ':created_at' => now()]);

        return (int) $this->database->lastInsertId();
    }
}
