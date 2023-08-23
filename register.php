<?php 

include './functions.php';

if (isset($_POST['submit'])) {
    $username = invaderMustDie($conn, $_POST['username']);
    $email = invaderMustDie($conn, $_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['confirm-password'];
    
    // Mengenkripsi password dengan password_hash
    $enkripsiPassword = password_hash($password, PASSWORD_DEFAULT);
    $role = "user";

    $selectUser = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email' OR username = '$username'");

    if (mysqli_num_rows($selectUser) > 0) {
        $error[] = 'user sudah ada!';
    } else {
        if ($password != $cpassword) {
            $error[] = 'password tidak sama!'; 
        } else {        
            $insertUser = mysqli_query($conn, "INSERT INTO user(username, email, password, role) VALUES ('$username','$email','$enkripsiPassword','$role')");
            // Mendapatkan id_user baru
            $idUser = mysqli_insert_id($conn);

            $insertDetailUser = mysqli_query($conn, "INSERT INTO detail_user(id_user) VALUES ('$idUser')");
            header('location:login.php');
        }
    }
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat | Register</title>
    <link rel="stylesheet" href="css/style_reg_log.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="shortcut icon" href="img/img-website/chocola-3.jpg" type="image/x-icon">
</head>
<body>
    <div class="form-container">
        <form action="" method="post">
            <h3 class="fw-bold">daftar sekarang</h3>
            <?php 
            if (isset($error)) {
                foreach ($error as $error) { ?>
                    <span class="pesan-error"><?= $error; ?></span>
        <?php   }
            }
            ?>
            <input type="text" name="username" id="username" required placeholder="masukkan username">
            <input type="email" name="email" id="email" required placeholder="masukkan email">
            <input type="password" name="password" id="password" required placeholder="masukkan password">
            <input type="password" name="confirm-password" id="confirm-password" required placeholder="konfirmasi password">
            <button type="submit" name="submit" class="btn-form">regestrasi sekarang</button>
            <p>sudah mempunyai akun? <a href="login.php">login sekarang</a></p>
        </form>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="fontawesome/js/all.js"></script>
</body>
</html>