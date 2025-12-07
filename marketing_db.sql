-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2025 at 03:01 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `marketing_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `agenda_kegiatan`
--

CREATE TABLE `agenda_kegiatan` (
  `id` int(11) NOT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `lokasi_mitra` varchar(255) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `target_peserta` int(11) DEFAULT NULL,
  `target_leads` int(11) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `status_global` enum('direncanakan','berjalan','selesai','dibatalkan') DEFAULT 'direncanakan',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_kegiatan`
--

CREATE TABLE `kategori_kegiatan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_kegiatan`
--

INSERT INTO `kategori_kegiatan` (`id`, `nama`) VALUES
(1, 'Roadshow Sekolah'),
(2, 'Expo Pendidikan'),
(3, 'Webinar'),
(4, 'Konten Digital'),
(5, 'Kunjungan Sekolah');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_tugas`
--

CREATE TABLE `laporan_tugas` (
  `id` int(11) NOT NULL,
  `tugas_id` int(11) NOT NULL,
  `tanggal_pelaksanaan` date DEFAULT NULL,
  `lokasi_aktual` varchar(255) DEFAULT NULL,
  `durasi_menit` int(11) DEFAULT NULL,
  `hasil_peserta` int(11) DEFAULT NULL,
  `hasil_leads` int(11) DEFAULT NULL,
  `materi_dibagikan` text DEFAULT NULL,
  `dokumentasi_link` varchar(255) DEFAULT NULL,
  `kendala` text DEFAULT NULL,
  `rekomendasi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(3, 'manager'),
(2, 'marketing');

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` int(11) NOT NULL,
  `agenda_id` int(11) NOT NULL,
  `judul_tugas` varchar(255) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('belum_mulai','in_progress','selesai','tertunda','batal') DEFAULT 'belum_mulai',
  `deadline` date DEFAULT NULL,
  `catatan_lapangan` text DEFAULT NULL,
  `waktu_realisasi` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `unit` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `contact`, `unit`, `created_at`) VALUES
(1, 'Admin', 'admin@demo.com', '$2y$10$wXWkWwZpH0S4K7aY4x9pOeZBdFZg9Hg7r8Ov5HykQAnfJZ9fUQ2qC', 1, NULL, NULL, '2025-12-07 01:42:32'),
(6, 'ell', 'admin@managament.com', '$2y$10$zDqb9.JX5HmBzmfxoitIaOOTvpcIpOWN3GOyXhlUR5wMz32PPq70i', 1, NULL, NULL, '2025-12-07 01:46:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agenda_kegiatan`
--
ALTER TABLE `agenda_kegiatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `kategori_kegiatan`
--
ALTER TABLE `kategori_kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan_tugas`
--
ALTER TABLE `laporan_tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_id` (`tugas_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agenda_id` (`agenda_id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agenda_kegiatan`
--
ALTER TABLE `agenda_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_kegiatan`
--
ALTER TABLE `kategori_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `laporan_tugas`
--
ALTER TABLE `laporan_tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agenda_kegiatan`
--
ALTER TABLE `agenda_kegiatan`
  ADD CONSTRAINT `agenda_kegiatan_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_kegiatan` (`id`),
  ADD CONSTRAINT `agenda_kegiatan_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `laporan_tugas`
--
ALTER TABLE `laporan_tugas`
  ADD CONSTRAINT `laporan_tugas_ibfk_1` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`);

--
-- Constraints for table `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `tugas_ibfk_1` FOREIGN KEY (`agenda_id`) REFERENCES `agenda_kegiatan` (`id`),
  ADD CONSTRAINT `tugas_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
