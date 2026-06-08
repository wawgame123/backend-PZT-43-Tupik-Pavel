<?php
$projects = [
    [
        'title' => 'Лабораторная 1',
        'folder' => 'lr1',
        'description' => 'Базовые переменные, константы, серверные данные и вывод PHP.',
        'primary' => ['label' => 'Открыть проект', 'href' => 'lr1/z1.php'],
        'links' => [
            ['label' => 'Задание 1', 'href' => 'lr1/z1.php'],
        ],
    ],
    [
        'title' => 'Лабораторная 2',
        'folder' => 'lr2',
        'description' => 'Операторы, функции, массивы и строки, собранные через общий файл.',
        'primary' => ['label' => 'Открыть проект', 'href' => 'lr2/main.php'],
        'links' => [
            ['label' => 'Главный файл', 'href' => 'lr2/main.php'],
            ['label' => 'Операторы', 'href' => 'lr2/operators.php'],
            ['label' => 'Функции', 'href' => 'lr2/functions.php'],
            ['label' => 'Массивы', 'href' => 'lr2/arrays.php'],
            ['label' => 'Строки', 'href' => 'lr2/strings.php'],
        ],
    ],
    [
        'title' => 'Лабораторная 3',
        'folder' => 'lr3',
        'description' => 'Набор отдельных PHP-заданий, формы регистрации, авторизации, фидбека и заказа.',
        'primary' => ['label' => 'Открыть проект', 'href' => 'lr3/index.php'],
        'links' => [
            ['label' => 'Все задания', 'href' => 'lr3/index.php'],
            ['label' => 'Задание 1', 'href' => 'lr3/1.php'],
            ['label' => 'Задание 2', 'href' => 'lr3/2.php'],
            ['label' => 'Задание 3', 'href' => 'lr3/3.php'],
            ['label' => 'Задание 4', 'href' => 'lr3/4.php'],
            ['label' => 'Задание 5', 'href' => 'lr3/5.php'],
            ['label' => 'Регистрация', 'href' => 'lr3/reg.php'],
            ['label' => 'Авторизация', 'href' => 'lr3/auth.php'],
            ['label' => 'Фидбек', 'href' => 'lr3/fb.php'],
            ['label' => 'Заказ', 'href' => 'lr3/ticket.php'],
        ],
    ],
    [
        'title' => 'Лабораторная 4',
        'folder' => 'lr4',
        'description' => 'Сессии, cookies и простая авторизация с переходом в защищенную страницу.',
        'primary' => ['label' => 'Открыть проект', 'href' => 'lr4/index.php'],
        'links' => [
            ['label' => 'Авторизация', 'href' => 'lr4/index.php'],
            ['label' => 'Кабинет', 'href' => 'lr4/second.php'],
        ],
    ],
    [
        'title' => 'Лабораторная 5',
        'folder' => 'lr5',
        'description' => 'Работа с файлами, каталогами, чтением, записью и блокировками.',
        'primary' => ['label' => 'Открыть проект', 'href' => 'lr5/index.php'],
        'links' => [
            ['label' => 'Файловые операции', 'href' => 'lr5/index.php'],
        ],
    ],
    [
        'title' => 'Лабораторная 5-6',
        'folder' => 'lr5-6',
        'description' => 'Верстка Money Fest с формой отправки заявки и сохранением результатов.',
        'primary' => ['label' => 'Открыть проект', 'href' => 'lr5-6/index.html'],
        'links' => [
            ['label' => 'Главная страница', 'href' => 'lr5-6/index.html'],
            ['label' => 'Обработчик формы', 'href' => 'lr5-6/send.php'],
        ],
    ],
    [
        'title' => 'Лабораторная 6',
        'folder' => 'Lr6',
        'description' => 'Отправка писем через mail() и PHPMailer, работа с XML и PDF-файлом.',
        'primary' => ['label' => 'Открыть проект', 'href' => 'Lr6/index.html'],
        'links' => [
            ['label' => 'Выбор способа отправки', 'href' => 'Lr6/index.html'],
            ['label' => 'mail()', 'href' => 'Lr6/indexmail.php'],
            ['label' => 'PHPMailer', 'href' => 'Lr6/indexMAiler.php'],
            ['label' => 'XML', 'href' => 'Lr6/emails.xml'],
            ['label' => 'PDF', 'href' => 'Lr6/constitution.pdf'],
        ],
    ],
    [
        'title' => 'Итоговый проект',
        'folder' => 'lrfull',
        'description' => 'Полная версия Money Fest на PHP: MVC, каталог, авторизация, корзина, заказы и SQLite.',
        'primary' => ['label' => 'Открыть проект', 'href' => 'lrfull/index.php'],
        'links' => [
            ['label' => 'Главная', 'href' => 'lrfull/index.php'],
            ['label' => 'Вход', 'href' => 'lrfull/index.php?action=login_page'],
            ['label' => 'Регистрация', 'href' => 'lrfull/index.php?action=register_page'],
            ['label' => 'Корзина', 'href' => 'lrfull/index.php?action=cart'], 
        ],
    ],
];

function isAvailable(string $href): bool
{
    $path = parse_url($href, PHP_URL_PATH) ?: $href;
    return is_file(__DIR__ . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path));
}

function siteUrl(string $href): string
{
    $siteBase = '/Back/backend/';

    if (preg_match('~^(https?://|mailto:|#)~', $href)) {
        return $href;
    }

    return $siteBase . ltrim($href, '/');
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Навигация по проектам</title>
    <style>
        :root {
            --bg: #f4f7fb;
            --surface: #ffffff;
            --text: #17202a;
            --muted: #5f6f81;
            --line: #dce4ee;
            --accent: #1f7a5a;
            --accent-dark: #15573f;
            --accent-soft: #e4f4ed;
            --warning: #a15c18;
            --warning-bg: #fff1dc;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.5;
        }

        .page {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
            padding: 32px 0 48px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
            padding: 18px 20px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--surface);
        }

        .brand {
            display: grid;
            gap: 4px;
        }

        .brand strong {
            font-size: 24px;
        }

        .brand span {
            color: var(--muted);
            font-size: 14px;
        }

        .quick-nav {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 8px;
        }

        .quick-nav a,
        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .quick-nav a {
            border: 1px solid var(--line);
            color: var(--text);
            background: #fbfdff;
        }

        .button {
            border: 1px solid var(--accent);
            color: #ffffff;
            background: var(--accent);
        }

        .button:hover {
            background: var(--accent-dark);
        }

        .intro {
            margin-bottom: 24px;
            padding: 24px;
            border-radius: 8px;
            background: #17202a;
            color: #ffffff;
        }

        .intro h1 {
            margin: 0 0 8px;
            font-size: clamp(30px, 5vw, 52px);
            line-height: 1.05;
        }

        .intro p {
            max-width: 760px;
            margin: 0;
            color: #d7e1ea;
            font-size: 18px;
        }

        .project-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
            gap: 16px;
        }

        .project-card {
            display: flex;
            flex-direction: column;
            min-height: 330px;
            padding: 20px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--surface);
        }

        .card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .project-card h2 {
            margin: 0;
            font-size: 22px;
        }

        .folder {
            display: inline-flex;
            align-items: center;
            min-height: 30px;
            padding: 5px 9px;
            border-radius: 999px;
            color: var(--accent-dark);
            background: var(--accent-soft);
            font-size: 13px;
            font-weight: 700;
        }

        .project-card p {
            margin: 0 0 16px;
            color: var(--muted);
        }

        .links {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 16px 0 0;
            padding: 0;
            list-style: none;
        }

        .links a {
            display: inline-flex;
            min-height: 34px;
            align-items: center;
            padding: 7px 10px;
            border: 1px solid var(--line);
            border-radius: 6px;
            color: var(--text);
            background: #fbfdff;
            font-size: 13px;
            text-decoration: none;
        }

        .links a:hover,
        .quick-nav a:hover {
            border-color: var(--accent);
            color: var(--accent-dark);
        }

        .card-actions {
            margin-top: auto;
            padding-top: 16px;
        }

        .missing {
            display: inline-flex;
            min-height: 34px;
            align-items: center;
            padding: 7px 10px;
            border-radius: 6px;
            color: var(--warning);
            background: var(--warning-bg);
            font-size: 13px;
            font-weight: 700;
        }

        @media (max-width: 720px) {
            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .quick-nav {
                justify-content: flex-start;
            }

            .project-card {
                min-height: auto;
            }
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="topbar" aria-label="Быстрая навигация">
            <div class="brand">
                <strong>Backend проекты</strong>
                <span>Единая точка входа для всех лабораторных и итогового проекта</span>
            </div>
            <nav class="quick-nav">
                <?php foreach ($projects as $project): ?>
                    <a href="<?= htmlspecialchars(siteUrl($project['primary']['href']), ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($project['folder'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </section>

        <section class="intro">
            <h1>Все проекты в одном месте</h1>
            <p>Открой нужную лабораторную, отдельное задание или итоговый проект прямо с этой страницы.</p>
        </section>

        <section class="project-grid" aria-label="Список проектов">
            <?php foreach ($projects as $project): ?>
                <article class="project-card" id="<?= htmlspecialchars($project['folder'], ENT_QUOTES, 'UTF-8') ?>">
                    <div class="card-head">
                        <h2><?= htmlspecialchars($project['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                        <span class="folder"><?= htmlspecialchars($project['folder'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <p><?= htmlspecialchars($project['description'], ENT_QUOTES, 'UTF-8') ?></p>

                    <ul class="links">
                        <?php foreach ($project['links'] as $link): ?>
                            <li>
                                <?php if (isAvailable($link['href'])): ?>
                                    <a href="<?= htmlspecialchars(siteUrl($link['href']), ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?>
                                    </a>
                                <?php else: ?>
                                    <span class="missing">
                                        <?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?> не найден
                                    </span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="card-actions">
                        <?php if (isAvailable($project['primary']['href'])): ?>
                            <a class="button" href="<?= htmlspecialchars(siteUrl($project['primary']['href']), ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($project['primary']['label'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        <?php else: ?>
                            <span class="missing">Стартовый файл не найден</span>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

    </main>
</body>
</html>
