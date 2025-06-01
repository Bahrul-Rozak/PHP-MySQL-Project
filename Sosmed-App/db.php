<?php
// db.php
$host = "localhost";
$user = "root";  // sesuaikan dengan user MySQL lo
$pass = "";      // sesuaikan dengan password MySQL lo
$db_name = "ratna_sosmed";

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// biar nggak error charset UTF8 biar support emoji dan bahasa lain
$conn->set_charset("utf8mb4");
?>
