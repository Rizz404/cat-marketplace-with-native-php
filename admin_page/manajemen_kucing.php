<?php 

include '../functions.php';
include 'session.php';

if (isset($_SESSION['insert-kucing'])) {
    $successMessage = $_SESSION['insert-kucing'];
    showAlert($successMessage);
    unset($_SESSION['insert-kucing']);
} elseif (isset($_SESSION['delete-kucing'])) {
    $successMessage = $_SESSION['delete-kucing'];
    showAlert($successMessage);
    unset($_SESSION['delete-kucing']);
}

$selectKucing = mysqli_query($conn, "SELECT * FROM kucing ORDER BY id_kucing DESC");
$rowCountKucing = mysqli_num_rows($selectKucing);
$idUser = $_SESSION['id'];

if (isset($_POST['submit'])) {
    $ras = invaderMustDie($conn, $_POST['ras']);
    $umur = invaderMustDie($conn, $_POST['umur']);
    $harga = invaderMustDie($conn, $_POST['harga']);
    $jumlah = invaderMustDie($conn, $_POST['jumlah']);
    $kategori = isset($_POST['kategori']) ? $_POST['kategori'] : [];
    // Mengubah tanda "_" menjadi spasi dan mengonversi huruf pertama menjadi huruf kapital
    $kategoriKucing = [];
    foreach ($kategori as $k) {
        $k = str_replace('_', ' ', $k);
        $k = ucwords($k);
        $kategoriKucing[] = $k;
    }
    // Menggabungkan kategori menjadi satu string dipisahkan dengan koma
    $kategoriString = implode(', ', $kategoriKucing);
    $kategoriKucing = mysqli_real_escape_string($conn, $kategoriString);
    $deskripsi = invaderMustDie($conn, $_POST['deskripsi']);
    
    $target_dir = "../img/img-product/";
    $nama_file = basename($_FILES["gambar"]["name"]);
    $target_file = $target_dir . $nama_file;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $image_size = ($_FILES["gambar"]["size"]);

    if ($nama_file != '') {
        if ($image_size > 10000000) { 
            $errorMessage = "File anda terlalu besar";
            showAlert($errorMessage);
            header("refresh: 2");
        } else {
            $file_ext = pathinfo($target_file, PATHINFO_EXTENSION);
            $file_name_only = pathinfo($target_file, PATHINFO_FILENAME);
            
            for ($i = 1; file_exists($target_file); $i++) {
                $target_file = $target_dir . '/' . $file_name_only . '(' . $i . ').' . $file_ext;
            }
            (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file));
        }
    }

    $nama_file_baru = basename($target_file);
    $insertKucing = mysqli_query($conn, "INSERT INTO kucing (id_user, ras, umur, harga, jumlah_tersedia, gambar, kategori_kucing, deskripsi) VALUES ('$idUser', '$ras', '$umur', '$harga', '$jumlah', '$nama_file_baru', '$kategoriString', '$deskripsi') ");

    if ($insertKucing) { 
        $successMessage = "Kucing berhasil ditambahkan";
        $_SESSION['insert-kucing'] = $successMessage;
        header("location: manajemen_kucing.php");
    } else {
        echo mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Maxwellcat-Admin | Manajemen Kucing</title>
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../css/style_admin.css">
	<link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="shortcut icon" href="../img/img-website/chocola-2.jpg" type="image/x-icon">
</head>
<body>
	<?php include 'navbar.php'; ?>
	
	<div class="container mt-3">
        <div class="row">
            <div class="col-12 text-center">
                <div class="my-3 col-md-6 mx-auto">
                    <button class="btn btn-outline-primary btn-lg" data-bs-toggle="modal" data-bs-target="#tambahKucingModal">
                        Tambah Kucing <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kucing -->
    <div class="modal fade" id="tambahKucingModal" tabindex="-1" aria-labelledby="tambahKucingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKucingModalLabel">Tambah Kucing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-lg-6 mb-2">
								<label for="ras">Ras kucing</label>
								<input type="text" name="ras" id="ras" autocomplete="off" required class="form-control">
							</div>
							<div class="col-lg-6 mb-2">
								<label for="umur">Pilih Umur</label>
								<select name="umur" id="umur" class="form-control">
									<option value="0" disabled selected>pilih umur</option>
									<option value="dibawah 3 bulan">dibawah 3 bulan</option>
									<option value="3 bulan - 7 bulan">3 bulan - 7 bulan</option>
									<option value="7 bulan - 1 tahun">7 bulan - 1 tahun</option>
									<option value="diatas 1 tahun">diatas 1 tahun</option>
								</select>
							</div>
							<div class="col-lg-6 mb-2">
								<label for="harga">Harga</label>
								<input type="number" name="harga" id="harga" autocomplete="off" required class="form-control">
							</div>
							<div class="col-lg-6 mb-2">
								<label for="jumlah">Jumlah Tersedia</label>
								<input type="number" name="jumlah" id="jumlah" autocomplete="off" required class="form-control">
							</div>
							<div class="col-12 mb-2">
								<label for="gambar">Foto</label>
								<input type="file" name="gambar" id="gambar" required accept=".jpg, .png, jpeg" class="form-control">
							</div>
						</div>

                        <div class="form-group mb-2">
                            <label for="kategori" class="form-label">Kategori Kucing</label>
                            <div class="row justify-content-center">
								<div class="col-lg-4 col-md-6 text-center">
									<div class="form-check mt-1">
										<input type="checkbox" class="btn-check" name="kategori[]" id="berbulu_panjang" value="berbulu_panjang">
										<label class="btn btn-outline-primary" for="berbulu_panjang">Berbulu Panjang</label>
									</div>
								</div>
								<div class="col-lg-4 col-md-6 text-center">
									<div class="form-check mt-1">
										<input type="checkbox" class="btn-check" name="kategori[]" id="berbulu_pendek" value="berbulu_pendek">
										<label class="btn btn-outline-primary" for="berbulu_pendek">Berbulu Pendek</label>
									</div>	
								</div>
								<div class="col-lg-4 col-md-6 text-center">
									<div class="form-check mt-1">
										<input type="checkbox" class="btn-check" name="kategori[]" id="berukuran_besar" value="berukuran_besar">
										<label class="btn btn-outline-primary" for="berukuran_besar">Berukuran Besar</label>
									</div>
								</div>
								<div class="col-lg-4 col-md-6 text-center">
									<div class="form-check mt-1">
										<input type="checkbox" class="btn-check" name="kategori[]" id="berukuran_sedang" value="berukuran_sedang">
										<label class="btn btn-outline-primary" for="berukuran_sedang">Berukuran Sedang</label>
									</div>
								</div>
								<div class="col-lg-4 col-md-6 text-center">
									<div class="form-check mt-1">
										<input type="checkbox" class="btn-check" name="kategori[]" id="berukuran_kecil" value="berukuran_kecil">
										<label class="btn btn-outline-primary" for="berukuran_kecil">Berukuran Kecil</label>
									</div>
								</div>
								<div class="col-lg-4 col-md-6 text-center">
									<div class="form-check mt-1">
										<input type="checkbox" class="btn-check" name="kategori[]" id="kucing_persilangan" value="kucing_persilangan">
										<label class="btn btn-outline-primary" for="kucing_persilangan">Persilangan</label>
									</div>
								</div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-primary" type="submit" name="submit">Tambah Kucing</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="mb-5">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="text-center fs-5">
                        <tr>
                            <th>No.</th>
                            <th>Foto Kucing</th>
                            <th>Ras</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        <?php
                        if ($rowCountKucing == 0) { ?>
                            <tr>
                                <td colspan="6">Tidak ada data produk</td>
                            </tr>
                        <?php   } else {
                            $nomer = 1;
                            while ($data = mysqli_fetch_array($selectKucing)) { ?>
                                <tr>
                                    <td class="text-center"><?php echo $nomer; ?></td>
                                    <td class="text-center"><img src="../img/img-product/<?php echo $data['gambar']; ?>" alt="gambar kucing" class="gambar-kucing img-thumbnail"></td>
                                    <td class="text-center"><?php echo $data['ras']; ?></td>
                                    <td class="text-end pe-5"><?php echo format_harga($data['harga']); ?></td>
                                    <td class="text-center"><?php echo $data['jumlah_tersedia']; ?></td>
                                    <td class="text-center">
                                        <a href="detail_kucing.php?id=<?php echo $data['id_kucing']; ?>" class="btn btn-info">
                                            <i class="fa-solid fa-magnifying-glass"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                        <?php
                                $nomer++;
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

	<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="../fontawesome/js/all.min.js"></script>
</body>
</html>