<?php
session_start();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auth-system</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <!-- Ambil sesuai urutan ya sayang : ) kalau nggak ntar ngambek -->
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid container">
            <a class="navbar-brand" href="#">🎮 GameZone</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Left -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">🏠 Home</a>
                    </li>
                </ul>

                <!-- Right -->
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item">
                            <span class="navbar-text">👋 Hi, <?php echo $_SESSION['username']; ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">🚪 Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">🔐 Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">📝 Register</a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->
    <!-- Body Content -->
    <div class="main-content">
        <h1>👾 Welcome to GameZone!</h1>
        <p>Insert Coin to Start<br>Play, Compete, and Win in Retro Style!</p>
        <button class="btn-retro">Start Game</button>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2025 GameZone. All Rights Reserved.<br>
            Powered by Retro Gamers 🎮</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>

</html>