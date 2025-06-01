<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$follower_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $following_id = intval($_POST['user_to_follow']);
    if ($following_id === $follower_id) {
        header("Location: profile.php?user=$following_id");
        exit;
    }

    // Cek apakah sudah follow
    $res = $conn->query("SELECT id FROM follows WHERE follower_id=$follower_id AND following_id=$following_id");

    if ($res->num_rows > 0) {
        // Unfollow
        $conn->query("DELETE FROM follows WHERE follower_id=$follower_id AND following_id=$following_id");
    } else {
        // Follow
        $conn->query("INSERT INTO follows (follower_id, following_id) VALUES ($follower_id, $following_id)");
    }

    header("Location: profile.php?user=$following_id");
    exit;
}
