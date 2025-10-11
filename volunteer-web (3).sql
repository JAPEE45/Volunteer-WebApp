-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2025 at 12:43 PM
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
(1, 'userD9BBBB', 'TDC{89[S)oa#', 4, '2025-10-08', 'volunteer', '2025-10-11'),
(2, 'admin123', 'pass@123', 0, '2025-10-08', 'admin', '2025-10-11');

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
(3, 5, 2, '2025-10-07'),
(4, 5, 3, '2025-10-07');

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
(5, 'Party', 'sa puso mo', '2025-10-21', '13.823687', '124.280243');

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
  `status` varchar(20) NOT NULL DEFAULT 'not deployed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `firstName`, `middleName`, `lastName`, `fullName`, `birthPlace`, `sex`, `dob`, `religion`, `height`, `weight`, `civilStatus`, `spouse`, `children`, `mobile`, `landline`, `address`, `health`, `medication`, `bloodType`, `elementary`, `elemYearGrad`, `highSchool`, `hsYearGrad`, `college`, `collegeYearGrad`, `postGrad`, `postGradYear`, `skills`, `languages`, `involvements`, `company`, `position`, `workDates`, `redCrossMember`, `membershipType`, `trainings`, `refName`, `refContact`, `age`, `created_at`, `documents`, `account_status`, `status`) VALUES
(2, 'volunteer', 'i', 'i', 'i', 'i i i', 'i', 'Female', '2025-10-08', '9', '9', '9', '', '9', 9, '9', '9', '9', '9', '9', '', '99', '9', '', '9', '9', '9', '9', '', '9', '9', '9', '9', '9', '9', '', '9', '9', '9', '9', 9, '2025-10-06 17:45:35', '[\"hi po.pptx\"]', 'accepted', 'deployed'),
(3, 'volunteer', 'jasper', 'angeles', 'fernandez', 'jasper angeles fernandez', 'cebu city', 'Male', '2009-02-09', ';', '162', '40', 'Single', 'kl', 9, '09197960151', '765867897', 'Sa puso mo', 'jk', 'jkj', 'kj', 'kjkj', 'kj', 'kj', 'kjk', 'jk', 'jk', 'j', 'kj', 'kjk', 'j', 'kj', 'k', 'jkj', 'k', 'Yes', 'ahaks', 'asa', 'japee', '0910', 12, '2025-10-06 21:08:39', '[\"index.php\"]', 'accepted', 'deployed'),
(4, 'volunteer', 'Jessel', 'Fenandez', 'Camolo', 'Jessel Fenandez Camolo', 'Masbate City', 'Male', '2003-09-13', 'Christian', '162', '45', 'Single', 'Jasper A. Fernandez', 0, '09197960151', '', 'san fernando masbate', '', '', '', 'Central Elementary school', '2012-2013', 'basta', '2019-2020', 'Osmena Colleges', 'Not yet', '', '', 'Singing hehe', 'Minasbate', 'Uwu', 'Acode', 'Head Staff', 'Oct 14, 2024', 'Yes', 'qs', 'dsa', 'b', '9', 22, '2025-10-07 16:07:13', '[\"550556286_1866043530997785_3261884751009638669_n.png\"]', 'accepted', 'not deployed'),
(5, 'volunteer', 'j', 'j', 'j', 'j j j', 'j', 'Male', '2025-10-20', 'kk', '80', '8', '', '9', 9, '9', '9', '9', '9', '9', '9', '9', '9', '9', '9', '999', '9', '9', '99', '', '9', '99', '9', '9', '9', '', '9', '9', '9', '9', 90, '2025-10-07 16:09:35', '[\"wps_wid.cid-996589457.1758604627.exe\"]', 'pending', 'not deployed');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `deployment`
--
ALTER TABLE `deployment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
