<?php

require_once __DIR__ . '/functions.php';

$db = initDatabase();
$post = initPost($db);
$imageHandler = initImageHandler();

// Príklad použitia
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $imagePath = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imagePath = $imageHandler->uploadImage($_FILES['image']);
    }

    $post->createPost($title, $content, $imagePath);

    header("Location: index.php");
    exit;
}

$posts = $post->getAllPosts();
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Jednoduchý Blog</title>
</head>
<body>
<h1>Pridaj nový článok</h1>
<form method="post" enctype="multipart/form-data">
    <div>
        <label for="title">Názov:</label>
        <input type="text" id="title" name="title" required>
    </div>
    <div>
        <label for="content">Obsah:</label>
        <textarea id="content" name="content" required></textarea>
    </div>
    <div>
        <label for="image">Obrázok:</label>
        <input type="file" id="image" name="image">
    </div>
    <button type="submit">Uložiť</button>
</form>

<h2>Články</h2>
<?php foreach ($posts as $postItem): ?>
    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
        <h3><?= htmlspecialchars($postItem['title']) ?></h3>
        <p><?= nl2br(htmlspecialchars($postItem['content'])) ?></p>
        <?php if ($postItem['image_path']): ?>
            <img src="http://localhost/testovanie-sw-integracny-testing-2025<?= htmlspecialchars($postItem['image_path']) ?>" alt="Obrázok článku" style="max-width: 200px;">
        <?php endif; ?>
        <p><small>Vytvorené: <?= $postItem['created_at'] ?></small></p>
    </div>
<?php endforeach; ?>
</body>
</html>