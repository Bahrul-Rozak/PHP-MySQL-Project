<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_email = trim($_POST['username_email']);
    $password = $_POST['password'];

    if (!$username_email || !$password) {
        $error = "Semua field harus diisi.";
    } else {
        $sql = "SELECT * FROM users WHERE username='$username_email' OR email='$username_email' LIMIT 1";
        $result = $conn->query($sql);
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Login berhasil
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: feed.php'); // nanti buat feed.php
                exit;
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "User tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Sosmed Instagram</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <div class="container">
        <h2>Login</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="login.php">
            <input type="text" name="username_email" placeholder="Username atau Email" value="<?= isset($username_email) ? htmlspecialchars($username_email) : '' ?>" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit">Login</button>
        </form>

        <a href="register.php">Belum punya akun? Daftar</a>
    </div>

</body>

</html>