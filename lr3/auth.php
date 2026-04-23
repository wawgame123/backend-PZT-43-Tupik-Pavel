<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = htmlspecialchars($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === 'Павел' && $password === '112233') {
        $_SESSION['logged_in'] = true;
        echo "<br>"."Авторизация прошла успешно.";
    } else {
        echo "<br>"."Неверные данные.";
    }
}
?>

<form method="POST">
    <input type="text" name="login" placeholder="Логин" required><br><br>
    <input type="password" name="password" placeholder="Пароль" required><br><br>
    <button type="submit">Войти</button>
</form>