<?php
// Массив городов (индексированный)
$cities = ['Минск', 'Гомель', 'Витебск'];

// Пользовательский профиль (ассоциативный массив)
// В ассоциативных массивах вместо чисел используются строковые ключи
$userProfile = [
    'username' => 'Alex',
    'email' => 'alex@example.com',
    'status' => 'active'
];

// Добавление элементов и объединение массивов
array_push($cities, 'Брест');
$citiesCount = count($cities);
$moreCities = ['Гродно', 'Могилев'];
$allCities = array_merge($cities, $moreCities);

// Проверка существования ключа в ассоциативном массиве
$hasEmail = array_key_exists('email', $userProfile);

// Получение всех ключей и всех значений отдельно
$profileKeys = array_keys($userProfile);
$profileValues = array_values($userProfile);

// Функции для работы с массивами

// Сортировка индексированного массива по алфавиту
function sortArrayAsc($array) {
    sort($array);
    return $array;
}

// Сортировка индексированного массива по убыванию
function sortArrayDesc($array) {
    rsort($array);
    return $array;
}

// Сортировка ассоциативного массива по значению (сохраняет ключи)
function sortAssocByValue($array) {
    asort($array);
    return $array;
}

// Сортировка ассоциативного массива по ключу
function sortAssocByKey($array) {
    ksort($array);
    return $array;
}

// Поиск элемента в массиве
function searchInArray($array, $value) {
    return in_array($value, $array);
}

// Создание многомерного массива
$multiArray = [
    'cities' => $allCities,
    'users' => [
        $userProfile,
        ['username' => 'Maria', 'email' => 'maria@example.com', 'status' => 'inactive']
    ]
];

// Сортировка городов
$multiArray['cities'] = sortArrayAsc($multiArray['cities']);

// Поиск города
$searchCity = 'Гомель';
$foundCity = searchInArray($multiArray['cities'], $searchCity) ? "Найдено" : "Не найдено";

// Вывод результатов
echo "Работа с одномерным массивом\n <br>";
echo "Все города: " . implode(", ", $allCities) . "\n <br>";
echo "Сортировка по алфавиту: " . implode(", ", sortArrayAsc($allCities)) . "\n <br>";
echo "Поиск города '$searchCity': " . $foundCity . "\n <br>";

echo "\nРабота с ассоциативным массивом (Профиль)\n <br>";
echo "Ключи профиля: " . implode(", ", $profileKeys) . "<br>";
echo "Наличие email: " . ($hasEmail ? "Да" : "Нет") . "<br>";

// Пример сортировки ассоциативного массива по ключам (status, email, username -> email, status, username)
$sortedProfile = sortAssocByKey($userProfile);
echo "Профиль, отсортированный по ключам: <br>";
foreach ($sortedProfile as $key => $value) {
    echo "[$key] => $value <br>";
}

echo "\nРабота с многомерным массивом (Пользователи)\n <br>";
foreach ($multiArray['users'] as $index => $profile) {
    echo "Пользователь #" . ($index + 1) . ":<br>";
    foreach ($profile as $key => $value) {
        echo "- $key: $value <br>";
    }
    echo "<br>";
}

echo "\nСписок городов из многомерного массива\n <br>";
foreach ($multiArray['cities'] as $city) {
    echo "• " . $city . "<br>";
}
?>