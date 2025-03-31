<?php

namespace Blog\Tests;

use Blog\Database;
use Blog\Post;
use Blog\ImageHandler;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    private $db;
    private $post;
    private $imageHandler;

    protected function setUp(): void
    {
        // Použite rovnakú konfiguráciu ako hlavná aplikácia
        $config = [
            'host' => 'localhost',
            'dbname' => 'simple_blog_test',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4'
        ];

        $this->db = new Database($config);
        $this->post = new Post($this->db);
        $this->imageHandler = new ImageHandler(sys_get_temp_dir());

        // Vytvorte testovaciu databázu ak neexistuje - môže aj nefungovať s dôvodu obmedzených práv
        //vtedy treba vykonať manuálne
        $this->db->getConnection()->exec("CREATE DATABASE IF NOT EXISTS simple_blog_test");
        $this->db->getConnection()->exec("USE simple_blog_test");
    }

    protected function tearDown(): void
    {
        // Vyčistenie databázy po teste
        $this->db->getConnection()->exec("DROP TABLE IF EXISTS posts");
    }

    public function testCreatePost()
    {
        $result = $this->post->createPost('Test Title', 'Test Content');
        $this->assertTrue($result);

        $posts = $this->post->getAllPosts();
        $this->assertCount(1, $posts);
        $this->assertEquals('Test Title', $posts[0]['title']);
    }

    public function testGetPost()
    {
        $this->post->createPost('Test Title', 'Test Content');
        $postId = $this->db->lastInsertId();

        $post = $this->post->getPost($postId);
        $this->assertNotNull($post);
        $this->assertEquals('Test Title', $post['title']);
    }

    public function testUpdatePost()
    {
        $this->post->createPost('Old Title', 'Old Content');
        $postId = $this->db->lastInsertId();

        $result = $this->post->updatePost($postId, 'New Title', 'New Content');
        $this->assertTrue($result);

        $updatedPost = $this->post->getPost($postId);
        $this->assertEquals('New Title', $updatedPost['title']);
    }

    public function testDeletePost()
    {
        $this->post->createPost('To Delete', 'Content');
        $postId = $this->db->lastInsertId();

        $result = $this->post->deletePost($postId);
        $this->assertTrue($result);

        $deletedPost = $this->post->getPost($postId);
        $this->assertNull($deletedPost);
    }

    public function testImageUpload()
    {
        // Simulácia uploadu súboru
        $tempFile = tempnam(sys_get_temp_dir(), 'test');
        file_put_contents($tempFile, 'test content');

        $file = [
            'name' => 'test.jpg',
            'type' => 'image/jpeg',
            'tmp_name' => $tempFile,
            'error' => UPLOAD_ERR_OK,
            'size' => filesize($tempFile)
        ];

        $imagePath = $this->imageHandler->uploadImage($file);
        $this->assertNotNull($imagePath);
        $this->assertFileExists(sys_get_temp_dir() . '/' . basename($imagePath));

        // Vyčistenie
        unlink(sys_get_temp_dir() . '/' . basename($imagePath));
    }
}