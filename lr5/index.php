<?php

// Конфигурация путей
$dirPath = 'my_test_folder';
$filePath = $dirPath . '/example.txt';
$message = "Выберите действие ниже.";

// Проверяем, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Создание каталога (mkdir)
    if (isset($_POST['create_dir'])) {
        if (!is_dir($dirPath)) {
            mkdir($dirPath); // Создает новую директорию
            $message = "Каталог создан.";
        } else {
            $message = "Каталог уже существует.";
        }
    }

    // 2. Проверка существования файла (file_exists)
    if (isset($_POST['check_exists'])) {
        if (file_exists($filePath)) { // Проверяет наличие файла по указанному пути
            $message = "Файл '$filePath' существует.";
        } else {
            $message = "Файл не найден.";
        }
    }

    // 3. Получение свойств файла (stat)
    if (isset($_POST['check_stats'])) {
        if (file_exists($filePath)) {
            $stats = stat($filePath); // Получает статистику файла
            $size = $stats['size']; // Размер в байтах
            $mtime = date("d.m.Y H:i:s", $stats['mtime']); // Время последнего изменения
            $mode = decoct($stats['mode'] & 0777); // Права доступа (например, 644)
            
            $message = "Свойства файла:<br>";
            $message .= "Размер: $size байт<br>";
            $message .= "Изменен: $mtime<br>";
            $message .= "Права (mode): $mode";
        } else {
            $message = "Файл не найден для получения свойств.";
        }
    }

    // 4. Эксклюзивная блокировка (LOCK_EX) — для записи
    if (isset($_POST['lock_ex'])) {
        if (file_exists($filePath)) {
            $handle = fopen($filePath, 'r+'); // Открываем для чтения и записи
            if (flock($handle, LOCK_EX)) { // Устанавливаем исключительную блокировку
                $message = "Файл успешно заблокирован для записи (LOCK_EX). Никто другой не может получить доступ.";
            }
            fclose($handle); 
        } else {
            $message = "Файл не найден для блокировки.";
        }
    }

    // 5. Разделяемая блокировка (LOCK_SH) — для чтения
    if (isset($_POST['lock_sh'])) {
        if (file_exists($filePath)) {
            $handle = fopen($filePath, 'r'); // Открытие файла для чтения
            if (flock($handle, LOCK_SH)) { // Устанавливаем блокировку совместного доступа
                $message = "Файл заблокирован для чтения (LOCK_SH). Другие могут читать, но не могут писать.";
            }
            fclose($handle);
        } else {
            $message = "Файл не найден.";
        }
    }

    // 6. Проверка признака конца файла (feof)
    if (isset($_POST['check_eof'])) {
        if (file_exists($filePath)) {
            $handle = fopen($filePath, 'r');
            $atStart = feof($handle) ? "Да" : "Нет"; 
            fread($handle, filesize($filePath) + 1); 
            $atEnd = feof($handle) ? "Да" : "Нет"; 
            $message = "EOF в начале: $atStart. EOF после вычитывания всего файла: $atEnd.";
            fclose($handle);
        } else {
            $message = "Файл не найден.";
        }
    }

    // 7. Запись данных (fopen + fwrite + fclose)
    if (isset($_POST['write_file'])) {
        if (is_dir($dirPath)) {
            $handle = fopen($filePath, 'w'); 
            if ($handle) {
                fwrite($handle, "Тестовая запись: " . date('H:i:s')); 
                fclose($handle); 
                $message = "Данные записаны (fopen + fwrite + fclose).";
            }
        } else {
            $message = "Сначала создайте каталог.";
        }
    }

    // 8. Чтение данных (fopen + fread + fclose)
    if (isset($_POST['read_file'])) {
        if (file_exists($filePath)) {
            $handle = fopen($filePath, 'r'); 
            $content = fread($handle, 1024); 
            fclose($handle);
            $message = "Прочитано: " . htmlspecialchars($content);
        } else {
            $message = "Файл не найден.";
        }
    }

    // 9. Удаление конкретного файла (unlink)
    if (isset($_POST['delete_file'])) {
        if (file_exists($filePath)) {
            unlink($filePath); 
            $message = "Файл удален (unlink).";
        } else {
            $message = "Файл не существует.";
        }
    }

    // 10. Полная очистка (unlink + rmdir)
    if (isset($_POST['delete_all'])) {
        if (file_exists($filePath)) unlink($filePath);
        if (is_dir($dirPath)) {
            rmdir($dirPath); 
        }
        $message = "Каталог и файлы очищены.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление файловой системой</title>
    <style>
        body { font-family: sans-serif; margin: 30px; line-height: 1.5; }
        .status { background: #eee; padding: 15px; border-radius: 4px; margin-bottom: 20px; font-weight: bold; }
        .group { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 4px; }
        .group-title { font-size: 0.9em; color: #666; margin-bottom: 8px; text-transform: uppercase; }
        button { padding: 8px 12px; cursor: pointer; margin-right: 5px; margin-bottom: 5px; }
        .danger { background: #ff4444; color: white; border: none; }
        .special { background: #44aaff; color: white; border: none; }
        .info { background: #2ecc71; color: white; border: none; }
    </style>
</head>
<body>

    <div class="status">
        Статус: <br> <?php echo $message; ?>
    </div>

    <form method="post">
        
        <div class="group">
            <div class="group-title">Базовые операции</div>
            <button type="submit" name="create_dir">Создать папку (mkdir)</button>
            <button type="submit" name="check_exists">Проверить наличие (file_exists)</button>
            <button type="submit" name="check_stats" class="info">Свойства файла (stat)</button>
            <button type="submit" name="delete_file" class="danger">Удалить файл (unlink)</button>
        </div>

        <div class="group">
            <div class="group-title">Работа с содержимым</div>
            <button type="submit" name="write_file">Открыть и Записать (fopen/fwrite)</button>
            <button type="submit" name="read_file">Открыть и Прочитать (fopen/fread)</button>
        </div>

        <div class="group">
            <div class="group-title">Специфические функции</div>
            <button type="submit" name="lock_ex" class="special">Блокировка LOCK_EX (flock)</button>
            <button type="submit" name="lock_sh" class="special">Блокировка LOCK_SH (flock)</button>
            <button type="submit" name="check_eof" class="special">Проверить конец файла (feof)</button>
        </div>

        <button type="submit" name="delete_all">Очистить всё</button>
    </form>

</body>
</html>