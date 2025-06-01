<?php
session_start();
include '../config/db.php';

// Cek login admin
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: ../admin/tickets.php");
//     exit();
// }

// Ambil data tiket beserta user dan event
$sql = "SELECT tickets.*, users.username, events.title AS event_title 
        FROM tickets
        JOIN users ON tickets.user_id = users.id
        JOIN events ON tickets.event_id = events.id
        ORDER BY tickets.created_at DESC";

$result = $conn->query($sql);

// Proses Approve/Reject
if (isset($_GET['action']) && isset($_GET['ticket_id'])) {
    $ticket_id = (int)$_GET['ticket_id'];
    $action = $_GET['action'] === 'approve' ? 'approved' : ($_GET['action'] === 'reject' ? 'rejected' : '');

    if ($action) {
        $conn->query("UPDATE tickets SET status='$action' WHERE id=$ticket_id");
        header("Location: tickets.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>🎫 Admin - Verifikasi Tiket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h2 {
            margin-top: 40px;
            font-weight: bold;
            color: #2c3e50;
        }

        .card-table {
            background-color: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            margin-top: 30px;
        }

        .table th {
            background-color: #2980b9;
            color: #fff;
            text-align: center;
            vertical-align: middle;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-success, .btn-danger {
            font-size: 0.85rem;
            padding: 6px 12px;
        }

        .btn-secondary {
            background-color: #7f8c8d;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #636e72;
        }

        .badge {
            font-size: 0.85rem;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>🛂 Verifikasi Tiket</h2>
        <a href="dashboard.php" class="btn btn-secondary mb-3">🔙 Dashboard Admin</a>

        <div class="card-table">
            <table class="table table-bordered table-hover table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID Tiket</th>
                        <th>User</th>
                        <th>Event</th>
                        <th>Bukti Transfer</th>
                        <th>Status</th>
                        <th>Waktu Beli</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="text-center"><?= $row['id']; ?></td>
                                <td><?= htmlspecialchars($row['username']); ?></td>
                                <td><?= htmlspecialchars($row['event_title']); ?></td>
                                <td class="text-center">
                                    <?php if ($row['payment_proof']): ?>
                                        <a href="../uploads/payment_proofs/<?= htmlspecialchars($row['payment_proof']); ?>" target="_blank" class="btn btn-outline-primary btn-sm">Lihat</a>
                                    <?php else: ?>
                                        <span class="text-muted">Tidak ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    if ($row['status'] == 'pending') echo '<span class="badge bg-warning text-dark">⏳ Pending</span>';
                                    elseif ($row['status'] == 'approved') echo '<span class="badge bg-success">✅ Approved</span>';
                                    else echo '<span class="badge bg-danger">❌ Rejected</span>';
                                    ?>
                                </td>
                                <td><?= $row['created_at']; ?></td>
                                <td class="text-center">
                                    <?php if ($row['status'] == 'pending'): ?>
                                        <a href="?action=approve&ticket_id=<?= $row['id']; ?>" class="btn btn-success btn-sm">✅ Approve</a>
                                        <a href="?action=reject&ticket_id=<?= $row['id']; ?>" class="btn btn-danger btn-sm">❌ Reject</a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada tiket yang dibeli.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>
