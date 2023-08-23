<?php

include '../functions.php';
include 'session.php';

if (isset($_SESSION['update-kucing'])) {
    $successMessage = $_SESSION['update-kucing'];
    showAlert($successMessage);
    unset($_SESSION['update-kucing']);
}

$id = $_GET['id'];

if (isset($id)) {
    $idKucing = $_GET['id'];

    $selectKucing = mysqli_query($conn, "SELECT * FROM kucing WHERE id_kucing = '$idKucing'");
    $dataKucing = mysqli_fetch_assoc($selectKucing);
    $kategoriKucing = explode(', ', $dataKucing['kategori_kucing']);
} else {
    header("location: manajemen_kucing.php");
    exit();
}

if (isset($_POST['update'])) {
    $ras = invaderMustDie($conn, $_POST['ras']);
    $umur = invaderMustDie($conn, $_POST['umur']);
    $harga = invaderMustDie($conn, $_POST['harga']);
    $jumlah = invaderMustDie($conn, $_POST['jumlah']);
    $kategori = isset($_POST['kategori']) ? $_POST['kategori'] : [];

    // Mengubah tanda "_" menjadi spasi dan mengonversi huruf pertama menjadi huruf kapital
    // Menghapus kategori yang tidak dicek
    $kategoriKucingBaru = [];
    foreach ($kategori as $k) {
        $k = str_replace('_', ' ', $k);
        $k = ucwords($k);
        $kategoriKucingBaru[] = $k;
    }

    // Menghapus kategori yang tidak dicek
    foreach ($kategoriKucing as $kategoriLama) {
        if (!in_array($kategoriLama, $kategoriKucingBaru)) {
            $kategoriKucingBaru = array_diff($kategoriKucingBaru, [$kategoriLama]);
        }
    }

    // Menggabungkan kategori menjadi satu string dipisahkan dengan koma
    $kategoriString = implode(', ', $kategoriKucingBaru);
    $kategoriKucing = invaderMustDie($conn, $kategoriString);
    $deskripsi = invaderMustDie($conn, $_POST['deskripsi']);

    // Cek apakah ada file gambar yang diunggah
    if ($_FILES["gambar"]["name"]) {
        $target_dir = "../img/img-product/";
        $nama_file = basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $nama_file;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $image_size = ($_FILES["gambar"]["size"]);

        // Ketika file tidak sesuai perintah
        if ($image_size > 10000000) { ?>
            <div role="alert">
                File tidak boleh lebih dari 10mb
            </div>
<?php   } else {
            // Untuk mengecek membuat file yang sama tidak teroverwrite
            $file_ext = pathinfo($target_file, PATHINFO_EXTENSION);
            $file_name_only = pathinfo($target_file, PATHINFO_FILENAME);

            for ($i = 1; file_exists($target_file); $i++) {
                $target_file = $target_dir . '/' . $file_name_only . '(' . $i . ').' . $file_ext;
            }
            move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file);

            // Query update dengan gambar yang baru
            $nama_file_baru = basename($target_file);
            $updateKucing = mysqli_query($conn, "UPDATE kucing SET ras = '$ras', umur = '$umur', harga = '$harga', jumlah_tersedia = '$jumlah', kategori_kucing = '$kategoriString', deskripsi = '$deskripsi', gambar = '$nama_file_baru' WHERE id_kucing = '$id'");
        }
    } else {
        // Query update tanpa mengubah gambar
        $updateKucing = mysqli_query($conn, "UPDATE kucing SET ras = '$ras', umur = '$umur', harga = '$harga', jumlah_tersedia = '$jumlah', kategori_kucing = '$kategoriString', deskripsi = '$deskripsi' WHERE id_kucing = '$id'");
    }

    if ($updateKucing) { 
        header('location: ' . $_SERVER['HTTP_REFERER']);
        $successMessage = "Kucing berhasil diperbarui";
        $_SESSION['update-kucing'] = $successMessage;
    } else {
        echo mysqli_error($conn);
    }

} elseif (isset($_POST['delete'])) {
    $deleteKucing = mysqli_query($conn, "DELETE FROM kucing WHERE id_kucing = '$id'");

    if ($deleteKucing) { 
        header("location: manajemen_kucing.php");
        $successMessage = "Kucing berhasil dihapus";
        $_SESSION['delete-kucing'] = $successMessage;
    } else {
        echo mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat-Admin | Detail Kucing</title>
    <link rel="stylesheet" href="../css/style_admin.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="shortcut icon" href="../img/img-website/chocola-2.jpg" type="image/x-icon">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="manajemen_kucing.php" class="text-decoration-none text-breadcrumb"><i class="fa fa-list"></i> List Kucing</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><i class="fa fa-cat"></i> Detail Kucing</li>
                </ol>
            </nav>
        </div>

        <!-- Konten -->
        <div class="container d-flex justify-content-center align-items-center my-3">
            <form action="" method="post" class="p-4 bg-light rounded" enctype="multipart/form-data">
                <div class="row">
                    <h2 class="text-center mb-3">Detail Kucing</h2>
                    <div class="d-flex justify-content-center mb-2">
                        <img src="../img/img-product/<?= $dataKucing['gambar']; ?>" alt="Gambar Kucing" class="img-thumbnail text-center img-pointer">
                    </div>
                    <div class="text-center mb-3">
                        <label for="gambar" class="btn btn-primary">Ganti foto <i class="fa fa-edit"></i></label>
                        <input type="file" name="gambar" value="<?= $dataKucing['gambar']; ?>" id="gambar" class="d-none" accept=".jpg, .png, jpeg">
                        <button type="submit" name="update" class="btn btn-success">Perbarui</button>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="ras">Ras kucing</label>
                        </div>
                        <div class="col">
                            <input type="text" name="ras" id="ras" value="<?php echo $dataKucing['ras']; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="umur">Umur</label>
                        </div>
                        <div class="col">
                            <input type="text" name="umur" id="umur" value="<?php echo $dataKucing['umur']; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="harga">Harga:</label>
                        </div>
                        <div class="col">
                            <input type="text" name="harga" id="harga" value="<?php echo $dataKucing['harga']; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="jumlah">Jumlah</label>
                        </div>
                        <div class="col">
                            <input type="text" name="jumlah" id="jumlah" value="<?php echo $dataKucing['jumlah_tersedia']; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="kategori">Kategori</label>
                        </div>
                        <div class="col">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="kategori[]" id="berbulu_panjang" value="Berbulu Panjang" <?php if (in_array('Berbulu Panjang', $kategoriKucing)) echo 'checked'; ?>>
                                <label class="form-check-label" for="berbulu_panjang">Berbulu Panjang</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="kategori[]" id="berbulu_pendek" value="Berbulu Pendek" <?php if (in_array('Berbulu Pendek', $kategoriKucing)) echo 'checked'; ?>>
                                <label class="form-check-label" for="berbulu_pendek">Berbulu Pendek</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="kategori[]" id="berukuran_besar" value="Berukuran Besar" <?php if (in_array('Berukuran Besar', $kategoriKucing)) echo 'checked'; ?>>
                                <label class="form-check-label" for="berukuran_besar">Berukuran Besar</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="kategori[]" id="berukuran_sedang" value="Berukuran Sedang" <?php if (in_array('Berukuran Sedang', $kategoriKucing)) echo 'checked'; ?>>
                                <label class="form-check-label" for="berukuran_sedang">Berukuran Sedang</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="kategori[]" id="berukuran_kecil" value="Berukuran Kecil" <?php if (in_array('Berukuran Kecil', $kategoriKucing)) echo 'checked'; ?>>
                                <label class="form-check-label" for="berukuran_kecil">Berukuran Kecil</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="kategori[]" id="persilangan" value="Persilangan" <?php if (in_array('Persilangan', $kategoriKucing)) echo 'checked'; ?>>
                                <label class="form-check-label" for="persilangan">Persilangan</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="deskripsi">Deskripsi:</label>
                        </div>
                        <div class="col">
                            <textarea name="deskripsi" id="deskripsi" cols="30" rows="5" class="form-control"><?php echo $dataKucing['deskripsi']; ?></textarea>
                        </div>
                    </div>
                    <div class="text-end mb-3">
                        <button type="submit" class="btn btn-danger" name="delete">Hapus</button>
                        <button type="submit" class="btn btn-primary" name="update">Perbarui</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="../fontawesome/js/all.min.js"></script>
</body>
</html>