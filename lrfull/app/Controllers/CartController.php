<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\OrderRepository;
use App\Services\Auth;
use App\Services\Cart;

final class CartController extends Controller
{
    public function __construct(
        private Cart $cart,
        private Auth $auth,
        private OrderRepository $orders
    ) {
    }

    public function show(): void
    {
        $this->render('cart', [
            'title' => 'Корзина',
            'user' => $this->auth->user(),
            'cartCount' => $this->cart->count(),
            'cartItems' => $this->cart->items(),
            'cartTotal' => $this->cart->total(),
            'messages' => $_SESSION['messages'] ?? [],
        ]);
        unset($_SESSION['messages']);
    }

    public function add(): void
    {
        // ЛР No3 + корзина: добавление товара выполняется POST-запросом.
        $this->cart->add((int)($_POST['product_id'] ?? 0));
        $_SESSION['messages'][] = 'Курс добавлен в корзину.';
        $this->redirect('index.php#catalog');
    }

    public function remove(): void
    {
        $this->cart->remove((int)($_POST['product_id'] ?? 0));
        $_SESSION['messages'][] = 'Позиция удалена из корзины.';
        $this->redirect('index.php?action=cart');
    }

    public function checkout(): void
    {
        $items = $this->cart->items();
        if (!$items) {
            $_SESSION['messages'][] = 'Корзина пуста.';
            $this->redirect('index.php?action=cart');
        }

        $name = trim((string)($_POST['name'] ?? ''));
        $phone = trim((string)($_POST['phone'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));

        if ($name === '' || $phone === '' || $email === '') {
            $_SESSION['messages'][] = 'Для оформления заказа заполните имя, телефон и email.';
            $this->redirect('index.php?action=cart');
        }

        $user = $this->auth->user();
        $orderId = $this->orders->create(
            $user ? (int)$user['id'] : null,
            $name,
            $phone,
            $email,
            $items,
            $this->cart->total()
        );
        $this->cart->clear();

        $_SESSION['messages'][] = 'Заказ No' . $orderId . ' оформлен. ID товаров и данные курсов сохранены в БД.';
        $this->redirect('index.php?action=cart');
    }
}
