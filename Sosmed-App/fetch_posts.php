<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo 'Login dulu ya';
    exit;
}

$user_id = $_SESSION['user_id'];

// Query semua post terbaru beserta user-nya
$sql = "SELECT posts.*, users.username, users.profile_pic 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($post = $result->fetch_assoc()) {
        // Hitung jumlah like
        $post_id = $post['id'];
        $like_count_res = $conn->query("SELECT COUNT(*) as total FROM likes WHERE post_id=$post_id");
        $like_count = $like_count_res->fetch_assoc()['total'];

        // Cek user sudah like post ini atau belum
        $liked_res = $conn->query("SELECT id FROM likes WHERE post_id=$post_id AND user_id=$user_id");
        $liked = $liked_res->num_rows > 0;

        // Ambil comment 3 terakhir
        $comments_res = $conn->query("SELECT comments.comment, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE post_id=$post_id ORDER BY comments.created_at DESC LIMIT 3");
        $comments = [];
        while ($c = $comments_res->fetch_assoc()) {
            $comments[] = $c;
        }

?>
        <div class="post">
            <div class="post-header">
                <img src="uploads/<?= htmlspecialchars($post['profile_pic']) ?>" alt="Profile" />
                <strong><?= htmlspecialchars($post['username']) ?></strong>
            </div>
            <div class="post-image">
                <img src="uploads/<?= htmlspecialchars($post['image']) ?>" alt="Post Image" />
            </div>
            <div class="post-content">
                <p><strong><?= htmlspecialchars($post['username']) ?></strong> <?= nl2br(htmlspecialchars($post['caption'])) ?></p>

                <span class="like-btn <?= $liked ? 'liked' : '' ?>" data-post-id="<?= $post_id ?>">
                    ❤️ <?= $like_count ?>
                </span>

                <div class="comments">
                    <?php foreach ($comments as $c): ?>
                        <p><strong><?= htmlspecialchars($c['username']) ?>:</strong> <?= htmlspecialchars($c['comment']) ?></p>
                    <?php endforeach; ?>
                </div>

                <form class="comment-form" data-post-id="<?= $post_id ?>">
                    <input type="text" name="comment" placeholder="Tulis komentar..." class="form-control" />
                </form>
            </div>
        </div>
<?php
    }
} else {
    echo '<p>Tidak ada post.</p>';
}
