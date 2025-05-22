<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include "config.php";
?>


<!DOCTYPE html>
<html>

<head>
    <title>Gallery App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="text-end mb-3">
            <span>Hi, <?= $_SESSION['username']; ?>!</span>
            <a href="logout.php" class="btn btn-sm btn-danger ms-2">Logout</a>
        </div>

        <h2 class="mb-4 text-center">📁 Gallery App - Upload Image</h2>

        <!-- Form Upload -->
        <form action="upload.php" method="POST" enctype="multipart/form-data" class="mb-4">
            <div class="input-group">
                <input type="file" name="image" class="form-control" required>
                <button type="submit" name="upload" class="btn btn-primary">Upload</button>
            </div>
        </form>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php
            include "config.php";
            // $result = mysqli_query($conn, "SELECT * FROM images ORDER BY uploaded_at DESC");
            $user_id = $_SESSION['user_id'];
            $result = mysqli_query($conn, "SELECT * FROM images WHERE user_id = $user_id ORDER BY uploaded_at DESC");
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="col">';
                echo '<div class="card shadow">';
                echo '<img src="uploads/' . $row['filename'] . '" class="card-img-top" alt="Image">';
                echo '<div class="card-body text-center">';
                echo '<p class="card-text text-truncate">' . $row['filename'] . '</p>';
                echo '<a href="edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning me-2">Edit</a>';
                echo '<a href="delete.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin mau hapus gambar ini?\')">Delete</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>

    </div>
</body>

</html>