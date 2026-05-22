<?php
header('Content-Type: application/json');

ini_set('SMTP', '127.0.0.1');
ini_set('smtp_port', '1025');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = isset($_POST['username']) ? trim(htmlspecialchars($_POST['username'])) : '';
    $phone = isset($_POST['phone']) ? trim(htmlspecialchars($_POST['phone'])) : '';
    $email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : '';

    if (empty($name) || empty($phone) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Все поля формы обязательны для заполнения.']);
        exit;
    }

    $dir = 'results';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $currentDate = date('Y-m-d_H-i-s');
    $filename = $dir . '/lead_' . $currentDate . '.txt';

    $fileContent = "Новая заявка с сайта Money Fest\n";
    $fileContent .= "=================================\n";
    $fileContent .= "Дата: " . date('d.m.Y H:i:s') . "\n";
    $fileContent .= "Имя: " . $name . "\n";
    $fileContent .= "Телефон: " . $phone . "\n";
    $fileContent .= "Email: " . $email . "\n";
    $fileContent .= "=================================\n";

    file_put_contents($filename, $fileContent);

    $to = "admin@moneyfest.local"; 
    $subject = "Новая заявка: Money Fest (" . date('d.m.Y H:i') . ")";
    
    $boundary = md5(uniqid(time()));

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "From: info@moneyfest.local\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"\r\n";

    $htmlBody = "
    <html>
    <head>
        <title>Заявка с сайта Money Fest</title>
    </head>
    <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
        <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-top: 5px solid #fbc04a;'>
            <h2 style='color: #111111; text-transform: uppercase; margin-bottom: 20px;'>MONEY <span style='color: #fbc04a;'>FEST</span></h2>
            <p style='font-size: 16px; color: #333333;'>Приветствуем! На вашем лендинге <strong>Черная Пятница (-30% на все курсы)</strong> оставлена новая заявка:</p>
            <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                <tr style='background-color: #f9f9f9;'>
                    <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>Имя:</td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>{$name}</td>
                </tr>
                <tr>
                    <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>Телефон:</td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>{$phone}</td>
                </tr>
                <tr style='background-color: #f9f9f9;'>
                    <td style='padding: 10px; border: 1px solid #ddd; font-weight: bold;'>Email:</td>
                    <td style='padding: 10px; border: 1px solid #ddd;'>{$email}</td>
                </tr>
            </table>
            <p style='font-size: 14px; color: #777777; margin-top: 30px; border-top: 1px solid #eeeeee; padding-top: 15px;'>
                Полная копия данных выгружена в текстовый файл и прикреплена к этому письму.
            </p>
        </div>
    </body>
    </html>
    ";

    $fileData = file_get_contents($filename);
    $fileBase64 = chunk_split(base64_encode($fileData));
    $justFilename = basename($filename);

    $body = "--" . $boundary . "\r\n";
    $body .= "Content-Type: text/html; charset=\"utf-8\"\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $htmlBody . "\r\n\r\n";

    $body .= "--" . $boundary . "\r\n";
    $body .= "Content-Type: application/octet-stream; name=\"" . $justFilename . "\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n";
    $body .= "Content-Disposition: attachment; filename=\"" . $justFilename . "\"\r\n\r\n";
    $body .= $fileBase64 . "\r\n";
    $body .= "--" . $boundary . "--\r\n";

    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Не удалось отправить почтовое уведомление сервером.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Недопустимый метод запроса.']);
}