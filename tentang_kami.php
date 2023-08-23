<?php
session_start();

include 'functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxwellcat | Tentang Kami</title>
    <link rel="stylesheet" href="css/style_user.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="shortcut icon" href="img/img-website/chocola-3.jpg" type="image/x-icon">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container-fluid py-4">
        <div class="container">
            <h1>Tentang Kami</h1>
            <p>Selamat datang di MaxwellCat, website jual beli kucing terpercaya! Kami adalah toko online yang menyediakan berbagai jenis kucing berkualitas dengan harga terbaik. Kami memiliki tim profesional yang peduli terhadap kesejahteraan kucing dan selalu berkomitmen untuk memberikan pelayanan terbaik kepada pelanggan kami.</p>
            
            <h2>Visi</h2>
            <p>Visi kami adalah menjadi tempat terbaik untuk membeli kucing impian Anda. Kami berusaha untuk menyediakan kucing-kucing dengan keturunan yang berkualitas tinggi serta memberikan informasi yang akurat dan lengkap kepada para calon pemilik kucing.</p>
            
            <h2>Misi</h2>
            <p>Kami memiliki beberapa misi yang kami tekankan dalam setiap aktivitas kami:</p>
            <ul>
                <li>Menyediakan kucing-kucing dengan kualitas terbaik dan kesehatan yang baik.</li>
                <li>Memberikan informasi yang jelas dan akurat mengenai setiap kucing yang kami jual.</li>
                <li>Menjaga kepuasan pelanggan dengan memberikan layanan pelanggan yang ramah dan responsif.</li>
                <li>Memastikan kucing-kucing kami diperlakukan dengan baik dan mendapatkan perawatan yang sesuai.</li>
            </ul>
            
            <h2>Tim Kami</h2>
            <p>Kami memiliki tim yang berdedikasi dan berpengalaman dalam bidang kucing. Tim kami terdiri dari:</p>
            <ul>
                <li>
                    <strong>Tim 1 - Rizqi</strong>
                    <p>Tim 1 adalah tim yang bertanggung jawab untuk mengurus aspek pemasaran dan promosi di MaxwellCat. Mereka memiliki keahlian dalam membangun strategi pemasaran yang efektif dan menciptakan koneksi yang kuat dengan pelanggan.</p>
                </li>
                <li>
                    <strong>Tim 2 - Rizqiansyah</strong>
                    <p>Tim 2 adalah tim yang mengurus proses pengadaan dan perawatan kucing di MaxwellCat. Mereka bekerja keras untuk memastikan kucing-kucing kami memiliki kualitas terbaik dan diperlakukan dengan baik sejak awal.</p>
                </li>
                <li>
                    <strong>Tim 3 - Rizqiansyah Ramadhan</strong>
                    <p>Tim 3 adalah tim yang fokus pada pengembangan teknologi dan desain di MaxwellCat. Mereka bertanggung jawab untuk meningkatkan pengalaman pengguna melalui pengembangan website dan fitur-fitur inovatif.</p>
                </li>
            </ul>
        </div>
    </div>

    <?php include 'footer.php' ?>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>
</body>
</html>