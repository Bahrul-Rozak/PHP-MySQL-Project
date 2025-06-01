<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Unauthorized";
    exit;
}

$user_id = $_SESSION['user_id'];
$message = trim($_POST['message'] ?? '');
$time = date('Y-m-d H:i:s');
$imagePath = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = $_FILES['image']['type'];

    if (!in_array($fileType, $allowedTypes)) {
        http_response_code(400);
        echo "Format gambar tidak didukung!";
        exit;
    }

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $imagePath = $targetFile;
    } else {
        http_response_code(500);
        echo "Gagal upload gambar.";
        exit;
    }
}

if ($message === '' && $imagePath === null) {
    http_response_code(400);
    echo "Pesan kosong.";
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO messages (user_id, message, image, created_at) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $message, $imagePath, $time]);
    echo "OK";
} catch (PDOException $e) {
    http_response_code(500);
    echo "Gagal menyimpan pesan: " . $e->getMessage();
}
