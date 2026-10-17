-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2024 at 01:16 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fd mgmt`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `name`, `email`, `picture`) VALUES
(1, 'admin@gmail.com', 'admin@123', 'Tejas Chavda', 'tc4220@gmail.com', '1645328816_1638437931_TQ7CaP.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `cid` int(10) NOT NULL,
  `c_name` varchar(80) NOT NULL,
  `age` varchar(10) NOT NULL,
  `mob` varchar(50) NOT NULL,
  `country` varchar(100) NOT NULL,
  `created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`cid`, `c_name`, `age`, `mob`, `country`, `created`) VALUES
(1, 'Tejas Chavda', '22', '+919737693302', 'India', '2024-06-19'),
(2, 'Rds', '20', '+918735003360', 'India', '2024-06-17');

-- --------------------------------------------------------

--
-- Table structure for table `fd`
--

CREATE TABLE `fd` (
  `id` int(10) NOT NULL,
  `fdissueddate` date DEFAULT NULL,
  `fdholder` varchar(50) NOT NULL,
  `fdofbank` varchar(100) NOT NULL,
  `principleamt` int(20) NOT NULL,
  `nodays` varchar(20) NOT NULL,
  `intrate` varchar(10) NOT NULL,
  `intamt` int(10) NOT NULL,
  `finalamt` int(20) NOT NULL,
  `maturitydate` date NOT NULL,
  `fdentrydate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fd`
--

INSERT INTO `fd` (`id`, `fdissueddate`, `fdholder`, `fdofbank`, `principleamt`, `nodays`, `intrate`, `intamt`, `finalamt`, `maturitydate`, `fdentrydate`) VALUES
(1, '2024-08-29', 'Rds', 'Saraswat Bank', 51418, '0.5', '5.75', 1458, 52876, '2025-02-27', '2024-06-19 12:45:16'),
(15, '2024-06-17', 'Tejas Chavda', 'Central Bank of India', 50000, '0.5', '6.10', 1537, 51537, '2024-06-19', '2024-06-17 12:43:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `fd`
--
ALTER TABLE `fd`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `cid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fd`
--
ALTER TABLE `fd`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
