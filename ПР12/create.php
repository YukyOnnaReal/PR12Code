<?php
require_once __DIR__ . '/includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $mediaPath = null;

    if (empty($title) || empty($content)) {
        $error = 'Заполните заголовок и содержимое записи';
    } elseif (strlen($title) < 5) {
        $error = 'Заголовок должен содержать минимум 5 символов';
    } elseif (strlen($content) < 10) {
        $error = 'Содержимое должно содержать минимум 10 символов';
    }

    if (!$error && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $maxSize = 2 * 1024 * 1024;

        if ($file['size'] > $maxSize) {
            $error = 'Размер файла не должен превышать 2Mb';
        } else {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $file['tmp_name']);
            finfo_close($fileInfo);

            $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($mimeType, $allowedTypes) || !in_array($ext, $allowedExts)) {
                $error = 'Разрешены только изображения JPG, PNG, GIF';
            } else {
                $filename = uniqid('img_', true) . '.' . $ext;
                $uploadPath = UPLOADS_DIR . '/' . $filename;

                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $mediaPath = 'uploads/' . $filename;
                } else {
                    $error = 'Ошибка при загрузке файла';
                }
            }
        }
    }

    if (!$error) {
        $newPost = [
            'id' => generateId(),
            'title' => $title,
            'content' => $content,
            'author_id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'created_at' => date('Y-m-d H:i:s'),
            'media' => $mediaPath ? [$mediaPath] : []
        ];

        $posts = getPosts();
        $posts[] = $newPost;

        if (saveData('posts.json', $posts)) {
            header('Location: post.php?id=' . $newPost['id']);
            exit;
        } else {
            $error = 'Ошибка при сохранении записи';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать запись</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Создать новую запись</h1>
            <nav class="nav">
                <a href="index.php">На главную</a>
                <a href="logout.php">Выход (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="form-container">
            <h2>Новая запись в блоге</h2>
            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Заголовок записи:</label>
                    <input type="text" id="title" name="title" required minlength="5">
                </div>
                <div class="form-group">
                    <label for="content">Содержимое:</label>
                    <textarea id="content" name="content" required minlength="10"></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Изображение (необязательно):</label>
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif">
                    <small>Максимальный размер: 2MB. Форматы: JPG, PNG, GIF</small>
                </div>
                <button type="submit" class="btn btn-primary">Опубликовать запись</button>
            </form>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Мой блог © <?= date('Y') ?> – Практический проект на PHP</p>
        </div>
    </footer>
</body>
</html>