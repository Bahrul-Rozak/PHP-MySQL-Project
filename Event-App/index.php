<?php
include 'config/db.php';
session_start();

if (isset($_POST['login'])) {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        if (password_verify($password, $data['password'])) {
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['role']    = $data['role'];
            $_SESSION['username'] = $data['username'];

            if ($data['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: user/index.php");
            }
        } else {
            echo "<script>alert('❌ Password salah!');</script>";
        }
    } else {
        echo "<script>alert('📭 Email tidak ditemukan!');</script>";
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🎟️ Login to Event 🎟️</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #edf2fb, #e2eafc);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-top: 15rem;
        }
        .event-card {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 30px;
            max-width: 500px;
            margin: 50px auto;
        }
        .event-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .event-header h2 {
            color: #3d5a80;
            font-weight: bold;
        }
        .btn-success {
            background-color: #3d5a80;
            border: none;
        }
        .btn-success:hover {
            background-color: #293241;
        }
        .form-control {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="event-card">
    <div class="event-header">
        <h2>🔐 Login to Your Event Account</h2>
        <p>Enter your details to join the celebration!</p>
    </div>
    <form method="POST">
        <input class="form-control mb-3" type="email" name="email" placeholder="📧 Email" required>
        <input class="form-control mb-3" type="password" name="password" placeholder="🔒 Password" required>
        <div class="d-grid">
            <button class="btn btn-success mb-2" name="login">🚀 Login</button>
            <a href="register.php" class="btn btn-outline-secondary">📝 Need an Account? Register</a>
        </div>
    </form>
</div>

</body>
</html>
