<?php
session_start();
include 'config.php';

// Tambah produk ke keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produk = (int)$_POST['id_produk'];
    $jumlah = (int)$_POST['jumlah'];

    // Ambil data produk dari DB
    $result = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id_produk");
    $produk = mysqli_fetch_assoc($result);
    if (!$produk) {
        die('Produk tidak ditemukan.');
    }

    // Kalau keranjang belum ada, buat array kosong
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Kalau produk sudah ada di keranjang, update jumlah
    if (isset($_SESSION['cart'][$id_produk])) {
        $_SESSION['cart'][$id_produk]['jumlah'] += $jumlah;
    } else {
        // Tambah produk baru ke keranjang
        $_SESSION['cart'][$id_produk] = [
            'nama' => $produk['nama'],
            'harga' => $produk['harga'],
            'gambar' => $produk['gambar'],
            'jumlah' => $jumlah
        ];
    }

    header('Location: cart.php');
    exit;
}

// Hapus produk dari keranjang
if (isset($_GET['hapus'])) {
    $hapus_id = (int)$_GET['hapus'];
    if (isset($_SESSION['cart'][$hapus_id])) {
        unset($_SESSION['cart'][$hapus_id]);
    }
    header('Location: cart.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Keranjang Belanja - FlowerShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="index.php">🌸 FlowerShop</a>
    </nav>

    <div class="container mt-4">
        <h3>🛒 Keranjang Belanja</h3>

        <?php if (empty($_SESSION['cart'])): ?>
            <p>Keranjang lo kosong, coba beli bunga dulu ya! 🌼</p>
            <a href="index.php" class="btn btn-primary">Belanja Sekarang</a>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $id => $item):
                        $subtotal = $item['harga'] * $item['jumlah'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td><img src="img/<?php echo $item['gambar']; ?>" width="80"></td>
                            <td><?php echo htmlspecialchars($item['nama']); ?></td>
                            <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                            <td><?php echo $item['jumlah']; ?></td>
                            <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                            <td>
                                <a href="cart.php?hapus=<?php echo $id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" class="text-right font-weight-bold">Total</td>
                        <td colspan="2" class="font-weight-bold">Rp <?php echo number_format($total, 0, ',', '.'); ?></td>
                    </tr>
                </tbody>
            </table>
            <a href="index.php" class="btn btn-secondary">Tambah Beli</a>
            <a href="checkout.php" class="btn btn-success">Checkout</a>
        <?php endif; ?>
    </div>

    <div class="footer mt-5">
        <p>© 2025 FlowerShop</p>
    </div>

</body>

</html>