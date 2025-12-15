<?php
require __DIR__ . '/includes/functions.php';

$error = '';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $users = loadData('users.json');
    $foundUser = null;
    
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            $foundUser = $user;
            break;
        }
    }

    if ($foundUser && password_verify($password, $foundUser['password_hash'])) {
        $_SESSION['user_id'] = $foundUser['id'];
        $_SESSION['username'] = $foundUser['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Неверное имя пользователя или пароль';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой блог - Вход</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Мой интернет-блог</h1>
            <nav class="nav">
                <a href="index.php">Главная</a>
                <a href="login.php">Войти</a>
                <a href="register.php">Регистрация</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <h2>Вход в систему</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div>
                <label>Имя пользователя:</label>
                <input type="text" name="username" required>
            </div>
            
            <div>
                <label>Пароль:</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit">Войти</button>
        </form>
        
        <p>Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a></p>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Мой блог © <?php echo date('Y'); ?> – Практический проект на PHP</p>
        </div>
    </footer>
</body>
</html>

<?php
require_once __DIR__ . '/includes/functions.php';

$error = '';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $users = loadData('users.json');
    $foundUser = null;

    foreach ($users as $user) {
        if ($user['username'] === $username) {
            $foundUser = $user;
            break;
        }
    }

    if ($foundUser && password_verify($password, $foundUser['password_hash'])) {
        $_SESSION['user_id'] = $foundUser['id'];
        $_SESSION['username'] = $foundUser['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Неверное имя пользователя или пароль';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <!-- Практическая работа 12.5: подключение стилей -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Вход в блог</h1>
            <nav class="nav">
                <a href="index.php">На главную</a>
                <a href="register.php">Регистрация</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="form-container">
            <h2>Вход в систему</h2>
            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Имя пользователя:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Войти</button>
            </form>
            <p style="margin-top: 20px;">Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a></p>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Мой блог © <?= date('Y') ?> – Практический проект на PHP</p>
        </div>
    </footer>
</body>
</html>