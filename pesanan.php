<?php

session_start();

include 'functions.php';
include 'login_required.php';

if (isset($_SESSION['pesananBerhasil'])) {
    $successMessage = $_SESSION['pesananBerhasil'];
    showAlert($successMessage);
    unset($_SESSION['pesananBerhasil']);
}

$userId = $_SESSION['id'];

$status = isset($_GET['status']) ? $_GET['status'] : '';

// Mengubah status menjadi query kondisional
$statusPesanan = '';
if ($status == 'dikemas') {
    $statusPesanan = "AND p.status_pesanan = 'dikemas'";
} elseif ($status == 'dikirim') {
    $statusPesanan = "AND p.status_pesanan = 'dikirim'";
} elseif ($status == 'selesai') {
    $statusPesanan = "AND p.status_pesanan = 'selesai'";
}

$selectPesanan = mysqli_query($conn, "SELECT * FROM pesanan $statusPesanan WHERE id_user = '$userId' ORDER BY id_pesanan DESC");
$rowCountPesanan = mysqli_num_rows($selectPesanan);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat | Pesanan</title>
    <link rel="stylesheet" href="css/style_user.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="shortcut icon" href="img/img-website/chocola-3.jpg" type="image/x-icon">
    <style>
        .card-img {
            height: 200px;
            object-fit: cover;
        }
        
        .status-pesanan {
            position: absolute;
            bottom: 0;
            right: 0;
            margin: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container-fluid py-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <a href="pesanan.php" class="btn btn-primary me-2 <?php echo $status == '' ? 'active' : ''; ?>"><i class="fas fa-clipboard-list me-1"></i>Semua</a>
                    <a href="pesanan.php?status=dikemas" class="btn btn-warning me-2 <?php echo $status == 'dikemas' ? 'active' : ''; ?>"><i class="fas fa-box me-1"></i>Dikemas</a>
                    <a href="pesanan.php?status=dikirim" class="btn btn-danger me-2 <?php echo $status == 'dikirim' ? 'active' : ''; ?>"><i class="fas fa-truck me-1"></i>Dikirim</a>
                    <a href="pesanan.php?status=selesai" class="btn btn-success me-2 <?php echo $status == 'selesai' ? 'active' : ''; ?>"><i class="fas fa-check-circle me-1"></i>Selesai</a>
                    <a href="histori_transaksi.php" class="btn btn-outline-secondary ms-auto"><i class="fas fa-history me-1"></i>Histori Transaksi</a>
                </div>
            </div>
            <div class="row">
                <?php if ($rowCountPesanan > 0) {
                    while ($data = mysqli_fetch_array($selectPesanan)) { ?>
                        <div class="col-md-12">
                            <div class="card mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="img/img-product/<?= $data['foto_kucing']; ?>" class="card align-self-center card-img img-fluid img-thumbnail" alt="gambar kucing">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title fs-4">Kucing <?= $data['kucing']; ?></h5>
                                            <div class="card-text text-end">x<?= $data['jumlah_kucing']; ?></div>
                                            <div class="card-text text-end"><span class="text-danger"><?= format_harga($data['harga_kucing']); ?></span></div>
                                            <div class="card-text text-end fw-bold mt-3">Total Pesanan : <span class="text-danger"><?= format_harga($data['total_harga_pesanan']); ?></span></div>
                                            <div class="text-end">
                                                <div class="text-bg-<?php echo ($data['status_pesanan'] == 'dikemas') ? 'warning' : (($data['status_pesanan'] == 'dikirim') ? 'danger' : 'success'); ?> p-2 status-pesanan"><?= $data['status_pesanan']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php }
                } else { ?>
                    <div class="col-12 text-center">
                        <p>Tidak ada pesanan tersedia</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="fontawesome/js/all.js"></script>
</body>
</html>