<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = $_GET['event_id'] ?? null;

if (!$event_id) {
    die("Event tidak ditemukan");
}

// Ambil data event
$sql = "SELECT * FROM events WHERE id = $event_id";
$result = $conn->query($sql);
$event = $result->fetch_assoc();
if (!$event) {
    die("Event tidak ditemukan");
}

if (isset($_POST['submit'])) {
    // Upload bukti transfer
    if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] == 0) {
        $ext = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['payment_proof']['tmp_name'], "../uploads/payment_proofs/" . $fileName);

        // Simpan pembelian tiket
        $sql = "INSERT INTO tickets (user_id, event_id, payment_proof, status) 
                VALUES ('$user_id', '$event_id', '$fileName', 'pending')";

        if ($conn->query($sql)) {
            $ticket_id = $conn->insert_id;
            header("Location: ticket_detail.php?id=$ticket_id");
            exit();
        } else {
            $error = "Gagal menyimpan data tiket: " . $conn->error;
        }
    } else {
        $error = "Upload bukti transfer wajib diisi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>🛒 Beli Tiket - <?= htmlspecialchars($event['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f0f4f8, #ffffff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h2 {
            margin-top: 40px;
            color: #2c3e50;
            font-weight: bold;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        label {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .btn-success {
            background-color: #27ae60;
            border: none;
        }

        .btn-success:hover {
            background-color: #1e8449;
        }

        .btn-secondary {
            background-color: #95a5a6;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        p {
            font-size: 1rem;
            font-weight: 500;
            color: #34495e;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="form-container">
            <h2>🎫 Beli Tiket untuk: <span class="text-primary"><?= htmlspecialchars($event['title']); ?></span></h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <p>💰 <strong>Harga Tiket:</strong> Rp <?= number_format($event['ticket_price'], 0, ',', '.'); ?></p>
                <div class="mb-3">
                    <label for="payment_proof">📎 Upload Bukti Transfer</label>
                    <input type="file" name="payment_proof" id="payment_proof" accept="image/*,application/pdf" required class="form-control">
                </div>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-success" name="submit">✅ Kirim Bukti & Beli Tiket</button>
                    <a href="events.php" class="btn btn-secondary">❌ Batal</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>