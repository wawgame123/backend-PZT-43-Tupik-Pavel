<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        ob_start();
        require dirname(__DIR__) . '/Views/' . $view . '.php';
        $content = ob_get_clean();

        require dirname(__DIR__) . '/Views/layout.php';
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    protected function request(string $key, string $default = ''): string
    {
        return trim((string)($_REQUEST[$key] ?? $default));
    }
}
