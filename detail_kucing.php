<?php 

session_start();
include 'functions.php';

if (isset($_SESSION['success-keranjang'])) {
    $successMessage = $_SESSION['success-keranjang'];
    showAlert($successMessage);
    unset($_SESSION['success-keranjang']);
}

$idKucing = $_GET['idKucing'];

$selectKucing = mysqli_query($conn, "SELECT * FROM kucing k JOIN user u ON k.id_user = u.id_user WHERE k.id_kucing = '$idKucing'");
$data = mysqli_fetch_array($selectKucing);

$selectKucingTerkait = mysqli_query($conn, "SELECT * FROM kucing WHERE CONCAT(',', kategori_kucing, ',') REGEXP ',(" . str_replace(',', '|', $data['kategori_kucing']) . "),' AND id_kucing != '$data[id_kucing]' LIMIT 4");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat | Detail Kucing</title>
    <link rel="stylesheet" href="css/style_user.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="shortcut icon" href="img/img-website/chocola-3.jpg" type="image/x-icon">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="beli_kucing.php" class="text-decoration-none text-breadcrumb"><i class="fa fa-list"></i> List Kucing</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><i class="fa fa-cat"></i> Detail Kucing</li>
                </ol>
            </nav>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-5">
                    <img src="img/img-product/<?= $data['gambar']; ?>" class="w-100 img-thumbnail" alt="gambar kucing">
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <h1><?= $data['ras'] ?></h1>
                    <p class="fs-5">
                        <?= $data['deskripsi'] ?>
                    </p>
                    <p class="fs-5">
                        Jumlah Tersedia: <b><?= $data['jumlah_tersedia'] ?></b>
                    </p>
                    <p class="fs-4 text-price">
                        Harga: <?php echo format_harga($data['harga']) ?>
                    </p>
                    <div class="d-flex justify-content-center gap-5 button-box py-2 rounded">
                        <a href="keranjang.php?idKucing=<?= $data['id_kucing']; ?>" class="btn btn-danger"><i class="fa fa-cart-arrow-down"></i> Masukkan Keranjang</a>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#jumlahModal">
                            <i class="fa fa-check"></i> Beli Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="jumlahModal" tabindex="-1" aria-labelledby="jumlahModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jumlahModalLabel">Pilih Jumlah Kucing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="buat_pesanan.php" method="post">
                        <div class="mb-3">
                            <input type="hidden" name="id-kucing" value="<?= $data['id_kucing']; ?>">
                            <label for="jumlah-kucing" class="form-label">Jumlah Kucing</label>
                            <input type="number" class="form-control" id="jumlah-kucing" name="jumlah-kucing" value="1" min="1" max="<?= $data['jumlah_tersedia']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="subtotal" class="form-label">Subtotal</label>
                            <input type="text" class="form-control" id="subtotal" name="subtotal" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary" name="checkout">Checkout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Produk Terkait -->
    <div class="container-fluid py-5 related-product">
        <div class="container">
            <h2 class="text-center text-white m-5">Produk Terkait</h2>
            <div class="row">
        <?php while ($kucingTerkait = mysqli_fetch_array($selectKucingTerkait)) { ?> 
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="image-box">
                        <a href="detail_kucing.php?idKucing=<?= $kucingTerkait['id_kucing']; ?>">
                            <img src="img/img-product/<?= $kucingTerkait['gambar']; ?>" class="img-fluid img-thumbnail" alt="">
                        </a>
                    </div>
                </div>
        <?php } ?>
            </div>
        </div>
    </div>

    <?php include 'footer.php' ?>

    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="fontawesome/js/all.js"></script>
    <script>
        const jumlahKucingInput = document.getElementById('jumlah-kucing');
        const subtotalInput = document.getElementById('subtotal');
        const hargaKucing = <?= $data['harga'] ?>;

        jumlahKucingInput.addEventListener('input', updateSubtotal);

        function updateSubtotal() {
            const jumlahKucing = jumlahKucingInput.value;
            const subtotal = jumlahKucing * hargaKucing;
            subtotalInput.value = formatCurrency(subtotal);
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
        }

        // Panggil updateSubtotal saat halaman dimuat
        updateSubtotal();
    </script>
</body>
</html>