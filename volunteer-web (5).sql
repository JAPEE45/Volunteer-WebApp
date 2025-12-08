-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2025 at 03:19 PM
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
(16, 'user_12', 'aff05b70', 12, '2025-10-14', 'volunteer', '2025-10-14'),
(17, 'user_13', '24f18166', 13, '2025-10-16', 'volunteer', '2025-10-16'),
(18, 'user_14', 'ac38135e', 14, '2025-12-07', 'volunteer', '2025-12-07');

-- --------------------------------------------------------

--
-- Table structure for table `activity_reports`
--

CREATE TABLE `activity_reports` (
  `id` int(11) NOT NULL,
  `deployment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `hours_worked` decimal(5,2) NOT NULL DEFAULT 0.00,
  `date_of_activity` date NOT NULL,
  `activities_performed` text NOT NULL,
  `challenges_faced` text DEFAULT NULL,
  `outcomes_achieved` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `supporting_documents` varchar(500) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `submitted_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deployment`
--

CREATE TABLE `deployment` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `createdAt` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deployment`
--

INSERT INTO `deployment` (`id`, `event_id`, `role`, `user_id`, `createdAt`) VALUES
(33, 12, NULL, 8, '2025-10-16'),
(34, 12, NULL, 10, '2025-10-16'),
(35, 14, NULL, 11, '2025-10-16'),
(36, 14, NULL, 8, '2025-11-29'),
(37, 14, NULL, 8, '2025-11-29'),
(38, 12, 'Communications Officer', 14, '2025-12-07');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `evaluator_id` int(11) NOT NULL,
  `physical_fitness` decimal(2,1) NOT NULL DEFAULT 0.0,
  `communication_skills` decimal(2,1) NOT NULL DEFAULT 0.0,
  `teamwork` decimal(2,1) NOT NULL DEFAULT 0.0,
  `reliability` decimal(2,1) NOT NULL DEFAULT 0.0,
  `overall_rating` decimal(2,1) NOT NULL DEFAULT 0.0,
  `comments` text DEFAULT NULL,
  `status` enum('passed','failed') NOT NULL DEFAULT 'passed',
  `evaluation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `eventName` varchar(50) NOT NULL,
  `location` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `duration` int(11) NOT NULL DEFAULT 8 COMMENT 'Duration in hours',
  `end_date` datetime DEFAULT NULL COMMENT 'Auto-calculated end date',
  `status` enum('upcoming','active','completed') NOT NULL DEFAULT 'upcoming',
  `latitude` varchar(20) NOT NULL,
  `longitude` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `eventName`, `location`, `date`, `duration`, `end_date`, `status`, `latitude`, `longitude`) VALUES
(12, 'birthday ni yas', 'San Andres', '2025-10-15', 8, '2025-10-15 08:00:00', 'completed', '13.646521', '124.043884'),
(14, 'catanduangan fest', 'virac', '2078-12-05', 8, '2078-12-05 08:00:00', 'upcoming', '13.699929', '124.243526');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `deployment_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `report_title` varchar(255) NOT NULL,
  `report_description` text DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `status` enum('pending','reviewed','approved','rejected') DEFAULT 'pending',
  `submission_date` datetime NOT NULL DEFAULT current_timestamp(),
  `review_date` datetime DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `deployment_id`, `event_id`, `report_title`, `report_description`, `file_name`, `file_path`, `file_size`, `file_type`, `status`, `submission_date`, `review_date`, `reviewed_by`, `comments`, `created_at`, `updated_at`) VALUES
(1, 8, 33, 12, 'jjiji', 'jk', 'AutoRepairHub-FINAL-NA.docx.pdf', '../uploads/reports/68f3d6406fc81_1760810560_AutoRepairHub-FINAL-NA.docx.pdf', 1271432, 'pdf', 'pending', '2025-10-19 02:02:40', NULL, NULL, NULL, '2025-10-18 18:02:40', '2025-10-18 18:02:40');

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
(13, 'volunteer', 'Shaira', 'Beatriz', 'Vargas', 'Shaira Beatriz Vargas', 'Catanduanes ', 'Female', '2000-10-14', 'Roman catholic', '150', '49', 'Single', 'N/a', 0, '09456646371', '0000000', '', 'Na', 'Na', '0', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 25, '2025-10-14 12:41:28', '[]', 'accepted', 'deployed', '', '2025-10-14'),
(14, 'volunteer', 'Shaira', '', 'Vargas', 'Shaira  Vargas', '', 'Female', '0000-00-00', '', '', '', '', '', 0, '09275237773', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '2025-10-14 12:42:35', '[]', 'accepted', 'deployed', '', '2025-10-14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_reports`
--
ALTER TABLE `activity_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deployment_id` (`deployment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `status` (`status`),
  ADD KEY `idx_user_status` (`user_id`,`status`),
  ADD KEY `idx_event_status` (`event_id`,`status`),
  ADD KEY `idx_submitted_at` (`submitted_at`);

--
-- Indexes for table `deployment`
--
ALTER TABLE `deployment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `evaluator_id` (`evaluator_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event_status` (`status`),
  ADD KEY `idx_end_date` (`end_date`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `deployment_id` (`deployment_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `status` (`status`),
  ADD KEY `idx_submission_date` (`submission_date`),
  ADD KEY `idx_user_status` (`user_id`,`status`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `activity_reports`
--
ALTER TABLE `activity_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deployment`
--
ALTER TABLE `deployment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`deployment_id`) REFERENCES `deployment` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reports_ibfk_3` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
