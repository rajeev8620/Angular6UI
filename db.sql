-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 29, 2019 at 11:16 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `MelanomaPredictor`
--

-- --------------------------------------------------------

--
-- Table structure for table `ConsumerDetails`
--

CREATE TABLE `ConsumerDetails` (
  `ConsumerId` bigint(21) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) DEFAULT NULL,
  `Email` varchar(150) NOT NULL,
  `Password` varchar(120) NOT NULL,
  `UserType` int(1) NOT NULL DEFAULT '1' COMMENT '1: Normal User',
  `Status` int(1) NOT NULL DEFAULT '1' COMMENT '0:Inactive,1:Newly Register,2: Active',
  `LastModified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ConsumerDetails`
--

INSERT INTO `ConsumerDetails` (`ConsumerId`, `FirstName`, `LastName`, `Email`, `Password`, `UserType`, `Status`, `LastModified`) VALUES
(1, 'Rajeev', 'Kumar', 'rajeev.kumar@cellworksgroup.com', '1b94f77281999de8f578d22462bfc0c0', 1, 2, '2019-05-22 12:28:06'),
(2, 'Aktar', 'Alam', 'aktar@cellworksgroup.com', '1b94f77281999de8f578d22462bfc0c0', 1, 1, '2019-05-28 14:51:18');

-- --------------------------------------------------------

--
-- Table structure for table `OrderDetails`
--

CREATE TABLE `OrderDetails` (
  `OrderId` bigint(21) NOT NULL,
  `ConsumerId` bigint(21) NOT NULL COMMENT 'Ref: ConsumerDetails',
  `UploadedFileName` varchar(500) NOT NULL,
  `UploadedOn` datetime NOT NULL,
  `Status` int(1) NOT NULL DEFAULT '0' COMMENT '0:Report Not Available,1:Report Available',
  `CompletedOn` datetime DEFAULT NULL,
  `ReportFileName` varchar(150) DEFAULT NULL,
  `LastModified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ConsumerDetails`
--
ALTER TABLE `ConsumerDetails`
  ADD PRIMARY KEY (`ConsumerId`);

--
-- Indexes for table `OrderDetails`
--
ALTER TABLE `OrderDetails`
  ADD PRIMARY KEY (`OrderId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ConsumerDetails`
--
ALTER TABLE `ConsumerDetails`
  MODIFY `ConsumerId` bigint(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `OrderDetails`
--
ALTER TABLE `OrderDetails`
  MODIFY `OrderId` bigint(21) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
