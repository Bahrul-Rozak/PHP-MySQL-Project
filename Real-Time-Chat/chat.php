<?php
session_start();
require 'config.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Realtime Chat - <?= htmlspecialchars($username) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: #ece5dd;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .chat-container {
            max-width: 720px;
            margin: 30px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: column;
            height: 80vh;
        }
        .chat-header {
            background: #075e54;
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            font-weight: 600;
        }
        .chat-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #ece5dd;
        }
        .chat-input {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ddd;
            background: #f0f0f0;
        }
        .chat-input textarea {
            resize: none;
            border-radius: 20px;
            border: 1px solid #ddd;
            padding: 10px 15px;
            flex: 1;
            font-size: 16px;
            height: 50px;
        }
        .btn-send {
            background-color: #25d366;
            border: none;
            color: white;
            margin-left: 10px;
            padding: 0 20px;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-send:hover {
            background-color: #128c4a;
        }
        .message {
            max-width: 60%;
            margin-bottom: 15px;
            padding: 10px 15px;
            border-radius: 15px;
            position: relative;
            clear: both;
            word-wrap: break-word;
        }
        .message.sent {
            background-color: #dcf8c6;
            margin-left: auto;
            border-bottom-right-radius: 0;
        }
        .message.received {
            background-color: white;
            border: 1px solid #ddd;
            border-bottom-left-radius: 0;
            margin-right: auto;
        }
        .message .time {
            font-size: 10px;
            color: #999;
            margin-top: 5px;
            text-align: right;
        }
        .message img {
            max-width: 100%;
            border-radius: 10px;
        }
        .logout-btn {
            color: white;
            font-weight: 600;
            text-decoration: none;
            float: right;
            background: #128c4a;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .logout-btn:hover {
            background: #0f6f3b;
            color: white;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            WhatsApp Chat - <?= htmlspecialchars($username) ?>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <div class="chat-messages" id="chatMessages">
            <!-- Pesan chat akan muncul di sini -->
        </div>

        <form id="chatForm" class="chat-input" enctype="multipart/form-data">
            <textarea name="message" id="messageInput" placeholder="Ketik pesan..." autocomplete="off"></textarea>
            <input type="file" id="imageInput" name="image" accept="image/*" style="display:none;" />
            <button type="button" id="btnImage" title="Upload Gambar" class="btn-send" style="margin-right:5px;">
                📷
            </button>
            <button type="submit" class="btn-send">➤</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const chatMessages = $('#chatMessages');
        const messageInput = $('#messageInput');
        const chatForm = $('#chatForm');
        const imageInput = $('#imageInput');
        const btnImage = $('#btnImage');

        // Scroll chat ke bawah otomatis
        function scrollToBottom() {
            chatMessages.scrollTop(chatMessages[0].scrollHeight);
        }

        // Load pesan chat dari server
        function loadMessages() {
            $.get('fetch_messages.php', function(data) {
                chatMessages.html(data);
                scrollToBottom();
            });
        }

        // Load pesan pertama kali dan polling tiap 3 detik
        loadMessages();
        setInterval(loadMessages, 3000);

        // Kirim pesan
        chatForm.on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: 'send_message.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    messageInput.val('');
                    imageInput.val('');
                    loadMessages();
                },
                error: function() {
                    alert('Gagal mengirim pesan.');
                }
            });
        });

        // Tombol upload gambar
        btnImage.on('click', function() {
            imageInput.click();
        });

        // Jika ada gambar dipilih, langsung kirim pesan (bisa plus teks juga)
        imageInput.on('change', function() {
            if (imageInput[0].files.length > 0) {
                chatForm.submit();
            }
        });
    </script>
</body>
</html>
