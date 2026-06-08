<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\ProductRepository;

final class Cart
{
    private const KEY = 'cart';

    public function __construct(private ProductRepository $products)
    {
        if (!isset($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = [];
        }

        if (empty($_COOKIE['cart_token'])) {
            // ЛР No4: COOKIE помечает корзину посетителя, а состав корзины хранится в SESSION.
            setcookie('cart_token', bin2hex(random_bytes(16)), time() + 60 * 60 * 24 * 7, '/');
        }
    }

    public function add(int $productId): void
    {
        if (!$this->products->find($productId)) {
            return;
        }

        $_SESSION[self::KEY][$productId] = ($_SESSION[self::KEY][$productId] ?? 0) + 1;
    }

    public function remove(int $productId): void
    {
        unset($_SESSION[self::KEY][$productId]);
    }

    public function clear(): void
    {
        $_SESSION[self::KEY] = [];
    }

    public function count(): int
    {
        return array_sum($_SESSION[self::KEY]);
    }

    public function items(): array
    {
        $items = [];
        foreach ($_SESSION[self::KEY] as $productId => $quantity) {
            $product = $this->products->find((int)$productId);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'quantity' => (int)$quantity,
                    'sum' => (int)$product['price'] * (int)$quantity,
                ];
            }
        }

        return $items;
    }

    public function total(): int
    {
        return array_sum(array_column($this->items(), 'sum'));
    }
}
