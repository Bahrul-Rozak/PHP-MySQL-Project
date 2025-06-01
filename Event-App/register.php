<?php
include 'config/db.php';
session_start();

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role']; // admin/user

    $query = "INSERT INTO users (username, email, password, role) 
              VALUES ('$username', '$email', '$password', '$role')";

    if ($conn->query($query)) {
        header("Location: index.php?msg=registered");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>🎊 Event Registration 🎊</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f9f7f7, #dbe2ef);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-top: 15rem;
        }

        .event-card {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 500px;
            margin: 50px auto;
        }

        .event-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .event-header h2 {
            color: #3f72af;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #3f72af;
            border: none;
        }

        .btn-primary:hover {
            background-color: #112d4e;
        }

        .form-control {
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <div class="event-card">
        <div class="event-header">
            <h2>🎉 Join the Event Now! 🎉</h2>
            <p>Fill the form to register and be part of something special</p>
        </div>
        <form method="POST">
            <input class="form-control mb-3" type="text" name="username" placeholder="👤 Username" required>
            <input class="form-control mb-3" type="email" name="email" placeholder="📧 Email" required>
            <input class="form-control mb-3" type="password" name="password" placeholder="🔒 Password" required>
            <select class="form-control mb-3" name="role" required>
                <option value="user">👥 User</option>
                <option value="admin">🛠️ Admin</option>
            </select>
            <div class="d-grid">
                <button class="btn btn-primary mb-2" name="register">🚀 Register</button>
                <a href="index.php" class="btn btn-outline-secondary">🔑 Already Registered? Login</a>
            </div>
        </form>
    </div>

</body>

</html>