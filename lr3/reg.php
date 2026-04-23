<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $pass = $_POST['password'] ?? '';

    if ($name && $email && $pass) {
        echo "Аккаунт $name успешно создан.";
    } else {
        echo "Заполните все поля.";
    }
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Имя" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Пароль" required><br><br>
    <button type="submit">Зарегистрироваться</button>
</form>