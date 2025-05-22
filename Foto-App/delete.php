<?php
include "config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil nama file dari DB
    $result = mysqli_query($conn, "SELECT filename FROM images WHERE id = $id");
    $data = mysqli_fetch_assoc($result);
    $filename = $data['filename'];

    // Hapus file dari folder
    $filepath = "uploads/" . $filename;
    if (file_exists($filepath)) {
        unlink($filepath);
    }

    // Hapus data dari DB sayang
    mysqli_query($conn, "DELETE FROM images WHERE id = $id");

    header("Location: index.php?delete=success");
} else {
    echo "ID tidak ditemukan!";
}
