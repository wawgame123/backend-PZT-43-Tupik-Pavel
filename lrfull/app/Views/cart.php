<div class="container">
    <section class="catalog-section">
        <div class="section-heading">
            <div>
                <h1>Корзина</h1>
                <p>Товары хранятся в сессии, а при оформлении заказ и состав заказа записываются в БД.</p>
            </div>
            <a class="btn-reset" href="index.php#catalog">Продолжить выбор</a>
        </div>

        <?php if (!$cartItems): ?>
            <div class="empty-box">Корзина пока пустая.</div>
        <?php else: ?>
            <div class="cart-list">
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-row">
                        <div>
                            <strong><?= e($item['product']['title']) ?></strong>
                            <span>
                                ID товара: <?= (int)$item['product']['id'] ?> •
                                <?= e($item['product']['category']) ?> •
                                <?= e($item['product']['level']) ?>
                            </span>
                            <span><?= (int)$item['quantity'] ?> x <?= (int)$item['product']['price'] ?> BYN</span>
                        </div>
                        <div class="cart-actions">
                            <b><?= (int)$item['sum'] ?> BYN</b>
                            <form method="post" action="index.php">
                                <input type="hidden" name="action" value="remove_from_cart">
                                <input type="hidden" name="product_id" value="<?= (int)$item['product']['id'] ?>">
                                <button class="btn-reset" type="submit">Удалить</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="cart-total">Итого: <?= (int)$cartTotal ?> BYN</div>
            </div>

            <!-- ЛР No8: order_items хранит product_id, цену, категорию, уровень и название купленного товара. -->
            <form class="checkout-form" method="post" action="index.php">
                <input type="hidden" name="action" value="checkout">
                <input type="text" name="name" value="<?= e($user['name'] ?? '') ?>" placeholder="Имя" required>
                <input type="tel" name="phone" placeholder="Телефон" required>
                <input type="email" name="email" value="<?= e($user['email'] ?? '') ?>" placeholder="Email" required>
                <button class="btn-send" type="submit">Оформить заказ</button>
            </form>
        <?php endif; ?>
    </section>
</div>
