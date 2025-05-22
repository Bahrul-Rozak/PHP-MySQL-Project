<?php
session_start();
include "config.php";

if (isset($_POST['upload'])) {
    $file = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $target = "uploads/" . basename($file);
    $user_id = $_SESSION['user_id'];

    if (move_uploaded_file($tmp_name, $target)) {
        $query = "INSERT INTO images (user_id, filename) VALUES ($user_id, '$file')";
        mysqli_query($conn, $query);
        header("Location: index.php?upload=success");
    } else {
        echo "Upload gagal!";
    }
}
