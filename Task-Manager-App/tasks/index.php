<?php
// Include koneksi database
require_once '../config/db.php';

// Ambil semua task dari database
$stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Task Manager - Daftar Task</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Custom CSS untuk tema retro -->
    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body class="retro-bg">
<div class="container py-4">
    <h1 class="mb-4 text-center retro-text">🕹️Task Manager</h1>
    
    <div class="mb-3 text-end">
        <a href="create.php" class="btn btn-primary retro-btn">+ Tambah Task</a>
    </div>

    <?php if (count($tasks) > 0): ?>
    <table class="table table-striped retro-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Judul Task</th>
                <th>Status</th>
                <th>Dibuat Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $index => $task): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($task['title']) ?></td>
                <td>
                    <?php
                        $statusBadge = [
                            'pending' => 'warning',
                            'in_progress' => 'info',
                            'completed' => 'success'
                        ];
                    ?>
                    <span class="badge bg-<?= $statusBadge[$task['status']] ?>">
                        <?= ucfirst(str_replace('_', ' ', $task['status'])) ?>
                    </span>
                </td>
                <td><?= date('d M Y H:i', strtotime($task['created_at'])) ?></td>
                <td>
                    <a href="edit.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-secondary retro-btn">Edit</a>
                    <a href="delete.php?id=<?= $task['id'] ?>" onclick="return confirm('Yakin mau hapus task ini?')" class="btn btn-sm btn-danger retro-btn">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p class="text-center retro-text">Belum ada task nih, buruan buat dulu ya!</p>
    <?php endif; ?>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
