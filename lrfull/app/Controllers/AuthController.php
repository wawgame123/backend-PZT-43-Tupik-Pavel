<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\OrderRepository;
use App\Models\UserRepository;
use App\Services\Auth;
use App\Services\Cart;

final class AuthController extends Controller
{
    public function __construct(
        private UserRepository $users,
        private Auth $auth,
        private OrderRepository $orders,
        private Cart $cart
    ) {
    }

    public function showRegister(): void
    {
        $this->render('register', [
            'title' => 'Регистрация',
            'user' => $this->auth->user(),
            'cartCount' => $this->cart->count(),
            'messages' => $_SESSION['messages'] ?? [],
        ]);
        unset($_SESSION['messages']);
    }

    public function showLogin(): void
    {
        $this->render('login', [
            'title' => 'Вход',
            'user' => $this->auth->user(),
            'cartCount' => $this->cart->count(),
            'messages' => $_SESSION['messages'] ?? [],
        ]);
        unset($_SESSION['messages']);
    }

    public function register(): void
    {
        // ЛР No3: регистрация намеренно обрабатывается GET-запросом по заданию.
        $name = trim((string)($_GET['name'] ?? ''));
        $email = trim((string)($_GET['email'] ?? ''));
        $password = trim((string)($_GET['password'] ?? ''));

        if ($name === '' || $email === '' || $password === '') {
            $_SESSION['messages'][] = 'Заполните имя, email и пароль для регистрации.';
            $this->redirect('index.php?action=register_page');
        }

        try {
            // ЛР No8: добавление нового пользователя в таблицу users.
            $user = $this->users->create($name, $email, $password);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['messages'][] = 'Регистрация выполнена, пользователь добавлен в БД.';
            $this->redirect('index.php?action=cabinet');
        } catch (\Throwable $throwable) {
            $_SESSION['messages'][] = $throwable->getMessage();
            $this->redirect('index.php?action=register_page');
        }
    }

    public function login(): void
    {
        // ЛР No3: авторизация принимает POST, чтобы показать второй метод передачи данных.
        $email = trim((string)($_POST['email'] ?? ''));
        $password = trim((string)($_POST['password'] ?? ''));
        $remember = isset($_POST['remember']);

        if ($this->auth->login($email, $password, $remember)) {
            $_SESSION['messages'][] = 'Вход выполнен успешно.';
            $this->redirect('index.php?action=cabinet');
        }

        $_SESSION['messages'][] = 'Неверный email или пароль.';
        $this->redirect('index.php?action=login_page');
    }

    public function logout(): void
    {
        $this->auth->logout();
        $_SESSION['messages'][] = 'Вы вышли из личного кабинета.';
        $this->redirect('index.php');
    }

    public function cabinet(): void
    {
        $user = $this->auth->user();
        if (!$user) {
            $_SESSION['messages'][] = 'Для доступа к личному кабинету нужно войти.';
            $this->redirect('index.php?action=login_page');
        }

        $this->render('cabinet', [
            'title' => 'Личный кабинет',
            'user' => $user,
            'cartCount' => $this->cart->count(),
            'orders' => $this->orders->byUser((int)$user['id']),
            'messages' => $_SESSION['messages'] ?? [],
        ]);
        unset($_SESSION['messages']);
    }
}
