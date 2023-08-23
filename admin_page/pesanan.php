<?php 

include '../functions.php';
include 'session.php';

if (isset($_SESSION['update-pesanan'])) {
    $successMessage = $_SESSION['update-pesanan'];
    showAlert($successMessage);
    unset($_SESSION['update-pesanan']);
} elseif (isset($_SESSION['delete-pesanan'])) {
    $successMessage = $_SESSION['delete-pesanan'];
    showAlert($successMessage);
    unset($_SESSION['delete-pesanan']);
}

$status = isset($_GET['status']) ? $_GET['status'] : '';

$statusPesanan = '1=1';
if ($status == 'dikemas') {
    $statusPesanan = "p.status_pesanan = 'dikemas'";
} elseif ($status == 'dikirim') {
    $statusPesanan = "p.status_pesanan = 'dikirim'";
} elseif ($status == 'selesai') {
    $statusPesanan = "p.status_pesanan = 'selesai'";
}

$selectPesanan = mysqli_query($conn, "SELECT * FROM pesanan p JOIN user u ON p.id_user = u.id_user WHERE $statusPesanan ORDER BY p.id_pesanan DESC");
$rowCount = mysqli_num_rows($selectPesanan);

if (isset($_POST['update'])) {
    $idPesanan = $_POST['id-pesanan'];
    $statusPesanan = $_POST['status-pesanan'];

    $updatePesanan = mysqli_query($conn, "UPDATE pesanan SET status_pesanan = '$statusPesanan' WHERE id_pesanan = '$idPesanan'");
    if ($updatePesanan) {
        $successMessage = "Status pesanan berhasil diperbarui";
        $_SESSION['update-pesanan'] = $successMessage;
        header("location: pesanan.php");
        exit();
    }
} elseif (isset($_POST['delete'])) {
    $idPesanan = $_POST['id-pesanan'];

    $deletePesanan = mysqli_query($conn, "DELETE FROM pesanan WHERE id_pesanan = '$idPesanan'");
    if ($deletePesanan) {
        $successMessage = "Pesanan berhasil dihapus";
        $_SESSION['delete-pesanan'] = $successMessage;
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
	<title>Maxwellcat-Admin | Pesanan</title>
	<link rel="stylesheet" href="../css/style_admin.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="shortcut icon" href="../img/img-website/chocola-2.jpg" type="image/x-icon">
    <style>
        .active {
            background-color: #007bff;
            color: #fff;
        }
        
        .active:hover {
            background-color: #0056b3;
        }
        
        .active:active {
            background-color: #003380;
        }
        
        .active:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.5);
        }

        .nomor-pesanan {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
	<?php include 'navbar.php'; ?>

	<div class="container-fluid py-3">
        <h2 class="text-center fw-bold mb-3">Daftar Pesanan</h2>

        <div class="container mb-3">
            <h4 class="me-2">Filter Pesanan</h4>
            <div>
                <a href="pesanan.php" class="btn btn-primary me-2 mb-2 <?php echo $status == '' ? 'active' : ''; ?>"><i class="fas fa-clipboard-list me-1"></i>Semua</a>
                <a href="pesanan.php?status=dikemas" class="btn btn-warning me-2 mb-2 <?php echo $status == 'dikemas' ? 'active' : ''; ?>"><i class="fas fa-box me-1"></i>Dikemas</a>
                <a href="pesanan.php?status=dikirim" class="btn btn-danger me-2 mb-2 <?php echo $status == 'dikirim' ? 'active' : ''; ?>"><i class="fas fa-truck me-1"></i>Dikirim</a>
                <a href="pesanan.php?status=selesai" class="btn btn-success me-2 mb-2 <?php echo $status == 'selesai' ? 'active' : ''; ?>"><i class="fas fa-check-circle me-1"></i>Selesai</a>
                <a href="transaksi.php" class="btn btn-secondary me-2 mb-2"><i class="fas fa-history me-1"></i>Transaksi</a>
            </div>
        </div>


		<div class="container">
            <div class="row">
                <?php $nomorPesanan = 1; 
                if ($rowCount > 0) {
                    while ($data = mysqli_fetch_array($selectPesanan)) { ?>
                        <div class="col-md-6">
                            <div class="card mb-4 border-">
                                <div class="card-body">
                                    <div class="nomor-pesanan bg-info bg-opacity-50"><?php echo $nomorPesanan; ?></div> <!-- Menampilkan nomor pesanan -->
                                    <table class="table table-borderless">
                                        <tr>
                                            <td>Nama Pembeli</td>
                                            <td>:</td>
                                            <td><?php echo $data['username'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Pembelian</td>
                                            <td>:</td>
                                            <td><?php echo format_tanggal($data['tanggal_pembelian']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Jam Pembelian</td>
                                            <td>:</td>
                                            <td><?php echo format_jam($data['tanggal_pembelian']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Keterangan Kucing</td>
                                            <td>:</td>
                                            <td><?php echo $data['kucing'] . ' (' . $data['jumlah_kucing'] . ' ekor)' ?></td>
                                        </tr>
                                        <tr>
                                            <td>Total Harga</td>
                                            <td>:</td>
                                            <td><?php echo format_harga($data['total_harga_pesanan']); ?></td>
                                        </tr>
                                    </table>
                                    <div class="flex-shrink-0">
                                        <form action="" method="post" class="d-flex">
                                            <?php if ($data['status_pesanan'] === 'dikemas') { ?>
                                                <select name="status-pesanan" class="form-select me-2" style="max-width: 200px;">
                                                    <option value="dikemas" selected>Dikemas</option>
                                                    <option value="dikirim">Dikirim</option>
                                                </select>
                                                <div class="ms-auto">
                                                    <input type="hidden" name="id-pesanan" value="<?= $data['id_pesanan'] ?>">
                                                    <button type="submit" name="delete" class="btn btn-danger me-2">Hapus</button>
                                                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                                                </div>
                                            <?php } elseif ($data['status_pesanan'] === 'dikirim') { ?>
                                                <select name="status-pesanan" class="form-select me-2" style="max-width: 200px;">
                                                    <option value="dikirim" selected>Dikirim</option>
                                                    <option value="selesai">Selesai</option>
                                                </select>
                                                <div class="ms-auto">
                                                    <input type="hidden" name="id-pesanan" value="<?= $data['id_pesanan'] ?>">
                                                    <button type="submit" name="delete" class="btn btn-danger me-2">Hapus</button>
                                                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                                                </div>
                                            <?php } else { ?>
                                                <span class="badge fs-5 bg-success d-flex justify-content-center align-items-center">Selesai</span>
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $nomorPesanan++;
                    }
                } else { ?>
                    <div class="col-12 text-center">
                        <p>Belum ada Pesanan</p>
                    </div>
                <?php } ?>
            </div>
        </div>
	</div>

	<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="../fontawesome/js/all.min.js"></script>
</body>
</html>
