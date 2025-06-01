<?php
$host = 'localhost';
$db   = 'ratna_real_time_chat';
$user = 'root';    // sesuaikan username mysql lo
$pass = '';        // sesuaikan password mysql lo

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    // set error mode ke exception supaya mudah debug
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
