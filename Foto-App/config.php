<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "foto_app";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal sayang " . mysqli_connect_error());
}
?>
