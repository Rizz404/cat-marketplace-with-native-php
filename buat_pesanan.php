<?php

session_start();

include 'functions.php';
include 'login_required.php';
include 'address_required.php';

$idUser = $_SESSION['id'];

$idKucing = $_POST['id-kucing'];
$jumlahKucing = $_POST['jumlah-kucing'];

$selectKucing = mysqli_query($conn, "SELECT * FROM kucing WHERE id_kucing = '$idKucing'");
$data = mysqli_fetch_array($selectKucing);

$selectUser = mysqli_query($conn, "SELECT * FROM user u LEFT JOIN detail_user du ON u.id_user = du.id_user WHERE u.id_user = '$idUser'");
$dataUser = mysqli_fetch_assoc($selectUser);

// JP = jasa pengiriman | MP = metode pembayaran
$selectJP = mysqli_query($conn, "SELECT * FROM jasa_pengiriman");
$selectMP = mysqli_query($conn, "SELECT * FROM metode_pembayaran");

$totalHarga = 0; // Inisialisasi total harga

if (isset($_POST['buat-pesanan'])) {
    $idKucing = $_POST['id-kucing'];
    $statusPesanan = "dikemas";
    $idJasaPengiriman = invaderMustDie($conn, $_POST['jasa-pengiriman']);
    $idMetodePembayaran = invaderMustDie($conn, $_POST['metode-pembayaran']);
    $jumlahKucing = invaderMustDie($conn, $_POST['jumlah-kucing']);
    $noWallet = invaderMustDie($conn, $_POST['no-wallet']);
    $buktiTransaksi = invaderMustDie($conn, $_POST['bukti-transaksi']);

    $target_dir = "img/proof-transfer/";
    $nama_file = basename($_FILES["bukti-transaksi"]["name"]);
    $target_file = $target_dir . $nama_file;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $image_size = ($_FILES["bukti-transaksi"]["size"]);

    // ketika file tidak sesuai perintah
    if ($nama_file != '') {
        if ($image_size > 10000000) { 
            $warningMessage = "File tidak boleh lebih dari 10 mb";
            showAlert($warningMessage);
        } else { // untuk mengecek membuat file yang sama tidak teroverwrite
            $file_ext = pathinfo($target_file, PATHINFO_EXTENSION);
            $file_name_only = pathinfo($target_file, PATHINFO_FILENAME);
            
            for ($i = 1; file_exists($target_file); $i++) {
                $target_file = $target_dir . '/' . $file_name_only . '(' . $i . ').' . $file_ext;
            }
            (move_uploaded_file($_FILES["bukti-transaksi"]["tmp_name"], $target_file));
        }
    }
    $proofTf = basename($target_file);
    
    $rasKucing = $data['ras'];
    $fotoKucing = $data['gambar'];
    $hargaKucingDipesan = $data['harga'];
    // Menghitung subtotal
    $subTotal = $data['harga'] * $jumlahKucing;

    // Mendapatkan harga jasa pengiriman
    $selectHargaJP = mysqli_query($conn, "SELECT harga_jasa FROM jasa_pengiriman WHERE id_jasa_pengiriman = '$idJasaPengiriman'");
    $dataHargaJP = mysqli_fetch_assoc($selectHargaJP);
    $hargaJP = $dataHargaJP['harga_jasa'];

    // Mendapatkan fee metode pembayaran
    $selectFeeMP = mysqli_query($conn, "SELECT fee FROM metode_pembayaran WHERE id_metode_pembayaran = '$idMetodePembayaran'");
    $dataFeeMP = mysqli_fetch_assoc($selectFeeMP);
    $feeMP = $dataFeeMP['fee'];

    // Menghitung total harga
    $totalHarga = $subTotal + $hargaJP + $feeMP;

    // Simpan pesanan ke dalam tabel pesanan
    $insertPesanan = mysqli_query($conn, "INSERT INTO pesanan (id_user, kucing, jumlah_kucing, foto_kucing, harga_kucing, total_harga_pesanan, status_pesanan) VALUES ('$idUser', '$rasKucing', '$jumlahKucing', '$fotoKucing', '$hargaKucingDipesan', '$subTotal', '$statusPesanan')");

    $idPesananBaru = mysqli_insert_id($conn);

    if ($insertPesanan) {
        // Kurangi jumlah kucing di tabel kucing
        $updateJumlahKucing = mysqli_query($conn, "UPDATE kucing SET jumlah_tersedia = jumlah_tersedia - '$jumlahKucing' WHERE id_kucing = '$idKucing'");

        $insertTransaksi = mysqli_query($conn, "INSERT INTO histori_transaksi (id_user, kucing, id_pesanan, jasa_pengiriman, metode_pembayaran, no_wallet, bukti_transaksi, total_harga) VALUES ('$idUser', '$rasKucing', '$idPesananBaru', '$idJasaPengiriman', '$idMetodePembayaran', '$noWallet', '$proofTf', '$totalHarga')");

        // Redirect ke halaman sukses atau halaman lain yang diinginkan
        $successMessage = "Berhasil membuat Pesanan";
        $_SESSION['pesananBerhasil'] = $successMessage;
        header("location: pesanan.php");
    } else {
        echo "Gagal membuat pesanan.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat | Buat Pesanan</title>
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
                    <li class="breadcrumb-item active" aria-current="page"><i class="fa fa-circle-check"></i> Buat Pesanan</li>
                </ol>
            </nav>
        </div>

        <div class="container">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row justify-content-center mb-3">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="col-md-auto">
                                <img src="img/img-product/<?= $data['gambar']; ?>" class="card-img-top img-thumbnail img-fluid d-block mx-auto" alt="Kucing Image">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $data['ras']; ?></h5>
                                <p class="card-text">Harga: <span class="text-price"><?= format_harga($data['harga']); ?></span></p>
                                <p class="card-text">Jumlah: <?= $jumlahKucing; ?></p>
                                <input type="hidden" name="jumlah-kucing" id="jumlah" value="<?= $jumlahKucing; ?>">
                                <input type="hidden" name="id-kucing" value="<?= $idKucing; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center border-top border-bottom border-3 border-danger">
                    <div class="col-12">
                        <div class="fs-3">
                            Alamat Tujuan
                        </div>
                        <div class="fs-5">
                            Alamat : <?= $dataUser['alamat']; ?>
                        </div>
                        <div class="fs-5">
                            Kode Pos : <?= $dataUser['kode_pos']; ?>
                        </div>
                        <div class="fs-5">
                            Detail Alamat : <?= $dataUser['detail_alamat']; ?>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="p-3">
                            <h4 class="card-title">Detail Pesanan</h4>
                            <div class="row align-items-center">
                                <!-- jasa pengiriman -->
                                <label for="jasa-pengiriman" class="col-md-4 col-form-label">Opsi Pengiriman</label>
                                <div class="col-md-8 mb-2">
                                    <select class="form-select jasa-pengiriman" name="jasa-pengiriman" required>
                                        <option value="" disabled selected>pilih opsi pengiriman</option>
                                        <?php while ($row = mysqli_fetch_assoc($selectJP)) { ?>
                                            <option value="<?php echo $row['id_jasa_pengiriman']; ?>" data-fee="<?php echo $row['harga_jasa']; ?>"><?php echo $row['nama_jasa']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
    
                                <!-- metode pembayaran -->
                                <label for="metode-pembayaran" class="col-md-4 col-form-label">Metode Pembayaran</label>
                                <div class="col-md-8 mb-2">
                                    <select class="form-select metode-pembayaran" name="metode-pembayaran" required>
                                        <option value="" disabled selected>pilih metode pembayaran</option>
                                        <?php while ($row = mysqli_fetch_assoc($selectMP)) { ?>
                                            <option value="<?php echo $row['id_metode_pembayaran']; ?>" data-fee="<?php echo $row['fee']; ?>"><?php echo $row['nama_metode_pembayaran']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                
                                <!-- no rek -->
                                <label for="no-wallet" class="col-md-4 col-form-label">No Rekening/Wallet</label>
                                <div class="col-md-8 mb-2">
                                    <input type="text" name="no-wallet" class="form-control" placeholder="No Rek/Wallet" required>
                                </div>
    
                                <!-- bukti transaksi -->
                                <label for="bukti-transaksi" class="col-md-4 col-form-label">Bukti Transaksi</label>
                                <div class="col-md-8 mb-2">
                                    <input type="file" name="bukti-transaksi" class="form-control" accept=".jpg, .png, jpeg" required>
                                </div>
                            </div>
                            <table class="table table-striped table-hover table-bordered">
                                <tbody>
                                    <tr>
                                        <td>Subtotal</td>
                                        <td id="subtotal" class="text-center">0</td>
                                    </tr>
                                    <tr>
                                        <td>Fee Pembayaran</td>
                                        <td id="fee-pembayaran" class="text-center">0</td>
                                    </tr>
                                    <tr>
                                        <td>Ongkir</td>
                                        <td id="ongkir" class="text-center">0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Total Pembayaran</td>
                                        <td id="total-pembayaran" class="fw-bold text-center total-pembayaran">0</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-center">
                                <button type="submit" name="buat-pesanan" class="btn btn-danger w-50 p-2 fs-4">Buat Pesanan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    

    <script>
        // Fungsi untuk mengupdate subtotal, fee pembayaran, ongkir, dan total pembayaran
        function updateHarga() {
            var jumlahKucing = parseInt(document.getElementById('jumlah').value);
            var hargaKucing = <?php echo $data['harga']; ?>;
            var jasaPengiriman = document.querySelector('.jasa-pengiriman');
            var metodePembayaran = document.querySelector('.metode-pembayaran');
            var feePembayaran = parseFloat(metodePembayaran.options[metodePembayaran.selectedIndex].getAttribute('data-fee'));
            var ongkir = parseFloat(jasaPengiriman.options[jasaPengiriman.selectedIndex].getAttribute('data-fee'));

            var subtotal = hargaKucing * jumlahKucing;
            var totalPembayaran = subtotal + feePembayaran + ongkir;

            document.getElementById('subtotal').textContent = 'Rp ' + formatHarga(subtotal);
            document.getElementById('fee-pembayaran').textContent = 'Rp ' + formatHarga(feePembayaran);
            document.getElementById('ongkir').textContent = 'Rp ' + formatHarga(ongkir);
            document.getElementById('total-pembayaran').textContent = 'Rp ' + formatHarga(totalPembayaran);
        }
        
        // Fungsi untuk memformat harga dengan tanda titik sebagai pemisah ribuan
        function formatHarga(harga) {
            return harga.toLocaleString('id-ID');
        }

        // Panggil fungsi updateHarga saat halaman dimuat dan ketika pilihan jasa pengiriman atau metode pembayaran berubah
        window.addEventListener('DOMContentLoaded', updateHarga);
        document.querySelector('.jasa-pengiriman').addEventListener('change', updateHarga);
        document.querySelector('.metode-pembayaran').addEventListener('change', updateHarga);
    </script>
    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="fontawesome/js/all.js"></script>
</body>
</html>