<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class ProductRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::pdo();
    }

    public function categories(): array
    {
        return $this->pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
    }

    public function news(): array
    {
        return $this->pdo->query('SELECT * FROM news ORDER BY datetime(created_at) DESC LIMIT 6')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT products.*, categories.name AS category
             FROM products
             JOIN categories ON categories.id = products.category_id
             WHERE products.id = :id'
        );
        $statement->execute(['id' => $id]);
        $product = $statement->fetch();

        return $product ?: null;
    }

    public function catalog(array $filters, int $page, int $perPage = 3): array
    {
        $where = [];
        $params = [];

        // ЛР No3 и No8: параметры поиска, фильтрации, сортировки и пагинации приходят методом GET.
        if (($filters['q'] ?? '') !== '') {
            $where[] = '(products.title LIKE :q OR products.description LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        if (($filters['category_id'] ?? '') !== '') {
            $where[] = 'products.category_id = :category_id';
            $params['category_id'] = (int)$filters['category_id'];
        }

        if (($filters['level'] ?? '') !== '') {
            $where[] = 'products.level = :level';
            $params['level'] = $filters['level'];
        }

        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $sortMap = [
            'price_asc' => 'products.price ASC',
            'price_desc' => 'products.price DESC',
            'title_asc' => 'products.title ASC',
            'newest' => 'datetime(products.created_at) DESC',
        ];
        $orderSql = $sortMap[$filters['sort'] ?? 'newest'] ?? $sortMap['newest'];

        $countStatement = $this->pdo->prepare("SELECT COUNT(*) FROM products $whereSql");
        $countStatement->execute($params);
        $total = (int)$countStatement->fetchColumn();

        $offset = max(0, ($page - 1) * $perPage);
        $statement = $this->pdo->prepare(
            "SELECT products.*, categories.name AS category
             FROM products
             JOIN categories ON categories.id = products.category_id
             $whereSql
             ORDER BY $orderSql
             LIMIT :limit OFFSET :offset"
        );

        foreach ($params as $key => $value) {
            $statement->bindValue(':' . $key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return [
            'items' => $statement->fetchAll(),
            'total' => $total,
            'pages' => max(1, (int)ceil($total / $perPage)),
            'page' => $page,
        ];
    }

    public function levels(): array
    {
        $statement = $this->pdo->query('SELECT DISTINCT level FROM products ORDER BY level');

        return array_column($statement->fetchAll(), 'level');
    }
}
