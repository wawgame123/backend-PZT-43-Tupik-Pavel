<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = htmlspecialchars($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === 'admin' && $password === '12345') {
        echo "Авторизация успешна. Добро пожаловать!";
    } else {
        echo "Неверный логин или пароль.";
    }
}
?>

<form method="POST">
    <input type="text" name="login" placeholder="Логин" required><br>
    <input type="password" name="password" placeholder="Пароль" required><br>
    <button type="submit">Войти</button>
</form>