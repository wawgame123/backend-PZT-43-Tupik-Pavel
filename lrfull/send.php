<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

ini_set('SMTP', '127.0.0.1');
ini_set('smtp_port', '1025');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Недопустимый метод запроса.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// ЛР No3: данные формы обратной связи передаются методом POST.
$name = trim((string)($_POST['username'] ?? ''));
$phone = trim((string)($_POST['phone'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));

if ($name === '' || $phone === '' || $email === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Все поля формы обязательны для заполнения.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Введите корректный email.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$safeName = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$safePhone = htmlspecialchars($phone, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$safeEmail = htmlspecialchars($email, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

// Старая логика сохранена: каждая заявка записывается отдельным файлом в папку results.
$dir = __DIR__ . DIRECTORY_SEPARATOR . 'results';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$currentDate = date('Y-m-d_H-i-s');
$filename = $dir . DIRECTORY_SEPARATOR . 'lead_' . $currentDate . '.txt';

$fileContent = "Новая заявка с сайта Money Fest\n";
$fileContent .= "=================================\n";
$fileContent .= "Дата: " . date('d.m.Y H:i:s') . "\n";
$fileContent .= "Имя: " . $name . "\n";
$fileContent .= "Телефон: " . $phone . "\n";
$fileContent .= "Email: " . $email . "\n";
$fileContent .= "=================================\n";

file_put_contents($filename, $fileContent);

// Старая логика сохранена: заявка отправляется письмом с прикрепленным txt-файлом.
$to = 'admin@moneyfest.local';
$subject = 'Новая заявка: Money Fest (' . date('d.m.Y H:i') . ')';
$boundary = md5(uniqid((string)time(), true));

$headers = "MIME-Version: 1.0\r\n";
$headers .= "From: info@moneyfest.local\r\n";
$headers .= "Reply-To: " . $safeEmail . "\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"\r\n";

$htmlBody = "
<html>
<head>
    <title>Заявка с сайта Money Fest</title>
</head>
<body style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
    <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; border-top: 5px solid #fbc04a;'>
        <h2 style='color: #111111; text-transform: uppercase; margin-bottom: 20px;'>MONEY <span style='color: #fbc04a;'>FEST</span></h2>
        <p style='font-size: 16px; color: #333333;'>На лендинге оставлена новая заявка:</p>
        <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
            <tr style='background-color: #f9f9f9;'>
                <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>Имя:</td>
                <td style='padding: 10px; border: 1px solid #ddd;'>{$safeName}</td>
            </tr>
            <tr>
                <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>Телефон:</td>
                <td style='padding: 10px; border: 1px solid #ddd;'>{$safePhone}</td>
            </tr>
            <tr style='background-color: #f9f9f9;'>
                <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>Email:</td>
                <td style='padding: 10px; border: 1px solid #ddd;'>{$safeEmail}</td>
            </tr>
        </table>
        <p style='font-size: 14px; color: #777777;'>Копия данных прикреплена к письму в txt-файле.</p>
    </div>
</body>
</html>";

$fileData = file_get_contents($filename);
$fileBase64 = chunk_split(base64_encode((string)$fileData));
$justFilename = basename($filename);

$body = "--" . $boundary . "\r\n";
$body .= "Content-Type: text/html; charset=\"utf-8\"\r\n";
$body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
$body .= $htmlBody . "\r\n\r\n";

$body .= "--" . $boundary . "\r\n";
$body .= "Content-Type: text/plain; name=\"" . $justFilename . "\"\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n";
$body .= "Content-Disposition: attachment; filename=\"" . $justFilename . "\"\r\n\r\n";
$body .= $fileBase64 . "\r\n";
$body .= "--" . $boundary . "--\r\n";

if (mail($to, $subject, $body, $headers)) {
    echo json_encode(['status' => 'success'], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    'status' => 'error',
    'message' => 'Не удалось отправить почтовое уведомление сервером.',
], JSON_UNESCAPED_UNICODE);
