<?php

include '../functions.php';
include 'session.php';

if (isset($_SESSION['admin_welcome'])) {
    $successMessage = $_SESSION['admin_welcome'];
    showAlert($successMessage);
    unset($_SESSION['admin_welcome']);
}

$selectKucing = mysqli_query($conn, "SELECT * FROM kucing");
$rowCountKucing = mysqli_num_rows($selectKucing);

$selectUser = mysqli_query($conn, "SELECT * FROM user WHERE role = 'user'");
$rowCountUser = mysqli_num_rows($selectUser);

$selectAdmin = mysqli_query($conn, "SELECT * FROM user WHERE role = 'super admin' OR role = 'admin'");
$rowCountAdmin = mysqli_num_rows($selectAdmin);

$selectDikemas = mysqli_query($conn, "SELECT * FROM pesanan WHERE status_pesanan = 'dikemas'");
$rowCountDikemas = mysqli_num_rows($selectDikemas);

$selectDikirim = mysqli_query($conn, "SELECT * FROM pesanan WHERE status_pesanan = 'dikirim'");
$rowCountDikirim = mysqli_num_rows($selectDikirim);

$selectSelesai = mysqli_query($conn, "SELECT * FROM pesanan WHERE status_pesanan = 'selesai'");
$rowCountSelesai = mysqli_num_rows($selectSelesai);

$selectProfit = mysqli_query($conn, "SELECT SUM(total_harga_pesanan) AS profit FROM pesanan");
$dataProfit = mysqli_fetch_array($selectProfit);
$profit = $dataProfit['profit'];

$selectTerjual = mysqli_query($conn, "SELECT SUM(jumlah_kucing) AS terjual FROM pesanan");
$dataProfit = mysqli_fetch_array($selectTerjual);
$terjual = $dataProfit['terjual'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat-Admin | Dashboard</title>
    <link rel="stylesheet" href="../css/style_admin.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="shortcut icon" href="../img/img-website/chocola-2.jpg" type="image/x-icon">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container-fluid py-3">
        <div class="container">
            <h2>Halo <?= $_SESSION['nama_admin']; ?></h2>

            <div class="row mt-3">
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <div class="card h-100 summary-box">
                        <div class="card-body text-center">
                            <h3 class="card-title">Ras Kucing</h3>
                            <i class="fas fa-cat fa-5x"></i>
                            <p class="card-text fs-4"><?= $rowCountKucing; ?> ras kucing</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <div class="card h-100 summary-box">
                        <div class="card-body text-center">
                            <h3 class="card-title">Admin</h3>
                            <i class="fas fa-user-secret fa-5x"></i>
                            <p class="card-text fs-4"><?= $rowCountAdmin; ?> admin</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <div class="card h-100 summary-box">
                        <div class="card-body text-center">
                            <h3 class="card-title">User</h3>
                            <i class="fas fa-user fa-5x"></i>
                            <p class="card-text fs-4"><?= $rowCountUser; ?> user</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <div class="card h-100 summary-box">
                        <div class="card-body text-center">
                            <h3 class="card-title">Dikemas</h3>
                            <i class="fas fa-hourglass-end fa-5x"></i>
                            <p class="card-text fs-4"><?= $rowCountDikemas; ?> dikemas</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <div class="card h-100 summary-box">
                        <div class="card-body text-center">
                            <h3 class="card-title">Dikirim</h3>
                            <i class="fas fa-truck-fast fa-5x"></i>
                            <p class="card-text fs-4"><?= $rowCountDikirim; ?> dikirim</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <div class="card h-100 summary-box">
                        <div class="card-body text-center">
                            <h3 class="card-title">Selesai</h3>
                            <i class="fas fa-check-circle fa-5x"></i>
                            <p class="card-text fs-4"><?= $rowCountSelesai; ?> Selesai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-4">
            <div class="row">
                <div class="col-md-6 col-12 mb-3">
                    <div class="card h-100 summary-box">
                        <div class="card-body text-center">
                            <h3 class="card-title">Profit</h3>
                            <i class="fas fa-sack-dollar fa-5x"></i>
                            <p class="card-text fs-4"><?= format_harga($profit); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-12 mb-3">
                    <div class="card h-100 summary-box">
                        <div class="card-body text-center">
                            <h3 class="card-title">Kucing Yerjual</h3>
                            <i class="fas fa-list-check fa-5x"></i>
                            <p class="card-text fs-4"><?= $terjual; ?> Kucing</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
    <script src="../fontawesome/js/all.js"></script>
</body>

</html>