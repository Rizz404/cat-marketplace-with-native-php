<?php 

include './functions.php';

session_start();

if (isset($_SESSION['warningMessage'])) {
    $warningMessage = $_SESSION['warningMessage'];
    unset($_SESSION['warningMessage']); // Hapus pesan dari session agar hanya ditampilkan sekali

    // Panggil fungsi showAlert() untuk menampilkan pesan peringatan
    showAlert($warningMessage);
}

if (isset($_POST['submit'])) {
    $email = invaderMustDie($conn, $_POST['email']);
    $password = invaderMustDie($conn, $_POST['password']);

    $selectUser = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");

    if (mysqli_num_rows($selectUser) > 0) {
        $data = mysqli_fetch_assoc($selectUser);

        if (password_verify($password, $data['password'])) {
            $_SESSION['id'] = $data['id_user'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['email'] = $data['email'];

            if ($data['role'] == 'super admin' || $data['role'] == 'admin') {
                $_SESSION['nama_admin'] = $data['username'];
                $successMessage = "Selamat datang Master " . $data['username'];
                $_SESSION['admin_welcome'] = $successMessage;
                header('location:admin_page/index.php');
            } elseif ($data['role'] == 'user') {
                $_SESSION['nama_user'] = $data['username'];
                $successMessage = "Anda berhasil login! Halo " . $data['username'];
                $_SESSION['user_welcome'] = $successMessage;
                header('location:index.php');
            }
        } else {
            $error[] = 'Password salah!';
        }
    } else {
        $error[] = 'Email tidak terdaftar!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat | Login</title>
    <link rel="stylesheet" href="css/style_reg_log.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="shortcut icon" href="img/img-website/chocola-3.jpg" type="image/x-icon">
</head>
<body>
    <div class="form-container">
        <form action="" method="post">
            <h3 class="fw-bold">masuk sekarang</h3>
            <?php 
            if (isset($error)) {
                foreach ($error as $error) { ?>
                    <span class="pesan-error"><?= $error; ?></span>
        <?php   }
            }
            ?>
            <input type="email" name="email" id="email" required placeholder="masukkan email">
            <input type="password" name="password" id="password" required placeholder="masukkan password">
            <button type="submit" name="submit" class="btn-form">masuk</button>
            <p>belum mempunyai akun? <a href="register.php">daftar sekarang</a></p>
        </form>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="fontawesome/js/all.js"></script>
</body>
</html>