<?php

session_start();

include 'functions.php';
include 'login_required.php';

$userId = $_SESSION['id'];

$selectTransaksi = mysqli_query($conn, "SELECT h.*, du.*, m.nama_metode_pembayaran, jp.nama_jasa, p.jumlah_kucing FROM histori_transaksi h JOIN user u ON h.id_user = u.id_user JOIN detail_user du ON u.id_user = du.id_user JOIN metode_pembayaran m ON h.metode_pembayaran = m.id_metode_pembayaran JOIN jasa_pengiriman jp ON h.jasa_pengiriman = jp.id_jasa_pengiriman JOIN pesanan p ON FIND_IN_SET(p.id_pesanan, h.id_pesanan) WHERE h.id_user = '$userId' ORDER BY h.id_transaksi DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat | Histori Transaksi</title>
    <link rel="stylesheet" href="css/style_user.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="shortcut icon" href="img/img-website/chocola-3.jpg" type="image/x-icon">
    <style>
        .card-img {
            height: 200px;
            object-fit: cover;
        }
        
        .contact-seller {
            position: absolute;
            bottom: 0;
            right: 0;
            margin: 10px;
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
                    <li class="breadcrumb-item"><a href="pesanan.php" class="text-decoration-none text-breadcrumb"><i class="fa fa-clipboard-list"></i> Pesanan</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><i class="fa fa-receipt"></i> Transaksi</li>
                </ol>
            </nav>
        </div>
        
        <div class="container">
            <h2 class="text-center mb-3">Histori Transaksi</h2>
            <div class="table-responsive">
                <table class="table table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal & Waktu Transaksi</th>
                            <th>Total Harga</th>
                            <th>Bukti Transaksi</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $nomer = 1;
                            while ($data = mysqli_fetch_array($selectTransaksi)) {
                                $alamat = $data['alamat'] . ', ' . $data['kode_pos'] . ', ' . $data['detail_alamat'];

                                $idKucing = $data['kucing'];
                                $idPesanan = $data['id_pesanan'];

                                $selectKucing = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_pesanan IN ($idPesanan)");

                                $rasKucing = '';
                                while ($dataPesanan = mysqli_fetch_array($selectKucing)) {
                                    $rasKucing .= $dataPesanan['kucing'] . ' (' . $dataPesanan['jumlah_kucing'] . ' ekor), ';
                                }
                                $rasKucing = rtrim($rasKucing, ', ');
                                ?>
                                <tr>
                                    <td><?= $nomer; ?></td>
                                    <td><?= format_tanggal($data['waktu_transaksi']) . ' Jam ' . format_jam($data['waktu_transaksi']); ?></td>
                                    <td><?= format_harga($data['total_harga']); ?></td>
                                    <td>
                                        <button type="button" id="bukti-transaksi" class="btn btn-warning" data-bukti-transaksi="<?= $data['bukti_transaksi']; ?>">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-detail"
                                            data-nama-user="<?= $data['nama_lengkap']; ?>"
                                            data-ras-kucing="<?= $rasKucing; ?>"
                                            data-jasa-pengiriman="<?= $data['nama_jasa']; ?>"
                                            data-metode-pembayaran="<?= $data['nama_metode_pembayaran']; ?>"
                                            data-total-harga="<?= $data['total_harga']; ?>"
                                            data-tanggal-transaksi="<?= format_tanggal($data['waktu_transaksi']); ?>"
                                            data-waktu-transaksi="<?= format_jam($data['waktu_transaksi']); ?>"
                                            data-no-wallet="<?= $data['no_wallet']; ?>"
                                            data-alamat="<?= $alamat; ?>"
                                            data-bukti-transaksi="<?= $data['bukti_transaksi']; ?>"
                                            data-bs-toggle="modal" data-bs-target="#transactionModal">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php
                                $nomer++;
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal modal-lg fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionModalLabel">Detail Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless align-middle">
                        <tbody>
                            <tr>
                                <td class="fw-bold">Nama Pembeli</td>
                                <td class="text-end">
                                    <div id="namaUser" class="text-end badge bg-danger fw-semibold fs-6"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kucing Dipesan</td>
                                <td id="rasKucing" class="text-end fw-semibold"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tanggal Transaksi</td>
                                <td id="tanggalTransaksi" class="text-end"></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="fw-bold">Waktu Transaksi</td>
                                <td id="waktuTransaksi" class="text-end"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jasa Pengiriman</td>
                                <td id="jasaPengiriman" class="text-end"></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Metode Pembayaran</td>
                                <td id="metodePembayaran" class="text-end"></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="fw-bold">No Rekening/Wallet</td>
                                <td id="noWallet" class="text-end"></td>
                            </tr>
                            <tr class="border-bottom">
                                <td class="fw-bold">Total Harga</td>
                                <td class="text-end">
                                    <div id="totalHarga" class="text-price fw-semibold badge bg-success fs-6"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Alamat Tujuan</td>
                                <td id="alamat" class="text-end"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button id="downloadModalButton" class="btn btn-primary">Download</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Bukti Transaksi -->
    <div class="modal fade" id="transactionProofModal" tabindex="-1" aria-labelledby="transactionProofModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionProofModalLabel">Bukti Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="proofImage" src="" class="mx-auto" alt="Bukti Transaksi" style="max-width: 100%;">
                </div>
                <div class="modal-footer">
                    <a id="downloadButton" class="btn btn-primary" href="#" download>Download</a>
                </div>
            </div>
        </div>
    </div>


    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="fontawesome/js/all.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
        const detailButtons = document.querySelectorAll('.btn-detail');
        const namaUserElement = document.getElementById('namaUser');
        const rasKucingElement = document.getElementById('rasKucing');
        const tanggalTransaksiElement = document.getElementById('tanggalTransaksi');
        const waktuTransaksiElement = document.getElementById('waktuTransaksi');
        const jasaPengirimanElement = document.getElementById('jasaPengiriman');
        const metodePembayaranElement = document.getElementById('metodePembayaran');
        const noWalletElement = document.getElementById('noWallet');
        const alamatElement = document.getElementById('alamat');
        const totalHargaElement = document.getElementById('totalHarga');
        const proofModal = new bootstrap.Modal(document.getElementById('transactionProofModal'));
        const proofImageElement = document.getElementById('proofImage');
        const buktiTransaksiButtons = document.querySelectorAll('#bukti-transaksi');
        const downloadButton = document.getElementById('downloadButton');
        const downloadTransaksiButton = document.getElementById('downloadModalButton');

        buktiTransaksiButtons.forEach(button => {
            button.addEventListener('click', () => {
                const buktiTransaksi = button.getAttribute('data-bukti-transaksi');
                proofImageElement.src = `img/proof-transfer/${buktiTransaksi}`;
                downloadButton.href = `img/proof-transfer/${buktiTransaksi}`;
                proofModal.show();
            });
        });

        detailButtons.forEach(button => {
            button.addEventListener('click', () => {
                const rasKucing = button.getAttribute('data-ras-kucing');
                const namaUser = button.getAttribute('data-nama-user');
                const tanggalTransaksi = button.getAttribute('data-tanggal-transaksi');
                const waktuTransaksi = button.getAttribute('data-waktu-transaksi');
                const jasaPengiriman = button.getAttribute('data-jasa-pengiriman');
                const metodePembayaran = button.getAttribute('data-metode-pembayaran');
                const noWallet = button.getAttribute('data-no-wallet');
                const alamat = button.getAttribute('data-alamat');
                const totalHarga = parseInt(button.getAttribute('data-total-harga'));

                const formattedTotalHarga = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(totalHarga);

                namaUserElement.textContent = namaUser;
                rasKucingElement.innerHTML = rasKucing;
                tanggalTransaksiElement.textContent = tanggalTransaksi;
                waktuTransaksiElement.textContent = waktuTransaksi;
                jasaPengirimanElement.textContent = jasaPengiriman;
                metodePembayaranElement.textContent = metodePembayaran;
                noWalletElement.textContent = noWallet;
                alamatElement.textContent = alamat;
                totalHargaElement.textContent = formattedTotalHarga;

                downloadTransaksiButton.addEventListener('click', () => {
                // Ambil elemen modal
                    const transactionModal = document.getElementById('transactionModal');
                    
                    // Sembunyikan modal footer sebelum melakukan konversi
                    const downloadFooter = transactionModal.querySelector('#downloadModalButton');
                    downloadFooter.style.display = 'none';

                    // Konversi modal menjadi gambar dengan menggunakan html2canvas
                    html2canvas(transactionModal).then(function(canvas) {
                        // Buat link untuk mendownload gambar
                        const link = document.createElement('a');
                        link.href = canvas.toDataURL('image/jpeg'); // Mengubah gambar menjadi format JPEG
                        link.download = 'maxwellcat-proof.jpg'; // Nama file yang akan di-download
                    
                        // Klik link secara otomatis untuk memulai proses download
                        link.click();

                        downloadFooter.style.display = 'block';
                    });
                });
            });
        });
    </script>
</body>
</html>