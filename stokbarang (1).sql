-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2025 at 04:20 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stokbarang`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `kd_barang` bigint(20) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`kd_barang`, `nama_barang`, `jumlah`, `satuan`, `tanggal_masuk`, `keterangan`) VALUES
(2025080902, 'Smartphone', 50, NULL, NULL, NULL),
(2025080903, 'Tablet', 23, NULL, NULL, NULL),
(2025080904, 'TV LED', 10, NULL, NULL, NULL),
(2025080905, 'Headphone', 37, NULL, NULL, NULL),
(2025080906, 'Keyboard', 30, NULL, NULL, NULL),
(2025080907, 'Mouse Wireless', 35, NULL, NULL, NULL),
(2025080908, 'Power Bank', 60, NULL, NULL, NULL),
(2025080909, 'Printer', 12, NULL, NULL, NULL),
(2025080910, 'Speaker Bluetooth', 20, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `NO` int(11) NOT NULL,
  `kd_barang` bigint(20) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `nama_peminjam` varchar(100) NOT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `kontak` varchar(100) DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `univ_jurusan` varchar(50) DEFAULT NULL,
  `cabang` varchar(100) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `STATUS` enum('Dipinjam','Dikembalikan') DEFAULT 'Dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`NO`, `kd_barang`, `nama_barang`, `nama_peminjam`, `nip`, `kontak`, `jumlah`, `univ_jurusan`, `cabang`, `keterangan`, `tanggal_pinjam`, `tanggal_kembali`, `STATUS`) VALUES
(1, 2025080903, 'Tablet', 'Admin', '123', '123', 1, 'Bandung', NULL, NULL, '2025-09-19', '2025-09-20', 'Dikembalikan'),
(2, 2025080903, 'Tablet', 'pegawai', '12113', '23232', 1, 'Bandung', NULL, NULL, '2025-09-19', '2025-09-20', 'Dikembalikan'),
(5, 2025080905, 'Headphone', 'Admin', '123', '123', 1, 'Managemen', NULL, NULL, '2025-09-24', NULL, 'Dipinjam'),
(6, 2025080905, 'Headphone', 'Admin', '123', '123', 1, 'Managemen', NULL, NULL, '2025-09-08', NULL, 'Dipinjam');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `NO` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nip` int(50) DEFAULT NULL,
  `kontak` int(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `ROLE` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`NO`, `nama`, `nip`, `kontak`, `email`, `PASSWORD`, `ROLE`) VALUES
(1, 'Admin', 123, 123, 'ghanimbusairi20@gmail.com', '$2y$10$e7fpH3HtI566Ddq8Q7i4XeJhSOxa3.J9V5YEessQQ7mUuKz4YGYoe', 'admin'),
(2, 'pegawai', 12113, 23232, 'pegawai@gmail.com', '12345', 'pegawai'),
(4, 'Hana', 242342, 14243242, 'indriraaa@gmail.com', '$2y$10$Yk/meCvQXLYsRp.ayT/9FOQMtP3BlHU1sRc65QSTlJa00G8rpOs.q', 'pegawai');

-- --------------------------------------------------------

--
-- Table structure for table `reset_password`
--

CREATE TABLE `reset_password` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expire` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reset_password`
--

INSERT INTO `reset_password` (`id`, `email`, `token`, `expire`) VALUES
(1, 'indriraaa@gmail.com', 'cdcdde35531430296b362f6790b0d0547eb66c28ccda0f036cc9d6cb78b7944bc4d828678dcc375f8957fc7ba53c9294f24d', '2025-10-09 12:41:25'),
(2, 'indriraaa@gmail.com', 'ba32f5b213b03f8fb10a73e6b2350d67573869a2872be7840c9eb14708b390e433c4b5d5635052a781307bff2e10206d82a1', '2025-10-09 12:41:32'),
(3, 'indriraaa@gmail.com', '54eb943cdbe2e2f4962e6dd32ab09ea553fb6bf3f831daae34e032c83284507e9ab169dc2c0720700a46129b37da8862c323', '2025-10-09 12:43:13'),
(4, 'indriraaa@gmail.com', '15508663fe02b3cd0b5d2310975f8354b8d02c7c00fa46a1999392e09849ca2bb1c086f93eee4b0e11fa6f03028255af98e5', '2025-10-09 12:49:23'),
(5, 'indriraaa@gmail.com', '6f51d0e5ec2300fd574cfbd1356dbc5ae0b096b845032b31ec8f0aff3a87b6013ada3fa5d03cd21c95ee58acba93f68307ec', '2025-10-09 12:49:37'),
(6, 'indriraaa@gmail.com', '7a2fc2e7dd9f51149133e4e4e02f2d4dc7c552944f70ca3fb5ae3ab46e946bc0c3d7a15bcab0f7b60409008dcf8101e6deef', '2025-10-09 12:50:59'),
(7, 'indriraaa@gmail.com', '6ed63ed49871c868a798e1b6b253b8d63c72cb3ab8b215632f85ed9cd190801cbef292bce5fe5306814c88791850f68bd596', '2025-10-09 12:51:46');

-- --------------------------------------------------------

--
-- Table structure for table `stok_keluar`
--

CREATE TABLE `stok_keluar` (
  `NO` int(11) NOT NULL,
  `kd_barang` bigint(20) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal_keluar` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stok_masuk`
--

CREATE TABLE `stok_masuk` (
  `id` int(11) NOT NULL,
  `kd_barang` bigint(20) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal_masuk` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`kd_barang`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`NO`),
  ADD KEY `fk_peminjaman_barang` (`kd_barang`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`NO`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `reset_password`
--
ALTER TABLE `reset_password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stok_keluar`
--
ALTER TABLE `stok_keluar`
  ADD PRIMARY KEY (`NO`),
  ADD KEY `fk_stok_keluar_barang` (`kd_barang`);

--
-- Indexes for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stok_masuk_barang` (`kd_barang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `kd_barang` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2025080911;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `NO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `NO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `stok_keluar`
--
ALTER TABLE `stok_keluar`
  MODIFY `NO` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `fk_peminjaman_barang` FOREIGN KEY (`kd_barang`) REFERENCES `barang` (`kd_barang`);

--
-- Constraints for table `stok_keluar`
--
ALTER TABLE `stok_keluar`
  ADD CONSTRAINT `fk_stok_keluar_barang` FOREIGN KEY (`kd_barang`) REFERENCES `barang` (`kd_barang`) ON DELETE CASCADE;

--
-- Constraints for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD CONSTRAINT `fk_stok_masuk_barang` FOREIGN KEY (`kd_barang`) REFERENCES `barang` (`kd_barang`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
