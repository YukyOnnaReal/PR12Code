<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/User.php';

$error = '';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = 'Заполните все обязательные поля';
    } elseif (strlen($username) < 3) {
        $error = 'Имя пользователя должно содержать минимум 3 символа';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль должен содержать минимум 6 символов';
    } elseif ($password !== $password_confirm) {
        $error = 'Пароли не совпадают';
    } else {
        $users = loadData('users.json');
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                $error = 'Пользователь с таким именем уже существует';
                break;
            }
        }

        if (!$error) {
            $user = new User($username, $email, $password);
            $users[] = $user->toArray();

            if (saveData('users.json', $users)) {
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['username'] = $user->getUsername();
                header('Location: index.php');
                exit;
            } else {
                $error = 'Ошибка при сохранении данных';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Регистрация в блоге</h1>
            <nav class="nav">
                <a href="index.php">На главную</a>
                <a href="login.php">Вход</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="form-container">
            <h2>Создание нового аккаунта</h2>
            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Имя пользователя:</label>
                    <input type="text" id="username" name="username" required minlength="3">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="password_confirm">Подтверждение пароля:</label>
                    <input type="password" id="password_confirm" name="password_confirm" required minlength="6">
                </div>
                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
            </form>
            <p style="margin-top: 20px;">Уже есть аккаунт? <a href="login.php">Войдите</a></p>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Мой блог © <?= date('Y') ?> – Практический проект на PHP</p>
        </div>
    </footer>
</body>
</html>