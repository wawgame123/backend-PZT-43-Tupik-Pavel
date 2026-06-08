<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\UserRepository;

final class Auth
{
    public function __construct(private UserRepository $users)
    {
        // ЛР No7: сервис авторизации инкапсулирует работу с пользователем как объект.
    }

    public function user(): ?array
    {
        if (isset($_SESSION['user_id'])) {
            return $this->users->find((int)$_SESSION['user_id']);
        }

        if (!empty($_COOKIE['remember_token'])) {
            // ЛР No4: COOKIE используется для восстановления входа пользователя.
            $user = $this->users->findByRememberToken((string)$_COOKIE['remember_token']);
            if ($user) {
                $_SESSION['user_id'] = (int)$user['id'];
                return $user;
            }
        }

        return null;
    }

    public function login(string $email, string $password, bool $remember): bool
    {
        $user = $this->users->findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        $_SESSION['user_id'] = (int)$user['id'];

        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $this->users->setRememberToken((int)$user['id'], $token);
            setcookie('remember_token', $token, time() + 60 * 60 * 24 * 30, '/');
        }

        return true;
    }

    public function logout(): void
    {
        unset($_SESSION['user_id']);
        setcookie('remember_token', '', time() - 3600, '/');
    }
}
