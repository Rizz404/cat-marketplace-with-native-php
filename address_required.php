<?php 

$idUser = $_SESSION['id'];

$selectUser = mysqli_query($conn, "SELECT * FROM user u LEFT JOIN detail_user du ON u.id_user = du.id_user WHERE u.id_user = '$idUser'");
$dataUser = mysqli_fetch_assoc($selectUser);
if (empty($dataUser['alamat']) || empty($dataUser['kode_pos']) || empty($dataUser['detail_alamat'])) {
    $warningMessage = "Silahkan isi alamat terlebih dahulu";
    $_SESSION['KYC'] = $warningMessage;
    header("location: akun.php");
    exit;
}

?>