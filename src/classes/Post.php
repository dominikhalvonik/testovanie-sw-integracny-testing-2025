<?php

namespace Blog;

class Post
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->createTable();
    }

    private function createTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            image_path VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        $this->db->execute($sql);
    }

    public function createPost(string $title, string $content, ?string $imagePath = null): bool
    {
        $sql = "INSERT INTO posts (title, content, image_path) VALUES (:title, :content, :image_path)";
        $params = [
            ':title' => $title,
            ':content' => $content,
            ':image_path' => $imagePath
        ];

        return $this->db->execute($sql, $params);
    }

    public function getPost(int $id): ?array
    {
        $sql = "SELECT * FROM posts WHERE id = :id";
        $stmt = $this->db->query($sql, [':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function getAllPosts(): array
    {
        $sql = "SELECT * FROM posts ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function updatePost(int $id, string $title, string $content, ?string $imagePath = null): bool
    {
        $sql = "UPDATE posts SET title = :title, content = :content, image_path = :image_path WHERE id = :id";
        $params = [
            ':id' => $id,
            ':title' => $title,
            ':content' => $content,
            ':image_path' => $imagePath
        ];

        return $this->db->execute($sql, $params);
    }

    public function deletePost(int $id): bool
    {
        $sql = "DELETE FROM posts WHERE id = :id";
        return $this->db->execute($sql, [':id' => $id]);
    }
}