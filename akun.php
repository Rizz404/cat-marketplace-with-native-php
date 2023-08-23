<?php

session_start();

include 'functions.php';

$idUser = $_SESSION['id'];

if (isset($_SESSION['KYC'])) {
    $warningMessage = $_SESSION['KYC'];
    showAlert($warningMessage);
    unset($_SESSION['KYC']);
} elseif (isset($_SESSION['update-user'])) {
    $successMessage = $_SESSION['update-user'];
    showAlert($successMessage);
    unset($_SESSION['update-user']);
} elseif (isset($_SESSION['password-not-match'])) {
    $errorMessage = $_SESSION['password-not-match'];
    showAlert($errorMessage);
    unset($_SESSION['password-not-match']);
} elseif (isset($_SESSION['update-password'])) {
    $successMessage = $_SESSION['update-password'];
    showAlert($successMessage);
    unset($_SESSION['update-password']);
} elseif (isset($_SESSION['update-password-failed'])) {
    $errorMessage = $_SESSION['update-password-failed'];
    showAlert($errorMessage);
    unset($_SESSION['update-password-failed']);
}

$selectUser = mysqli_query($conn, "SELECT * FROM user u LEFT JOIN detail_user du ON u.id_user = du.id_user WHERE u.id_user = '$idUser'");
$dataUser = mysqli_fetch_assoc($selectUser);

if (isset($_POST['submit'])) {
    $username = invaderMustDie($conn, $_POST['username']);
    $email = invaderMustDie($conn, $_POST['email']);
    $fullname = invaderMustDie($conn, $_POST['fullname']);
    $gender = invaderMustDie($conn, $_POST['gender']);
    $birthdate = invaderMustDie($conn, $_POST['birth']);
    $alamat = invaderMustDie($conn, $_POST['alamat']);
    $kodePos = invaderMustDie($conn, $_POST['kode-pos']);
    $detailAlamat = invaderMustDie($conn, $_POST['detail-alamat']);

    // ketika file tidak sesuai perintah
    if ($_FILES["change-picture"]["name"]) {
        $target_dir = "img/user-picture/";
        $nama_file = basename($_FILES["change-picture"]["name"]);
        $target_file = $target_dir . $nama_file;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $image_size = ($_FILES["change-picture"]["size"]);

        if ($image_size > 10000000) {
            $warningMessage = "File tidak boleh lebih dari 10 mb";
            showAlert($warningMessage);
        } else { // untuk mengecek membuat file yang sama tidak teroverwrite
            $file_ext = pathinfo($target_file, PATHINFO_EXTENSION);
            $file_name_only = pathinfo($target_file, PATHINFO_FILENAME);

            for ($i = 1; file_exists($target_file); $i++) {
                $target_file = $target_dir . '/' . $file_name_only . '(' . $i . ').' . $file_ext;
            }
            (move_uploaded_file($_FILES["change-picture"]["tmp_name"], $target_file));

            $fotoProfileBaru = basename($target_file);
            if ($dataUser && !empty($dataUser['id_user'])) {
                // Jika data detail_user sudah ada, perbarui data yang diinputkan
                $updateDetailUser = mysqli_query($conn, "UPDATE detail_user SET
                    nama_lengkap = IFNULL('$fullname', nama_lengkap),
                    gender = IFNULL('$gender', gender),
                    tanggal_lahir = IFNULL('$birthdate', tanggal_lahir),
                    alamat = IFNULL('$alamat', alamat),
                    kode_pos = IFNULL('$kodePos', kode_pos),
                    detail_alamat = IFNULL('$detailAlamat', detail_alamat),
                    foto_profile = IFNULL('$fotoProfileBaru', foto_profile)
                    WHERE id_user = '$idUser'");
            } elseif (empty($dataUser['id_user'])) {
                // Jika data detail_user belum ada, tambahkan data baru
                $insertDetailUser = mysqli_query($conn, "INSERT INTO detail_user (id_user, nama_lengkap, gender, tanggal_lahir, alamat, kode_pos, detail_alamat, foto_profile)
                    VALUES ('$idUser', '$fullname', '$gender', '$birthdate', '$kodePos', '$alamat', '$detailAlamat', '$fotoProfileBaru')");
            }
        }
    } else {
        if ($dataUser && !empty($dataUser['id_user'])) {
            // Jika data detail_user sudah ada, perbarui data yang diinputkan
            $updateDetailUser = mysqli_query($conn, "UPDATE detail_user SET
                nama_lengkap = IFNULL('$fullname', nama_lengkap),
                gender = IFNULL('$gender', gender),
                tanggal_lahir = IFNULL('$birthdate', tanggal_lahir),
                alamat = IFNULL('$alamat', alamat),
                kode_pos = IFNULL('$kodePos', kode_pos),
                detail_alamat = IFNULL('$detailAlamat', detail_alamat)
                WHERE id_user = '$idUser'");
        } elseif (empty($dataUser['id_user'])) {
            // Jika data detail_user belum ada, tambahkan data baru
            $insertDetailUser = mysqli_query($conn, "INSERT INTO detail_user (id_user, nama_lengkap, gender, tanggal_lahir, alamat, kode_pos, detail_alamat)
                VALUES ('$idUser', '$fullname', '$gender', '$birthdate', '$kodePos', '$alamat', '$detailAlamat')");
        }
    }

    // Perbarui data pengguna di database
    $updateUser = mysqli_query($conn, "UPDATE user SET username = '$username', email = '$email' WHERE id_user = '$idUser'");

    if ($updateUser && (($dataUser && !empty($dataUser)) ? $updateDetailUser : $insertDetailUser)) {
        // Perbarui data pengguna dalam variabel session
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        $successMessage = "Data berhasil diperbarui";
        $_SESSION['update-user'] = $successMessage;
        header("location: akun.php");
    } else {
        echo mysqli_error($conn);
    }
}

if (isset($_POST['old-password'])) {
    $oldPassword = $_POST['current-password'];

    // Get the hashed password from the database for the logged-in user
    $selectOldPassword = mysqli_query($conn, "SELECT password FROM user WHERE id_user = '$idUser'");
    $userData = mysqli_fetch_assoc($selectOldPassword);
    $hashedPassword = $userData['password'];

    if (validatePassword($oldPassword, $hashedPassword)) {
        // Tampilkan modal untuk mengganti kata sandi
        echo '<script>
          document.addEventListener("DOMContentLoaded", function() {
            var modal = document.getElementById("changePasswordModal");
            var modalInstance = new bootstrap.Modal(modal);
    
            modalInstance.show();
          });
        </script>';
    } else {
        // Kata sandi tidak cocok, tampilkan pesan kesalahan
        $errorMessage = "Password tidak sama";
        $_SESSION['password-not-match'] = $errorMessage;
        header("location: akun.php");
    }
}

if (isset($_POST['change-password'])) {
    $newPassword = $_POST['new-password'];
    $confirmPassword = $_POST['confirm-password'];

    if ($newPassword === $confirmPassword) {
        // Enkripsi kata sandi baru
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Perbarui kata sandi pengguna di database
        $updatePassword = mysqli_query($conn, "UPDATE user SET password = '$hashedPassword' WHERE id_user = '$idUser'");

        if ($updatePassword) {
            $successMessage = "Kata sandi berhasil diubah";
            $_SESSION['update-password'] = $successMessage;
            header("location: akun.php");
        } else {
            $errorMessage = "Terjadi kesalahan. Silakan coba lagi";
            header("location: akun.php");
        }
    } else {
        $errorMessage = "Konfirmasi kata sandi tidak cocok";
        $_SESSION['update-password-failed'] = $errorMessage;
        header("location: akun.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat | Akun</title>
    <link rel="stylesheet" href="css/style_user.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="shortcut icon" href="img/img-website/chocola-3.jpg" type="image/x-icon">
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
        <div class="container d-flex justify-content-center align-items-center my-3">
            <form action="" method="post" class="p-4 bg-light rounded" enctype="multipart/form-data">
                <div class="row">
                    <h2 class="text-center mb-3">Profil Saya</h2>
                    <div class="d-flex justify-content-center mb-2">
                        <img src="img/user-picture/<?= $dataUser['foto_profile']; ?>" alt="Gambar Profil" class="profile-image img-account text-center img-pointer" data-bs-toggle="modal" data-bs-target="#imageModal">
                    </div>
                    <div class="text-center mb-3">
                        <label for="change-picture" class="btn btn-primary">Ganti foto <i class="fa fa-edit"></i></label>
                        <input type="file" name="change-picture" value="<?= $dataUser['foto_profile']; ?>" id="change-picture" class="d-none" accept=".jpg, .png, jpeg">
                        <button type="submit" name="submit" class="btn btn-success">Perbarui</button>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="username" class="form-label">Username</label>
                        </div>
                        <div class="col">
                            <input type="text" name="username" id="username" class="form-control" value="<?= $dataUser['username']; ?>">
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="email" class="form-label">Email</label>
                        </div>
                        <div class="col">
                            <input type="text" name="email" id="email" class="form-control" value="<?= $dataUser['email']; ?>">
                        </div>
                    </div>
                    <div class="text-center my-4">
                        <!-- karena bootstrap 5 tambahkan bs ingat!!! -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#passwordModal">Ganti password</button>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="fullname" class="form-label">Nama Lengkap</label>
                        </div>
                        <div class="col">
                            <input type="text" name="fullname" id="fullname" class="form-control" value="<?= $dataUser['nama_lengkap']; ?>" placeholder="nama lengkap anda">
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="gender" class="form-label">Jenis kelamin</label>
                        </div>
                        <div class="col">
                            <select name="gender" id="gender" class="form-select">
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
                            <input type="date" name="birth" id="birth" class="form-control" value="<?= $dataUser['tanggal_lahir']; ?>">
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="alamat" class="form-label">Alamat</label>
                        </div>
                        <div class="col">
                            <textarea name="alamat" id="alamat" rows="1" class="form-control" placeholder="Provinsi, Kota, Kecamatan" required><?= $dataUser['alamat']; ?></textarea>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="kode-pos" class="form-label">Kode Pos</label>
                        </div>
                        <div class="col">
                            <input type="number" name="kode-pos" id="kode-pos" class="form-control" placeholder="Kode Pos" value="<?= $dataUser['kode_pos']; ?>" required>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="col-3">
                            <label for="detail-alamat" class="form-label">Detail Alamat</label>
                        </div>
                        <div class="col">
                            <textarea name="detail-alamat" id="detail-alamat" cols="30" rows="5" class="form-control" required><?= $dataUser['detail_alamat']; ?></textarea>
                        </div>
                    </div>
                    <div class="text-end mb-3">
                        <a href="logout.php" class="btn btn-danger">Logout</a>
                        <button type="submit" name="submit" class="btn btn-primary">Perbarui</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal konfirmasi password lama -->
    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Masukkan password lama</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="password" name="current-password" class="form-control" id="current-password" placeholder="Masukkan password lama" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" name="old-password" class="btn btn-primary" id="changePasswordBtn" data-bs-toggle="modal" data-bs-target="#modal2">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0 d-flex justify-content-center">
                    <img src="img/user-picture/<?= $dataUser['foto_profile']; ?>" alt="Gambar Profil" class="w-100 img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk ganti kata sandi -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Ganti Kata Sandi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <div class="input-group mb-2">
                            <input type="password" name="new-password" class="form-control" id="newPasswordInput" placeholder="Masukkan password baru" required>
                            <button type="button" id="newPasswordButton" class="btn btn-outline-secondary">
                                <i id="newPasswordEyeIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="input-group">
                            <input type="password" name="confirm-password" class="form-control" id="confirmPasswordInput" placeholder="Konfirmasi password baru" required>
                            <button type="button" id="confirmPasswordButton" class="btn btn-outline-secondary">
                                <i id="confirmPasswordEyeIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="change-password" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="./js/script_user.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="fontawesome/js/all.js"></script>
</body>

</html>