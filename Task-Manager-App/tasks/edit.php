<?php
require_once '../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit();
}

// Ambil data task berdasarkan id
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    echo "Task tidak ditemukan!";
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'pending';

    if ($title === '') {
        $error = 'Judul task harus diisi!';
    } elseif (!in_array($status, ['pending', 'in_progress', 'completed'])) {
        $error = 'Status tidak valid!';
    } else {
        $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
        if ($stmt->execute([$title, $description, $status, $id])) {
            $success = 'Task berhasil diperbarui!';
            // Refresh data task dari DB setelah update
            $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
            $stmt->execute([$id]);
            $task = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = 'Gagal memperbarui task, coba lagi.';
        }
    }
} else {
    // Inisialisasi nilai form dari data task
    $title = $task['title'];
    $description = $task['description'];
    $status = $task['status'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Task - Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body class="retro-bg">
<div class="container py-4">
    <h1 class="mb-4 retro-text">Edit Task</h1>

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

        <button type="submit" class="btn btn-primary retro-btn">Update Task</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
