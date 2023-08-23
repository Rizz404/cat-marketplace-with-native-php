<?php

if (!isset($_SESSION['nama_user']) || !isset($_SESSION['id'])) {
    $warningMessage = "Silahkan login terlebih dulu";
    $_SESSION['warningMessage'] = $warningMessage;
    header('location: login.php');
    exit;
}

?>