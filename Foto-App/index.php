<!DOCTYPE html>
<html>

<head>
    <title>Gallery App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4 text-center">📁 Gallery App - Upload Image</h2>

        <!-- Form Upload -->
        <form action="upload.php" method="POST" enctype="multipart/form-data" class="mb-4">
            <div class="input-group">
                <input type="file" name="image" class="form-control" required>
                <button type="submit" name="upload" class="btn btn-primary">Upload</button>
            </div>
        </form>

        <div class="row">
            <?php
            include "config.php";
            $result = mysqli_query($conn, "SELECT * FROM images ORDER BY uploaded_at DESC");
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="col-md-3 mb-4">';
                echo '<div class="card shadow">';
                echo '<img src="uploads/' . $row['filename'] . '" class="card-img-top" alt="Image">';
                echo '<div class="card-body text-center">';
                echo '<p class="card-text">' . $row['filename'] . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>

</html>