<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$sql = "SELECT * FROM events WHERE date >= CURDATE() ORDER BY date ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>🎟️ Daftar Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e3f2fd, #ffffff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h2 {
            margin-top: 30px;
            color: #2c3e50;
            font-weight: bold;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-img-top {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            height: 200px;
            object-fit: cover;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #34495e;
        }

        .card-text {
            font-size: 0.95rem;
            color: #555;
        }

        .btn-primary {
            background-color: #2a9d8f;
            border: none;
        }

        .btn-primary:hover {
            background-color: #21867a;
        }

        .btn-danger {
            background-color: #e74c3c;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .event-meta {
            font-size: 0.9rem;
            color: #333;
        }

        .logout-btn {
            float: right;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-3">🎉 Daftar Event</h2>
    <a href="../logout.php" class="btn btn-danger logout-btn mb-4">🚪 Logout</a>

    <?php if ($result->num_rows > 0): ?>
        <div class="row">
            <?php while ($event = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if ($event['image']): ?>
                            <img src="../uploads/<?= htmlspecialchars($event['image']); ?>" class="card-img-top" alt="Event Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($event['title']); ?></h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars($event['description'])); ?></p>
                            <p class="event-meta"><strong>📅 Tanggal:</strong> <?= $event['date']; ?></p>
                            <p class="event-meta"><strong>📍 Lokasi:</strong> <?= htmlspecialchars($event['location']); ?></p>
                            <p class="event-meta"><strong>💵 Harga Tiket:</strong> Rp <?= number_format($event['ticket_price'],0,',','.'); ?></p>
                            <a href="buy_ticket.php?event_id=<?= $event['id']; ?>" class="btn btn-primary w-100">Beli Tiket 🎫</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-4">
            Tidak ada event yang tersedia saat ini.
        </div>
    <?php endif; ?>
</div>

</body>
</html>

