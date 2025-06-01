<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Toko Bunga Online</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <a class="navbar-brand container" href="#">🌸 FlowerShop</a>
</nav>

<div class="container mt-4">
    <h3 class="text-center mb-4">🌷 Daftar Bunga</h3>
    <div class="row">
        <?php
        $result = mysqli_query($conn, "SELECT * FROM produk");
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="img/<?php echo $row['gambar']; ?>" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['nama']; ?></h5>
                        <p>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                        <a href="detail.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="footer">
    <p>© 2025 FlowerShop</p>
</div>

</body>
</html>
