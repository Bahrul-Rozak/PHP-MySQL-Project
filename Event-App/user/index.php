<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../index.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard User - Event App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: #e9f0fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        nav.navbar {
            box-shadow: 0 3px 8px rgba(0, 123, 255, 0.3);
        }

        .welcome-card {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgb(0 0 0 / 0.1);
            margin-top: 40px;
            transition: box-shadow 0.3s ease;
        }

        .welcome-card:hover {
            box-shadow: 0 12px 40px rgb(0 0 0 / 0.15);
        }

        .welcome-card h2 {
            font-weight: 700;
            font-size: 2.4rem;
        }

        .welcome-card .text-primary {
            color: #0d6efd !important;
        }

        .welcome-card p.lead {
            font-size: 1.25rem;
            color: #555;
            margin-top: 15px;
        }

        .menu-card {
            background: #fff;
            border-radius: 15px;
            padding: 30px 25px;
            box-shadow: 0 4px 15px rgb(0 0 0 / 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .menu-card:hover {
            box-shadow: 0 12px 25px rgb(0 123 255 / 0.3);
            transform: translateY(-10px);
            color: #0d6efd !important;
        }

        .menu-icon {
            font-size: 58px;
            margin-bottom: 15px;
            transition: color 0.3s ease;
        }

        .menu-card:hover .menu-icon {
            color: #0d6efd;
        }

        .menu-card h4 {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 8px;
        }

        .menu-card p {
            flex-grow: 1;
            font-size: 1.05rem;
            color: #666;
        }

        a.text-decoration-none {
            text-decoration: none !important;
        }

        /* Responsive tweaks */
        @media (max-width: 575.98px) {
            .welcome-card {
                padding: 30px 20px;
                margin-top: 20px;
            }

            .welcome-card h2 {
                font-size: 1.8rem;
            }

            .menu-icon {
                font-size: 48px;
                margin-bottom: 12px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-4">
        <a class="navbar-brand fw-bold fs-4" href="#">EventApp</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto fw-semibold">
                <li class="nav-item"><a class="nav-link" href="events.php">Lihat Event</a></li>
                <li class="nav-item"><a class="nav-link" href="my_tickets.php">Tiket Saya</a></li>
                <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <main class="container flex-grow-1">
        <div class="welcome-card text-center">
            <h2>Selamat datang, <span class="text-primary"><?= htmlspecialchars($username); ?>!</span></h2>
            <p class="lead">Mau ikut event apa hari ini? Yuk cari event menarik dan beli tiketnya!</p>
        </div>

        <div class="row text-center mt-5 g-4">
            <div class="col-md-4 col-sm-6">
                <a href="events.php" class="text-decoration-none text-dark">
                    <div class="menu-card h-100">
                        <div class="menu-icon">🎉</div>
                        <h4>Lihat Event</h4>
                        <p>Temukan event seru dan pilih yang kamu suka</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4 col-sm-6">
                <a href="my_tickets.php" class="text-decoration-none text-dark">
                    <div class="menu-card h-100">
                        <div class="menu-icon">🎫</div>
                        <h4>Tiket Saya</h4>
                        <p>Lihat dan print tiket event yang sudah kamu beli</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4 col-sm-6 mx-auto mx-md-0">
                <a href="../logout.php" class="text-decoration-none text-dark">
                    <div class="menu-card h-100">
                        <div class="menu-icon">🚪</div>
                        <h4>Logout</h4>
                        <p>Keluar dari akun dan kembali ke halaman login</p>
                    </div>
                </a>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>