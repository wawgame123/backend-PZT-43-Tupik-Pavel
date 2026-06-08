<div class="container">
    <section class="auth-page">
        <div class="auth-panel">
            <div class="section-heading">
                <div>
                    <h1>Вход</h1>
                    <p>Аутентификация проверяет пользователя из БД и сохраняет его id в сессии.</p>
                </div>
            </div>

            <!-- ЛР No4: аутентификация пользователя + COOKIE remember_token. -->
            <form class="stack-form" method="post" action="index.php">
                <input type="hidden" name="action" value="login">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <label class="check-row"><input type="checkbox" name="remember" value="1"> Запомнить меня через cookie</label>
                <button class="btn-send" type="submit">Войти</button>
            </form>

            <p class="auth-note">Нет аккаунта? <a href="index.php?action=register_page">Зарегистрироваться</a></p>
        </div>
    </section>
</div>
