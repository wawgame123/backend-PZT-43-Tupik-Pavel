<div class="container">
    <section class="auth-page">
        <div class="auth-panel">
            <div class="section-heading">
                <div>
                    <h1>Регистрация</h1>
                    <p>Форма отправляет данные методом GET и добавляет пользователя в таблицу users.</p>
                </div>
            </div>

            <!-- ЛР No3: регистрация методом GET. Для учебного задания пароль тоже передается GET. -->
            <form class="stack-form" method="get" action="index.php">
                <input type="hidden" name="action" value="register">
                <input type="text" name="name" placeholder="Имя" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <button class="btn-send" type="submit">Зарегистрироваться</button>
            </form>

            <p class="auth-note">Уже есть аккаунт? <a href="index.php?action=login_page">Войти</a></p>
        </div>
    </section>
</div>
