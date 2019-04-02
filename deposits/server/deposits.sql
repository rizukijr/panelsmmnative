-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 15 Apr 2018 pada 16.08
-- Versi Server: 10.1.28-MariaDB
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smmpanel`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `deposits`
--

CREATE TABLE `deposits` (
  `id` int(10) NOT NULL,
  `code` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `user` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `method` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  `note` text COLLATE utf8_swedish_ci NOT NULL,
  `sender` varchar(250) COLLATE utf8_swedish_ci NOT NULL,
  `quantity` int(10) NOT NULL,
  `balance` int(10) NOT NULL,
  `status` enum('Pending','Success','Error') COLLATE utf8_swedish_ci NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data untuk tabel `deposits`
--

INSERT INTO `deposits` (`id`, `code`, `user`, `method`, `note`, `sender`, `quantity`, `balance`, `status`, `date`) VALUES
(1, '650711679513444', 'admin', 'Bank BCA ', '12345678 A.N SMM PANEL', '50000', 100007, 100007, 'Success', '2018-04-15'),
(2, '091496902889716', 'admin', 'Bank BCA ', '12345678 A.N SMM PANEL', '10000', 100655, 100655, 'Success', '2018-04-15'),
(3, '182495309106729', 'admin', 'Bank BCA ', '12345678 A.N SMM PANEL', '5000', 10000, 10000, 'Success', '2018-04-15'),
(4, '945223261164041', 'admin', 'Bank BRI', 'BRI 1234567910 A.N SMM PANEL', 'maklo', 10346, 10346, 'Pending', '2018-04-14'),
(5, '548396400971118', 'admin', 'Bank BRI', 'BRI 1234567910 A.N SMM PANEL', 'maklo', 10505, 10505, 'Pending', '2018-04-14'),
(6, '015190178506010', 'admin', 'Bank BRI', 'BRI 1234567910 A.N SMM PANEL', 'maklo', 10187, 10187, 'Pending', '2018-04-14'),
(7, '823663272670443', 'admin', 'Bank BRI', 'BRI 1234567910 A.N SMM PANEL', 'maklo', 10933, 10933, 'Pending', '2018-04-14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `deposit_method`
--

CREATE TABLE `deposit_method` (
  `id` int(10) NOT NULL,
  `name` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `rate` varchar(10) COLLATE utf8_swedish_ci NOT NULL,
  `note` text COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data untuk tabel `deposit_method`
--

INSERT INTO `deposit_method` (`id`, `name`, `rate`, `note`) VALUES
(4, 'Bank BCA [ Automatis ]', '1', '123456789 A.N Pemilik Rekening'),
(6, 'Bank Mandiri [ Automatis ]', '1', '123456789 A.N Pemilik Rekening');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposit_method`
--
ALTER TABLE `deposit_method`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `deposit_method`
--
ALTER TABLE `deposit_method`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
