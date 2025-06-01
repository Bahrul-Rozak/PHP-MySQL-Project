<?php
session_start();
include 'config.php';

// Kalau keranjang kosong, langsung balik ke index
if (empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);

    // Upload file bukti transfer
    if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] == 0) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $_FILES['bukti']['name'];
        $file_tmp = $_FILES['bukti']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = uniqid('bukti_') . '.' . $file_ext;
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $upload_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Simpan pesanan ke DB
                mysqli_query($conn, "INSERT INTO pesanan (nama_pembeli, alamat, no_hp, bukti_transfer) VALUES ('$nama', '$alamat', '$no_hp', '$new_file_name')");
                $id_pesanan = mysqli_insert_id($conn);

                // Simpan detail pesanan
                foreach ($_SESSION['cart'] as $id_produk => $item) {
                    $jumlah = $item['jumlah'];
                    mysqli_query($conn, "INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah) VALUES ($id_pesanan, $id_produk, $jumlah)");
                }

                // Bersihkan keranjang
                unset($_SESSION['cart']);
                $success = "Pesanan berhasil dikirim! Terima kasih sudah beli bunga 🌸.";
            } else {
                $error = "Gagal mengupload file bukti transfer.";
            }
        } else {
            $error = "Format file tidak diperbolehkan. Gunakan jpg, jpeg, png, atau gif.";
        }
    } else {
        $error = "Harap upload bukti transfer.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - FlowerShop</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <a class="navbar-brand" href="index.php">🌸 FlowerShop</a>
</nav>

<div class="container mt-4">
    <h3>Checkout Pembelian</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <a href="index.php" class="btn btn-primary">Kembali ke Beranda</a>
    <?php else: ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label>Nomor HP</label>
                <input type="text" name="no_hp" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Bukti Transfer (jpg, png, gif)</label>
                <input type="file" name="bukti" accept=".jpg,.jpeg,.png,.gif" class="form-control-file" required>
            </div>
            <button type="submit" class="btn btn-success">Kirim Pesanan</button>
        </form>
    <?php endif; ?>
</div>

<div class="footer mt-5">
    <p>© 2025 FlowerShop</p>
</div>

</body>
</html>
