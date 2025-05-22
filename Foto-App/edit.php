<?php
include "config.php";

// Ambil data gambar berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM images WHERE id = $id");
    $data = mysqli_fetch_assoc($result);
}

// Proses update nama file
if (isset($_POST['update'])) {
    $newName = $_POST['new_name'];
    $id = $_POST['id'];

    $query = "UPDATE images SET filename = '$newName' WHERE id = $id";
    mysqli_query($conn, $query);

    // Rename file di folder uploads/
    $oldPath = "uploads/" . $_POST['old_name'];
    $newPath = "uploads/" . $newName;
    rename($oldPath, $newPath);

    header("Location: index.php?edit=success");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4 text-center">✏️ Edit Image Name</h3>

    <form action="edit.php" method="POST" class="w-50 mx-auto">
        <input type="hidden" name="id" value="<?= $data['id']; ?>">
        <input type="hidden" name="old_name" value="<?= $data['filename']; ?>">

        <div class="mb-3">
            <label for="new_name" class="form-label">New File Name</label>
            <input type="text" name="new_name" class="form-control" value="<?= $data['filename']; ?>" required>
        </div>

        <button type="submit" name="update" class="btn btn-success">Update</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
