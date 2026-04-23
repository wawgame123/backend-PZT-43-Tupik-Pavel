<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = htmlspecialchars($_POST['city'] ?? '');
    $address = htmlspecialchars($_POST['address'] ?? '');
    $payment = htmlspecialchars($_POST['payment'] ?? '');

    if ($city && $address && $payment) {
        echo "Ваш заказ оформлен. Доставка в $city, по адресу: $address. Способ оплаты: $payment.";
    } else {
        echo "Проверьте правильность введенных данных.";
    }
}
?>

<form method="POST">
    <input type="text" name="city" placeholder="Город доставки" required><br><br>
    <input type="text" name="address" placeholder="Улица, дом, квартира" required><br><br>
    
    <p>Способ оплаты:</p>
    <label><input type="radio" name="payment" value="Наличными" required> Наличными курьеру</label><br>
    <label><input type="radio" name="payment" value="Картой"> Картой онлайн</label><br><br>
    
    <button type="submit">Подтвердить заказ</button>
</form>