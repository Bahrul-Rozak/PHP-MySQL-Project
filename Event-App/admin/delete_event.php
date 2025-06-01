<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];

// Ambil gambar event dulu untuk dihapus dari folder
$sql = "SELECT image FROM events WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['image'] && file_exists("../uploads/" . $row['image'])) {
        unlink("../uploads/" . $row['image']);
    }
}

// Delete event dari database
$sql = "DELETE FROM events WHERE id = $id";
$conn->query($sql);

header("Location: dashboard.php");
