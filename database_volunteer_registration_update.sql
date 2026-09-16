-- ===============================================
-- COMPREHENSIVE VOLUNTEER REGISTRATION DATABASE UPDATE
-- Philippine Red Cross - Volunteer Management System
-- Date: December 9, 2025
-- ===============================================
-- This SQL updates the users table to include ALL fields from the official PRC volunteer registration form
-- INSTRUCTIONS: Run this in phpMyAdmin to update your database structure

-- First, backup your current users table (optional but recommended)
-- CREATE TABLE users_backup AS SELECT * FROM users;

-- Drop the existing users table to recreate with new structure
-- WARNING: This will delete all existing data. Export first if needed!
DROP TABLE IF EXISTS `users`;

-- Create the new comprehensive users table
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` enum('admin','volunteer','staff') DEFAULT 'volunteer',
  
  -- I. PERSONAL INFORMATION
  `family_name` varchar(100) DEFAULT NULL COMMENT 'Last Name',
  `given_name` varchar(100) DEFAULT NULL COMMENT 'First Name',
  `middle_name` varchar(100) DEFAULT NULL COMMENT 'Middle Name',
  `nick_name` varchar(50) DEFAULT NULL,
  `fullName` varchar(200) GENERATED ALWAYS AS (CONCAT_WS(' ', `given_name`, `middle_name`, `family_name`)) STORED,
  `sex` enum('Male','Female') DEFAULT NULL,
  `dob` date DEFAULT NULL COMMENT 'Date of Birth',
  `age` int(3) DEFAULT NULL,
  `birth_place` varchar(200) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `height` int(3) DEFAULT NULL COMMENT 'Height in cm',
  `weight` decimal(5,2) DEFAULT NULL COMMENT 'Weight in kilos',
  `civil_status` enum('Single','Married','Widowed','Separated','Divorced') DEFAULT 'Single',
  `spouse_name` varchar(200) DEFAULT NULL COMMENT 'If married',
  `contact_number_personal` varchar(20) DEFAULT NULL,
  `number_of_children` int(2) DEFAULT 0,
  `mobile_number` varchar(20) DEFAULT NULL,
  `landline_number` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  
  -- Address Fields
  `house_no` varchar(50) DEFAULT NULL,
  `street_block_lot` varchar(200) DEFAULT NULL,
  `district_barangay_village` varchar(200) DEFAULT NULL,
  `municipality_city` varchar(200) DEFAULT NULL,
  `province` varchar(200) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `complete_address` text GENERATED ALWAYS AS (CONCAT_WS(', ', 
    NULLIF(`house_no`, ''),
    NULLIF(`street_block_lot`, ''),
    NULLIF(`district_barangay_village`, ''),
    NULLIF(`municipality_city`, ''),
    NULLIF(`province`, ''),
    NULLIF(`zip_code`, '')
  )) STORED,
  
  -- II. MEDICAL HISTORY
  `medical_conditions` text DEFAULT NULL COMMENT 'Pre-existing conditions/disability/allergies',
  `current_medications` text DEFAULT NULL,
  `blood_type` varchar(5) DEFAULT NULL,
  
  -- Emergency Contact 1 (Immediate Family)
  `emergency_contact1_name` varchar(200) DEFAULT NULL,
  `emergency_contact1_relationship` varchar(100) DEFAULT NULL,
  `emergency_contact1_landline` varchar(20) DEFAULT NULL,
  `emergency_contact1_mobile` varchar(20) DEFAULT NULL,
  
  -- Emergency Contact 2 (Other than Immediate Family)
  `emergency_contact2_name` varchar(200) DEFAULT NULL,
  `emergency_contact2_relationship` varchar(100) DEFAULT NULL,
  `emergency_contact2_landline` varchar(20) DEFAULT NULL,
  `emergency_contact2_mobile` varchar(20) DEFAULT NULL,
  
  -- III. FAMILY BACKGROUND
  `father_name` varchar(200) DEFAULT NULL,
  `father_age` int(3) DEFAULT NULL,
  `father_occupation` varchar(200) DEFAULT NULL,
  `mother_name` varchar(200) DEFAULT NULL,
  `mother_age` int(3) DEFAULT NULL,
  `mother_occupation` varchar(200) DEFAULT NULL,
  `number_of_siblings` int(2) DEFAULT NULL,
  `position_in_family` varchar(50) DEFAULT NULL COMMENT 'e.g., 1st, 2nd, Youngest, Eldest',
  
  -- IV. EDUCATIONAL BACKGROUND
  -- Elementary
  `elementary_school` varchar(200) DEFAULT NULL,
  `elementary_year_graduated` varchar(10) DEFAULT NULL,
  `elementary_honors` text DEFAULT NULL,
  
  -- High School
  `highschool_school` varchar(200) DEFAULT NULL,
  `highschool_year_graduated` varchar(10) DEFAULT NULL,
  `highschool_honors` text DEFAULT NULL,
  
  -- College
  `college_school` varchar(200) DEFAULT NULL,
  `college_course` varchar(200) DEFAULT NULL,
  `college_year_graduated` varchar(10) DEFAULT NULL,
  `college_honors` text DEFAULT NULL,
  
  -- Vocational
  `vocational_school` varchar(200) DEFAULT NULL,
  `vocational_year_graduated` varchar(10) DEFAULT NULL,
  `vocational_honors` text DEFAULT NULL,
  
  -- Higher Studies
  `higher_studies_school` varchar(200) DEFAULT NULL,
  `higher_studies_year_graduated` varchar(10) DEFAULT NULL,
  `higher_studies_honors` text DEFAULT NULL,
  
  -- IX. TALENTS AND SKILLS
  `talents` text DEFAULT NULL COMMENT 'What would you consider as your talent(s)?',
  `skills` text DEFAULT NULL COMMENT 'What are some skill(s) you possess?',
  `languages_dialects` text DEFAULT NULL COMMENT 'Languages & dialects you can speak, read and understand fluently',
  
  -- V. SOCIO-CIVIC, CULTURAL & RELIGIOUS INVOLVEMENTS
  -- Involvement 1
  `involvement1_organization` varchar(300) DEFAULT NULL,
  `involvement1_position` varchar(200) DEFAULT NULL,
  `involvement1_year` varchar(20) DEFAULT NULL,
  
  -- Involvement 2
  `involvement2_organization` varchar(300) DEFAULT NULL,
  `involvement2_position` varchar(200) DEFAULT NULL,
  `involvement2_year` varchar(20) DEFAULT NULL,
  
  -- VI. WORK EXPERIENCE
  -- Experience 1
  `work_exp1_company` varchar(300) DEFAULT NULL,
  `work_exp1_position` varchar(200) DEFAULT NULL,
  `work_exp1_year` varchar(50) DEFAULT NULL,
  
  -- Experience 2
  `work_exp2_company` varchar(300) DEFAULT NULL,
  `work_exp2_position` varchar(200) DEFAULT NULL,
  `work_exp2_year` varchar(50) DEFAULT NULL,
  
  -- VII. RED CROSS EXPERIENCE
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
  
  -- IX. REFERENCES
  -- Reference 1
  `reference1_name` varchar(200) DEFAULT NULL,
  `reference1_contact` varchar(100) DEFAULT NULL,
  `reference1_company` varchar(300) DEFAULT NULL,
  `reference1_position` varchar(200) DEFAULT NULL,
  
  -- Reference 2
  `reference2_name` varchar(200) DEFAULT NULL,
  `reference2_contact` varchar(100) DEFAULT NULL,
  `reference2_company` varchar(300) DEFAULT NULL,
  `reference2_position` varchar(200) DEFAULT NULL,
  
  -- VIII. VOLUNTEER WAIVER
  `waiver_signatory_name` varchar(200) DEFAULT NULL,
  `waiver_signature` varchar(500) DEFAULT NULL COMMENT 'Can store signature image path',
  `waiver_date_place` varchar(200) DEFAULT NULL,
  
  -- VIII. CHILD PROTECTION POLICY & CODE OF CONDUCT
  `policy_signatory_name` varchar(200) DEFAULT NULL,
  `policy_signature` varchar(500) DEFAULT NULL COMMENT 'Can store signature image path',
  `policy_date_place` varchar(200) DEFAULT NULL,
  
  -- CERTIFICATION & CONFIDENTIALITY
  `cert_signatory_name` varchar(200) DEFAULT NULL,
  `cert_signature` varchar(500) DEFAULT NULL COMMENT 'Can store signature image path',
  `cert_date` date DEFAULT NULL,
  
  -- TO BE FILLED UP BY VSO STAFF (REFERENCE CHECK)
  `ref_check_person_contacted` varchar(200) DEFAULT NULL,
  `ref_check_company` varchar(300) DEFAULT NULL,
  `ref_check_contact_number` varchar(50) DEFAULT NULL,
  `ref_check_date` date DEFAULT NULL,
  `ref_check_comments` text DEFAULT NULL,
  
  -- OVER-ALL EVALUATION
  `eval_highly_recommended` tinyint(1) DEFAULT 0,
  `eval_recommended` tinyint(1) DEFAULT 0,
  `eval_not_recommended` tinyint(1) DEFAULT 0,
  
  -- FINAL DECISION
  `final_accepted` tinyint(1) DEFAULT 0,
  `final_rejected` tinyint(1) DEFAULT 0,
  
  -- OFFICIAL SIGNATURES
  `vs_staff_signature` varchar(500) DEFAULT NULL,
  `vs_staff_name` varchar(200) DEFAULT NULL,
  `vs_staff_date_place` varchar(200) DEFAULT NULL,
  `head_office_signature` varchar(500) DEFAULT NULL,
  `head_office_name` varchar(200) DEFAULT NULL,
  
  -- Supporting Documents
  `documents` text DEFAULT NULL COMMENT 'JSON array of uploaded document paths',
  
  -- System Fields
  `account_status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `status` enum('not deployed','deployed','inactive') DEFAULT 'not deployed',
  `reason` text DEFAULT NULL COMMENT 'Reason for rejection if applicable',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  
  PRIMARY KEY (`id`),
  KEY `idx_account_status` (`account_status`),
  KEY `idx_volunteer_status` (`status`),
  KEY `idx_user_type` (`user_type`),
  KEY `idx_fullname` (`fullName`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ===============================================
-- END OF SQL UPDATE
-- ===============================================
-- After running this SQL:
-- 1. Update register.php with the new form fields
-- 2. Update addUser.php to handle the new fields
-- 3. Update profile pages to display the new fields
-- ===============================================
