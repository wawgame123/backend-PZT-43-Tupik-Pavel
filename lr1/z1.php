<?php
// переменные и константы
$drobi = 1.7;
$name = "Павел";
$age = 17;
$_count = 10;
define("USER_NAME", "Андрей");
$user_age = 67;
const PZT = 43;
// Вывод значений
echo "дробь: $drobi <br>";
echo "группа: ПЗТ-" . PZT . "<br>";
echo "Имя: $name <br>";
echo "Возраст: $age <br>";
echo "Количество: $_count <br>";
echo "Имя пользователя: " . USER_NAME . "<br>";
echo "Возраст пользователя: $user_age <br>";
// Изменение значения переменной
$name = "Паша";
echo "Обновленное имя: $name <br>";
// Операции с переменными
$number = 10;
$result = $number * 2;
echo "Результат: $result <br>";
echo $_SERVER['HTTP_USER_AGENT']; // Браузер пользователя
echo $_SERVER['REMOTE_ADDR'];     // IP адрес
echo $_SERVER['SCRIPT_NAME'];    // Путь к скрипту
echo phpinfo();
?>