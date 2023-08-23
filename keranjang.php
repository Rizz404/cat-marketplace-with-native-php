<?php
session_start();

include 'functions.php';
include 'login_required.php';

if (isset($_SESSION['update-keranjang'])) {
    $successMessage = $_SESSION['update-keranjang'];
    showAlert($successMessage);
    unset($_SESSION['update-keranjang']);
} elseif (isset($_SESSION['delete-keranjang'])) {
    $infoMessage = $_SESSION['delete-keranjang'];
    showAlert($infoMessage);
    unset($_SESSION['delete-keranjang']);
}

$idUser = $_SESSION['id'];

if (isset($_GET['idKucing'])) {
    $getIdKucing = $_GET['idKucing'];

    // Ambil data kucing dari tabel kucing berdasarkan id_kucing yang dipilih
    $selectKucing = mysqli_query($conn, "SELECT * FROM kucing WHERE id_kucing = '$getIdKucing'");
    $dataKucing = mysqli_fetch_array($selectKucing);
    $rowCountKucing = mysqli_num_rows($selectKucing);

    if ($rowCountKucing > 0) {
        // Cek apakah kucing sudah ada dalam keranjang
        $selectKeranjang = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_kucing = '$getIdKucing' AND id_user = '$idUser'");
        $rowCountKeranjang = mysqli_num_rows($selectKeranjang);

        if ($rowCountKeranjang > 0) {
            // Jika kucing sudah ada dalam keranjang, update jumlah dan total harga
            $updateKeranjang = mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + 1, total_harga = harga * jumlah WHERE id_kucing = '$getIdKucing' AND id_user = '$idUser'");
        } else {
            // Jika kucing belum ada dalam keranjang, simpan data kucing ke dalam tabel "keranjang" dengan jumlah 1 ekor
            $kucingID = $dataKucing['id_kucing'];
            $jumlah = 1;
            $harga = $dataKucing['harga'];

            // Query INSERT INTO untuk menyimpan data kucing ke dalam tabel "keranjang"
            $queryKeranjang = mysqli_query($conn, "INSERT INTO keranjang (id_user, id_kucing, jumlah, harga, total_harga) VALUES ('$idUser', '$kucingID', '$jumlah', '$harga', '$harga')");
        }
    }
    header('location: ' . $_SERVER['HTTP_REFERER']);
    $successMessage = "Berhasil memasukkan keranjang";
    $_SESSION['success-keranjang'] = $successMessage;
    exit();
}

if (isset($_POST['update'])) {
    $idKeranjang = $_POST['id-keranjang'];
    $jumlahArray = $_POST['jumlah'];

    foreach ($jumlahArray as $idKeranjang => $jumlah) {
        $idKeranjang = invaderMustDie($conn, $idKeranjang);
        $jumlah = invaderMustDie($conn, $jumlah);

        $updateKeranjang = mysqli_query($conn, "UPDATE keranjang SET jumlah = '$jumlah', total_harga = harga * jumlah WHERE id_keranjang = '$idKeranjang'");
        
        if ($updateKeranjang) {
            $successMessage = "Keranjang berhasil diupdate";
            $_SESSION['update-keranjang'] = $successMessage;
            header("location: keranjang.php");
        }
    }
} elseif (isset($_POST['delete'])) {
    $deleteArray = $_POST['delete'];

    foreach ($deleteArray as $idKeranjang => $value) {
        $idKeranjang = invaderMustDie($conn, $idKeranjang);
        $deleteKeranjang = mysqli_query($conn, "DELETE FROM keranjang WHERE id_keranjang = '$idKeranjang'");

        if ($deleteKeranjang) {
            $infoMessage = "Kucing berhasil dihapus dari keranjang";
            $_SESSION['delete-keranjang'] = $infoMessage;
            header("location: keranjang.php");
        }
    }
}

$selectKeranjangKucing = mysqli_query($conn, "SELECT * FROM keranjang kr JOIN kucing k ON kr.id_kucing = k.id_kucing WHERE kr.id_user = '$idUser' ORDER BY kr.id_keranjang DESC");
$rowCountKucingKeranjang = mysqli_num_rows($selectKeranjangKucing);

$selectTotalHarga = mysqli_query($conn, "SELECT SUM(total_harga) AS total FROM keranjang WHERE id_user = '$idUser'");
$totalHarga = mysqli_fetch_array($selectTotalHarga)['total'];

$selectPesanan = mysqli_query($conn, "SELECT COUNT(id_keranjang) AS jumlah_pesanan FROM keranjang WHERE id_user = '$idUser'");
$jumlahPesanan = mysqli_fetch_array($selectPesanan)['jumlah_pesanan'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat | Keranjang</title>
    <link rel="stylesheet" href="css/style_user.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="shortcut icon" href="img/img-website/chocola-3.jpg" type="image/x-icon">
    <style>
        .input-jumlah {
            width: 70px;
        }

        .input-total-harga {
            width: 150px;
        }

        .img-keranjang {
            max-height: 200px;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container py-4">
        <div class="container">
            <h1 class="text-center fw-bold mb-3">Keranjang Anda</h1>

            <?php if ($rowCountKucingKeranjang == 0) { ?>
                <div class="text-center">
                    <p>Tidak ada data produk</p>
                </div>
            <?php } else { ?>
                <form action="" method="post">
                    <div class="row">
                        <?php while ($data = mysqli_fetch_array($selectKeranjangKucing)) { ?>
                            <div class="col-md-12">
                                <div class="card mb-3 border border-3">
                                    <div class="row g-0">
                                        <div class="col-md-3 text-center">
                                            <img src="img/img-product/<?= $data['gambar']; ?>" alt="Gambar Produk" class="img-fluid img-thumbnail img-keranjang">
                                        </div>
                                        <div class="col-md-3 d-flex justify-content-center align-items-center">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo $data['ras']; ?></h5>
                                                <p class="card-text">Harga: <span class="text-price"><?php echo format_harga($data['harga']); ?></span></p>
                                                <label for="jumlah[<?= $data['id_keranjang']; ?>]">Jumlah</label>
                                                <input type="number" name="jumlah[<?= $data['id_keranjang']; ?>]" min="1" value="<?php echo $data['jumlah']; ?>" max="<?php echo $data['jumlah_tersedia']; ?>" class="form-control d-inline text-center input-jumlah">
                                            </div>
                                        </div>
                                        <div class="col-md-2 d-flex justify-content-center align-items-center">
                                            <div class="card-body">
                                                <p class="card-text"><span class="text-price fw-semibold fs-5"><?php echo format_harga($data['total_harga']); ?></span></p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex justify-content-between align-items-center">
                                            <div class="card-body text-center">
                                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                                            </div>
                                            <div class="card-body text-center">
                                                <button type="submit" name="delete[<?= $data['id_keranjang']; ?>]" class="btn btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id-keranjang" value="<?= $data['id_keranjang']; ?>">
                            </div>
                        <?php } ?>
                    </div>
                    <div class="row py-3 bg-light rounded border-top border-bottom border-3 border-danger">
                        <div class="col-md-6 col-xl-12 text-end">
                            <h4>Total <span class="text-price"><?= format_harga($totalHarga); ?></span></h4>
                            <a href="buat_pesanan_keranjang.php" class="btn btn-success fs-5">Checkout (<?= $jumlahPesanan; ?>)</a>
                        </div>
                    </div>
                </form>
            <?php } ?>
        </div>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="fontawesome/js/all.js"></script>
</body>
</html>