<?php
$products = [
    ['name' => 'Ноутбук', 'category' => 'electronics', 'price' => 50000],
    ['name' => 'Смартфон', 'category' => 'electronics', 'price' => 20000],
    ['name' => 'Футболка', 'category' => 'clothing', 'price' => 1500],
    ['name' => 'Джинсы', 'category' => 'clothing', 'price' => 3000]
];

$category_filter = $_GET['category'] ?? 'all';

echo "<form method='GET'>";
echo "<select name='category'>";
echo "<option value='all' " . ($category_filter === 'all' ? 'selected' : '') . ">Все категории</option>";
echo "<option value='electronics' " . ($category_filter === 'electronics' ? 'selected' : '') . ">Электроника</option>";
echo "<option value='clothing' " . ($category_filter === 'clothing' ? 'selected' : '') . ">Одежда</option>";
echo "</select> ";
echo "<button type='submit'>Отфильтровать</button>";
echo "</form><br>";

echo "<ul>";
foreach ($products as $product) {
    if ($category_filter === 'all' || $product['category'] === $category_filter) {
        echo "<li>{$product['name']} - {$product['price']} руб.</li>";
    }
}
echo "</ul>";
?>