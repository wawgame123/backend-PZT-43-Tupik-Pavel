<?php
if (isset($_GET['name']) && isset($_GET['age'])) {
    $name = htmlspecialchars($_GET['name']);
    $age = (int)$_GET['age'];
    echo "Привет, $name! Тебе $age лет.";
} else {
    echo "<a href='?name=Павел&age=17'>Передать тестовые данные</a>";
}
?>