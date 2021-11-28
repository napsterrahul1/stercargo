-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2021 at 07:08 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stepcargo`
--

-- --------------------------------------------------------

--
-- Table structure for table `prs`
--

CREATE TABLE `prs` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `vehicle_number` varchar(100) NOT NULL,
  `vendor_name` varchar(100) NOT NULL,
  `hire_amount` varchar(100) NOT NULL,
  `docket` varchar(100) NOT NULL,
  `client_id` varchar(100) NOT NULL,
  `receiver_name` varchar(100) NOT NULL,
  `boy_name` varchar(100) NOT NULL,
  `total_docket` varchar(100) NOT NULL,
  `amount_to_be_collected` varchar(100) NOT NULL,
  `total_weight` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prs`
--

INSERT INTO `prs` (`id`, `date`, `vehicle_number`, `vendor_name`, `hire_amount`, `docket`, `client_id`, `receiver_name`, `boy_name`, `total_docket`, `amount_to_be_collected`, `total_weight`, `created_at`, `updated_at`, `code`, `status_id`) VALUES
(1, '2021-11-28', 'jj', 'jj', '100', '1', '1', 'hh', 'h', 'h', '110', '1', '2021-11-28 11:26:43', '2021-11-28 12:05:40', '001', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prs`
--
ALTER TABLE `prs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prs`
--
ALTER TABLE `prs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
