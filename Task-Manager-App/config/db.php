<?php
$host = 'localhost';
$db   = 'ratna_task_manager';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Koneksi Terhubung Yang";
} catch (\PDOException $e) {
    echo "Koneksi Gagal Yang: " . $e->getMessage();
    exit();
}
?>
