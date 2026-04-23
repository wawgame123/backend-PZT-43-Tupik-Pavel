<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        echo "Пожалуйста, заполните все поля.";
    } elseif ($password !== $password_confirm) {
        echo "Пароли не совпадают.";
    } else {
        echo "Пользователь $username ($email) успешно зарегистрирован.";
    }
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="Имя пользователя" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Пароль" required><br>
    <input type="password" name="password_confirm" placeholder="Повторите пароль" required><br>
    <button type="submit">Зарегистрироваться</button>
</form>