<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;

class UserRepository
{
    public function __construct(private Database $database)
    {
    }

    public function create(array $data): void
    {
        $sql = 'INSERT INTO user (name, email, password_hash, api_key, api_key_hash)
                VALUES (:name, :email, :password_hash, :api_key, :api_key_hash)';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':password_hash', $data['password_hash']);
        $stmt->bindValue(':api_key', $data['api_key']);
        $stmt->bindValue(':api_key_hash', $data['api_key_hash']);

        $stmt->execute();
    }

    public function find(string $column, $value): array|bool
    {
        $sql = "SELECT *
                FROM user
                WHERE $column = :value";

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':value', $value);

        $stmt->execute();

        return $stmt->fetch();
    }
}