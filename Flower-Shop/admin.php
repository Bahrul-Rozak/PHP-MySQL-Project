<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

include 'config.php';

// **NOTE: Ini admin sederhana tanpa login, untuk demo doang.**
// Kalau mau aman, tinggal tambah sistem login nanti ya.

// Handle delete produk
if (isset($_GET['hapus_produk'])) {
    $id = (int)$_GET['hapus_produk'];
    mysqli_query($conn, "DELETE FROM produk WHERE id = $id");
    header('Location: admin.php');
    exit;
}

// Handle delete pesanan
if (isset($_GET['hapus_pesanan'])) {
    $id = (int)$_GET['hapus_pesanan'];
    mysqli_query($conn, "DELETE FROM pesanan WHERE id = $id");
    mysqli_query($conn, "DELETE FROM detail_pesanan WHERE id_pesanan = $id");
    header('Location: admin.php');
    exit;
}

// Handle tambah produk
if (isset($_POST['tambah_produk'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga = (int)$_POST['harga'];

    // Upload gambar produk
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed_ext = ['jpg','jpeg','png','gif'];
        $file_name = $_FILES['gambar']['name'];
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_ext)) {
            $new_name = uniqid('produk_') . '.' . $file_ext;
            $upload_dir = 'uploads/';
            $upload_path = $upload_dir . $new_name;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                mysqli_query($conn, "INSERT INTO produk (nama, deskripsi, harga, gambar) VALUES ('$nama', '$deskripsi', $harga, '$new_name')");
                header('Location: admin.php');
                exit;
            } else {
                $error = "Gagal upload gambar produk.";
            }
        } else {
            $error = "Format gambar tidak didukung.";
        }
    } else {
        $error = "Harap upload gambar produk.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - FlowerShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container mt-4">
    <h2>Admin Panel 🌸</h2>
    <a href="admin_logout.php" class="btn btn-danger btn-sm mt-3">Logout</a>


    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <h4>Tambah Produk Baru</h4>
    <form method="post" enctype="multipart/form-data" class="mb-4">
        <div class="form-group">
            <label>Nama Produk</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Deskripsi Produk</label>
            <textarea name="deskripsi" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label>Harga (Rp)</label>
            <input type="number" name="harga" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Gambar Produk</label>
            <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.gif" class="form-control-file" required>
        </div>
        <button type="submit" name="tambah_produk" class="btn btn-primary">Tambah Produk</button>
    </form>

    <hr>

    <h4>Daftar Produk</h4>
    <?php
    $produk_list = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
    if (mysqli_num_rows($produk_list) > 0):
    ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($p = mysqli_fetch_assoc($produk_list)): ?>
            <tr>
                <td><img src="uploads/<?php echo $p['gambar']; ?>" width="80"></td>
                <td><?php echo htmlspecialchars($p['nama']); ?></td>
                <td>Rp <?php echo number_format($p['harga'],0,',','.'); ?></td>
                <td>
                    <a href="admin_edit.php?id=<?php echo $p['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="admin.php?hapus_produk=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus produk ini?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Belum ada produk.</p>
    <?php endif; ?>

    <hr>

    <h4>Daftar Pesanan</h4>
    <?php
    $pesanan_list = mysqli_query($conn, "SELECT * FROM pesanan ORDER BY id DESC");
    if (mysqli_num_rows($pesanan_list) > 0):
    ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Nama Pembeli</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>Bukti Transfer</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($order = mysqli_fetch_assoc($pesanan_list)): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo htmlspecialchars($order['nama_pembeli']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($order['alamat'])); ?></td>
                <td><?php echo htmlspecialchars($order['no_hp']); ?></td>
                <td>
                    <?php if ($order['bukti_transfer'] && file_exists('uploads/' . $order['bukti_transfer'])): ?>
                        <a href="uploads/<?php echo $order['bukti_transfer']; ?>" target="_blank">
                            <img src="uploads/<?php echo $order['bukti_transfer']; ?>" width="100">
                        </a>
                    <?php else: ?>
                        Tidak ada bukti
                    <?php endif; ?>
                </td>
                <td>
                    <a href="admin.php?hapus_pesanan=<?php echo $order['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus pesanan ini?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Belum ada pesanan.</p>
    <?php endif; ?>

</div>

<div class="footer mt-5">
    <p>© 2025 FlowerShop</p>
</div>

</body>
</html>
