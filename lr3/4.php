<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $size = htmlspecialchars($_POST['size'] ?? '');
    $sauce = htmlspecialchars($_POST['sauce'] ?? '');
    $toppings = $_POST['toppings'] ?? [];

    echo "Заказ принят, $name!<br>";
    echo "Размер: $size<br>";
    echo "Соус: $sauce<br>";
    
    if (!empty($toppings)) {
        echo "Добавки: " . implode(', ', array_map('htmlspecialchars', $toppings)) . "<br>";
    }
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Ваше имя" required><br><br>
    
    <p>Размер пиццы:</p>
    <label><input type="radio" name="size" value="Маленькая" required> Маленькая</label><br>
    <label><input type="radio" name="size" value="Средняя"> Средняя</label><br>
    <label><input type="radio" name="size" value="Большая"> Большая</label><br><br>
    
    <p>Дополнительные ингредиенты:</p>
    <label><input type="checkbox" name="toppings[]" value="Сыр"> Сыр бортик</label><br>
    <label><input type="checkbox" name="toppings[]" value="Грибы"> Пепперони</label><br>
    <label><input type="checkbox" name="toppings[]" value="Оливки"> Халапеньо</label><br><br>
    
    <p>Выбор соуса:</p>
    <select name="sauce">
        <option value="Томатный">Томатный</option>
        <option value="Чесночный">Чесночный</option>
        <option value="Сырный">Сырный</option>
    </select><br><br>
    
    <button type="submit">Сделать заказ</button>
</form>