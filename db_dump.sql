-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2016 at 09:02 PM
-- Server version: 10.1.8-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `userID` int(11) NOT NULL,
  `userTitle` varchar(100) DEFAULT NULL,
  `userFname` varchar(100) NOT NULL,
  `userMname` varchar(100) DEFAULT NULL,
  `userLname` varchar(100) NOT NULL,
  `userEmail` varchar(100) NOT NULL,
  `userPhone` varchar(100) DEFAULT NULL,
  `userWebpage` varchar(100) DEFAULT NULL,
  `userOrganization` varchar(100) DEFAULT NULL,
  `userDepartment` varchar(100) DEFAULT NULL,
  `userAddress` varchar(100) NOT NULL,
  `userCity` varchar(100) NOT NULL,
  `userState` varchar(100) NOT NULL,
  `userZip` varchar(100) NOT NULL,
  `userCountry` varchar(100) NOT NULL,
  `userAccompany` int(11) NOT NULL,
  `userPresentation` varchar(10) NOT NULL,
  `userStudent` varchar(10) NOT NULL,
  `userPaper` varchar(10) NOT NULL,
  `userStatus` enum('Y','N') NOT NULL DEFAULT 'N',
  `tokenCode` varchar(100) NOT NULL,
  `userTimeRegistered` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `userEmail` (`userEmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
