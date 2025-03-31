<?php

namespace Blog;

class ImageHandler
{
    private $uploadDir;
    private $webUploadPath;

    public function __construct(string $uploadDir = 'uploads', string $webUploadPath = '/uploads')
    {
        $this->uploadDir = $uploadDir;
        $this->webUploadPath = $webUploadPath;

        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function uploadImage(array $file): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $validTypes)) {
            return null;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('img_') . '.' . $extension;
        $destination = $this->uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $this->webUploadPath . '/' . $filename;
        }

        return null;
    }

    public function deleteImage(?string $imagePath): bool
    {
        if ($imagePath && file_exists($this->uploadDir . '/' . basename($imagePath))) {
            return unlink($this->uploadDir . '/' . basename($imagePath));
        }
        return false;
    }
}