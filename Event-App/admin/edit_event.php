<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM events WHERE id = $id";
$result = $conn->query($sql);
$event = $result->fetch_assoc();

if (!$event) {
    die("Event tidak ditemukan");
}

if (isset($_POST['submit'])) {
    $title       = $_POST['title'];
    $description = $_POST['description'];
    $date        = $_POST['date'];
    $location    = $_POST['location'];
    $price       = $_POST['ticket_price'];
    $imageName   = $event['image'];

    // Upload gambar baru jika ada
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $imageName);
    }

    $sql = "UPDATE events SET 
                title='$title',
                description='$description',
                date='$date',
                location='$location',
                ticket_price='$price',
                image='$imageName'
            WHERE id = $id";

    if ($conn->query($sql)) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>✏️ Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e6f0ff, #ffffff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .edit-container {
            margin-top: 60px;
            background-color: #fff;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #354f52;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .btn-success {
            background-color: #2a9d8f;
            border: none;
        }

        .btn-success:hover {
            background-color: #21867a;
        }

        .btn-secondary {
            background-color: #c7c7c7;
            border: none;
        }

        label {
            font-weight: 500;
            color: #333;
        }

        .event-image {
            max-width: 100%;
            border-radius: 8px;
            margin-top: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="edit-container mx-auto col-md-8">
            <h2>✏️ Edit Event</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Judul Event</label>
                    <input class="form-control" type="text" name="title" value="<?= htmlspecialchars($event['title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="description" rows="4" required><?= htmlspecialchars($event['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label>Tanggal</label>
                    <input class="form-control" type="date" name="date" value="<?= $event['date']; ?>" required>
                </div>
                <div class="mb-3">
                    <label>Lokasi</label>
                    <input class="form-control" type="text" name="location" value="<?= htmlspecialchars($event['location']); ?>" required>
                </div>
                <div class="mb-3">
                    <label>Harga Tiket (Rp)</label>
                    <input class="form-control" type="number" name="ticket_price" value="<?= $event['ticket_price']; ?>" min="0" required>
                </div>
                <div class="mb-3">
                    <label>Gambar Saat Ini</label><br>
                    <?php if ($event['image']) { ?>
                        <img src="../uploads/<?= htmlspecialchars($event['image']); ?>" class="event-image" alt="Event Image">
                    <?php } else {
                        echo "Tidak ada gambar";
                    } ?>
                </div>
                <div class="mb-4">
                    <label>Ganti Gambar</label>
                    <input class="form-control" type="file" name="image" accept="image/*">
                </div>
                <button class="btn btn-success me-2" name="submit">💾 Update</button>
                <a href="dashboard.php" class="btn btn-secondary">❌ Batal</a>
            </form>
        </div>
    </div>

</body>

</html>