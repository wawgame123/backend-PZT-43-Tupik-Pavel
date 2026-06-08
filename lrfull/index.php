<?php
declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\HomeController;
use App\Models\OrderRepository;
use App\Models\ProductRepository;
use App\Models\UserRepository;
use App\Services\Auth;
use App\Services\Cart;

$products = new ProductRepository();
$users = new UserRepository();
$orders = new OrderRepository();
$auth = new Auth($users);
$cart = new Cart($products);

// ЛР No9: фронт-контроллер MVC. Все действия проходят через index.php.
$action = $_GET['action'] ?? $_POST['action'] ?? 'home';
$authController = new AuthController($users, $auth, $orders, $cart);
$cartController = new CartController($cart, $auth, $orders);

try {
    match ($action) {
        'register_page' => $authController->showRegister(),
        'login_page' => $authController->showLogin(),
        'register' => $authController->register(),
        'login' => $authController->login(),
        'logout' => $authController->logout(),
        'cabinet' => $authController->cabinet(),
        'cart' => $cartController->show(),
        'add_to_cart' => $cartController->add(),
        'remove_from_cart' => $cartController->remove(),
        'checkout' => $cartController->checkout(),
        default => (new HomeController($products, $auth, $cart))->index(),
    };
} catch (Throwable $throwable) {
    http_response_code(500);
    $_SESSION['messages'][] = 'Ошибка приложения: ' . $throwable->getMessage();
    (new HomeController($products, $auth, $cart))->index();
}
