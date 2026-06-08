<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

final class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::pdo();
    }

    public function create(string $name, string $email, string $password): array
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO users (name, email, password_hash, created_at)
             VALUES (:name, :email, :password_hash, :created_at)'
        );

        try {
            $statement->execute([
                'name' => $name,
                'email' => strtolower($email),
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'created_at' => date('c'),
            ]);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                throw new \RuntimeException('Пользователь с таким email уже зарегистрирован.');
            }

            throw $exception;
        }

        return $this->find((int)$this->pdo->lastInsertId());
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $statement->execute(['id' => $id]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $statement->execute(['email' => strtolower($email)]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public function findByRememberToken(string $token): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM users WHERE remember_token = :token');
        $statement->execute(['token' => $token]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public function setRememberToken(int $id, string $token): void
    {
        $statement = $this->pdo->prepare('UPDATE users SET remember_token = :token WHERE id = :id');
        $statement->execute(['token' => $token, 'id' => $id]);
    }
}
