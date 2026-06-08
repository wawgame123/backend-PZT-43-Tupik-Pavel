<div class="container">
    <section class="catalog-section">
        <div class="section-heading">
            <div>
                <h1>Личный кабинет</h1>
                <p>Раздел доступен только авторизованному пользователю.</p>
            </div>
            <a class="btn-reset" href="index.php">На главную</a>
        </div>

        <!-- ЛР No4: авторизация доступа. Контроллер не показывает кабинет без активной сессии. -->
        <div class="panel">
            <h2><?= e($user['name']) ?></h2>
            <p>Email: <?= e($user['email']) ?></p>
            <p>Дата регистрации: <?= e(date('d.m.Y H:i', strtotime($user['created_at']))) ?></p>
        </div>

        <h2 class="section-title">Мои заказы</h2>
        <?php if (!$orders): ?>
            <div class="empty-box">Заказов пока нет.</div>
        <?php else: ?>
            <div class="cart-list">
                <?php foreach ($orders as $order): ?>
                    <div class="cart-row">
                        <div>
                            <strong>Заказ No<?= (int)$order['id'] ?></strong>
                            <span><?= e(date('d.m.Y H:i', strtotime($order['created_at']))) ?></span>
                        </div>
                        <b><?= (int)$order['total'] ?> BYN</b>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>
