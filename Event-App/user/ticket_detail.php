<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];

$sql = "SELECT tickets.*, events.title, events.date, events.location, events.ticket_price 
        FROM tickets 
        JOIN events ON tickets.event_id = events.id 
        WHERE tickets.id = $id AND tickets.user_id = $user_id";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Tiket tidak ditemukan atau bukan milik Anda.");
}

$ticket = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>🎫 Detail Tiket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h2 {
            margin-top: 40px;
            font-weight: 700;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }

        .ticket-detail {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .ticket-detail p {
            font-size: 1.1rem;
            color: #34495e;
            margin-bottom: 12px;
        }

        .ticket-detail p strong {
            color: #34495e;
            width: 120px;
            display: inline-block;
        }

        .status {
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 20px;
            color: #fff;
            font-size: 0.95rem;
            text-transform: capitalize;
            display: inline-block;
            min-width: 100px;
            text-align: center;
        }

        .status-pending {
            background-color: #f39c12;
        }

        .status-approved {
            background-color: #27ae60;
        }

        .status-rejected {
            background-color: #e74c3c;
        }

        .proof-img {
            max-width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 15px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-back {
            display: block;
            margin: 30px auto 0;
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 30px;
        }
    </style>
</head>

<body>
    <div class="ticket-detail">
        <h2>🎫 Detail Tiket</h2>

        <p><strong>Event:</strong> <?= htmlspecialchars($ticket['title']); ?></p>
        <p><strong>Tanggal:</strong> <?= $ticket['date']; ?></p>
        <p><strong>Lokasi:</strong> <?= htmlspecialchars($ticket['location']); ?></p>
        <p><strong>Harga Tiket:</strong> Rp <?= number_format($ticket['ticket_price'],0,',','.'); ?></p>
        <p>
            <strong>Status:</strong>
            <?php 
                $statusClass = '';
                switch ($ticket['status']) {
                    case 'pending': $statusClass = 'status-pending'; break;
                    case 'approved': $statusClass = 'status-approved'; break;
                    case 'rejected': $statusClass = 'status-rejected'; break;
                    default: $statusClass = 'status-pending';
                }
            ?>
            <span class="status <?= $statusClass; ?>"><?= ucfirst($ticket['status']); ?></span>
        </p>

        <?php if ($ticket['payment_proof']): ?>
            <p><strong>Bukti Transfer:</strong></p>
            <img src="../uploads/payment_proofs/<?= htmlspecialchars($ticket['payment_proof']); ?>" alt="Bukti Transfer" class="proof-img" />
        <?php else: ?>
            <p><em>Bukti transfer belum diupload.</em></p>
        <?php endif; ?>

        <a href="my_tickets.php" class="btn btn-secondary btn-back">← Kembali ke Tiket Saya</a>
    </div>
</body>

</html>

