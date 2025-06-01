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
    $comment = trim($_POST['comment']);

    if (empty($comment)) {
        echo "empty_comment";
        exit;
    }

    $comment_esc = $conn->real_escape_string($comment);

    $sql = "INSERT INTO comments (user_id, post_id, comment) VALUES ($user_id, $post_id, '$comment_esc')";
    if ($conn->query($sql)) {
        echo "success";
    } else {
        echo "failed";
    }
} else {
    echo "Invalid request";
}
