<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private static ?PDO $pdo = null;

    public static function boot(string $path): void
    {
        if (self::$pdo !== null) {
            return;
        }

        try {
            // ЛР No8: PDO + SQLite используются как слой доступа к базе данных.
            self::$pdo = new PDO('sqlite:' . $path);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::migrate();
            self::seed();
        } catch (PDOException $exception) {
            throw new RuntimeException('Не удалось подключиться к SQLite: ' . $exception->getMessage());
        }
    }

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            throw new RuntimeException('База данных не инициализирована.');
        }

        return self::$pdo;
    }

    private static function migrate(): void
    {
        $sql = [
            'CREATE TABLE IF NOT EXISTS categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE
            )',
            'CREATE TABLE IF NOT EXISTS products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category_id INTEGER NOT NULL,
                title TEXT NOT NULL,
                description TEXT NOT NULL,
                price INTEGER NOT NULL,
                level TEXT NOT NULL,
                duration TEXT NOT NULL,
                image TEXT,
                created_at TEXT NOT NULL,
                FOREIGN KEY(category_id) REFERENCES categories(id)
            )',
            'CREATE TABLE IF NOT EXISTS news (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                body TEXT NOT NULL,
                created_at TEXT NOT NULL
            )',
            'CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                remember_token TEXT,
                created_at TEXT NOT NULL
            )',
            'CREATE TABLE IF NOT EXISTS orders (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER,
                customer_name TEXT NOT NULL,
                phone TEXT NOT NULL,
                email TEXT NOT NULL,
                total INTEGER NOT NULL,
                created_at TEXT NOT NULL,
                FOREIGN KEY(user_id) REFERENCES users(id)
            )',
            'CREATE TABLE IF NOT EXISTS order_items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                order_id INTEGER NOT NULL,
                product_id INTEGER NOT NULL,
                product_title TEXT,
                category_name TEXT,
                level TEXT,
                quantity INTEGER NOT NULL,
                price INTEGER NOT NULL,
                FOREIGN KEY(order_id) REFERENCES orders(id),
                FOREIGN KEY(product_id) REFERENCES products(id)
            )',
        ];

        foreach ($sql as $statement) {
            self::pdo()->exec($statement);
        }

        self::ensureColumn('order_items', 'product_title', 'TEXT');
        self::ensureColumn('order_items', 'category_name', 'TEXT');
        self::ensureColumn('order_items', 'level', 'TEXT');
    }

    private static function ensureColumn(string $table, string $column, string $type): void
    {
        $columns = self::pdo()->query('PRAGMA table_info(' . $table . ')')->fetchAll();
        foreach ($columns as $info) {
            if (($info['name'] ?? '') === $column) {
                return;
            }
        }

        self::pdo()->exec('ALTER TABLE ' . $table . ' ADD COLUMN ' . $column . ' ' . $type);
    }

    private static function seed(): void
    {
        self::seedCatalog();
        self::seedNews();
    }

    private static function seedCatalog(): void
    {
        $count = (int)self::pdo()->query('SELECT COUNT(*) FROM categories')->fetchColumn();
        if ($count > 0) {
            return;
        }

        $categories = ['Инвестиции', 'Трейдинг', 'Персональное обучение'];
        $insertCategory = self::pdo()->prepare('INSERT INTO categories (name) VALUES (:name)');
        foreach ($categories as $category) {
            $insertCategory->execute(['name' => $category]);
        }

        $products = [
            [1, 'Криптоинвестиции: успешный старт', 'Базовый курс по покупке, хранению и анализу криптовалют.', 299, 'Начальный', '4 недели', 'image/phone_light.png'],
            [1, 'Портфель инвестора', 'Сбор портфеля, риск-менеджмент и ребалансировка активов.', 449, 'Средний', '6 недель', 'image/learn.png'],
            [2, 'Мастерская трейдинга', 'Практические сделки, стратегии входа и выхода из позиции.', 599, 'Продвинутый', '8 недель', 'image/phone_dark.png'],
            [2, 'Технический анализ', 'Свечные модели, уровни, индикаторы и торговый план.', 349, 'Средний', '5 недель', 'image/videorequest.png'],
            [3, 'Индивидуальное сопровождение', 'Личные консультации эксперта и проверка домашних заданий.', 899, 'Индивидуально', '1 месяц', 'image/Nastya.png'],
            [3, 'Закрытое комьюнити', 'Доступ к чату, разборам рынка и еженедельным встречам.', 199, 'Любой', '3 месяца', 'image/tg.png'],
        ];
        $insertProduct = self::pdo()->prepare(
            'INSERT INTO products (category_id, title, description, price, level, duration, image, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        foreach ($products as $product) {
            $insertProduct->execute([...$product, date('c')]);
        }
    }

    private static function seedNews(): void
    {
        $news = [
            ['Открыт набор на летний поток', 'Добавлены новые практические занятия и еженедельные разборы портфелей.'],
            ['Каталог курсов обновлен', 'Теперь курсы можно искать, сортировать, фильтровать и добавлять в корзину.'],
            ['Новый модуль по безопасности кошельков', 'Разобрали хранение seed-фраз, двухфакторную защиту и типичные схемы мошенничества.'],
            ['Еженедельные разборы рынка', 'Студенты получают свежие обзоры BTC, ETH и популярных альткоинов с понятными сценариями.'],
            ['Обновлена программа трейдинга', 'В курс добавлены блоки по риск-менеджменту, дневнику сделок и контролю эмоций.'],
            ['Появились рассрочка и промокоды', 'Оплату обучения можно разбить на части, а скидка применяется прямо при оформлении заявки.'],
        ];

        $insertNews = self::pdo()->prepare('INSERT INTO news (title, body, created_at) VALUES (?, ?, ?)');
        $newsExists = self::pdo()->prepare('SELECT COUNT(*) FROM news WHERE title = ?');
        foreach ($news as $item) {
            $newsExists->execute([$item[0]]);
            if ((int)$newsExists->fetchColumn() > 0) {
                continue;
            }

            $insertNews->execute([$item[0], $item[1], date('c')]);
        }
    }
}
