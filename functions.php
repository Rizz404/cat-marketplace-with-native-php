<?php 

$hostname = "localhost";
$username = "root";
$password = "";
$database = "project_kucingpoi";

$conn = mysqli_connect($hostname, $username, $password, $database);

// cek koneksi
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

// invader must die!!!!
function invaderMustDie($koneksi, $input) {
  // Menghapus karakter yang tidak diinginkan
  $input = trim($input); // Menghapus spasi di awal dan akhir
  $input = stripslashes($input); // Menghapus karakter backslash

  // Menerapkan htmlspecialchars
  $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

  // Menghindari SQL Injection dengan mysqli_real_escape_string
  $input = mysqli_real_escape_string($koneksi, $input);

  return $input;
}

// fungsi validasi hash password
function validatePassword($inputPassword, $hashedPassword) {
  return password_verify($inputPassword, $hashedPassword);
}

// fungsi format harga
function format_harga($harga) {
  return 'Rp ' . number_format($harga, 0, ',', '.');
}

// fungsi untuk menampilkan alert
function showAlert($message) {
  $type = 'info'; // Tipe alert default
  $successMessage = isset($GLOBALS['successMessage']) ? $GLOBALS['successMessage'] : null;
  $errorMessage = isset($GLOBALS['errorMessage']) ? $GLOBALS['errorMessage'] : null;
  $warningMessage = isset($GLOBALS['warningMessage']) ? $GLOBALS['warningMessage'] : null;

  if ($message === $successMessage) {
    $type = 'success';
  } elseif ($message === $errorMessage) {
    $type = 'danger';
  } elseif ($message === $warningMessage) {
    $type = 'warning';
  }

  echo '<div id="alert" class="alert alert-' . $type . ' fade show text-center fs-4" role="alert">';
  echo $message;
  echo '</div>';

  // Tambahkan script JavaScript untuk menghilangkan alert setelah 2 detik
  echo '<script>
    setTimeout(function() {
      document.getElementById("alert").remove();
    }, 2000);
  </script>';
}

// fungsi format tanggal indonesia
function format_tanggal($tanggal)
{
    $nama_bulan = array(
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );

    $tahun = date('Y', strtotime($tanggal));
    $bulan = date('n', strtotime($tanggal));
    $tanggal = date('j', strtotime($tanggal));

    $tanggal_indonesia = $tanggal . ' ' . $nama_bulan[$bulan - 1] . ' ' . $tahun;

    return $tanggal_indonesia;
}

// fungsi format jam indonesia
function format_jam($timestamp) {
  $jam = date("H:i", strtotime($timestamp));
  $keterangan = '';

  if ($jam >= '00:00' && $jam < '10:00') {
      $keterangan = 'Pagi';
  } elseif ($jam >= '10:00' && $jam < '15:00') {
      $keterangan = 'Siang';
  } elseif ($jam >= '15:00' && $jam < '18:00') {
      $keterangan = 'Sore';
  } else {
      $keterangan = 'Malam';
  }

  return $jam . ' ' . $keterangan;
}

// Fungsi penanganan error kustom
// function customErrorHandler($errno, $errstr, $errfile, $errline) {
//   static $isImageDisplayed = false; // Flag untuk menandai apakah gambar sudah ditampilkan

//   if (!$isImageDisplayed) {
//       $image = "./img/img-admin/History_TS_ProgrammingMemes_image1.png";
//       echo "<img src='$image' alt='Error Image' class='error-image'>";
//       $isImageDisplayed = true;
//   }

//   $errorType = "";
//   switch ($errno) {
//       case E_ERROR:
//           $errorType = "Fatal Error";
//           break;
//       case E_WARNING:
//           $errorType = "Warning";
//           break;
//       case E_NOTICE:
//           $errorType = "Notice";
//           break;
//       case E_PARSE:
//           $errorType = "Parse Error";
//           break;
//       default:
//           $errorType = "Error";
//           break;
//   }

//   $errorMessage = "<b>[$errorType]</b> $errstr <b>di folder>file $errfile</b> <b>di baris $errline</b>";
//   echo "$errorMessage<br>";
// }

// // Mengaitkan fungsi penanganan error kustom dengan error handler PHP
// set_error_handler("customErrorHandler", E_ALL);

// ?>

<style>
  .error-image {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 25%;
    z-index: 9999;
  }

  #alert {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 9999;
  }
</style>