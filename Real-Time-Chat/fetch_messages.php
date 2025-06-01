<?php
session_start();
require 'config.php';  // pastikan ini file PDO connection

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Unauthorized";
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("
        SELECT m.*, u.username 
        FROM messages m 
        JOIN users u ON m.user_id = u.id 
        ORDER BY m.created_at ASC
    ");
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($messages as $row) {
        $isSent = ($row['user_id'] == $user_id);
        $class = $isSent ? 'sent' : 'received';
        $username = htmlspecialchars($row['username']);
        $time = date('H:i', strtotime($row['created_at']));
        $msg = nl2br(htmlspecialchars($row['message']));

        echo '<div class="message ' . $class . '">';
        if (!$isSent) {
            echo '<strong>' . $username . '</strong><br>';
        }

        if (!empty($row['image_path'])) {
            echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="Image" /><br>';
        }
        echo $msg;
        echo '<div class="time">' . $time . '</div>';
        echo '</div>';
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo "Error fetching messages: " . $e->getMessage();
}
