-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2025 at 07:05 AM
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
-- Database: `volunteer-web`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `createdAt` date NOT NULL DEFAULT current_timestamp(),
  `user_type` varchar(20) NOT NULL DEFAULT 'volunteer',
  `lastLogin` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `username`, `password`, `user_id`, `createdAt`, `user_type`, `lastLogin`) VALUES
(2, 'admin123', 'pass@123', 0, '2025-10-08', 'admin', '2025-10-11'),
(8, 'user_8', 'a35da20c', 8, '2025-10-14', 'volunteer', '2025-10-14'),
(13, 'user_10', 'b0fac96e', 10, '2025-10-14', 'volunteer', '2025-10-14'),
(14, 'user_11', '62fe1dfd', 11, '2025-10-14', 'volunteer', '2025-10-14'),
(15, 'user_11', '2ad3d7c8', 11, '2025-10-14', 'volunteer', '2025-10-14'),
(16, 'user_12', 'aff05b70', 12, '2025-10-14', 'volunteer', '2025-10-14');

-- --------------------------------------------------------

--
-- Table structure for table `deployment`
--

CREATE TABLE `deployment` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `createdAt` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deployment`
--

INSERT INTO `deployment` (`id`, `event_id`, `user_id`, `createdAt`) VALUES
(19, 12, 8, '2025-10-14'),
(25, 12, 9, '2025-10-14'),
(26, 14, 10, '2025-10-14'),
(27, 12, 10, '2025-10-14'),
(28, 14, 11, '2025-10-14'),
(29, 14, 12, '2025-10-14'),
(30, 14, 10, '2025-10-14');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `eventName` varchar(50) NOT NULL,
  `location` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `latitude` varchar(20) NOT NULL,
  `longitude` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `eventName`, `location`, `date`, `latitude`, `longitude`) VALUES
(12, 'birthday ni yas', 'San Andres', '2025-10-15', '13.646521', '124.043884'),
(14, 'catanduangan fest', 'virac', '2078-12-05', '13.699929', '124.243526'),
(15, 'catanduangan fest', 'virac', '2025-10-15', '13.591799', '124.211426');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_type` enum('admin','volunteer','staff') DEFAULT 'volunteer',
  `firstName` varchar(50) DEFAULT NULL,
  `middleName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `fullName` varchar(150) DEFAULT NULL,
  `birthPlace` varchar(100) DEFAULT NULL,
  `sex` enum('Male','Female','Other') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `height` varchar(10) DEFAULT NULL,
  `weight` varchar(10) DEFAULT NULL,
  `civilStatus` enum('Single','Married','Widowed','Separated') DEFAULT NULL,
  `spouse` varchar(100) DEFAULT NULL,
  `children` int(11) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `landline` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `health` text DEFAULT NULL,
  `medication` text DEFAULT NULL,
  `bloodType` varchar(5) DEFAULT NULL,
  `elementary` varchar(100) DEFAULT NULL,
  `elemYearGrad` varchar(10) DEFAULT NULL,
  `highSchool` varchar(100) DEFAULT NULL,
  `hsYearGrad` varchar(10) DEFAULT NULL,
  `college` varchar(100) DEFAULT NULL,
  `collegeYearGrad` varchar(10) DEFAULT NULL,
  `postGrad` varchar(100) DEFAULT NULL,
  `postGradYear` varchar(10) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `languages` varchar(100) DEFAULT NULL,
  `involvements` text DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `workDates` varchar(50) DEFAULT NULL,
  `redCrossMember` enum('Yes','No') DEFAULT 'No',
  `membershipType` varchar(50) DEFAULT NULL,
  `trainings` text DEFAULT NULL,
  `refName` varchar(100) DEFAULT NULL,
  `refContact` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `documents` varchar(500) NOT NULL,
  `account_status` varchar(20) NOT NULL DEFAULT 'pending',
  `status` varchar(20) NOT NULL DEFAULT 'not deployed',
  `reason` varchar(500) NOT NULL,
  `createdAt` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `firstName`, `middleName`, `lastName`, `fullName`, `birthPlace`, `sex`, `dob`, `religion`, `height`, `weight`, `civilStatus`, `spouse`, `children`, `mobile`, `landline`, `address`, `health`, `medication`, `bloodType`, `elementary`, `elemYearGrad`, `highSchool`, `hsYearGrad`, `college`, `collegeYearGrad`, `postGrad`, `postGradYear`, `skills`, `languages`, `involvements`, `company`, `position`, `workDates`, `redCrossMember`, `membershipType`, `trainings`, `refName`, `refContact`, `age`, `created_at`, `documents`, `account_status`, `status`, `reason`, `createdAt`) VALUES
(8, 'volunteer', 'danniella', 'aquino', 'delfino', 'danniella aquino delfino', 'san andres', 'Female', '2004-09-24', 'Roman Catholic', '151', '52', 'Single', '', 2, '09294690908', '', 'bislig, san andres catanduanes', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'No', '', '', '', '', 21, '2025-10-14 02:05:24', '[\"d5fa34fc8036cb75e16982e3eb0bb745.jpg\"]', 'accepted', 'deployed', '', '2025-10-14'),
(9, 'volunteer', 'Nadine', 'aquino', 'Vargas', 'Nadine aquino Vargas', 'san andres', 'Female', '2004-09-24', 'Roman Catholic', '151', '52', 'Single', '', 2, '09275237773', '', 'asdsdfghhjhk', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'lorem', '', '', '', '', '', '', '', 21, '2025-10-14 02:12:47', '[\"Screenshot (7).png\"]', 'pending', 'deployed', '', '2025-10-14'),
(10, 'volunteer', 'Yasmien Joy', 'Villalarbo', 'Avila', 'Yasmien Joy Villalarbo Avila', 'Virac', 'Female', '2003-11-11', 'Roman Catholic', '152', '60', 'Single', '', 0, '09482917041', '', 'Purok 7 Francia Virac Catanduanes', 'N/A', 'N/A', 'o', 'Virac Pilot Elementary School', '2016', 'Immaculate Conception Seminary Academy', '2022', 'Catanduanes State University', '', '', '', '', '', '', 'lorem', '', '', '', '', '', '', '', 21, '2025-10-14 04:00:13', '[\"Screenshot (2).png\"]', 'accepted', 'deployed', '', '2025-10-14'),
(11, 'volunteer', 'reym,und', 'alsol', 'reginaldo', 'reym,und alsol reginaldo', 'virac', 'Male', '1980-12-05', 'Roman Catholic', '', '75', 'Married', 'Jamie Angelik S. Reginaldo', 2, '09959352577', '', 'mabini st., calatagan, virac, catanduanes', 'N/A', '', '0+', 'virac central elementary school', '', 'cnhs', '', 'catsu', '', '', '', 'none', '', 'odd fellows', 'prc', 'volunteer', '', 'Yes', '', 'n/a', '', '', 44, '2025-10-14 05:51:12', '[]', 'accepted', 'deployed', '', '2025-10-14'),
(12, 'volunteer', 'Mark', 'M.', 'Jamero', 'Mark M. Jamero', 'Virac', 'Male', '2025-10-01', 'Roman Catholic', '164', '60', 'Single', 'na', 0, '09482865624', '', 'Calatagan, Virac, Catanduanes', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '2025-10-14 06:14:02', '[]', 'accepted', 'deployed', '', '2025-10-14'),
(13, 'volunteer', 'Shaira', 'Beatriz', 'Vargas', 'Shaira Beatriz Vargas', 'Catanduanes ', 'Female', '2000-10-14', 'Roman catholic', '150', '49', 'Single', 'N/a', 0, '09456646371', '0000000', '', 'Na', 'Na', '0', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 25, '2025-10-14 12:41:28', '[]', 'pending', 'not deployed', '', '2025-10-14'),
(14, 'volunteer', 'Shaira', '', 'Vargas', 'Shaira  Vargas', '', 'Female', '0000-00-00', '', '', '', '', '', 0, '09275237773', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '2025-10-14 12:42:35', '[]', 'pending', 'not deployed', '', '2025-10-14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deployment`
--
ALTER TABLE `deployment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `deployment`
--
ALTER TABLE `deployment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
