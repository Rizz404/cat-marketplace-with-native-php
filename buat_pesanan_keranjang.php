<?php

session_start();

include 'functions.php';
include 'login_required.php';
include 'address_required.php';

$idUser = $_SESSION['id'];

$selectKeranjangKucing = mysqli_query($conn, "SELECT * FROM keranjang kr JOIN kucing k ON kr.id_kucing = k.id_kucing WHERE kr.id_user = '$idUser'");
$rowCountKucingKeranjang = mysqli_num_rows($selectKeranjangKucing);
$selectTotalHarga = mysqli_query($conn, "SELECT SUM(total_harga) AS total FROM keranjang WHERE id_user = '$idUser'");
$subtotal = mysqli_fetch_array($selectTotalHarga)['total'];

// JP = jasa pengiriman | MP = metode pembayaran
$selectJP = mysqli_query($conn, "SELECT * FROM jasa_pengiriman");
$selectMP = mysqli_query($conn, "SELECT * FROM metode_pembayaran");

$totalHarga = 0; // Inisialisasi total harga
$totalHargaTransaksi = 0;

if (isset($_POST['buat-pesanan'])) {
    $statusPesanan = "dikemas";
    $idJasaPengiriman = invaderMustDie($conn, $_POST['jasa-pengiriman']);
    $idMetodePembayaran = invaderMustDie($conn, $_POST['metode-pembayaran']);
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

    // Menginisialisasi array untuk menyimpan Id kucing dan Id pesanan
    $rasKucingArray = [];
    $idPesananArray = [];

    // Iterasi melalui setiap baris keranjang kucing
    while ($data = mysqli_fetch_array($selectKeranjangKucing)) {
        $rasKucing = $data['ras'];
        $jumlahKucing = $data['jumlah'];
        $fotoKucing = $data['gambar'];
        $hargaKucingDipesan = $data['harga'];
        $totalHarga = $data['total_harga'];

        // Lakukan insert ke tabel pesanan
        $insertPesanan = mysqli_query($conn, "INSERT INTO pesanan (id_user, kucing, jumlah_kucing, foto_kucing, harga_kucing, total_harga_pesanan, status_pesanan) VALUES ('$idUser', '$rasKucing', '$jumlahKucing', '$fotoKucing', '$hargaKucingDipesan', '$totalHarga', '$statusPesanan')");

        if ($insertPesanan) {
            // Mendapatkan ID pesanan yang baru saja dimasukkan
            $idPesananBaru = mysqli_insert_id($conn);

            $updateJumlahKucing = mysqli_query($conn, "UPDATE kucing SET jumlah_tersedia = jumlah_tersedia - '$jumlahKucing' WHERE id_kucing = '$idKucing'");

            // Menggabungkan ras kucing ke dalam array $idKucingArray
            $idKucingArray[] = $idKucing;
            $rasKucingArray[] = $rasKucing;
            // Menggabungkan ID pesanan ke dalam array $idPesanan
            $idPesananArray[] = $idPesananBaru;
        } else {
            echo "Gagal membuat pesanan.";
        }
    }

    // Memisahkan ID kucing dengan koma
    $idKucingString = implode(', ', $idKucingArray);
    $rasKucingString = implode(', ', $rasKucingArray);
    $idPesananString = implode(', ', $idPesananArray);

    // Menghitung total harga transaksi
    $seletHargaOngkir = mysqli_query($conn, "SELECT harga_jasa FROM jasa_pengiriman WHERE id_jasa_pengiriman = '$idJasaPengiriman'");
    $hargaJP = mysqli_fetch_array($seletHargaOngkir)['harga_jasa'];

    $selectFeePembayaran = mysqli_query($conn, "SELECT fee FROM metode_pembayaran WHERE id_metode_pembayaran = '$idMetodePembayaran'");
    $feePembayaran = mysqli_fetch_array($selectFeePembayaran)['fee'];

    $totalHargaTransaksi = $subtotal + $hargaJP + $feePembayaran;

    // Insert data ke tabel histori_transaksi
    $insertTransaksi = mysqli_query($conn, "INSERT INTO histori_transaksi (id_user, kucing, id_pesanan, jasa_pengiriman, metode_pembayaran, no_wallet, bukti_transaksi, total_harga) VALUES ('$idUser', '$rasKucingString', '$idPesananString', '$idJasaPengiriman', '$idMetodePembayaran', '$noWallet', '$proofTf', $totalHargaTransaksi)");

    if ($insertTransaksi) {
        $deleteKeranjang = mysqli_query($conn, "DELETE FROM keranjang WHERE id_user = '$idUser'");

        // Redirect ke halaman sukses pembayaran
        $successMessage = "Berhasil membuat Pesanan";
        $_SESSION['pesananBerhasil'] = $successMessage;
        header("location: pesanan.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat | Buat Pesanan</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/style_user.css">
    <link rel="shortcut icon" href="img/img-website/chocola-3.jpg" type="image/x-icon">
    <style>
        .card-img-top {
            max-height: 200px;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="keranjang.php" class="text-decoration-none text-breadcrumb"><i class="fa fa-cart-shopping"></i> Keranjang</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><i class="fa fa-circle-check"></i> Buat Pesanan</li>
                </ol>
            </nav>
        </div>

        <div class="container">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                    <?php while ($data = mysqli_fetch_array($selectKeranjangKucing)) { ?>
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <img src="img/img-product/<?= $data['gambar']; ?>" class="card-img-top img-thumbnail img-fluid d-block mx-auto" alt="Kucing Image">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $data['ras']; ?></h5>
                                    <p class="card-text">Harga: <?php echo format_harga($data['harga']) ?></p>
                                    <p class="card-text">Jumlah: <?= $data['jumlah']; ?></p>
                                    <input type="hidden" name="jumlah-kucing" id="jumlah" value="<?= $data['jumlah']; ?>">
                                    <input type="hidden" name="id-kucing" value="<?= $data['id_kucing']; ?>">
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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
                                <label for="jasaPengiriman" class="col-md-4 col-form-label">Opsi Pengiriman</label>
                                <div class="col-md-8 mb-2">
                                    <select id="jasaPengiriman" class="form-select" name="jasa-pengiriman" required>
                                        <option value="" disabled selected>pilih opsi pengiriman</option>
                                        <?php while ($row = mysqli_fetch_assoc($selectJP)) { ?>
                                            <option value="<?php echo $row['id_jasa_pengiriman']; ?>" data-fee="<?php echo $row['harga_jasa']; ?>"><?php echo $row['nama_jasa']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <!-- metode pembayaran -->
                                <label for="metodePembayaran" class="col-md-4 col-form-label">Metode Pembayaran</label>
                                <div class="col-md-8 mb-2">
                                    <select id="metodePembayaran" class="form-select" name="metode-pembayaran" required>
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
                                    <input type="file" name="bukti-transaksi" class="form-control" accept=".jpg, .png, .jpeg" required>
                                </div>
                            </div>
                            <table class="table table-striped table-hover table-bordered">
                                <tbody>
                                    <tr>
                                        <input type="hidden" name="" id="subtotalValue" value="<?= $subtotal; ?>">
                                        <td>Subtotal</td>
                                        <td id="subtotal" class="text-center"><?= format_harga($subtotal); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Fee Pembayaran</td>
                                        <td id="feePembayaran" class="text-center">0</td>
                                    </tr>
                                    <tr>
                                        <td>Ongkir</td>
                                        <td id="ongkir" class="text-center">0</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Total Pembayaran</td>
                                        <td id="totalPembayaran" class="fw-bold text-center">0</td>
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
        function updateHarga() {
            const subtotalValue = document.querySelector("#subtotalValue").value;
            const jasaPengiriman = document.querySelector("#jasaPengiriman");
            const metodePembayaran = document.querySelector("#metodePembayaran");
            const feePembayaran = parseInt(metodePembayaran.options[metodePembayaran.selectedIndex].getAttribute("data-fee"));
            const ongkir = parseInt(jasaPengiriman.options[jasaPengiriman.selectedIndex].getAttribute("data-fee"));

            const feePembayaranValue = isNaN(feePembayaran) ? 0 : feePembayaran;
            const ongkirValue = isNaN(ongkir) ? 0 : ongkir;

            let subtotalInt = parseInt(subtotalValue);
            let totalPembayaran = subtotalInt + feePembayaranValue + ongkirValue;

            document.getElementById("feePembayaran").textContent = `Rp ${formatHarga(feePembayaranValue)}`;
            document.getElementById("ongkir").textContent = `Rp ${formatHarga(ongkirValue)}`;
            document.getElementById("totalPembayaran").textContent = `Rp ${formatHarga(totalPembayaran)}`;
        }

        function formatHarga(harga) {
            return harga.toLocaleString("id-ID");
        }

        updateHarga();
        document.querySelector("#jasaPengiriman").addEventListener("change", updateHarga);
        document.querySelector("#metodePembayaran").addEventListener("change", updateHarga);
    </script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>
</body>

</html>