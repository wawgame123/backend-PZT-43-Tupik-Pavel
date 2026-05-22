<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$message = "";
$status = "";
$xmlFile = 'emails.xml';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fio = htmlspecialchars($_POST['fio'] ?? 'Не указано');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $region = htmlspecialchars($_POST['region'] ?? 'Не указан');

    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);
        
        if ($xml && isset($xml->user)) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = '127.0.0.1';
                $mail->SMTPAuth   = false;
                $mail->SMTPAutoTLS = false;
                $mail->Port       = 1025;
                $mail->CharSet    = 'UTF-8';

                $mail->setFrom('system@localhost.com', 'Портал Правовой Информации');

                if (file_exists('constitution.pdf')) {
                    $mail->addAttachment('constitution.pdf', 'Constitution_RB.pdf');
                }

                $mail->isHTML(true);
                $mail->Subject = "Электронная копия Конституции РБ для $fio";
                $mail->Body    = "
                    <div style='font-family: Arial, sans-serif; border-left: 5px solid #009640; padding-left: 15px;'>
                        <h2 style='color: #CF101A;'>Конституция Республики Беларусь</h2>
                        <p>Уважаемый(ая) <b>$fio</b>!</p>
                        <p>По вашему запросу направляем актуальную версию основного закона страны в электронном формате.</p>
                        <ul>
                            <li><b>Регион запроса:</b> $region</li>
                            <li><b>Дата:</b> " . date("d.m.Y") . "</li>
                        </ul>
                        <p>Файл прикреплен к данному письму.</p>
                        <hr>
                        <p style='font-size: 12px; color: #666;'>Данное письмо сформировано автоматически.</p>
                    </div>
                ";

                $successCount = 0;
                $totalCount = 0;

                foreach ($xml->user as $user) {
                    $recipientEmail = trim((string)$user->email);
                    if (!empty($recipientEmail)) {
                        $totalCount++;
                        try {
                            $mail->addAddress($recipientEmail);
                            if ($mail->send()) {
                                $successCount++;
                            }
                        } catch (Exception $e) {
                            
                        }
                        $mail->clearAddresses();
                    }
                }

                if ($successCount > 0) {
                    $status = "success";
                    $message = "Документ успешно отправлен на $successCount из $totalCount адресов в MailHog!";
                } else {
                    $status = "error";
                    $message = "Не удалось отправить ни одного письма.";
                }
            } catch (Exception $e) {
                $status = "error";
                $message = "Ошибка при инициализации почты: {$mail->ErrorInfo}";
            }
        } else {
            $status = "error";
            $message = "Файл XML пуст или имеет неверную структуру.";
        }
    } else {
        $status = "error";
        $message = "Файл со списком почт ($xmlFile) не найден.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Получение Конституции РБ</title>
    <style>
        :root {
            --rb-red: #CF101A;
            --rb-green: #009640;
            --text: #2d3436;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f2f5;
            background-image: linear-gradient(180deg, #CF101A 0%, #CF101A 15%, #009640 15%, #009640 20%, #f0f2f5 20%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: var(--text);
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            border-top: 4px solid var(--rb-red);
        }

        h2 { text-align: center; margin-bottom: 10px; color: var(--rb-red); font-size: 1.4rem; }
        .subtitle { text-align: center; margin-bottom: 30px; font-size: 0.9rem; color: #636e72; }

        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.85rem; color: #444; }
        
        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #dcdde1;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input:focus { border-color: var(--rb-green); outline: none; }

        button {
            width: 100%;
            padding: 14px;
            background-color: var(--rb-green);
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button:hover { background-color: #007a33; }

        #overlay {
            display: <?= $status ? 'flex' : 'none' ?>;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal {
            background: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            max-width: 350px;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .modal h3 { color: <?= $status == 'success' ? '#009640' : '#CF101A' ?>; margin-top: 0; }
        .modal button { background: var(--rb-red); }
    </style>
</head>
<body>

<div class="container">
    <h2>Запрос Конституции</h2>
    <p class="subtitle">Сервис рассылки актуальной редакции Конституции Республики Беларусь</p>
    
    <form action="" method="POST">
        <div class="form-group">
            <label>ФИО заявителя</label>
            <input type="text" name="fio" required placeholder="Напр. Иванов Иван Иванович">
        </div>

        <div class="form-group">
            <label>Адрес электронной почты</label>
            <input type="email" name="email" required placeholder="mailbox@example.com">
        </div>

        <div class="form-group">
            <label>Регион (область)</label>
            <select name="region">
                <option value="Минск">г. Минск</option>
                <option value="Минская обл.">Минская область</option>
                <option value="Брестская обл.">Брестская область</option>
                <option value="Витебская обл.">Витебская область</option>
                <option value="Гомельская обл.">Гомельская область</option>
                <option value="Гродненская обл.">Гродненская область</option>
                <option value="Могилевская обл.">Могилевская область</option>
            </select>
        </div>

        <button type="submit">Получить документ</button>
    </form>
</div>

<div id="overlay">
    <div class="modal">
        <h3><?= $status == 'success' ? 'Запрос принят' : 'Внимание' ?></h3>
        <p><?= $message ?></p>
        <button onclick="window.location.href=window.location.pathname">Вернуться</button>
    </div>
</div>

</body>
</html>