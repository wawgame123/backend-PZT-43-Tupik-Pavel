<?php
declare(strict_types=1);

use App\Core\Database;

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

date_default_timezone_set('Europe/Minsk');

$dataDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0777, true);
}

$sessionDir = $dataDir . DIRECTORY_SEPARATOR . 'sessions';
if (!is_dir($sessionDir)) {
    mkdir($sessionDir, 0777, true);
}

session_save_path($sessionDir);

if (session_status() !== PHP_SESSION_ACTIVE) {
    // ЛР No4: запуск механизма сессий для авторизации пользователя и хранения корзины.
    session_start();
}

Database::boot($dataDir . DIRECTORY_SEPARATOR . 'moneyfest.sqlite');

function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
