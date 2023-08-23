<?php

include '../functions.php';
include 'session.php';

// Periksa apakah parameter ID produk ada dalam URL
$id = $_GET['id'];
if (isset($id)) {
    $idUser = $_GET['id'];

    $selectUser = mysqli_query($conn, "SELECT * FROM user u LEFT JOIN detail_user du ON u.id_user = du.id_user WHERE u.id_user = '$idUser'");
    $dataUser = mysqli_fetch_assoc($selectUser);
} else {
    header("Location: manajemen_user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat-Admin | Detail User</title>
    <link rel="stylesheet" href="../css/style_admin.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="shortcut icon" href="../img/img-website/chocola-2.jpg" type="image/x-icon">
    <style>
        #kode-pos {
            max-width: 150px;
        }

        .img-pointer {
            cursor: pointer;
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
                    <li class="breadcrumb-item"><a href="manajemen_user.php" class="text-decoration-none text-breadcrumb"><i class="fa fa-user-group"></i> List User</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><i class="fa fa-user"></i> Detail User</li>
                </ol>
            </nav>
        </div>

        <div class="container d-flex justify-content-center align-items-center my-3">
            <form action="" method="post" class="p-4 bg-light rounded" enctype="multipart/form-data">
                <div class="row">
                    <h2 class="text-center mb-3">Profil Saya</h2>
                    <div class="d-flex justify-content-center mb-2">
                        <img src="../img/user-picture/<?= $dataUser['foto_profile']; ?>" alt="Gambar Profil" class="profile-image img-account text-center img-pointer" data-bs-toggle="modal" data-bs-target="#imageModal">
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="username" class="form-label">Username</label>
                        </div>
                        <div class="col">
                            <input type="text" name="username" id="username" class="form-control" value="<?= $dataUser['username']; ?>" disabled>
                        </div>
                    </div>
                    <div class="d-flex mb-5">
                        <div class="col-3">
                            <label for="email" class="form-label">Email</label>
                        </div>
                        <div class="col">
                            <input type="text" name="email" id="email" class="form-control" value="<?= $dataUser['email']; ?>" disabled>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="fullname" class="form-label">Nama Lengkap</label>
                        </div>
                        <div class="col">
                            <input type="text" name="fullname" id="fullname" class="form-control" value="<?= $dataUser['nama_lengkap']; ?>" placeholder="nama lengkap anda" disabled>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="gender" class="form-label">Jenis kelamin</label>
                        </div>
                        <div class="col">
                            <select name="gender" id="gender" class="form-select" disabled>
                                <option value="0" <?= $dataUser['gender'] == 'NULL' ? 'disabled selected' : '' ?>>Pilih Kelamin</option>
                                <option value="Pria" <?= $dataUser['gender'] == 'Pria' ? 'selected' : '' ?>>Pria</option>
                                <option value="Wanita" <?= $dataUser['gender'] == 'Wanita' ? 'selected' : '' ?>>Wanita</option>
                                <option value="Lainnya" <?= $dataUser['gender'] == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="birth" class="form-label">Tanggal Lahir</label>
                        </div>
                        <div class="col">
                            <input type="date" name="birth" id="birth" class="form-control" value="<?= $dataUser['tanggal_lahir']; ?>" disabled>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="alamat" class="form-label">Alamat</label>
                        </div>
                        <div class="col">
                            <textarea name="alamat" id="alamat" rows="1" class="form-control" placeholder="Provinsi, Kota, Kecamatan" required disabled><?= $dataUser['alamat']; ?></textarea>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="kode-pos" class="form-label">Kode Pos</label>
                        </div>
                        <div class="col">
                            <input type="number" name="kode-pos" id="kode-pos" class="form-control" placeholder="Kode Pos" value="<?= $dataUser['kode_pos']; ?>" required disabled>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="detail-alamat" class="form-label">Detail Alamat</label>
                        </div>
                        <div class="col">
                            <textarea name="detail-alamat" id="detail-alamat" cols="30" rows="5" class="form-control" required disabled><?= $dataUser['detail_alamat']; ?></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0 d-flex justify-content-center">
                    <img src="../img/user-picture/<?= $dataUser['foto_profile']; ?>" alt="Gambar Profil" class="w-100 img-fluid">
                </div>
            </div>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
	<script src="../fontawesome/js/all.min.js"></script>
</body>
</html>