<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$ticket_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$ticket_id) {
    die("Tiket tidak ditemukan");
}

$sql = "SELECT tickets.*, events.title, events.date, events.location, events.ticket_price, users.username 
        FROM tickets
        JOIN events ON tickets.event_id = events.id
        JOIN users ON tickets.user_id = users.id
        WHERE tickets.id = $ticket_id AND tickets.user_id = $user_id AND tickets.status = 'approved'";

$result = $conn->query($sql);
$ticket = $result->fetch_assoc();

if (!$ticket) {
    die("Tiket tidak valid atau belum disetujui.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>🖨️ Print Tiket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                background: #fff;
                -webkit-print-color-adjust: exact;
            }
        }

        body {
            background-color: #f9fafb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px 20px;
        }

        .ticket-box {
            max-width: 520px;
            margin: auto;
            border: 2px dashed #4a90e2;
            padding: 30px 35px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(74, 144, 226, 0.15);
        }

        .ticket-header {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        .ticket-header h2 {
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 5px;
        }

        .ticket-header h4 {
            font-weight: 600;
            font-size: 1.3rem;
            color: #34495e;
        }

        .ticket-details p {
            font-size: 1.1rem;
            color: #34495e;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .ticket-details p strong {
            width: 140px;
            display: inline-block;
            color: #2c3e50;
        }

        button.btn-print {
            display: inline-block;
            margin-top: 25px;
            width: 150px;
            font-weight: 600;
            border-radius: 30px;
            box-shadow: 0 6px 12px rgba(74, 144, 226, 0.3);
            transition: background-color 0.3s ease;
        }

        button.btn-print:hover {
            background-color: #2a74d9;
            box-shadow: 0 8px 18px rgba(42, 116, 217, 0.6);
        }

        a.btn-back {
            display: inline-block;
            margin-top: 25px;
            margin-left: 15px;
            width: 150px;
            font-weight: 600;
            border-radius: 30px;
            text-align: center;
            line-height: 38px;
            color: #34495e;
            background-color: #e0e0e0;
            text-decoration: none;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        a.btn-back:hover {
            background-color: #cacaca;
            color: #2c3e50;
        }
    </style>
</head>

<body>
    <div class="ticket-box">
        <div class="ticket-header">
            <h2>🎟️ Tiket Event</h2>
            <h4><?= htmlspecialchars($ticket['title']); ?></h4>
        </div>
        <div class="ticket-details">
            <p><strong>ID Tiket:</strong> <?= $ticket['id']; ?></p>
            <p><strong>Nama Pemesan:</strong> <?= htmlspecialchars($ticket['username']); ?></p>
            <p><strong>Tanggal Event:</strong> <?= $ticket['date']; ?></p>
            <p><strong>Lokasi:</strong> <?= htmlspecialchars($ticket['location']); ?></p>
            <p><strong>Harga Tiket:</strong> Rp <?= number_format($ticket['ticket_price'], 0, ',', '.'); ?></p>
            <p><strong>Status:</strong> <?= ucfirst($ticket['status']); ?></p>
        </div>

        <button onclick="window.print()" class="btn btn-primary btn-print no-print">🖨️ Print Tiket</button>
        <a href="my_tickets.php" class="btn-back no-print">← Kembali</a>
    </div>
</body>

</html>