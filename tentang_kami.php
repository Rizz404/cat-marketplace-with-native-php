<?php
session_start();

include 'functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat | Tentang Kami</title>
    <link rel="stylesheet" href="css/style_user.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="shortcut icon" href="img/img-website/maxwellcat-mark.svg" type="image/svg+xml">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container-fluid py-4">
        <div class="container">
            <h1>Tentang Maxwellcat</h1>
            <p>Maxwellcat adalah project website marketplace kucing yang dikembangkan oleh Rizqiansyah Ramadhan. Aplikasi ini memakai PHP native, MySQL, Bootstrap, dan Font Awesome.</p>
            <h2>Jelajahi Katalog</h2>
            <p>Pengunjung dapat menelusuri ras kucing, membaca detail katalog, melihat harga dan jumlah tersedia, serta menambahkan pilihan ke keranjang melalui akun pembeli.</p>
            <h2>Pengelolaan Pesanan</h2>
            <p>Source aplikasi menyediakan halaman pesanan, riwayat transaksi, pengaturan akun, dan panel administrasi. Ketersediaan halaman ini tidak berarti seluruh proses pembayaran atau pengiriman telah diuji.</p>
            <h2>Pengembangan</h2>
            <p>Project ini dibuat sebagai latihan pengembangan aplikasi web dengan pemrosesan form, session, query database, dan antarmuka responsif.</p>
            <a href="https://github.com/Rizz404/cat-marketplace-with-native-php" target="_blank" rel="noopener">Lihat repository Maxwellcat</a>
        </div>
    </div>

    <?php include 'footer.php' ?>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>
</body>
</html>