<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Ambil semua event dari database
$sql = "SELECT * FROM events ORDER BY date DESC";
$result = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>🎪 Admin Dashboard - Event List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #dff6ff, #f0faff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            margin-top: 50px;
        }

        .dashboard-card {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .btn-primary {
            background-color: #3d5a80;
            border: none;
        }

        .btn-primary:hover {
            background-color: #293241;
        }

        h2 {
            color: #3d5a80;
            font-weight: bold;
        }

        .table thead {
            background-color: #3d5a80;
            color: white;
        }

        .table img {
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <div class="container dashboard-container">
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>🎉 Event Management - Admin Dashboard</h2>
                <a href="../logout.php" class="btn btn-danger">🚪 Logout</a>
            </div>

            <div class="mb-3">
                <a href="add_event.php" class="btn btn-primary">➕ Tambah Event Baru</a>
                <a href="tickets.php" class="btn btn-secondary">Lihat Payment</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead>
                        <tr>
                            <th>🎫 Judul</th>
                            <th>📅 Tanggal</th>
                            <th>📍 Lokasi</th>
                            <th>💸 Harga Tiket</th>
                            <th>🖼️ Gambar</th>
                            <th>⚙️ Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($event = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= htmlspecialchars($event['title']); ?></td>
                                <td><?= htmlspecialchars($event['date']); ?></td>
                                <td><?= htmlspecialchars($event['location']); ?></td>
                                <td>Rp <?= number_format($event['ticket_price'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php if ($event['image']) { ?>
                                        <img src="../uploads/<?= htmlspecialchars($event['image']); ?>" width="100" alt="Event Image">
                                    <?php } else {
                                        echo "📭 Tidak ada gambar";
                                    } ?>
                                </td>
                                <td>
                                    <a href="edit_event.php?id=<?= $event['id']; ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
                                    <a href="delete_event.php?id=<?= $event['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus event ini?')">🗑️ Hapus</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>