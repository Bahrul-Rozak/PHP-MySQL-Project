<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['submit'])) {
    $title       = $_POST['title'];
    $description = $_POST['description'];
    $date        = $_POST['date'];
    $location    = $_POST['location'];
    $price       = $_POST['ticket_price'];

    // Upload gambar
    $imageName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $imageName);
    }

    $sql = "INSERT INTO events (title, description, date, location, ticket_price, image)
            VALUES ('$title', '$description', '$date', '$location', '$price', '$imageName')";

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
    <title>➕ Tambah Event Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #fdfcfb, #e2d1c3);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-container {
            margin-top: 60px;
            background-color: #fff;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #4b3f72;
            margin-bottom: 25px;
            font-weight: bold;
        }

        .btn-success {
            background-color: #6a994e;
            border: none;
        }

        .btn-success:hover {
            background-color: #386641;
        }

        .btn-secondary {
            background-color: #bcb8b1;
            border: none;
        }

        label {
            font-weight: 500;
            color: #333;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="form-container mx-auto col-md-8">
            <h2>🎫 Tambah Event Baru</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Judul Event</label>
                    <input class="form-control" type="text" name="title" placeholder="Contoh: Konser Musik Indie" required>
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="description" placeholder="Deskripsikan acara kamu..." rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label>Tanggal</label>
                    <input class="form-control" type="date" name="date" required>
                </div>
                <div class="mb-3">
                    <label>Lokasi</label>
                    <input class="form-control" type="text" name="location" placeholder="Contoh: Jakarta Convention Center" required>
                </div>
                <div class="mb-3">
                    <label>Harga Tiket (Rp)</label>
                    <input class="form-control" type="number" name="ticket_price" placeholder="Contoh: 100000" min="0" required>
                </div>
                <div class="mb-4">
                    <label>Upload Gambar</label>
                    <input class="form-control" type="file" name="image" accept="image/*">
                </div>
                <button class="btn btn-success me-2" name="submit">✅ Simpan</button>
                <a href="dashboard.php" class="btn btn-secondary">❌ Batal</a>
            </form>
        </div>
    </div>

</body>

</html>