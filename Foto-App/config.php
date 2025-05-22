<?php
$host = "localhost";
$databaseName = "foto_app"; // Ganti jadi nama database yang ada di phpmyadmin ya sayang...
$user = "root";
$pass = "";

try {
    $connect = new PDO("mysql:host=$host;dbname=$databaseName", $user, $pass);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Berhasil Terhubung Sayang : )";
} catch (PDOException $e) {
    echo "Yah... Gak Terhubung Sayang. Error: " . $e->getMessage();
}
