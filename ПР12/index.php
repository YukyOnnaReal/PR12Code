<?php
require_once __DIR__ . '/includes/functions.php';

$posts = getPosts();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой блог</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Мой интернет-блог</h1>
            <nav class="nav">
                <a href="index.php">Главная</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="create.php">Создать пост</a>
                    <a href="logout.php">Выход (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
                <?php else: ?>
                    <a href="login.php">Войти</a>
                    <a href="register.php">Регистрация</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container">
        <?php if (empty($posts)): ?>
            <div class="empty-state">
                <p>Пока здесь нет ни одной записи.</p>
            </div>
        <?php else: ?>
            <div class="posts-list">
                <?php foreach ($posts as $post): ?>
                    <article class="post-card">
                        <h2>
                            <a href="post.php?id=<?= $post['id'] ?>">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </h2>
                        <div class="post-meta">
                            Автор: <?= htmlspecialchars($post['username']) ?? 'Неизвестен' ?> |
                            <?= date('d.m.Y H:i', strtotime($post['created_at'])) ?>
                        </div>
                        <p class="post-preview">
                            <?= mb_substr($post['content'], 0, 150) . (mb_strlen($post['content']) > 150 ? '...' : '') ?>
                        </p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Мой блог © <?= date('Y') ?> – Практический проект на PHP</p>
        </div>
    </footer>
</body>
</html>