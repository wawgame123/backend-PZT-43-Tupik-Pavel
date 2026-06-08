<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ProductRepository;
use App\Services\Auth;
use App\Services\Cart;

final class HomeController extends Controller
{
    public function __construct(
        private ProductRepository $products,
        private Auth $auth,
        private Cart $cart
    ) {
        // ЛР No9: контроллер получает модели/сервисы и передает данные во View.
    }

    public function index(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $filters = [
            'q' => trim((string)($_GET['q'] ?? '')),
            'category_id' => trim((string)($_GET['category_id'] ?? '')),
            'level' => trim((string)($_GET['level'] ?? '')),
            'sort' => trim((string)($_GET['sort'] ?? 'newest')),
        ];

        $catalog = $this->products->catalog($filters, $page);

        $this->render('home', [
            'title' => 'Money Fest',
            'user' => $this->auth->user(),
            'cartCount' => $this->cart->count(),
            'categories' => $this->products->categories(),
            'levels' => $this->products->levels(),
            'news' => $this->products->news(),
            'catalog' => $catalog,
            'filters' => $filters,
            'messages' => $_SESSION['messages'] ?? [],
            'cartItems' => $this->cart->items(),
            'cartTotal' => $this->cart->total(),
        ]);

        unset($_SESSION['messages']);
    }
}
