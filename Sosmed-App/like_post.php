<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "not_logged_in";
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = intval($_POST['post_id']);

    // Cek sudah like atau belum
    $res = $conn->query("SELECT id FROM likes WHERE user_id=$user_id AND post_id=$post_id");
    if ($res->num_rows > 0) {
        // Unlike
        $conn->query("DELETE FROM likes WHERE user_id=$user_id AND post_id=$post_id");
        echo "unliked";
    } else {
        // Like
        $conn->query("INSERT INTO likes (user_id, post_id) VALUES ($user_id, $post_id)");
        echo "liked";
    }
} else {
    echo "Invalid request";
}
