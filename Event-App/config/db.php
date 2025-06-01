<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "ratna_event";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
