<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = $conn->real_escape_string(trim($_POST['bio']));

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_pic'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowed_types)) {
            $error = "Format gambar tidak didukung.";
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'profile_'.$user_id.'_'.uniqid().'.'.$ext;
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            $target = $upload_dir . $filename;

            if (move_uploaded_file($file['tmp_name'], $target)) {
                // Update profile_pic di DB
                $conn->query("UPDATE users SET profile_pic='$filename' WHERE id=$user_id");
                $_SESSION['profile_pic'] = $filename;
            } else {
                $error = "Gagal upload gambar profil.";
            }
        }
    }

    if (!isset($error)) {
        // Update bio
        $conn->query("UPDATE users SET bio='$bio' WHERE id=$user_id");
        $success = "Profil berhasil diperbarui.";
    }
}

$user_res = $conn->query("SELECT * FROM users WHERE id=$user_id");
$user = $user_res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-light bg-white border-bottom mb-4">
  <div class="container">
    <a class="navbar-brand" href="feed.php">Sosmed IG Clone</a>
    <div>
        <span>Hai, <?=htmlspecialchars($_SESSION['username'])?></span>
        <a href="logout.php" class="btn btn-outline-danger btn-sm ms-3">Logout</a>
    </div>
  </div>
</nav>

<div class="container">
    <h3>Edit Profil</h3>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="profile_pic" class="form-label">Foto Profil</label><br/>
            <img src="uploads/<?= htmlspecialchars($user['profile_pic']) ?>" width="100" height="100" style="border-radius: 50%; object-fit: cover;" /><br/><br/>
            <input type="file" name="profile_pic" id="profile_pic" accept="image/*" />
        </div>

        <div class="mb-3">
            <label for="bio" class="form-label">Bio</label>
            <textarea name="bio" id="bio" rows="4" class="form-control"><?= htmlspecialchars($user['bio']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="profile.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
