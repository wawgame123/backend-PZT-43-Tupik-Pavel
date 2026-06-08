<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Money Fest') ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="site-header">
        <div class="promo-header">
            <div class="tapes-wrapper">
                <div class="tape tape-1">Черная пятница • Черная пятница</div>
                <div class="tape tape-2">Черная пятница • Черная пятница</div>
            </div>
            <div class="promo-text">-30% на все курсы</div>
        </div>

        <div class="container">
            <header>
                <a class="logo-section" href="index.php" aria-label="Money Fest">
                    <div class="logo">
                        <div class="logo-row">MON<div class="e-strips"><div class="strip"></div><div class="strip"></div><div class="strip"></div></div>Y</div>
                        <div class="logo-row bottom">F<div class="e-strips"><div class="strip"></div><div class="strip"></div><div class="strip"></div></div>ST</div>
                    </div>
                </a>

                <nav id="nav-menu">
                    <ul>
                        <li><a href="index.php">Главная</a></li>
                        <li><a href="index.php#catalog">Курсы</a></li>
                        <li><a href="index.php#news">Новости</a></li>
                        <li><a href="index.php?action=cart">Корзина</a></li>
                        <?php if (!empty($user)): ?>
                            <li class="mobile-only"><a href="index.php?action=cabinet">Личный кабинет</a></li>
                            <li class="mobile-only"><a href="index.php?action=logout">Выйти</a></li>
                        <?php else: ?>
                            <li class="mobile-only"><a href="index.php?action=login_page">Вход</a></li>
                            <li class="mobile-only"><a href="index.php?action=register_page">Регистрация</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>

                <div class="header-actions">
                    <a class="btn-cart" href="index.php?action=cart">Корзина <span><?= (int)($cartCount ?? 0) ?></span></a>

                    <?php if (!empty($user)): ?>
                        <a class="btn-cabinet" href="index.php?action=cabinet"><?= e($user['name']) ?></a>
                        <a class="btn-link" href="index.php?action=logout">Выйти</a>
                    <?php else: ?>
                        <a class="btn-link" href="index.php?action=login_page">Вход</a>
                        <a class="btn-cabinet" href="index.php?action=register_page">Регистрация</a>
                    <?php endif; ?>

                    <button class="burger-menu" type="button" onclick="toggleMenu()" aria-label="Меню">
                        <span class="burger-line"></span><span class="burger-line"></span><span class="burger-line"></span>
                    </button>
                </div>
            </header>
        </div>
    </div>

    <div class="container page-notices">
        <?php foreach (($messages ?? []) as $message): ?>
            <div class="notice"><?= e($message) ?></div>
        <?php endforeach; ?>
    </div>

    <?= $content ?>

    <footer class="footer">
        <div class="container">
            <div class="scroll-wrapper">
                <button class="btn-scroll-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Наверх"></button>
            </div>
            <div class="footer-top-grid">
                <div class="footer-col brand-col">
                    <div class="logo white">
                        <div class="logo-row">MON<div class="e-strips"><div class="strip"></div><div class="strip"></div><div class="strip"></div></div>Y</div>
                        <div class="logo-row bottom">F<div class="e-strips"><div class="strip"></div><div class="strip"></div><div class="strip"></div></div>ST</div>
                    </div>
                    <div class="social-links">
                        <a href="#"><img src="image/vk.png" alt="vk"></a>
                        <a href="#"><img src="image/yt.png" alt="youtube"></a>
                        <a href="#"><img src="image/tg.png" alt="telegram"></a>
                    </div>
                </div>
                <div class="footer-col courses-col">
                    <h4>ВСЕ КУРСЫ</h4>
                    <ul>
                        <li><a href="index.php#catalog">Криптоинвестиции: успешный старт</a></li>
                        <li><a href="index.php#catalog">Мастерская трейдинга</a></li>
                        <li><a href="index.php#catalog">Индивидуальное сопровождение</a></li>
                    </ul>
                </div>
                <div class="footer-col menu-col">
                    <ul>
                        <li><a href="index.php">Главная</a></li>
                        <li><a href="index.php#catalog">Курсы</a></li>
                        <li><a href="index.php?action=cart">Корзина</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="footer-copyright"><p>© ООО «Манифест»</p></div>
                <div class="legal-links">
                    <a href="#">Политика конфиденциальности</a>
                    <a href="#">Договор-оферта</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            document.getElementById('nav-menu').classList.toggle('active');
        }
    </script>
</body>
</html>
