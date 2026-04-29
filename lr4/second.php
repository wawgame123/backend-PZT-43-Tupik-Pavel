<?php
session_start();

if (!isset($_SESSION['auth'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <style>
        body {
            font-family: sans-serif;
            line-height: 1.6;
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .success-box {
            background: #eef9ff;
            border-left: 5px solid #3498db;
            padding: 20px;
            border-radius: 5px;
        }
        h2 { color: #2c3e50; }
        .data { font-weight: bold; color: #2ecc71; }
        a { color: #e74c3c; text-decoration: none; }
    </style>
</head>
<body>

    <div class="success-box">
        <h2>Вход успешный!</h2>
        <p>Ваш логин: <span class="data"><?php echo htmlspecialchars($_SESSION['login']); ?></span></p>
        <p>Ваш пароль: <span class="data"><?php echo htmlspecialchars($_SESSION['pass']); ?></span></p>
        <hr>
        <p><a href="index.php?exit=1">Выйти из системы</a></p>
    </div>

</body>
</html>