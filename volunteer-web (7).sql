-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2025 at 04:35 PM
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
(2, 'admin123', 'pass@123', 0, '2025-10-08', 'admin', '2025-12-09'),
(20, 'user_19', 'c342baf7', 19, '2025-12-09', 'volunteer', '2025-12-09');

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

--
-- Dumping data for table `activity_reports`
--

INSERT INTO `activity_reports` (`id`, `deployment_id`, `user_id`, `event_id`, `hours_worked`, `date_of_activity`, `activities_performed`, `challenges_faced`, `outcomes_achieved`, `recommendations`, `supporting_documents`, `status`, `admin_notes`, `reviewed_by`, `reviewed_at`, `submitted_at`, `updated_at`) VALUES
(1, 33, 8, 12, 2.00, '2025-10-15', 'o', 'p', 'p', 'p', '[\"activity_reports\\/693828fc7b742_1765288188.pdf\"]', 'approved', 'yehey', 0, '2025-12-09 21:54:43', '2025-12-09 21:49:48', '2025-12-09 21:54:43'),
(2, 39, 19, 12, 2.00, '2025-10-17', 'o', 'p', 'p', 'p', '[\"activity_reports\\/69384019471ef_1765294105.png\"]', 'approved', 'hh', 0, '2025-12-09 23:31:19', '2025-12-09 23:28:25', '2025-12-09 23:31:19');

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
(39, 12, 'Documentation', 19, '2025-12-09');

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_type` enum('admin','volunteer','staff') DEFAULT 'volunteer',
  `family_name` varchar(100) DEFAULT NULL COMMENT 'Last Name',
  `given_name` varchar(100) DEFAULT NULL COMMENT 'First Name',
  `middle_name` varchar(100) DEFAULT NULL COMMENT 'Middle Name',
  `nick_name` varchar(50) DEFAULT NULL,
  `fullName` varchar(200) GENERATED ALWAYS AS (concat_ws(' ',`given_name`,`middle_name`,`family_name`)) STORED,
  `sex` enum('Male','Female') DEFAULT NULL,
  `dob` date DEFAULT NULL COMMENT 'Date of Birth',
  `religion` varchar(100) DEFAULT NULL,
  `height` varchar(10) DEFAULT NULL,
  `weight` varchar(10) DEFAULT NULL,
  `civil_status` enum('Single','Married','Widowed','Separated','Divorced') DEFAULT 'Single',
  `spouse_name` varchar(200) DEFAULT NULL COMMENT 'If married',
  `contact_number_personal` varchar(20) DEFAULT NULL,
  `number_of_children` int(2) DEFAULT 0,
  `mobile_number` varchar(20) DEFAULT NULL,
  `landline_number` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `house_no` varchar(50) DEFAULT NULL,
  `street_block_lot` varchar(200) DEFAULT NULL,
  `district_barangay_village` varchar(200) DEFAULT NULL,
  `municipality_city` varchar(200) DEFAULT NULL,
  `province` varchar(200) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `complete_address` text GENERATED ALWAYS AS (concat_ws(', ',nullif(`house_no`,''),nullif(`street_block_lot`,''),nullif(`district_barangay_village`,''),nullif(`municipality_city`,''),nullif(`province`,''),nullif(`zip_code`,''))) STORED,
  `medical_conditions` text DEFAULT NULL COMMENT 'Pre-existing conditions/disability/allergies',
  `current_medications` text DEFAULT NULL,
  `blood_type` varchar(5) DEFAULT NULL,
  `emergency_contact1_name` varchar(200) DEFAULT NULL,
  `emergency_contact1_relationship` varchar(100) DEFAULT NULL,
  `emergency_contact1_landline` varchar(20) DEFAULT NULL,
  `emergency_contact1_mobile` varchar(20) DEFAULT NULL,
  `emergency_contact2_name` varchar(200) DEFAULT NULL,
  `emergency_contact2_relationship` varchar(100) DEFAULT NULL,
  `emergency_contact2_landline` varchar(20) DEFAULT NULL,
  `emergency_contact2_mobile` varchar(20) DEFAULT NULL,
  `father_name` varchar(200) DEFAULT NULL,
  `father_age` int(3) DEFAULT NULL,
  `father_occupation` varchar(200) DEFAULT NULL,
  `mother_name` varchar(200) DEFAULT NULL,
  `mother_age` int(3) DEFAULT NULL,
  `mother_occupation` varchar(200) DEFAULT NULL,
  `number_of_siblings` int(2) DEFAULT NULL,
  `position_in_family` varchar(50) DEFAULT NULL COMMENT 'e.g., 1st, 2nd, Youngest, Eldest',
  `elementary_school` varchar(200) DEFAULT NULL,
  `elementary_year_graduated` varchar(10) DEFAULT NULL,
  `elementary_honors` text DEFAULT NULL,
  `highschool_school` varchar(200) DEFAULT NULL,
  `highschool_year_graduated` varchar(10) DEFAULT NULL,
  `highschool_honors` text DEFAULT NULL,
  `college_school` varchar(200) DEFAULT NULL,
  `college_course` varchar(200) DEFAULT NULL,
  `college_year_graduated` varchar(10) DEFAULT NULL,
  `college_honors` text DEFAULT NULL,
  `vocational_school` varchar(200) DEFAULT NULL,
  `vocational_year_graduated` varchar(10) DEFAULT NULL,
  `vocational_honors` text DEFAULT NULL,
  `higher_studies_school` varchar(200) DEFAULT NULL,
  `higher_studies_year_graduated` varchar(10) DEFAULT NULL,
  `higher_studies_honors` text DEFAULT NULL,
  `talents` text DEFAULT NULL COMMENT 'What would you consider as your talent(s)?',
  `skills` text DEFAULT NULL,
  `languages_dialects` text DEFAULT NULL COMMENT 'Languages & dialects you can speak, read and understand fluently',
  `involvement1_organization` varchar(300) DEFAULT NULL,
  `involvement1_position` varchar(200) DEFAULT NULL,
  `involvement1_year` varchar(20) DEFAULT NULL,
  `involvement2_organization` varchar(300) DEFAULT NULL,
  `involvement2_position` varchar(200) DEFAULT NULL,
  `involvement2_year` varchar(20) DEFAULT NULL,
  `work_exp1_company` varchar(300) DEFAULT NULL,
  `work_exp1_position` varchar(200) DEFAULT NULL,
  `work_exp1_year` varchar(50) DEFAULT NULL,
  `work_exp2_company` varchar(300) DEFAULT NULL,
  `work_exp2_position` varchar(200) DEFAULT NULL,
  `work_exp2_year` varchar(50) DEFAULT NULL,
  `rc_is_volunteer` enum('YES','NO') DEFAULT 'NO',
  `rc_month_year_started` varchar(20) DEFAULT NULL,
  `rc_has_maab` enum('YES','NO') DEFAULT 'NO' COMMENT 'Membership with Accident Assistance Benefits',
  `rc_maab_serial_no` varchar(100) DEFAULT NULL,
  `rc_maab_validity` varchar(50) DEFAULT NULL,
  `rc_basic_orientation` enum('YES','NO') DEFAULT 'NO',
  `rc_basic_orientation_year` varchar(10) DEFAULT NULL,
  `rc_rc143_training` enum('YES','NO') DEFAULT 'NO',
  `rc_rc143_training_year` varchar(10) DEFAULT NULL,
  `rc_other_trainings` text DEFAULT NULL COMMENT 'Other Red Cross Training/Courses Acquired',
  `rc_training_dates` text DEFAULT NULL COMMENT 'Exclusive Dates',
  `reference1_name` varchar(200) DEFAULT NULL,
  `reference1_contact` varchar(100) DEFAULT NULL,
  `reference1_company` varchar(300) DEFAULT NULL,
  `reference1_position` varchar(200) DEFAULT NULL,
  `reference2_name` varchar(200) DEFAULT NULL,
  `reference2_contact` varchar(100) DEFAULT NULL,
  `reference2_company` varchar(300) DEFAULT NULL,
  `reference2_position` varchar(200) DEFAULT NULL,
  `waiver_signatory_name` varchar(200) DEFAULT NULL,
  `waiver_signature` varchar(500) DEFAULT NULL COMMENT 'Can store signature image path',
  `waiver_date_place` varchar(200) DEFAULT NULL,
  `policy_signatory_name` varchar(200) DEFAULT NULL,
  `policy_signature` varchar(500) DEFAULT NULL COMMENT 'Can store signature image path',
  `policy_date_place` varchar(200) DEFAULT NULL,
  `cert_signatory_name` varchar(200) DEFAULT NULL,
  `cert_signature` varchar(500) DEFAULT NULL COMMENT 'Can store signature image path',
  `cert_date` date DEFAULT NULL,
  `ref_check_person_contacted` varchar(200) DEFAULT NULL,
  `ref_check_company` varchar(300) DEFAULT NULL,
  `ref_check_contact_number` varchar(50) DEFAULT NULL,
  `ref_check_date` date DEFAULT NULL,
  `ref_check_comments` text DEFAULT NULL,
  `eval_highly_recommended` tinyint(1) DEFAULT 0,
  `eval_recommended` tinyint(1) DEFAULT 0,
  `eval_not_recommended` tinyint(1) DEFAULT 0,
  `final_accepted` tinyint(1) DEFAULT 0,
  `final_rejected` tinyint(1) DEFAULT 0,
  `vs_staff_signature` varchar(500) DEFAULT NULL,
  `vs_staff_name` varchar(200) DEFAULT NULL,
  `vs_staff_date_place` varchar(200) DEFAULT NULL,
  `head_office_signature` varchar(500) DEFAULT NULL,
  `head_office_name` varchar(200) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `birth_place` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `documents` text DEFAULT NULL COMMENT 'JSON array of uploaded document paths',
  `account_status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `status` enum('not deployed','deployed','inactive') DEFAULT 'not deployed',
  `reason` text DEFAULT NULL COMMENT 'Reason for rejection if applicable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `family_name`, `given_name`, `middle_name`, `nick_name`, `sex`, `dob`, `religion`, `height`, `weight`, `civil_status`, `spouse_name`, `contact_number_personal`, `number_of_children`, `mobile_number`, `landline_number`, `email`, `house_no`, `street_block_lot`, `district_barangay_village`, `municipality_city`, `province`, `zip_code`, `medical_conditions`, `current_medications`, `blood_type`, `emergency_contact1_name`, `emergency_contact1_relationship`, `emergency_contact1_landline`, `emergency_contact1_mobile`, `emergency_contact2_name`, `emergency_contact2_relationship`, `emergency_contact2_landline`, `emergency_contact2_mobile`, `father_name`, `father_age`, `father_occupation`, `mother_name`, `mother_age`, `mother_occupation`, `number_of_siblings`, `position_in_family`, `elementary_school`, `elementary_year_graduated`, `elementary_honors`, `highschool_school`, `highschool_year_graduated`, `highschool_honors`, `college_school`, `college_course`, `college_year_graduated`, `college_honors`, `vocational_school`, `vocational_year_graduated`, `vocational_honors`, `higher_studies_school`, `higher_studies_year_graduated`, `higher_studies_honors`, `talents`, `skills`, `languages_dialects`, `involvement1_organization`, `involvement1_position`, `involvement1_year`, `involvement2_organization`, `involvement2_position`, `involvement2_year`, `work_exp1_company`, `work_exp1_position`, `work_exp1_year`, `work_exp2_company`, `work_exp2_position`, `work_exp2_year`, `rc_is_volunteer`, `rc_month_year_started`, `rc_has_maab`, `rc_maab_serial_no`, `rc_maab_validity`, `rc_basic_orientation`, `rc_basic_orientation_year`, `rc_rc143_training`, `rc_rc143_training_year`, `rc_other_trainings`, `rc_training_dates`, `reference1_name`, `reference1_contact`, `reference1_company`, `reference1_position`, `reference2_name`, `reference2_contact`, `reference2_company`, `reference2_position`, `waiver_signatory_name`, `waiver_signature`, `waiver_date_place`, `policy_signatory_name`, `policy_signature`, `policy_date_place`, `cert_signatory_name`, `cert_signature`, `cert_date`, `ref_check_person_contacted`, `ref_check_company`, `ref_check_contact_number`, `ref_check_date`, `ref_check_comments`, `eval_highly_recommended`, `eval_recommended`, `eval_not_recommended`, `final_accepted`, `final_rejected`, `vs_staff_signature`, `vs_staff_name`, `vs_staff_date_place`, `head_office_signature`, `head_office_name`, `age`, `birth_place`, `created_at`, `documents`, `account_status`, `status`, `reason`) VALUES
(15, 'volunteer', 'Santos', 'Maria', 'Cruz', 'Ria', 'Female', '1995-05-15', 'Roman Catholic', '165', '58.5', 'Single', NULL, '09171234567', 0, '09171234567', '(02) 8123-4567', 'maria.santos@email.com', '123', 'Rizal Street', 'Barangay San Antonio', 'Quezon City', 'Metro Manila', '1105', 'Allergic to penicillin', 'None', 'O+', 'Juan Santos', 'Father', NULL, '09189876543', 'Ana Reyes', 'Friend', NULL, '09176543210', 'Juan Santos', 58, 'Engineer', 'Rosa Santos', 55, 'Teacher', 2, '2nd', 'Manila Elementary School', '2007', 'With Honors', 'Quezon City High School', '2011', 'Honor Roll', 'University of the Philippines', 'BS Nursing', '2015', 'Cum Laude', NULL, NULL, NULL, NULL, NULL, NULL, 'Singing, Public Speaking, Arts', 'First Aid, CPR Certified, Basic Life Support, Disaster Response', 'English, Filipino, Tagalog, Basic Spanish', 'Barangay Youth Council', 'Secretary', '2018-2020', NULL, NULL, NULL, 'St. Luke\'s Medical Center', 'Staff Nurse', '2015-Present', NULL, NULL, NULL, 'YES', 'January 2020', 'YES', NULL, NULL, 'YES', '2020', 'YES', '2020', 'Disaster Response Training, Blood Donation Seminar, Community Health', NULL, 'Dr. Michael Tan', '09181112222', 'St. Luke\'s Medical Center', 'Chief Nurse', 'Prof. Linda Garcia', '09183334444', 'University of the Philippines', 'Nursing Professor', 'Maria Cruz Santos', NULL, 'Quezon City, December 9, 2025', 'Maria Cruz Santos', NULL, 'Quezon City, December 9, 2025', 'Maria Cruz Santos', NULL, '2025-12-09', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 29, 'Manila City', '2025-12-09 14:31:37', NULL, 'accepted', 'not deployed', NULL),
(16, 'volunteer', 'Dela Cruz', 'Juan ', 'Carnova', NULL, 'Male', '2025-12-16', 'christian', '169', '48', 'Single', 'Janna Angeles', NULL, 2, '09197960151', '090916780', NULL, NULL, NULL, 'san fernando masbate', NULL, NULL, NULL, 'Skin irratation', 'none', 'b', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'kjk', 'jkjk', NULL, '2024', 'kjk', NULL, 'jk', NULL, '2025', NULL, NULL, NULL, NULL, 'kjk', '2001', NULL, NULL, 'jk', 'jkjk', 'jkjk', NULL, NULL, NULL, NULL, NULL, 'jk', 'jkj', 'kj', NULL, NULL, NULL, 'NO', NULL, 'YES', NULL, NULL, 'NO', NULL, 'NO', NULL, 'jk', NULL, 'jk', 'jk', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, -1, 'cebu city', '2025-12-09 14:38:20', '[\"597904903_1203333491748798_7367235545208481877_n.jpg\"]', 'rejected', 'not deployed', 'ha'),
(17, 'volunteer', 'j', 'j', 'j', NULL, 'Male', '2025-12-16', 'k', '9', '9', '', '9', NULL, 9, '09197960151', '9', NULL, NULL, NULL, '9', NULL, NULL, NULL, 'k', 'k', 'k', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'k', 'k', NULL, 'kk', 'k', NULL, 'k', NULL, 'k', NULL, NULL, NULL, NULL, 'k', 'k', NULL, NULL, 'k', 'k', 'kk', NULL, NULL, NULL, NULL, NULL, 'k', 'k', 'k', NULL, NULL, NULL, 'YES', NULL, 'YES', NULL, NULL, 'NO', NULL, 'NO', NULL, 'k', NULL, 'k', 'k', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, -1, 'j', '2025-12-09 14:41:33', '[\"597904903_1203333491748798_7367235545208481877_n.jpg\"]', 'pending', 'not deployed', NULL),
(18, 'volunteer', 'jk', 'jkj', 'kjk', NULL, 'Male', '2025-12-26', 'jkj', '9', '9', '', 'jk', NULL, 3, '09197960151', '0909', NULL, NULL, NULL, 'san fernando masbate', NULL, NULL, NULL, 'j', 'jk', 'jkj', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'kj', 'kj', NULL, 'k', 'jk', NULL, 'jk', NULL, 'j', NULL, NULL, NULL, NULL, 'kj', 'k', NULL, NULL, 'jk', 'j', 'kj', NULL, NULL, NULL, NULL, NULL, 'k', 'jk', 'jk', NULL, NULL, NULL, 'YES', NULL, 'YES', NULL, NULL, 'NO', NULL, 'NO', NULL, 'k', NULL, 'jk', 'jkj', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, -1, 'jk', '2025-12-09 14:53:07', '[\"597904903_1203333491748798_7367235545208481877_n.jpg\"]', 'pending', 'not deployed', NULL),
(19, 'volunteer', 'p', 'p', 'p', NULL, 'Male', '2025-12-24', 'jk', '8', '8', '', 'j', NULL, 3, '09197960151', '8', NULL, NULL, NULL, 'san fernando masbate', NULL, NULL, NULL, 'jj', 'j', 'j', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'j', 'j', NULL, 'j', 'j', NULL, 'j', NULL, 'j', NULL, NULL, NULL, NULL, 'j', 'j', NULL, NULL, 'j', 'j', 'j', NULL, NULL, NULL, NULL, NULL, 'j', 'j', 'j', NULL, NULL, NULL, 'YES', NULL, 'YES', NULL, NULL, 'NO', NULL, 'NO', NULL, 'i', NULL, 'i', 'i', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, -1, 'p', '2025-12-09 15:00:56', '[\"597904903_1203333491748798_7367235545208481877_n.jpg\"]', 'accepted', 'deployed', '');

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
  ADD UNIQUE KEY `unique_deployment_report` (`deployment_id`),
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_account_status` (`account_status`),
  ADD KEY `idx_volunteer_status` (`status`),
  ADD KEY `idx_user_type` (`user_type`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `activity_reports`
--
ALTER TABLE `activity_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `deployment`
--
ALTER TABLE `deployment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
