<?php 

session_start();

if (!isset($_SESSION['nama_admin'])) {
    header('location:../login.php');
}

?>