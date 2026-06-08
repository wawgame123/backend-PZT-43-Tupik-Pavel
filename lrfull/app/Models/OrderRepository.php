<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class OrderRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::pdo();
    }

    public function create(?int $userId, string $name, string $phone, string $email, array $items, int $total): int
    {
        $this->pdo->beginTransaction();

        try {
            $orderStatement = $this->pdo->prepare(
                'INSERT INTO orders (user_id, customer_name, phone, email, total, created_at)
                 VALUES (:user_id, :customer_name, :phone, :email, :total, :created_at)'
            );
            $orderStatement->execute([
                'user_id' => $userId,
                'customer_name' => $name,
                'phone' => $phone,
                'email' => strtolower($email),
                'total' => $total,
                'created_at' => date('c'),
            ]);

            $orderId = (int)$this->pdo->lastInsertId();
            $itemStatement = $this->pdo->prepare(
                'INSERT INTO order_items
                    (order_id, product_id, product_title, category_name, level, quantity, price)
                 VALUES
                    (:order_id, :product_id, :product_title, :category_name, :level, :quantity, :price)'
            );

            foreach ($items as $item) {
                $product = $item['product'];
                $itemStatement->execute([
                    'order_id' => $orderId,
                    'product_id' => (int)$product['id'],
                    'product_title' => (string)$product['title'],
                    'category_name' => (string)($product['category'] ?? ''),
                    'level' => (string)$product['level'],
                    'quantity' => (int)$item['quantity'],
                    'price' => (int)$product['price'],
                ]);
            }

            $this->pdo->commit();

            return $orderId;
        } catch (\Throwable $throwable) {
            $this->pdo->rollBack();
            throw $throwable;
        }
    }

    public function byUser(int $userId): array
    {
        $statement = $this->pdo->prepare('SELECT * FROM orders WHERE user_id = :user_id ORDER BY datetime(created_at) DESC');
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll();
    }
}
