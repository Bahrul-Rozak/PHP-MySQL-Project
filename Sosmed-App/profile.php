<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$logged_user_id = $_SESSION['user_id'];

// Kalau ada param user di URL, tampil profil user itu, kalau enggak tampil profil sendiri
$profile_user_id = isset($_GET['user']) ? intval($_GET['user']) : $logged_user_id;

// Ambil data user
$sql = "SELECT * FROM users WHERE id = $profile_user_id";
$res = $conn->query($sql);
if ($res->num_rows === 0) {
    echo "User tidak ditemukan";
    exit;
}
$user = $res->fetch_assoc();

// Ambil post user ini
$sql_post = "SELECT * FROM posts WHERE user_id = $profile_user_id ORDER BY created_at DESC";
$post_res = $conn->query($sql_post);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profil <?=htmlspecialchars($user['username'])?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<nav class="navbar navbar-light bg-white border-bottom mb-4">
  <div class="container">
    <a class="navbar-brand" href="feed.php">Sosmed IG Clone</a>
    <div>
        <a href="feed.php" class="btn btn-outline-primary btn-sm me-2">Feed</a>
        <span>Hai, <?=htmlspecialchars($_SESSION['username'])?></span>
        <a href="logout.php" class="btn btn-outline-danger btn-sm ms-3">Logout</a>
    </div>
  </div>
</nav>

<div class="container">
    <div class="d-flex align-items-center mb-4">
        <img src="uploads/<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile Pic" width="100" height="100" style="border-radius:50%; object-fit:cover; margin-right:20px;">
        <div>
            <h3><?= htmlspecialchars($user['username']) ?></h3>
            <p><?= nl2br(htmlspecialchars($user['bio'])) ?></p>

            <?php if ($logged_user_id === $profile_user_id): ?>
                <a href="edit_profile.php" class="btn btn-warning btn-sm">Edit Profil</a>
            <?php else: ?>
                <!-- Tombol follow/unfollow (lanjut di Step 5) -->
                <?php
                // Cek apakah sudah follow
                $fres = $conn->query("SELECT id FROM follows WHERE follower_id=$logged_user_id AND following_id=$profile_user_id");
                $is_following = $fres->num_rows > 0;
                ?>
                <form method="POST" action="follow.php" style="display:inline;">
                    <input type="hidden" name="user_to_follow" value="<?=$profile_user_id?>" />
                    <button type="submit" class="btn btn-sm <?= $is_following ? 'btn-danger' : 'btn-primary' ?>">
                        <?= $is_following ? 'Unfollow' : 'Follow' ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <hr/>

    <h5>Postingan</h5>
    <div class="row">
        <?php while($post = $post_res->fetch_assoc()): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="uploads/<?= htmlspecialchars($post['image']) ?>" class="card-img-top" alt="Post Image" style="object-fit:cover; max-height:300px;">
                    <div class="card-body">
                        <p class="card-text"><?= nl2br(htmlspecialchars($post['caption'])) ?></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
