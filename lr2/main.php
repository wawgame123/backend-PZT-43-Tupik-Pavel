<?php
// Подключаем наши модули
include 'operators.php';
include 'functions.php';
include 'arrays.php';
include 'strings.php';
echo "--- Результаты работы операторов ---\n <br>";
echo "Остаток от деления 10 на 3: " . $mod . "\n <br>" ;
echo "Статус проверки x: " . $status . "\n <br>" ;
echo "Результат цикла do-while (j): " . $j . "\n <br>";
echo "\n--- Результаты работы функций ---\n <br>" ;
echo "Дата через неделю: " . $nextWeekDate . "\n <br>" ;
echo "Рандомное число: " . $randomNumber . "\n <br>" ;
echo "\n--- Работа с массивами ---\n <br>" ;
echo "Все города: " . implode(", ", $allCities) . "\n <br> ";
