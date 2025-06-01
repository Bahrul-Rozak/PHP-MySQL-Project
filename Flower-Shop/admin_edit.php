<?php
session_start();
include 'config.php';

if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$id = (int)$_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
$produk = mysqli_fetch_assoc($result);

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga = (int)$_POST['harga'];

    $update_gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $_FILES['gambar']['name'];
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_ext)) {
            $new_name = uniqid('produk_') . '.' . $file_ext;
            $upload_dir = 'uploads/';
            $upload_path = $upload_dir . $new_name;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Hapus gambar lama
                if ($produk['gambar'] && file_exists('uploads/' . $produk['gambar'])) {
                    unlink('uploads/' . $produk['gambar']);
                }
                $update_gambar = ", gambar = '$new_name'";
            } else {
                $error = "Gagal upload gambar.";
            }
        } else {
            $error = "Format gambar tidak didukung.";
        }
    }

    if (!$error) {
        mysqli_query($conn, "UPDATE produk SET nama='$nama', deskripsi='$deskripsi', harga=$harga $update_gambar WHERE id = $id");
        header('Location: admin.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Produk - FlowerShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container mt-4">
        <h2>Edit Produk</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($produk['nama']) ?>" required>
            </div>
            <div class="form-group">
                <label>Deskripsi Produk</label>
                <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" value="<?= $produk['harga'] ?>" required>
            </div>
            <div class="form-group">
                <label>Gambar Produk (biarkan kosong jika tidak ganti)</label><br>
                <img src="uploads/<?= $produk['gambar'] ?>" width="150"><br><br>
                <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.gif" class="form-control-file">
            </div>
            <button type="submit" class="btn btn-primary">Update Produk</button>
            <a href="admin.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>