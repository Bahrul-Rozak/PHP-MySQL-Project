<?php
session_start();
include "config.php";

if (isset($_POST['upload'])) {
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $user_id = $_SESSION['user_id'];
    $count = count($_FILES['images']['name']);

    for ($i = 0; $i < $count; $i++) {
        $fileName = $_FILES['images']['name'][$i];
        $tmpName = $_FILES['images']['tmp_name'][$i];

        // Cek jika ada file yang valid
        if ($fileName != '') {
            $target = "uploads/" . basename($fileName);
            if (move_uploaded_file($tmpName, $target)) {
               //  $query = "INSERT INTO images (user_id, filename) VALUES ($user_id, '$fileName')";
                $query = "INSERT INTO images (user_id, filename, category) VALUES ($user_id, '$fileName', '$category')";
                mysqli_query($conn, $query);
            }
        }
    }

    header("Location: index.php?upload=success");
    exit();
}
