<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Login dulu ya";
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo "Gagal upload gambar.";
        exit;
    }

    $caption = trim($_POST['caption']);
    $image = $_FILES['image'];

    // Validasi tipe file (gambar aja)
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($image['type'], $allowed_types)) {
        echo "Format gambar tidak didukung. Gunakan JPG, PNG, atau GIF.";
        exit;
    }

    // Simpan gambar di folder uploads
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $target = $upload_dir . $filename;

    if (move_uploaded_file($image['tmp_name'], $target)) {
        // Simpan data post ke database
        $caption = $conn->real_escape_string($caption);
        $sql = "INSERT INTO posts (user_id, image, caption) VALUES ($user_id, '$filename', '$caption')";
        if ($conn->query($sql)) {
            echo "Post berhasil diupload!";
        } else {
            echo "Gagal menyimpan post ke database.";
        }
    } else {
        echo "Gagal memindahkan file gambar.";
    }
} else {
    echo "Method tidak valid.";
}
