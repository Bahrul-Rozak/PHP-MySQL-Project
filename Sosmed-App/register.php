<?php
session_start();
require 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $fullname = trim($_POST['fullname']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (!$username || !$email || !$fullname || !$password || !$password_confirm) {
        $error = "Semua field harus diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email tidak valid.";
    } elseif ($password !== $password_confirm) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } else {
        // Cek username atau email sudah dipakai atau belum
        $sql = "SELECT id FROM users WHERE username='$username' OR email='$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $error = "Username atau email sudah digunakan.";
        } else {
            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, email, fullname, password) VALUES ('$username', '$email', '$fullname', '$password_hash')";
            if ($conn->query($sql) === TRUE) {
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Terjadi kesalahan saat menyimpan data.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register - Sosmed Instagram</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
</head>
<body>

<div class="container">
    <h2>Buat Akun Baru</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" action="register.php">
        <input type="text" name="username" placeholder="Username" value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" required />
        <input type="email" name="email" placeholder="Email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required />
        <input type="text" name="fullname" placeholder="Nama Lengkap" value="<?= isset($fullname) ? htmlspecialchars($fullname) : '' ?>" required />
        <input type="password" name="password" placeholder="Password" required />
        <input type="password" name="password_confirm" placeholder="Konfirmasi Password" required />
        <button type="submit">Daftar</button>
    </form>

    <a href="login.php">Sudah punya akun? Login</a>
</div>

</body>
</html>
