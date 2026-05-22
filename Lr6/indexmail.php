<?php
ini_set('SMTP', '127.0.0.1');
ini_set('smtp_port', '1025');

$statusMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['username'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $user_message = htmlspecialchars($_POST['message'] ?? '');

    $to = "admin@example.com";
    $subject = "Новое сообщение от $name";
    
    $message = "Имя: $name\n";
    $message .= "Email: $email\n";
    $message .= "Сообщение:\n$user_message";

    $headers = "From: system@localhost\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=utf-8";

    if (mail($to, $subject, $message, $headers)) {
        $statusMessage = "<div class='status success'>Сообщение отправлено в MailHog!</div>";
    } else {
        $statusMessage = "<div class='status error'>Ошибка отправки.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обратная связь</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            margin-top: 0;
            color: #333;
            text-align: center;
        }

        .status {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-size: 14px;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box; 
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #007bff;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Связаться с нами</h2>
    
    <?php echo $statusMessage; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Ваше имя</label>
            <input type="text" id="username" name="username" placeholder="Иван Иванов" required>
        </div>

        <div class="form-group">
            <label for="email">Электронная почта</label>
            <input type="email" id="email" name="email" placeholder="example@mail.com" required>
        </div>

        <div class="form-group">
            <label for="message">Ваше сообщение</label>
            <textarea id="message" name="message" placeholder="Введите текст..." required></textarea>
        </div>

        <button type="submit">Отправить сообщение</button>
    </form>
</div>

</body>
</html>