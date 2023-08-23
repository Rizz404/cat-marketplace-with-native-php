<?php

include '../functions.php';
include 'session.php';

if (isset($_SESSION['new-role'])) {
    $successMessage = $_SESSION['new-role'];
    showAlert($successMessage);
    unset($_SESSION['new-role']);
}

$selectUser = mysqli_query($conn, "SELECT u.*, du.nama_lengkap, du.gender, du.tanggal_lahir, du.alamat FROM user u LEFT JOIN detail_user du ON u.id_user = du.id_user ORDER BY u.id_user");
$rowCount = mysqli_num_rows($selectUser);

if (isset($_POST['update'])) {
    $idUser = $_POST['id_user'];
    $role = $_POST['role'];

    // Lakukan query untuk memperbarui role user
    $updateRoleUser = mysqli_query($conn, "UPDATE user SET role = '$role' WHERE id_user = '$idUser'");

    if ($updateRoleUser) {
        $successMessage = "Role berhasil diubah";
        $_SESSION['new-role'] = $successMessage;
        header("location: manajemen_user.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat-Admin | Manajemen User</title>
    <link rel="stylesheet" href="../css/style_admin.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="shortcut icon" href="../img/img-website/chocola-2.jpg" type="image/x-icon">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container-fluid py-3">
        <div class="container">
            <h2 class="text-center fw-bold">Daftar User</h2>
            <div class="table-responsive mt-4 text-center">
                <table class="table table-striped">
                    <thead class="align-middle text-center fs-5">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        <?php
                        if ($rowCount == 0) { ?>
                            <tr>
                                <td colspan="6">Tidak ada data produk</td>
                            </tr>

                            <?php    } else {
                            $nomer = 1;
                            while ($data = mysqli_fetch_array($selectUser)) { ?>
                                <tr>
                                    <td class="text-center"><?= $nomer; ?></td>
                                    <td class="ps-4"><?= $data['username']; ?></td>
                                    <td class="ps-4"><?= $data['email']; ?></td>
                                    <td class="text-center">
                                        <?= $data['role']; ?>
                                        <button type="button" id="editRoleButtons" class="btn" data-role="<?= $data['role']; ?>" data-bs-toggle="modal" data-bs-target="#editRoleModal" data-id="<?= $data['id_user']; ?>">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($data['nama_lengkap'] != null) { ?>
                                            <a href="detail_user.php?id=<?= $data['id_user']; ?>" class="btn btn-outline-info">Detail <i class="fa-solid fa-magnifying-glass"></i></a>
                                        <?php   } else { ?>
                                            Detail belum tersedia
                                        <?php   } ?>
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

    <!-- Modal -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" id="editRoleForm">
                    <div class="modal-body">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="role1" value="super admin">
                            <label class="form-check-label" for="role1">
                                Super Admin
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="role2" value="admin">
                            <label class="form-check-label" for="role2">
                                Admin
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="role3" value="user">
                            <label class="form-check-label" for="role3">
                                User
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_user" id="idUserInput" value="">
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../js/script_admin.js"></script>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/js/all.min.js"></script>
</body>

</html>