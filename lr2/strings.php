<?php
// Переменная для демонстрации интерполяции
$topic = "PHP";

// Инициализация в одинарных кавычках: переменные внутри НЕ обрабатываются
$singleQuoteText = 'Программирование на $topic очень увлекательно';

// Инициализация в двойных кавычках: переменная $topic будет заменена на "PHP"
$doubleQuoteText = "Программирование на $topic очень увлекательно";

// Heredoc: ведет себя как двойные кавычки, удобен для многострочного текста
$heredocText = <<<HTML
Программирование на $topic
очень увлекательно
HTML;

// Nowdoc: ведет себя как одинарные кавычки, переменные НЕ обрабатываются
$nowdocText = <<<'HTML'
Программирование на $topic
очень увлекательно
HTML;

// Вывод результатов инициализации для сравнения
echo "Одинарные кавычки: " . $singleQuoteText . "<br>"; // Выведет: ...на $topic
echo "Двойные кавычки: " . $doubleQuoteText . "<br>"; // Выведет: ...на PHP
echo "Heredoc: " . $heredocText . "<br>";             // Выведет: ...на PHP
echo "Nowdoc: " . $nowdocText . "<br>";           // Выведет: ...на $topic

// Основная строка для работы с функциями
$text = "Программирование на PHP очень увлекательно";

// strlen возвращает количество байт в строке
$length = strlen($text);

// explode разбивает строку на массив, используя пробел как разделитель
$words = explode(" ", $text);

// implode собирает массив обратно в строку с указанным разделителем
$joinedText = implode(" ", $words);

// strpos ищет позицию первого вхождения подстроки
$searchWord = "PHP";
$found = strpos($text, $searchWord) !== false ? "Найдено" : "Не найдено";

// str_replace заменяет все вхождения искомой строки на новую
$replacedText = str_replace("PHP", "JavaScript", $text);

// Вывод обработанных данных
echo "Оригинальная строка: $text <br>";
echo "Длина строки: $length <br>";
echo "Разделение на слова: " . implode(", ", $words) . "<br>";
echo "Поиск слова '$searchWord': $found <br>";
echo "Замена 'PHP' на 'JavaScript': $replacedText <br>";

// strtoupper и strtolower меняют регистр символов (латиница)
echo "Верхний регистр: " . strtoupper($text) . "<br>";
echo "Нижний регистр: " . strtolower($text) . "<br>";

// substr вырезает часть строки: (строка, старт, длина)
echo "Подстрока с 3-го символа, длиной 5: " . substr($text, 3, 5) . "<br>";
?>