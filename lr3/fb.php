<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);

    if ($subject && $message && $email) {
        echo "Письмо на тему '$subject' отправлено.";
    } else {
        echo "Заполните все поля для отправки.";
    }
}
?>

<form method="POST">
    <input type="email" name="email" placeholder="Ваш Email" required><br><br>
    <input type="text" name="subject" placeholder="Тема письма" required><br><br>
    <textarea name="message" placeholder="Текст сообщения" required></textarea><br><br>
    <button type="submit">Отправить письмо</button>
</form>