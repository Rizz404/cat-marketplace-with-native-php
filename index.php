<?php

session_start();

include 'functions.php';

$selectKucing = mysqli_query($conn, "SELECT * FROM kucing WHERE jumlah_tersedia != 0 LIMIT 6");

if (isset($_SESSION['user_welcome'])) {
    $successMessage = $_SESSION['user_welcome'];
    showAlert($successMessage);
    unset($_SESSION['user_welcome']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:image" content="img/img-website/iconic-duo.jpg">
    <meta property="og:title" content="Maxwellcat">
    <meta property="og:description" content="Temukan dan beli kucing peliharaan impianmu di Maxwellcat, platform terpercaya untuk jual beli kucing. Dapatkan kucing berkualitas dengan berbagai ras yang tersedia. Kunjungi sekarang dan temukan teman setia yang sempurna!">
    <meta property="og:url" content="https://maxwellcat.seceria.com/">
    <title>Maxwellcat | Home</title>
    <link rel="stylesheet" href="css/style_user.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="shortcut icon" href="img/img-website/chocola-3.jpg" type="image/x-icon">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container-fluid banner d-flex align-items-center">
        <div class="container text-center text-white">
            <h3>Temukan kucing pilihan terbaikmu</h3>
            <div class="col-md-8 offset-md-2">
                <form action="beli_kucing.php" method="get" autocomplete="off">
                    <div class="input-group input-group-lg my-4">
                        <input type="text" name="search" class="form-control" placeholder="Ras Kucing" aria-describedby="basic-addon2">
                        <button type="submit" class="btn btn-success text-white"><i class="fa fa-search"></i> Telusuri</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container text-center">
            <h3 class="fw-bold">Kategori Terpopuler</h3>
            <div class="row mt-5">
                <div class="col-md-4 mb-3">
                    <a href="beli_kucing.php?kategori=Berbulu%Pendek" class=" text-decoration-none">
                        <div class="highlighted-category short-haired-cat d-flex justify-content-center align-items-center">
                            <h4>Kucing Bulu Pendek</h4>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="beli_kucing.php?kategori=Berbulu%Panjang" class=" text-decoration-none">
                        <div class="highlighted-category long-haired-cat d-flex justify-content-center align-items-center">
                            <h4>Kucing Bulu Panjang</h4>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="beli_kucing.php?kategori=Persilangan" class=" text-decoration-none">
                        <div class="highlighted-category cross-breed-cat d-flex justify-content-center align-items-center">
                            <h4>Kucing Persilangan</h4>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5 about-us">
        <div class="container text-center">
            <h3 class="mt-2 fw-bold">Tentang Kami</h3>
            <p class="fs-5 my-3">
                MaxWellCat adalah platform online untuk para pecinta kucing. Kami menyediakan tempat yang sempurna untuk menemukan dan membeli kucing pilihan Anda. Dengan koleksi kucing berkualitas dan beragam, Anda dapat menemukan kucing sesuai preferensi Anda. Tim kami terdiri dari ahli dan penggemar kucing yang berkomitmen untuk memberikan pengalaman belanja terbaik. Temukan kucing yang sesuai di MaxWellCat. Terima kasih atas kepercayaan Anda pada kami.
            </p>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container text-center">
            <h3 class="fw-bold">Rekomendasi Kucing</h3>

            <div class="row mt-5">
                <?php while ($data = mysqli_fetch_array($selectKucing)) { ?>
                    <div class="col-sm-6 col-md-4 mb-3">
                        <div class="card h-100 card-3d">
                            <div class="image-box">
                                <img src="img/img-product/<?php echo $data['gambar'] ?>" class="card-img-top" alt="...">
                            </div>
                            <div class="card-body">
                                <h4 class="card-title"><?= $data['ras']; ?></h4>
                                <p class="card-text text-truncate"><?= $data['deskripsi']; ?></p>
                                <p class="card-text text-price"><?= format_harga($data['harga']); ?></p>
                                <a href="detail_kucing.php?idKucing=<?= $data['id_kucing']; ?>" class="btn btn-warning text-white">Detail</a>
                            </div>
                        </div>
                    </div>
                <?php   } ?>
            </div>
            <a href="beli_kucing.php" class="btn btn-outline-warning">Lihat lebih banyak</a>
        </div>
    </div>

    <?php include 'footer.php' ?>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>
</body>

</html>