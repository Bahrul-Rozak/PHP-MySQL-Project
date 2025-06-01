<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT tickets.*, events.title, events.date, events.location, events.ticket_price
        FROM tickets 
        JOIN events ON tickets.event_id = events.id
        WHERE tickets.user_id = $user_id
        ORDER BY tickets.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>🎟️ Daftar Tiket Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f9fbfd;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h2 {
            margin-top: 40px;
            color: #34495e;
            font-weight: 700;
        }

        .btn-primary {
            background-color: #007bff;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            font-weight: 600;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-sm {
            font-size: 0.85rem;
            padding: 6px 12px;
        }

        table {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        thead tr {
            background: linear-gradient(90deg, #4b6cb7, #182848);
            color: #fff;
        }

        th, td {
            vertical-align: middle !important;
            text-align: center;
        }

        tbody tr:hover {
            background-color: #e9f1ff;
            cursor: pointer;
        }

        .badge {
            font-size: 0.9rem;
            padding: 0.45em 0.9em;
            border-radius: 12px;
            font-weight: 600;
        }

        .status-pending {
            background-color: #f39c12;
            color: #fff;
        }

        .status-approved {
            background-color: #27ae60;
            color: #fff;
        }

        .status-rejected {
            background-color: #e74c3c;
            color: #fff;
        }

        .actions {
            white-space: nowrap;
        }
    </style>
</head>

<body class="container">
    <h2>🎫 Daftar Tiket Saya</h2>

    <div class="mb-3 d-flex justify-content-between">
        <a href="events.php" class="btn btn-primary">Lihat Event</a>
        <a href="../logout.php" class="btn btn-danger">Logout</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Tiket</th>
                <th>Event</th>
                <th>Tanggal Event</th>
                <th>Lokasi</th>
                <th>Harga Tiket</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($ticket = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $ticket['id']; ?></td>
                        <td><?= htmlspecialchars($ticket['title']); ?></td>
                        <td><?= $ticket['date']; ?></td>
                        <td><?= htmlspecialchars($ticket['location']); ?></td>
                        <td>Rp <?= number_format($ticket['ticket_price'],0,',','.'); ?></td>
                        <td>
                            <?php 
                                if ($ticket['status'] == 'pending') echo '<span class="badge status-pending">⏳ Pending</span>';
                                elseif ($ticket['status'] == 'approved') echo '<span class="badge status-approved">✅ Approved</span>';
                                else echo '<span class="badge status-rejected">❌ Rejected</span>';
                            ?>
                        </td>
                        <td class="actions">
                            <?php if ($ticket['status'] == 'approved'): ?>
                                <a href="ticket_print.php?id=<?= $ticket['id']; ?>" target="_blank" class="btn btn-sm btn-success">Print Tiket</a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center text-muted">Belum ada tiket yang dibeli.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>
