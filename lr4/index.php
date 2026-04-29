<?php
session_start();

if (!isset($_SESSION['counter'])) {
    $_SESSION['counter'] = 0;
}
$_SESSION['counter']++;

$lastVisit = $_COOKIE['visit_time'] ?? "первый раз";
setcookie('visit_time', date('H:i:s d.m.y'), time() + 86400, "/");

$error = '';

if (isset($_POST['login_btn'])) {
    $login = $_POST['user_login'];
    $pass = $_POST['user_pass'];

    if ($login === 'admin' && $pass === '1234') {
        $_SESSION['auth'] = true;
        $_SESSION['login'] = $login;
        $_SESSION['pass'] = $pass;
        header("Location: second.php");
        exit;
    } else {
        $error = "Неверный пароль!";
    }
}

if (isset($_GET['exit'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа №4</title>
    <style>
        body {
            font-family: sans-serif;
            line-height: 1.6;
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
            color: #333;
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
            color: #444;
        }
        .section {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input {
            display: block;
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            background-color: #2ecc71;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        .error {
            color: #e74c3c;
            font-weight: bold;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <h1>Лабораторная работа</h1>

    <div class="section">
        <h2>Посещения и Cookies</h2>
        <p>Счётчик в сессии: <strong><?php echo $_SESSION['counter']; ?></strong></p>
        <p>Время прошлого визита: <span style="color: #666;"><?php echo $lastVisit; ?></span></p>
        <p><a href="?">Обновить состояние</a></p>
    </div>

    <div class="section">
        <h2>Авторизация</h2>

        <?php if (!isset($_SESSION['auth'])): ?>
            <form method="POST">
                <?php if ($error): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <input type="text" name="user_login" placeholder="Логин (admin)" required>
                <input type="password" name="user_pass" placeholder="Пароль (1234)" required>
                <button type="submit" name="login_btn">Войти в кабинет</button>
            </form>
        <?php else: ?>
            <p>Вы уже авторизованы. <a href="second.php">Перейти в кабинет</a> или <a href="?exit=1">Выйти</a></p>
        <?php endif; ?>
    </div>

</body>
</html>