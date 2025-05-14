<?php
require_once '../config/db.php';

// Inisialisasi variabel error dan success
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'pending';

    // Validasi sederhana
    if ($title === '') {
        $error = 'Judul task harus diisi!';
    } elseif (!in_array($status, ['pending', 'in_progress', 'completed'])) {
        $error = 'Status tidak valid!';
    } else {
        // Insert ke database
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)");
        if ($stmt->execute([$title, $description, $status])) {
            $success = 'Task berhasil ditambahkan!';
            // Clear form
            $title = $description = '';
            $status = 'pending';
        } else {
            $error = 'Gagal menambahkan task, coba lagi.';
        }
    }
} else {
    // default values
    $title = '';
    $description = '';
    $status = 'pending';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body class="retro-bg">
<div class="container py-4">
    <h1 class="mb-4 retro-text">Tambah Task Baru</h1>

    <a href="index.php" class="btn btn-secondary retro-btn mb-3">&larr; Kembali ke Daftar Task</a>

    <?php if ($error): ?>
        <div class="alert alert-danger retro-alert"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success retro-alert"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="title" class="form-label retro-text">Judul Task</label>
            <input type="text" class="form-control retro-input" id="title" name="title" value="<?= htmlspecialchars($title) ?>" required />
        </div>

        <div class="mb-3">
            <label for="description" class="form-label retro-text">Deskripsi</label>
            <textarea class="form-control retro-input" id="description" name="description" rows="4"><?= htmlspecialchars($description) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label retro-text">Status</label>
            <select class="form-select retro-input" id="status" name="status">
                <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="in_progress" <?= $status === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Completed</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary retro-btn">Tambah Task</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
