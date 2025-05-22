<?php
include "config.php";

if(isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if(mysqli_num_rows($check) > 0){
        $error = "Username sudah dipakai!";
    } else {
        mysqli_query($conn, "INSERT INTO users(username, password) VALUES('$username', '$password')");
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 w-25">
    <h3 class="mb-4 text-center">Register</h3>
    <?php if(isset($error)) { ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php } ?>
    <form method="POST" action="">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button class="btn btn-primary w-100" name="register">Register</button>
        <p class="mt-3 text-center">Sudah punya akun? <a href="login.php">Login</a></p>
    </form>
</div>
</body>
</html>
