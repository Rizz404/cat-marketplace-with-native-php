<?php

session_start();

include 'functions.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

if (!empty($search)) {
    $selectKucing = mysqli_query($conn, "SELECT * FROM kucing WHERE ras LIKE '%$search%' AND jumlah_tersedia != 0");
} elseif (!empty($kategori)) {
    $selectKucing = mysqli_query($conn, "SELECT * FROM kucing WHERE kategori_kucing LIKE '%$kategori%' AND jumlah_tersedia != 0");
} else {
    $selectKucing = mysqli_query($conn, "SELECT * FROM kucing WHERE jumlah_tersedia != 0");
}

$rowCountKucing = mysqli_num_rows($selectKucing);

if (isset($_SESSION['success-keranjang'])) {
    $successMessage = $_SESSION['success-keranjang'];
    showAlert($successMessage);
    unset($_SESSION['success-keranjang']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat | Beli Kucing</title>
    <link rel="stylesheet" href="css/style_user.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="shortcut icon" href="img/img-website/chocola-3.jpg" type="image/x-icon">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container-fluid banner-produk d-flex align-items-center">
        <div class="container text-center text-white">
            <h1>Temukan Kucing Pilihanmu</h1>
        </div>
    </div>

    <div class="container py-4">
        <div class="mb-5">
            <div class="row">
                <div class="col-md-2">
                    <h2>Kategori</h2>
                </div>
                <div class="col-md-10">
                    <form action="beli_kucing.php" method="GET">
                        <div class="mb-3">
                            <select class="form-select" name="kategori" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                <option value="Berbulu Pendek" <?= $kategori == 'Berbulu Pendek' ? 'selected' : '' ?>>Kucing Bulu Pendek</option>
                                <option value="Berbulu Panjang" <?= $kategori == 'Berbulu Panjang' ? 'selected' : '' ?>>Kucing Bulu Panjang</option>
                                <option value="Berukuran Kecil" <?= $kategori == 'Berukuran Kecil' ? 'selected' : '' ?>>Kucing Berukuran Kecil</option>
                                <option value="Berukuran Sedang" <?= $kategori == 'Berukuran Sedang' ? 'selected' : '' ?>>Kucing Berukuran Sedang</option>
                                <option value="Berukuran Besar" <?= $kategori == 'Berukuran Besar' ? 'selected' : '' ?>>Kucing Berukuran Besar</option>
                                <option value="Persilangan" <?= $kategori == 'Persilangan' ? 'selected' : '' ?>>Kucing Persilangan</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <?php if ($rowCountKucing < 1) { ?>
                        <h4 class="text-center my-5">Maaf kucing yang anda cari belum ada</h4>
                    <?php   } ?>

                    <?php while ($kucing = mysqli_fetch_array($selectKucing)) { ?>
                        <div class="col-md-3 mb-4">
                            <div class="card h-100 card-3d">
                                <div class="image-box">
                                    <img src="img/img-product/<?= $kucing['gambar'] ?>" class="card-img-top" alt="...">
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title text-center"><?= $kucing['ras']; ?></h4>
                                    <p class="card-text text-center text-success">Tersedia <?= $kucing['jumlah_tersedia']; ?> Kucing</p>
                                    <p class="card-text text-price text-center"><?= format_harga($kucing['harga']); ?></p>
                                    <div class="d-flex justify-content-between">
                                        <a href="detail_kucing.php?idKucing=<?= $kucing['id_kucing']; ?>" class="btn btn-warning text-white">Detail</a>
                                        <a href="keranjang.php?idKucing=<?= $kucing['id_kucing']; ?>" class="btn btn-danger text-white" title="tambah ke keranjang"><i class="fa fa-cart-arrow-down fs-5">Keranjang</i></a>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php   } ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="fontawesome/js/all.js"></script>
</body>

</html>