-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2023 at 07:12 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_kucingpoi`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_user`
--

CREATE TABLE `detail_user` (
  `id_detail_user` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `gender` enum('Pria','Wanita','Lainnya') DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kode_pos` varchar(50) DEFAULT NULL,
  `detail_alamat` text DEFAULT NULL,
  `foto_profile` varchar(255) DEFAULT NULL,
  `pembaruan_terakhir` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_user`
--

INSERT INTO `detail_user` (`id_detail_user`, `id_user`, `nama_lengkap`, `gender`, `tanggal_lahir`, `alamat`, `kode_pos`, `detail_alamat`, `foto_profile`, `pembaruan_terakhir`) VALUES
(1, 2, 'Kita Ikuyo', 'Wanita', '2004-10-24', 'Banten, Tangerang, Cikupa', '177013', 'Jl. Mawar', 'kita-chan(18).jpg', '2023-06-20 15:42:39'),
(2, 3, 'Dika Yonanda Putra', 'Pria', '2003-01-20', 'jakarta', NULL, 'Jaksel', 'History_TS_ProgrammingMemes_image3(1).png', '2023-06-20 16:12:02'),
(3, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-06-20 16:12:17'),
(4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-06-20 16:12:28'),
(5, 6, 'Tegar Saputra', 'Pria', '2023-01-05', 'jakarta', NULL, 'apa aja', 'ragdoll.jpg', '2023-06-21 02:12:00'),
(6, 7, 'Ramadhan Julianti', 'Pria', '2023-06-05', 'test', '12393', 'test', NULL, '2023-06-21 03:25:31'),
(7, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-01 13:58:37'),
(8, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-07-02 08:18:58');

-- --------------------------------------------------------

--
-- Table structure for table `histori_transaksi`
--

CREATE TABLE `histori_transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `kucing` text NOT NULL,
  `id_pesanan` varchar(255) NOT NULL,
  `jasa_pengiriman` int(11) UNSIGNED NOT NULL,
  `metode_pembayaran` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `no_wallet` varchar(50) NOT NULL,
  `bukti_transaksi` varchar(255) NOT NULL,
  `waktu_transaksi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `histori_transaksi`
--

INSERT INTO `histori_transaksi` (`id_transaksi`, `id_user`, `kucing`, `id_pesanan`, `jasa_pengiriman`, `metode_pembayaran`, `total_harga`, `no_wallet`, `bukti_transaksi`, `waktu_transaksi`) VALUES
(1, 2, 'Burmese', '1', 3, 2, 3352500, '7431941515', 'puzzled.png', '2023-07-03 15:03:28'),
(2, 2, 'Anggora, Russian Blue, Maine Coon', '2, 3, 4', 2, 2, 10522500, '7431941515', 'confused.png', '2023-07-03 15:04:20'),
(3, 2, 'Anggora', '5', 2, 2, 8022500, '7431941515', 'download (2).png', '2023-07-03 16:25:00'),
(4, 2, 'Russian Blue, Exotic Shorthair, Birman', '6, 7, 8', 1, 3, 14117500, '177013', 'images.jpg', '2023-07-04 14:07:02'),
(5, 2, 'Anggora, Maine Coon, Persia', '9, 10, 11', 1, 1, 4315000, '080808098391572', 'CSS 1.png', '2023-07-04 14:07:56'),
(6, 2, 'Maine Coon', '12', 1, 3, 1517500, '7431941515', 'download (2)(1).png', '2023-07-04 14:22:29'),
(7, 7, 'Exotic Shorthair', '13', 3, 2, 1852500, '7431941515', 'images(1).jpg', '2023-07-05 04:16:43');

-- --------------------------------------------------------

--
-- Table structure for table `jasa_pengiriman`
--

CREATE TABLE `jasa_pengiriman` (
  `id_jasa_pengiriman` int(11) UNSIGNED NOT NULL,
  `nama_jasa` varchar(100) NOT NULL,
  `harga_jasa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jasa_pengiriman`
--

INSERT INTO `jasa_pengiriman` (`id_jasa_pengiriman`, `nama_jasa`, `harga_jasa`) VALUES
(1, 'standar', 15000),
(2, 'cepat', 20000),
(3, 'instan', 50000);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_kucing` int(11) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `tanggal_ditambahkan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `id_user`, `id_kucing`, `jumlah`, `harga`, `total_harga`, `tanggal_ditambahkan`) VALUES
(129, 8, 1, 10, 1600000, 16000000, '2023-07-01 15:26:25'),
(130, 8, 2, 5, 1400000, 7000000, '2023-07-01 15:26:37'),
(158, 7, 2, 1, 1400000, 1400000, '2023-07-05 04:14:40'),
(159, 7, 3, 1, 1800000, 1800000, '2023-07-05 04:14:45');

-- --------------------------------------------------------

--
-- Table structure for table `kucing`
--

CREATE TABLE `kucing` (
  `id_kucing` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) NOT NULL,
  `ras` varchar(50) NOT NULL,
  `umur` varchar(30) NOT NULL,
  `harga` int(11) NOT NULL,
  `jumlah_tersedia` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `kategori_kucing` text NOT NULL,
  `deskripsi` text NOT NULL,
  `pembaruan_terakhir` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kucing`
--

INSERT INTO `kucing` (`id_kucing`, `id_user`, `ras`, `umur`, `harga`, `jumlah_tersedia`, `gambar`, `kategori_kucing`, `deskripsi`, `pembaruan_terakhir`) VALUES
(1, 1, 'Anggora', '1,5 tahun', 1600000, 21, 'anggora.jpg', 'Berbulu Panjang, Berukuran Sedang', 'Kucing ras Anggora dengan bulu panjang dan berukuran sedang. Bulunya sangat lembut dan elegan. Memiliki tingkat kecerdasan yang tinggi dan dikenal dengan sifat yang ramah dan penyayang.', '2023-06-20 14:39:55'),
(2, 2, 'Russian Blue', '2 tahun', 1400000, 9, 'russian_blue.jpg', 'Berbulu Pendek, Berukuran Sedang', 'Kucing ras Russian Blue dengan bulu pendek dan berukuran sedang. Bulunya berwarna abu-abu dengan pola bulu yang indah.', '2023-06-20 14:39:55'),
(3, 1, 'Exotic Shorthair', '2,5 tahun', 1800000, 5, 'exotic_shorthair.jpg', 'Berbulu Pendek, Berukuran Sedang', 'Kucing ras Exotic Shorthair dengan bulu pendek dan berukuran sedang. Mirip dengan Persia tetapi dengan bulu yang lebih pendek.', '2023-06-20 14:39:55'),
(4, 2, 'Abyssinian', '1 tahun', 950000, 3, 'abyssinian.jpg', 'Berbulu Pendek, Berukuran Sedang', 'Kucing ras Abyssinian dengan bulu pendek dan berukuran sedang. Dikenal memiliki kecerdasan tinggi dan energi yang melimpah.', '2023-06-20 14:39:55'),
(5, 1, 'Burmese', '1,5 tahun', 1100000, 13, 'burmese.jpg', 'Berbulu Pendek, Berukuran Sedang', 'Kucing ras Burmese dengan bulu pendek dan berukuran sedang. Memiliki sifat ramah dan penyayang.', '2023-06-20 14:39:55'),
(6, 1, 'Maine Coon', '2 tahun', 1500000, 37, 'maine_coon.jpg', 'Berbulu Panjang, Berukuran Besar', 'Kucing ras Maine Coon yang berbulu panjang dan berukuran besar. Sangat ramah dan lincah.', '2023-06-20 14:27:01'),
(7, 2, 'Persia', '1,5 tahun', 1200000, 50, 'persia.jpg', 'Berbulu Panjang, Berukuran Sedang', 'Kucing ras Persia yang memiliki bulu panjang dan berukuran sedang. Cocok untuk pemilik yang suka kucing yang menggemaskan.', '2023-06-20 14:27:01'),
(8, 1, 'Siamese', '1 tahun', 800000, 140, 'siamese.jpg', 'Berbulu Pendek, Berukuran Sedang', 'Kucing ras Siamese yang berbulu pendek dan berukuran sedang. Sangat cerdas dan aktif.', '2023-06-20 14:27:01'),
(9, 2, 'Bengal', '2,5 tahun', 2000000, 189, 'bengal.jpg', 'Berbulu Pendek, Berukuran Besar', 'Kucing ras Bengal yang berbulu pendek dan berukuran besar. Memiliki pola bulu yang unik dan mengagumkan.', '2023-06-20 14:27:01'),
(10, 2, 'Scottish Fold', '1 tahun', 1000000, 50, 'scottish_fold.jpg', 'Berbulu Pendek, Berukuran Kecil, Persilangan', 'Kucing ras Scottish Fold yang memiliki bulu pendek dan berukuran kecil. Telinga mereka melipat ke depan, memberikan penampilan yang menggemaskan.', '2023-06-20 14:27:01'),
(11, 1, 'Sphynx', '3 tahun', 2500000, 27, 'sphynx.jpg', 'Berbulu Pendek, Berukuran Kecil', 'Kucing ras Sphynx yang tidak memiliki bulu dan berukuran kecil. Perlu perawatan ekstra karena tidak memiliki lapisan pelindung bulu.', '2023-06-20 14:27:01'),
(12, 2, 'Ragdoll', '2 tahun', 1800000, 23, 'ragdoll.jpg', 'Berbulu Panjang, Berukuran Besar', 'Kucing ras Ragdoll yang memiliki bulu panjang dan berukuran besar. Sangat ramah dan tenang.', '2023-06-20 14:27:01'),
(13, 1, 'British Shorthair', '1,5 tahun', 900000, 23, 'british_shorthair.jpg', 'Berbulu Pendek, Berukuran Besar', 'Kucing ras British Shorthair yang memiliki bulu pendek dan berukuran besar. Memiliki penampilan yang gagah dan kepribadian yang tenang.', '2023-06-20 14:27:01'),
(14, 1, 'Birman', '2 tahun', 1500000, 78, 'birman.jpg', 'Berbulu Panjang, Berukuran Sedang', 'Kucing ras Birman yang memiliki bulu panjang dan berukuran sedang. Terkenal dengan mata biru yang indah.', '2023-06-20 14:27:01'),
(15, 2, 'Norwegian Forest Cat', '3 tahun', 2200000, 6, 'norwegian_forest_cat.jpg', 'Berbulu Panjang, Berukuran Besar', 'Kucing ras Norwegian Forest Cat yang memiliki bulu panjang dan berukuran besar. Cocok untuk kehidupan luar ruangan dan memiliki naluri berburu yang kuat.', '2023-06-20 14:27:01');

-- --------------------------------------------------------

--
-- Table structure for table `metode_pembayaran`
--

CREATE TABLE `metode_pembayaran` (
  `id_metode_pembayaran` int(11) NOT NULL,
  `nama_metode_pembayaran` varchar(20) NOT NULL,
  `fee` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `metode_pembayaran`
--

INSERT INTO `metode_pembayaran` (`id_metode_pembayaran`, `nama_metode_pembayaran`, `fee`) VALUES
(1, 'dana', 0),
(2, 'go-pay', 2500),
(3, 'Bank', 2500),
(4, 'Paypal', 1500);

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) NOT NULL,
  `kucing` text NOT NULL,
  `jumlah_kucing` int(11) NOT NULL,
  `foto_kucing` varchar(255) NOT NULL,
  `harga_kucing` int(11) NOT NULL,
  `total_harga_pesanan` int(11) NOT NULL,
  `tanggal_pembelian` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_pesanan` enum('dikemas','dikirim','selesai') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_user`, `kucing`, `jumlah_kucing`, `foto_kucing`, `harga_kucing`, `total_harga_pesanan`, `tanggal_pembelian`, `status_pesanan`) VALUES
(1, 2, 'Burmese', 3, 'burmese.jpg', 1100000, 3300000, '2023-07-03 15:03:28', 'dikemas'),
(2, 2, 'Anggora', 2, 'anggora.jpg', 1600000, 3200000, '2023-07-03 15:04:20', 'dikemas'),
(3, 2, 'Russian Blue', 2, 'russian_blue.jpg', 1400000, 2800000, '2023-07-03 15:04:20', 'dikemas'),
(4, 2, 'Maine Coon', 3, 'maine_coon.jpg', 1500000, 4500000, '2023-07-03 15:04:20', 'dikemas'),
(5, 2, 'Anggora', 5, 'anggora.jpg', 1600000, 8000000, '2023-07-03 16:25:00', 'dikemas'),
(6, 2, 'Russian Blue', 3, 'russian_blue.jpg', 1400000, 4200000, '2023-07-04 14:07:02', 'dikemas'),
(7, 2, 'Exotic Shorthair', 3, 'exotic_shorthair.jpg', 1800000, 5400000, '2023-07-04 14:07:02', 'dikemas'),
(8, 2, 'Birman', 3, 'birman.jpg', 1500000, 4500000, '2023-07-04 14:07:02', 'dikemas'),
(9, 2, 'Anggora', 1, 'anggora.jpg', 1600000, 1600000, '2023-07-04 14:07:55', 'dikemas'),
(10, 2, 'Maine Coon', 1, 'maine_coon.jpg', 1500000, 1500000, '2023-07-04 14:07:55', 'dikemas'),
(11, 2, 'Persia', 1, 'persia.jpg', 1200000, 1200000, '2023-07-04 14:07:56', 'dikemas'),
(12, 2, 'Maine Coon', 1, 'maine_coon.jpg', 1500000, 1500000, '2023-07-04 14:22:29', 'dikemas'),
(13, 7, 'Exotic Shorthair', 1, 'exotic_shorthair.jpg', 1800000, 1800000, '2023-07-05 04:16:43', 'selesai');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super admin','admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `email`, `password`, `role`) VALUES
(1, 'Rizqiansyah', 'rizzthenotable@gmail.com', '$2y$10$9aglcwyfqYgRqPmEBLnxBONAkziKg.GcoyGRCIwcs.d/zcufxSmOa', 'super admin'),
(2, 'Kita-chan', 'kitaaura@gmail.com', '$2y$10$wflVwcOxf3bSw6qMcuaIYeYiwW7tNg/3S3N2fp9GvhRdDmiTIBgu2', 'user'),
(3, 'Dikayo', 'yonandaputra05@gmail.com', '$2y$10$jw31hJcVsbed/aOLPyq8NuZMwD377.hCkfjQNmSVq9WoulUWeWCFa', 'user'),
(4, 'Areia', 'areia-chan@gmail.com', '$2y$10$M4y3wGR7qw4XsFUkHAEWf.h9Cx4dAEDN12eIX2qpE2BB8Z7zxhhVi', 'admin'),
(5, 'Shaltear', 'bloodfallen@gmail.com', '$2y$10$7UPGtvQVVjFeOHEwoXPXlO6qQpEO/M14HUOuas.cSKrDzRXelLoJm', 'user'),
(6, 'tegar123', 'tegar@gmail.com', '$2y$10$NZDuTJ5IpjeCes.XKMlrneW2L2xO0yWHcnGWcCq/.szLAhAMC74pC', 'user'),
(7, 'Ramadhan', 'ramadhan@gmail.com', '$2y$10$3cYkZWX9sMGQ2MTdPsYq9O6elTFbCF.kcOl5Ue2pe//Qa.PeqIEVe', 'user'),
(8, 'bocchi-chan', 'hitoribocchi@gmail.com', '$2y$10$BBCjA2bF/NtmwmLOlXroiuTEH93UdVvi8hZoWlJ2fItr7dCfCcMgq', 'user'),
(9, 'nijika-chan', 'ijichinijika@gmail.com', '$2y$10$aJI8URhjKm3My.IEAfFb6O4ESzr4YHrHUqvX/9bT2POQJm53laPQK', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_user`
--
ALTER TABLE `detail_user`
  ADD PRIMARY KEY (`id_detail_user`),
  ADD KEY `fk_id_user_detail_user` (`id_user`);

--
-- Indexes for table `histori_transaksi`
--
ALTER TABLE `histori_transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `fk_id_user_histori_transaksi` (`id_user`),
  ADD KEY `fk_id_metode_pembayaran_histori_transaksi` (`metode_pembayaran`),
  ADD KEY `fk_id_jasa_pengiriman_histori_transaksi` (`jasa_pengiriman`);

--
-- Indexes for table `jasa_pengiriman`
--
ALTER TABLE `jasa_pengiriman`
  ADD PRIMARY KEY (`id_jasa_pengiriman`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `fk_id_user_keranjang` (`id_user`),
  ADD KEY `fk_id_kucing_keranjang` (`id_kucing`);

--
-- Indexes for table `kucing`
--
ALTER TABLE `kucing`
  ADD PRIMARY KEY (`id_kucing`),
  ADD KEY `fk_id_user_kucing` (`id_user`);

--
-- Indexes for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  ADD PRIMARY KEY (`id_metode_pembayaran`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `fk_id_user_pesanan` (`id_user`),
  ADD KEY `fk_kode_kucing_pesanan` (`kucing`(768));

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_user`
--
ALTER TABLE `detail_user`
  MODIFY `id_detail_user` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `histori_transaksi`
--
ALTER TABLE `histori_transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jasa_pengiriman`
--
ALTER TABLE `jasa_pengiriman`
  MODIFY `id_jasa_pengiriman` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `kucing`
--
ALTER TABLE `kucing`
  MODIFY `id_kucing` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  MODIFY `id_metode_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_user`
--
ALTER TABLE `detail_user`
  ADD CONSTRAINT `fk_id_user_detail_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `histori_transaksi`
--
ALTER TABLE `histori_transaksi`
  ADD CONSTRAINT `fk_id_jasa_pengiriman_histori_transaksi` FOREIGN KEY (`jasa_pengiriman`) REFERENCES `jasa_pengiriman` (`id_jasa_pengiriman`),
  ADD CONSTRAINT `fk_id_metode_pembayaran_histori_transaksi` FOREIGN KEY (`metode_pembayaran`) REFERENCES `metode_pembayaran` (`id_metode_pembayaran`),
  ADD CONSTRAINT `fk_id_user_histori_transaksi` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `fk_id_kucing_keranjang` FOREIGN KEY (`id_kucing`) REFERENCES `kucing` (`id_kucing`),
  ADD CONSTRAINT `fk_id_user_keranjang` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `kucing`
--
ALTER TABLE `kucing`
  ADD CONSTRAINT `fk_id_user_kucing` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `fk_id_user_pesanan` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
