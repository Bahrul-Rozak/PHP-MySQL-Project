<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Feed - Sosmed Instagram</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
    <style>
    /* Tambahin custom styling untuk post */
    .post {
        border: 1px solid #dbdbdb;
        background: white;
        margin-bottom: 30px;
        border-radius: 5px;
    }
    .post-header {
        padding: 10px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #dbdbdb;
    }
    .post-header img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
    }
    .post-image img {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
    }
    .post-content {
        padding: 10px;
    }
    .like-btn {
        cursor: pointer;
        color: #262626;
        font-weight: 600;
    }
    .like-btn.liked {
        color: #ed4956;
    }
    .comments {
        margin-top: 10px;
        font-size: 14px;
    }
    .comment-input {
        margin-top: 10px;
    }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white border-bottom mb-4">
  <div class="container">
    <a class="navbar-brand" href="#">Sosmed IG Clone</a>
    <div>
        <span>Hai, <?=htmlspecialchars($username)?>!</span>
        <a href="logout..php" class="btn btn-outline-danger btn-sm ms-3">Logout</a>
    </div>
  </div>
</nav>

<div class="container">

    <!-- Upload post -->
    <div class="container mb-4 p-3 bg-white border rounded">
        <h5>Buat Post Baru</h5>
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="file" name="image" id="image" accept="image/*" required /><br /><br />
            <textarea name="caption" id="caption" rows="3" class="form-control" placeholder="Tulis caption..." required></textarea><br />
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
        <div id="uploadMsg" class="mt-2"></div>
    </div>

    <!-- Feed -->
    <div id="feed">
        <!-- Post akan muncul di sini lewat AJAX -->
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function loadPosts() {
    $.ajax({
        url: 'fetch_posts.php',
        method: 'GET',
        success: function(data) {
            $('#feed').html(data);
        }
    });
}

$(document).ready(function(){
    loadPosts();

    // Reload feed tiap 10 detik biar realtime-ish
    setInterval(loadPosts, 10000);

    // Upload post
    $('#uploadForm').submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        $('#uploadMsg').text('Uploading...');

        $.ajax({
            url: 'upload_post.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res){
                $('#uploadMsg').text(res);
                $('#uploadForm')[0].reset();
                loadPosts();
            }
        });
    });

    // Delegate klik like button (karena konten dinamis)
    $(document).on('click', '.like-btn', function(){
        var postId = $(this).data('post-id');
        var btn = $(this);
        $.post('like_post.php', {post_id: postId}, function(response){
            if(response === 'liked'){
                btn.addClass('liked');
            } else {
                btn.removeClass('liked');
            }
            loadPosts();
        });
    });

    // Submit comment form
    $(document).on('submit', '.comment-form', function(e){
        e.preventDefault();
        var form = $(this);
        var postId = form.data('post-id');
        var commentInput = form.find('input[name="comment"]');
        var comment = commentInput.val();
        if(comment.trim() === '') return;

        $.post('comment_post.php', {post_id: postId, comment: comment}, function(response){
            if(response === 'success') {
                commentInput.val('');
                loadPosts();
            }
        });
    });
});
</script>

</body>
</html>
