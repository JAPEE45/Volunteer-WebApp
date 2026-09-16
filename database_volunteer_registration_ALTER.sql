-- ===============================================
-- COMPREHENSIVE VOLUNTEER REGISTRATION DATABASE UPDATE
-- Philippine Red Cross - Volunteer Management System
-- ALTER TABLE VERSION - Preserves Existing Data
-- Date: December 9, 2025
-- ===============================================
-- This SQL updates the users table using ALTER TABLE commands
-- INSTRUCTIONS: Run this in phpMyAdmin to update your database structure
-- Existing data will be preserved where column names match

-- ===============================================
-- STEP 1: Drop unnecessary/old columns
-- ===============================================

-- Drop old redundant columns (if they exist)
ALTER TABLE `users` 
  DROP COLUMN IF EXISTS `firstName`,
  DROP COLUMN IF EXISTS `middleName`,
  DROP COLUMN IF EXISTS `lastName`,
  DROP COLUMN IF EXISTS `birthPlace`,
  DROP COLUMN IF EXISTS `dob`,
  DROP COLUMN IF EXISTS `civilStatus`,
  DROP COLUMN IF EXISTS `spouse`,
  DROP COLUMN IF EXISTS `children`,
  DROP COLUMN IF EXISTS `mobile`,
  DROP COLUMN IF EXISTS `landline`,
  DROP COLUMN IF EXISTS `address`,
  DROP COLUMN IF EXISTS `health`,
  DROP COLUMN IF EXISTS `medication`,
  DROP COLUMN IF EXISTS `bloodType`,
  DROP COLUMN IF EXISTS `elementary`,
  DROP COLUMN IF EXISTS `elemYearGrad`,
  DROP COLUMN IF EXISTS `highSchool`,
  DROP COLUMN IF EXISTS `hsYearGrad`,
  DROP COLUMN IF EXISTS `college`,
  DROP COLUMN IF EXISTS `collegeYearGrad`,
  DROP COLUMN IF EXISTS `postGrad`,
  DROP COLUMN IF EXISTS `postGradYear`,
  DROP COLUMN IF EXISTS `languages`,
  DROP COLUMN IF EXISTS `involvements`,
  DROP COLUMN IF EXISTS `company`,
  DROP COLUMN IF EXISTS `position`,
  DROP COLUMN IF EXISTS `workDates`,
  DROP COLUMN IF EXISTS `redCrossMember`,
  DROP COLUMN IF EXISTS `membershipType`,
  DROP COLUMN IF EXISTS `trainings`,
  DROP COLUMN IF EXISTS `refName`,
  DROP COLUMN IF EXISTS `refContact`,
  DROP COLUMN IF EXISTS `createdAt`;

-- ===============================================
-- STEP 2: Add new comprehensive columns
-- ===============================================

-- I. PERSONAL INFORMATION
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `family_name` varchar(100) DEFAULT NULL COMMENT 'Last Name' AFTER `user_type`,
  ADD COLUMN IF NOT EXISTS `given_name` varchar(100) DEFAULT NULL COMMENT 'First Name' AFTER `family_name`,
  ADD COLUMN IF NOT EXISTS `middle_name` varchar(100) DEFAULT NULL COMMENT 'Middle Name' AFTER `given_name`,
  ADD COLUMN IF NOT EXISTS `nick_name` varchar(50) DEFAULT NULL AFTER `middle_name`,
  MODIFY COLUMN `fullName` varchar(200) GENERATED ALWAYS AS (CONCAT_WS(' ', `given_name`, `middle_name`, `family_name`)) STORED,
  MODIFY COLUMN `sex` enum('Male','Female') DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `dob` date DEFAULT NULL COMMENT 'Date of Birth' AFTER `sex`,
  ADD COLUMN IF NOT EXISTS `age` int(3) DEFAULT NULL AFTER `dob`,
  ADD COLUMN IF NOT EXISTS `birth_place` varchar(200) DEFAULT NULL AFTER `age`,
  MODIFY COLUMN `religion` varchar(100) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `height` int(3) DEFAULT NULL COMMENT 'Height in cm' AFTER `religion`,
  ADD COLUMN IF NOT EXISTS `weight` decimal(5,2) DEFAULT NULL COMMENT 'Weight in kilos' AFTER `height`,
  ADD COLUMN IF NOT EXISTS `civil_status` enum('Single','Married','Widowed','Separated','Divorced') DEFAULT 'Single' AFTER `weight`,
  ADD COLUMN IF NOT EXISTS `spouse_name` varchar(200) DEFAULT NULL COMMENT 'If married' AFTER `civil_status`,
  ADD COLUMN IF NOT EXISTS `contact_number_personal` varchar(20) DEFAULT NULL AFTER `spouse_name`,
  ADD COLUMN IF NOT EXISTS `number_of_children` int(2) DEFAULT 0 AFTER `contact_number_personal`,
  ADD COLUMN IF NOT EXISTS `mobile_number` varchar(20) DEFAULT NULL AFTER `number_of_children`,
  ADD COLUMN IF NOT EXISTS `landline_number` varchar(20) DEFAULT NULL AFTER `mobile_number`,
  ADD COLUMN IF NOT EXISTS `email` varchar(150) DEFAULT NULL AFTER `landline_number`;

-- Address Fields
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `house_no` varchar(50) DEFAULT NULL AFTER `email`,
  ADD COLUMN IF NOT EXISTS `street_block_lot` varchar(200) DEFAULT NULL AFTER `house_no`,
  ADD COLUMN IF NOT EXISTS `district_barangay_village` varchar(200) DEFAULT NULL AFTER `street_block_lot`,
  ADD COLUMN IF NOT EXISTS `municipality_city` varchar(200) DEFAULT NULL AFTER `district_barangay_village`,
  ADD COLUMN IF NOT EXISTS `province` varchar(200) DEFAULT NULL AFTER `municipality_city`,
  ADD COLUMN IF NOT EXISTS `zip_code` varchar(10) DEFAULT NULL AFTER `province`,
  ADD COLUMN IF NOT EXISTS `complete_address` text GENERATED ALWAYS AS (CONCAT_WS(', ', 
    NULLIF(`house_no`, ''),
    NULLIF(`street_block_lot`, ''),
    NULLIF(`district_barangay_village`, ''),
    NULLIF(`municipality_city`, ''),
    NULLIF(`province`, ''),
    NULLIF(`zip_code`, '')
  )) STORED AFTER `zip_code`;

-- II. MEDICAL HISTORY
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `medical_conditions` text DEFAULT NULL COMMENT 'Pre-existing conditions/disability/allergies' AFTER `complete_address`,
  ADD COLUMN IF NOT EXISTS `current_medications` text DEFAULT NULL AFTER `medical_conditions`,
  ADD COLUMN IF NOT EXISTS `blood_type` varchar(5) DEFAULT NULL AFTER `current_medications`;

-- Emergency Contact 1 (Immediate Family)
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `emergency_contact1_name` varchar(200) DEFAULT NULL AFTER `blood_type`,
  ADD COLUMN IF NOT EXISTS `emergency_contact1_relationship` varchar(100) DEFAULT NULL AFTER `emergency_contact1_name`,
  ADD COLUMN IF NOT EXISTS `emergency_contact1_landline` varchar(20) DEFAULT NULL AFTER `emergency_contact1_relationship`,
  ADD COLUMN IF NOT EXISTS `emergency_contact1_mobile` varchar(20) DEFAULT NULL AFTER `emergency_contact1_landline`;

-- Emergency Contact 2 (Other than Immediate Family)
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `emergency_contact2_name` varchar(200) DEFAULT NULL AFTER `emergency_contact1_mobile`,
  ADD COLUMN IF NOT EXISTS `emergency_contact2_relationship` varchar(100) DEFAULT NULL AFTER `emergency_contact2_name`,
  ADD COLUMN IF NOT EXISTS `emergency_contact2_landline` varchar(20) DEFAULT NULL AFTER `emergency_contact2_relationship`,
  ADD COLUMN IF NOT EXISTS `emergency_contact2_mobile` varchar(20) DEFAULT NULL AFTER `emergency_contact2_landline`;

-- III. FAMILY BACKGROUND
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `father_name` varchar(200) DEFAULT NULL AFTER `emergency_contact2_mobile`,
  ADD COLUMN IF NOT EXISTS `father_age` int(3) DEFAULT NULL AFTER `father_name`,
  ADD COLUMN IF NOT EXISTS `father_occupation` varchar(200) DEFAULT NULL AFTER `father_age`,
  ADD COLUMN IF NOT EXISTS `mother_name` varchar(200) DEFAULT NULL AFTER `father_occupation`,
  ADD COLUMN IF NOT EXISTS `mother_age` int(3) DEFAULT NULL AFTER `mother_name`,
  ADD COLUMN IF NOT EXISTS `mother_occupation` varchar(200) DEFAULT NULL AFTER `mother_age`,
  ADD COLUMN IF NOT EXISTS `number_of_siblings` int(2) DEFAULT NULL AFTER `mother_occupation`,
  ADD COLUMN IF NOT EXISTS `position_in_family` varchar(50) DEFAULT NULL COMMENT 'e.g., 1st, 2nd, Youngest, Eldest' AFTER `number_of_siblings`;

-- IV. EDUCATIONAL BACKGROUND
-- Elementary
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `elementary_school` varchar(200) DEFAULT NULL AFTER `position_in_family`,
  ADD COLUMN IF NOT EXISTS `elementary_year_graduated` varchar(10) DEFAULT NULL AFTER `elementary_school`,
  ADD COLUMN IF NOT EXISTS `elementary_honors` text DEFAULT NULL AFTER `elementary_year_graduated`;

-- High School
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `highschool_school` varchar(200) DEFAULT NULL AFTER `elementary_honors`,
  ADD COLUMN IF NOT EXISTS `highschool_year_graduated` varchar(10) DEFAULT NULL AFTER `highschool_school`,
  ADD COLUMN IF NOT EXISTS `highschool_honors` text DEFAULT NULL AFTER `highschool_year_graduated`;

-- College
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `college_school` varchar(200) DEFAULT NULL AFTER `highschool_honors`,
  ADD COLUMN IF NOT EXISTS `college_course` varchar(200) DEFAULT NULL AFTER `college_school`,
  ADD COLUMN IF NOT EXISTS `college_year_graduated` varchar(10) DEFAULT NULL AFTER `college_course`,
  ADD COLUMN IF NOT EXISTS `college_honors` text DEFAULT NULL AFTER `college_year_graduated`;

-- Vocational
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `vocational_school` varchar(200) DEFAULT NULL AFTER `college_honors`,
  ADD COLUMN IF NOT EXISTS `vocational_year_graduated` varchar(10) DEFAULT NULL AFTER `vocational_school`,
  ADD COLUMN IF NOT EXISTS `vocational_honors` text DEFAULT NULL AFTER `vocational_year_graduated`;

-- Higher Studies
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `higher_studies_school` varchar(200) DEFAULT NULL AFTER `vocational_honors`,
  ADD COLUMN IF NOT EXISTS `higher_studies_year_graduated` varchar(10) DEFAULT NULL AFTER `higher_studies_school`,
  ADD COLUMN IF NOT EXISTS `higher_studies_honors` text DEFAULT NULL AFTER `higher_studies_year_graduated`;

-- IX. TALENTS AND SKILLS
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `talents` text DEFAULT NULL COMMENT 'What would you consider as your talent(s)?' AFTER `higher_studies_honors`,
  ADD COLUMN IF NOT EXISTS `skills` text DEFAULT NULL COMMENT 'What are some skill(s) you possess?' AFTER `talents`,
  ADD COLUMN IF NOT EXISTS `languages_dialects` text DEFAULT NULL COMMENT 'Languages & dialects you can speak, read and understand fluently' AFTER `skills`;

-- V. SOCIO-CIVIC, CULTURAL & RELIGIOUS INVOLVEMENTS
-- Involvement 1
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `involvement1_organization` varchar(300) DEFAULT NULL AFTER `languages_dialects`,
  ADD COLUMN IF NOT EXISTS `involvement1_position` varchar(200) DEFAULT NULL AFTER `involvement1_organization`,
  ADD COLUMN IF NOT EXISTS `involvement1_year` varchar(20) DEFAULT NULL AFTER `involvement1_position`;

-- Involvement 2
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `involvement2_organization` varchar(300) DEFAULT NULL AFTER `involvement1_year`,
  ADD COLUMN IF NOT EXISTS `involvement2_position` varchar(200) DEFAULT NULL AFTER `involvement2_organization`,
  ADD COLUMN IF NOT EXISTS `involvement2_year` varchar(20) DEFAULT NULL AFTER `involvement2_position`;

-- VI. WORK EXPERIENCE
-- Experience 1
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `work_exp1_company` varchar(300) DEFAULT NULL AFTER `involvement2_year`,
  ADD COLUMN IF NOT EXISTS `work_exp1_position` varchar(200) DEFAULT NULL AFTER `work_exp1_company`,
  ADD COLUMN IF NOT EXISTS `work_exp1_year` varchar(50) DEFAULT NULL AFTER `work_exp1_position`;

-- Experience 2
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `work_exp2_company` varchar(300) DEFAULT NULL AFTER `work_exp1_year`,
  ADD COLUMN IF NOT EXISTS `work_exp2_position` varchar(200) DEFAULT NULL AFTER `work_exp2_company`,
  ADD COLUMN IF NOT EXISTS `work_exp2_year` varchar(50) DEFAULT NULL AFTER `work_exp2_position`;

-- VII. RED CROSS EXPERIENCE
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `rc_is_volunteer` enum('YES','NO') DEFAULT 'NO' AFTER `work_exp2_year`,
  ADD COLUMN IF NOT EXISTS `rc_month_year_started` varchar(20) DEFAULT NULL AFTER `rc_is_volunteer`,
  ADD COLUMN IF NOT EXISTS `rc_has_maab` enum('YES','NO') DEFAULT 'NO' COMMENT 'Membership with Accident Assistance Benefits' AFTER `rc_month_year_started`,
  ADD COLUMN IF NOT EXISTS `rc_maab_serial_no` varchar(100) DEFAULT NULL AFTER `rc_has_maab`,
  ADD COLUMN IF NOT EXISTS `rc_maab_validity` varchar(50) DEFAULT NULL AFTER `rc_maab_serial_no`,
  ADD COLUMN IF NOT EXISTS `rc_basic_orientation` enum('YES','NO') DEFAULT 'NO' AFTER `rc_maab_validity`,
  ADD COLUMN IF NOT EXISTS `rc_basic_orientation_year` varchar(10) DEFAULT NULL AFTER `rc_basic_orientation`,
  ADD COLUMN IF NOT EXISTS `rc_rc143_training` enum('YES','NO') DEFAULT 'NO' AFTER `rc_basic_orientation_year`,
  ADD COLUMN IF NOT EXISTS `rc_rc143_training_year` varchar(10) DEFAULT NULL AFTER `rc_rc143_training`,
  ADD COLUMN IF NOT EXISTS `rc_other_trainings` text DEFAULT NULL COMMENT 'Other Red Cross Training/Courses Acquired' AFTER `rc_rc143_training_year`,
  ADD COLUMN IF NOT EXISTS `rc_training_dates` text DEFAULT NULL COMMENT 'Exclusive Dates' AFTER `rc_other_trainings`;

-- IX. REFERENCES
-- Reference 1
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `reference1_name` varchar(200) DEFAULT NULL AFTER `rc_training_dates`,
  ADD COLUMN IF NOT EXISTS `reference1_contact` varchar(100) DEFAULT NULL AFTER `reference1_name`,
  ADD COLUMN IF NOT EXISTS `reference1_company` varchar(300) DEFAULT NULL AFTER `reference1_contact`,
  ADD COLUMN IF NOT EXISTS `reference1_position` varchar(200) DEFAULT NULL AFTER `reference1_company`;

-- Reference 2
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `reference2_name` varchar(200) DEFAULT NULL AFTER `reference1_position`,
  ADD COLUMN IF NOT EXISTS `reference2_contact` varchar(100) DEFAULT NULL AFTER `reference2_name`,
  ADD COLUMN IF NOT EXISTS `reference2_company` varchar(300) DEFAULT NULL AFTER `reference2_contact`,
  ADD COLUMN IF NOT EXISTS `reference2_position` varchar(200) DEFAULT NULL AFTER `reference2_company`;

-- VIII. VOLUNTEER WAIVER
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `waiver_signatory_name` varchar(200) DEFAULT NULL AFTER `reference2_position`,
  ADD COLUMN IF NOT EXISTS `waiver_signature` varchar(500) DEFAULT NULL COMMENT 'Can store signature image path' AFTER `waiver_signatory_name`,
  ADD COLUMN IF NOT EXISTS `waiver_date_place` varchar(200) DEFAULT NULL AFTER `waiver_signature`;

-- VIII. CHILD PROTECTION POLICY & CODE OF CONDUCT
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `policy_signatory_name` varchar(200) DEFAULT NULL AFTER `waiver_date_place`,
  ADD COLUMN IF NOT EXISTS `policy_signature` varchar(500) DEFAULT NULL COMMENT 'Can store signature image path' AFTER `policy_signatory_name`,
  ADD COLUMN IF NOT EXISTS `policy_date_place` varchar(200) DEFAULT NULL AFTER `policy_signature`;

-- CERTIFICATION & CONFIDENTIALITY
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `cert_signatory_name` varchar(200) DEFAULT NULL AFTER `policy_date_place`,
  ADD COLUMN IF NOT EXISTS `cert_signature` varchar(500) DEFAULT NULL COMMENT 'Can store signature image path' AFTER `cert_signatory_name`,
  ADD COLUMN IF NOT EXISTS `cert_date` date DEFAULT NULL AFTER `cert_signature`;

-- TO BE FILLED UP BY VSO STAFF (REFERENCE CHECK)
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `ref_check_person_contacted` varchar(200) DEFAULT NULL AFTER `cert_date`,
  ADD COLUMN IF NOT EXISTS `ref_check_company` varchar(300) DEFAULT NULL AFTER `ref_check_person_contacted`,
  ADD COLUMN IF NOT EXISTS `ref_check_contact_number` varchar(50) DEFAULT NULL AFTER `ref_check_company`,
  ADD COLUMN IF NOT EXISTS `ref_check_date` date DEFAULT NULL AFTER `ref_check_contact_number`,
  ADD COLUMN IF NOT EXISTS `ref_check_comments` text DEFAULT NULL AFTER `ref_check_date`;

-- OVER-ALL EVALUATION
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `eval_highly_recommended` tinyint(1) DEFAULT 0 AFTER `ref_check_comments`,
  ADD COLUMN IF NOT EXISTS `eval_recommended` tinyint(1) DEFAULT 0 AFTER `eval_highly_recommended`,
  ADD COLUMN IF NOT EXISTS `eval_not_recommended` tinyint(1) DEFAULT 0 AFTER `eval_recommended`;

-- FINAL DECISION
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `final_accepted` tinyint(1) DEFAULT 0 AFTER `eval_not_recommended`,
  ADD COLUMN IF NOT EXISTS `final_rejected` tinyint(1) DEFAULT 0 AFTER `final_accepted`;

-- OFFICIAL SIGNATURES
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `vs_staff_signature` varchar(500) DEFAULT NULL AFTER `final_rejected`,
  ADD COLUMN IF NOT EXISTS `vs_staff_name` varchar(200) DEFAULT NULL AFTER `vs_staff_signature`,
  ADD COLUMN IF NOT EXISTS `vs_staff_date_place` varchar(200) DEFAULT NULL AFTER `vs_staff_name`,
  ADD COLUMN IF NOT EXISTS `head_office_signature` varchar(500) DEFAULT NULL AFTER `vs_staff_date_place`,
  ADD COLUMN IF NOT EXISTS `head_office_name` varchar(200) DEFAULT NULL AFTER `head_office_signature`;

-- Modify existing columns if they exist
ALTER TABLE `users`
  MODIFY COLUMN `documents` text DEFAULT NULL COMMENT 'JSON array of uploaded document paths',
  MODIFY COLUMN `account_status` enum('pending','accepted','rejected') DEFAULT 'pending',
  MODIFY COLUMN `status` enum('not deployed','deployed','inactive') DEFAULT 'not deployed',
  MODIFY COLUMN `reason` text DEFAULT NULL COMMENT 'Reason for rejection if applicable';

-- ===============================================
-- STEP 3: Add indexes for performance
-- ===============================================

ALTER TABLE `users`
  ADD INDEX IF NOT EXISTS `idx_account_status` (`account_status`),
  ADD INDEX IF NOT EXISTS `idx_volunteer_status` (`status`),
  ADD INDEX IF NOT EXISTS `idx_user_type` (`user_type`),
  ADD INDEX IF NOT EXISTS `idx_email` (`email`);

-- ===============================================
-- VERIFICATION QUERY
-- ===============================================
-- Run this to verify all columns were added:
-- DESCRIBE users;

-- Count total columns (should be around 119):
-- SELECT COUNT(*) as total_columns FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = 'volunteer-web' AND TABLE_NAME = 'users';

-- ===============================================
-- END OF SQL UPDATE
-- ===============================================
-- Your existing data is preserved!
-- New registrations will use the new comprehensive form.
-- ===============================================
