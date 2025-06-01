<?php
session_start();
require 'config.php';

// Jika user sudah login, redirect ke chat.php
if (isset($_SESSION['user_id'])) {
    header("Location: chat.php");
    exit;
}

$errors = [];
// Proses register
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (strlen($username) < 3) {
        $errors[] = "Username minimal 3 karakter.";
    }
    if (strlen($password) < 5) {
        $errors[] = "Password minimal 5 karakter.";
    }

    if (empty($errors)) {
        // Cek username sudah dipakai belum
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Username sudah digunakan.";
        } else {
            // Insert user baru
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hash]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            header("Location: chat.php");
            exit;
        }
    }
}

// Proses login
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: chat.php");
        exit;
    } else {
        $errors[] = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Realtime Chat - Login/Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: #e5ddd5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-top: 15rem;
        }
        .login-container {
            max-width: 400px;
            margin: 60px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.2);
            padding: 30px;
        }
        .whatsapp-green {
            color: #075e54;
        }
        .nav-tabs .nav-link.active {
            background-color: #25d366;
            color: white !important;
            font-weight: 600;
        }
        .form-control:focus {
            border-color: #25d366;
            box-shadow: 0 0 5px #25d366;
        }
        .btn-whatsapp {
            background-color: #25d366;
            color: white;
            font-weight: 600;
        }
        .btn-whatsapp:hover {
            background-color: #128c4a;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h3 class="text-center whatsapp-green mb-4">Realtime Chat</h3>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $err): ?>
                    <div><?= htmlspecialchars($err) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <ul class="nav nav-tabs mb-3" id="tabAuth" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">Login</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">Register</button>
            </li>
        </ul>

        <div class="tab-content" id="tabAuthContent">
            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="loginUsername" class="form-label">Username</label>
                        <input type="text" name="username" id="loginUsername" class="form-control" required autofocus />
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Password</label>
                        <input type="password" name="password" id="loginPassword" class="form-control" required />
                    </div>
                    <button type="submit" name="login" class="btn btn-whatsapp w-100">Login</button>
                </form>
            </div>

            <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="registerUsername" class="form-label">Username</label>
                        <input type="text" name="username" id="registerUsername" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="registerPassword" class="form-label">Password</label>
                        <input type="password" name="password" id="registerPassword" class="form-control" required />
                    </div>
                    <button type="submit" name="register" class="btn btn-whatsapp w-100">Register</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
