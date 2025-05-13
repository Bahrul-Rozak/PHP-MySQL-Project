<?php
session_start();
require("config.php");

?>

<?php
// ini itu untuk check submit sayang
$error = '';

if (isset($_POST['submit'])) {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $error = "Masih ada yang gak di isi sayang :)";
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $login = $connect->query("SELECT * FROM users WHERE email = '$email'");
        $login->execute();
        $data = $login->fetch(PDO::FETCH_ASSOC);

        // check keberadaan datanya, kalau 1 berarti ada ya
        // echo $login->rowCount();

        if ($login->rowCount() > 0) {

            if (password_verify($password, $data['mypassword'])) {
                // echo "logged in";
                $_SESSION['username'] = $data['username'];
                $_SESSION['email'] = $data['email'];

                header("location: index.php");
            } else {
                echo "email atau passwordnya ada yang salah nih sayang";
            }
        } else {
            echo "email atau passwordnya ada yang salah nih sayang";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - GameZone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');

        body {
            background-color: #1a1a1a;
            font-family: 'Press Start 2P', cursive;
            color: #00ffcc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background-color: #000;
            border: 4px solid #00ffcc;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 0 20px #00ffcc;
            text-align: center;
        }

        h2 {
            color: #ffcc00;
            font-size: 16px;
            margin-bottom: 30px;
            text-shadow: 2px 2px #ff0066;
        }

        .form-control {
            font-size: 12px;
            background-color: #1a1a1a;
            border: 2px solid #00ffcc;
            color: #00ffcc;
            margin-bottom: 20px;
        }

        .form-control::placeholder {
            color: #00ffcc;
            opacity: 0.6;
        }

        .btn-retro {
            background-color: #00ffcc;
            color: #1a1a1a;
            border: none;
            padding: 10px 20px;
            font-size: 12px;
            box-shadow: 0 0 10px #00ffcc;
            width: 100%;
        }

        .btn-retro:hover {
            background-color: #ffcc00;
            color: #000;
            box-shadow: 0 0 20px #ffcc00;
        }

        .footer-text {
            font-size: 10px;
            margin-top: 20px;
            color: #666;
        }

        .icon {
            font-size: 24px;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <div class="icon">🔐</div>
        <h2>Login to GameZone</h2>

        <?php if (!empty($error)): ?>
            <div style="color: red; margin-bottom: 10px;"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="text" class="form-control" placeholder="💌 Email" name="email">
            <input type="password" class="form-control" placeholder="🔑 Password" name="password">
            <button type="submit" class="btn-retro" name="submit">START</button>
        </form>
        <div class="footer-text">
            Don't have an account? <a href="#" style="color:#ffcc00;">Register Now</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>