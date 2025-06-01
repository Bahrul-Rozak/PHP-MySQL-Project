<?php
$host = "localhost";
$user = "root";        // sesuaikan dengan user MySQL ya syaang
$password = "";        // sesuaikan password MySQL ya sayang
$dbname = "ratna_chat_app";

// Buat koneksi
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal sayang: " . $conn->connect_error);
}
?>
