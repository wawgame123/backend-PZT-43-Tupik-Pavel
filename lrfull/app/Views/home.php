<?php
$queryWithoutPage = $_GET;
unset($queryWithoutPage['page']);
?>
<div class="container">
    <div class="hero-wrapper">
        <section class="hero">
            <div class="hero-content">
                <h1>Учимся зарабатывать на криптовалюте с нуля</h1>
                <p>Онлайн-школа, каталог курсов и профессиональное сообщество.</p>
                <a class="btn-course" href="#catalog">Выбрать курс</a>
            </div>
            <div class="hero-images">
                <img src="image/phone_dark.png" alt="Phone" class="phone phone-back">
                <img src="image/phone_light.png" alt="Phone" class="phone phone-front">
            </div>
        </section>
    </div>

    <section class="learn-section">
        <h2>Как проходит обучение</h2>
        <div class="learn-banner">
            <img src="image/learn.png" alt="Процесс обучения">
            <div class="play-button"></div>
        </div>
        <div class="learn-steps">
            <div class="step-item"><div class="step-header"><span class="step-number">1</span></div><h3>Видеоуроки и онлайн-встречи</h3><p>Доступ остается на весь период обучения.</p></div>
            <div class="step-item"><div class="step-header"><span class="step-number">2</span></div><h3>Домашние задания</h3><p>Практика с обратной связью от эксперта.</p></div>
            <div class="step-item"><div class="step-header"><span class="step-number">3</span></div><h3>Кураторы курсов</h3><p>Помогают разобраться с вопросами и ошибками.</p></div>
            <div class="step-item"><div class="step-header"><span class="step-number">4</span></div><h3>Чат участников</h3><p>Новости, прогнозы, материалы и общение.</p></div>
        </div>
    </section>

    <section class="catalog-section" id="catalog">
        <div class="section-heading">
            <div>
                <h2>Каталог продукции</h2>
                <p>Курсы и услуги загружаются из базы данных.</p>
            </div>
            <a class="btn-reset" href="index.php?action=cart">Открыть корзину</a>
        </div>

        <!-- ЛР No3/No8: GET-форма управляет поиском, сортировкой, фильтрацией и пагинацией каталога. -->
        <form class="filter-form" method="get" action="index.php#catalog">
            <input type="text" name="q" value="<?= e($filters['q']) ?>" placeholder="Поиск курса">
            <select name="category_id">
                <option value="">Все категории</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int)$category['id'] ?>" <?= (string)$category['id'] === (string)$filters['category_id'] ? 'selected' : '' ?>>
                        <?= e($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="level">
                <option value="">Любой уровень</option>
                <?php foreach ($levels as $level): ?>
                    <option value="<?= e($level) ?>" <?= $level === $filters['level'] ? 'selected' : '' ?>><?= e($level) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="sort">
                <option value="newest" <?= $filters['sort'] === 'newest' ? 'selected' : '' ?>>Сначала новые</option>
                <option value="price_asc" <?= $filters['sort'] === 'price_asc' ? 'selected' : '' ?>>Цена по возрастанию</option>
                <option value="price_desc" <?= $filters['sort'] === 'price_desc' ? 'selected' : '' ?>>Цена по убыванию</option>
                <option value="title_asc" <?= $filters['sort'] === 'title_asc' ? 'selected' : '' ?>>По названию</option>
            </select>
            <button class="btn-send" type="submit">Применить</button>
            <a class="btn-reset" href="index.php#catalog">Сбросить</a>
        </form>

        <div class="product-grid">
            <?php foreach ($catalog['items'] as $product): ?>
                <article class="product-card">
                    <img src="<?= e($product['image']) ?>" alt="<?= e($product['title']) ?>">
                    <div class="product-body">
                        <div class="product-meta"><?= e($product['category']) ?> • <?= e($product['level']) ?> • <?= e($product['duration']) ?></div>
                        <h3><?= e($product['title']) ?></h3>
                        <p><?= e($product['description']) ?></p>
                        <div class="product-footer">
                            <strong><?= (int)$product['price'] ?> BYN</strong>
                            <!-- ЛР No4: товар добавляется в корзину, которая хранится в SESSION. -->
                            <form method="post" action="index.php#catalog">
                                <input type="hidden" name="action" value="add_to_cart">
                                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                                <button class="btn-course small" type="submit">В корзину</button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="pagination">
            <?php for ($i = 1; $i <= $catalog['pages']; $i++): ?>
                <?php $pageQuery = http_build_query([...$queryWithoutPage, 'page' => $i]); ?>
                <a class="<?= $i === $catalog['page'] ? 'active' : '' ?>" href="index.php?<?= e($pageQuery) ?>#catalog"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </section>

    <section class="news-section" id="news">
        <div class="section-heading">
            <div>
                <h2>Новости</h2>
                <p>Актуальные обновления школы, каталога и учебной программы.</p>
            </div>
        </div>
        <div class="news-grid">
            <?php foreach ($news as $item): ?>
                <article class="news-card">
                    <div class="date"><?= e(date('d.m.Y', strtotime($item['created_at']))) ?></div>
                    <h3><?= e($item['title']) ?></h3>
                    <p class="review-text"><?= e($item['body']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="contact-section" id="feedback">
        <div class="contact-banner">
            <div class="contact-text">
                <h2>Есть вопросы? Сейчас ответим</h2>
                <p>Оставьте свои контакты</p>
            </div>

            <!-- Старая форма обратной связи: POST в send.php, запись в файл results и отправка письма. -->
            <form class="contact-form" id="ajax-form">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Имя" required>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <input type="tel" name="phone" placeholder="8 (996) 238-56-58" required>
                    </div>
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                </div>
                <div class="form-footer">
                    <p class="policy">Нажимая «Отправить», соглашаюсь с <a href="#">правилами</a></p>
                    <button type="submit" class="btn-send">Отправить</button>
                </div>
            </form>
        </div>
    </section>
</div>

<div id="success-modal" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-icon">✓</div>
        <h2>Заявка принята!</h2>
        <p>Мы получили ваши данные. Копия заявки сохранена в файл и отправлена на почту.</p>
        <button class="btn-modal-close" onclick="closeModal()">Отлично</button>
    </div>
</div>

<script>
    const feedbackForm = document.getElementById('ajax-form');

    if (feedbackForm) {
        feedbackForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('.btn-send');
            submitBtn.disabled = true;
            submitBtn.innerText = 'Отправка...';

            fetch('send.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Отправить';

                    if (data.status === 'success') {
                        document.getElementById('success-modal').classList.add('active');
                        feedbackForm.reset();
                    } else {
                        alert('Ошибка: ' + data.message);
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Отправить';
                    console.error('Error:', error);
                    alert('Произошла ошибка при отправке.');
                });
        });
    }

    function closeModal() {
        document.getElementById('success-modal').classList.remove('active');
    }
</script>
