<?php

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Post.php';
require_once __DIR__ . '/classes/ImageHandler.php';

use Blog\Database;
use Blog\Post;
use Blog\ImageHandler;

function initDatabase(): Database
{
    $config = require __DIR__ . '/../config/database.php';
    return new Database($config);
}

function initPost(Database $db): Post
{
    return new Post($db);
}

function initImageHandler(): ImageHandler
{
    // Použijeme relatívnu cestu pre web
    return new ImageHandler(
        __DIR__ . '/../uploads',  // Absolútna cesta pre server
        '/uploads'                // Relatívna cesta pre web
    );
}