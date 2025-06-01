<?php
include 'config.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
$produk = mysqli_fetch_assoc($result);

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Bunga - <?php echo htmlspecialchars($produk['nama']); ?></title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <a class="navbar-brand" href="index.php">🌸 FlowerShop</a>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <img src="uploads/<?php echo $produk['gambar']; ?>" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-6">
            <h2><?php echo htmlspecialchars($produk['nama']); ?></h2>
            <h4 class="text-pink">Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></h4>
            <p><?php echo nl2br(htmlspecialchars($produk['deskripsi'])); ?></p>

            <form method="post" action="cart.php">
                <input type="hidden" name="id_produk" value="<?php echo $produk['id']; ?>">
                <div class="form-group">
                    <label for="jumlah">Jumlah:</label>
                    <input type="number" name="jumlah" id="jumlah" value="1" min="1" class="form-control" style="width: 100px;">
                </div>
                <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
            </form>
        </div>
    </div>
</div>

<div class="footer mt-5">
    <p>© 2025 FlowerShop</p>
</div>

</body>
</html>
