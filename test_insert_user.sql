-- ===============================================
-- TEST USER REGISTRATION
-- Sample data to test new database structure
-- Date: December 9, 2025
-- ===============================================

-- Insert a comprehensive test user with all required fields
INSERT INTO users (
    -- Personal Information
    family_name,
    given_name,
    middle_name,
    nick_name,
    sex,
    dob,
    age,
    birth_place,
    religion,
    height,
    weight,
    civil_status,
    spouse_name,
    number_of_children,
    
    -- Contact Information
    contact_number_personal,
    mobile_number,
    landline_number,
    email,
    
    -- Address
    house_no,
    street_block_lot,
    district_barangay_village,
    municipality_city,
    province,
    zip_code,
    
    -- Medical Information
    medical_conditions,
    current_medications,
    blood_type,
    
    -- Emergency Contacts
    emergency_contact1_name,
    emergency_contact1_relationship,
    emergency_contact1_mobile,
    emergency_contact2_name,
    emergency_contact2_relationship,
    emergency_contact2_mobile,
    
    -- Family Background
    father_name,
    father_age,
    father_occupation,
    mother_name,
    mother_age,
    mother_occupation,
    number_of_siblings,
    position_in_family,
    
    -- Educational Background
    elementary_school,
    elementary_year_graduated,
    elementary_honors,
    highschool_school,
    highschool_year_graduated,
    highschool_honors,
    college_school,
    college_course,
    college_year_graduated,
    college_honors,
    
    -- Skills & Talents
    talents,
    skills,
    languages_dialects,
    
    -- Involvements
    involvement1_organization,
    involvement1_position,
    involvement1_year,
    
    -- Work Experience
    work_exp1_company,
    work_exp1_position,
    work_exp1_year,
    
    -- Red Cross Experience
    rc_is_volunteer,
    rc_month_year_started,
    rc_has_maab,
    rc_basic_orientation,
    rc_basic_orientation_year,
    rc_rc143_training,
    rc_rc143_training_year,
    rc_other_trainings,
    
    -- References
    reference1_name,
    reference1_contact,
    reference1_company,
    reference1_position,
    reference2_name,
    reference2_contact,
    reference2_company,
    reference2_position,
    
    -- Waivers & Certifications
    waiver_signatory_name,
    waiver_date_place,
    policy_signatory_name,
    policy_date_place,
    cert_signatory_name,
    cert_date,
    
    -- Account Details
    user_type,
    account_status,
    status
) VALUES (
    -- Personal Information
    'Santos',                           -- family_name
    'Maria',                            -- given_name
    'Cruz',                             -- middle_name
    'Ria',                              -- nick_name
    'Female',                           -- sex
    '1995-05-15',                       -- dob
    29,                                 -- age
    'Manila City',                      -- birth_place
    'Roman Catholic',                   -- religion
    165,                                -- height (cm)
    58.5,                               -- weight (kg)
    'Single',                           -- civil_status
    NULL,                               -- spouse_name (single)
    0,                                  -- number_of_children
    
    -- Contact Information
    '09171234567',                      -- contact_number_personal
    '09171234567',                      -- mobile_number
    '(02) 8123-4567',                   -- landline_number
    'maria.santos@email.com',           -- email
    
    -- Address
    '123',                              -- house_no
    'Rizal Street',                     -- street_block_lot
    'Barangay San Antonio',             -- district_barangay_village
    'Quezon City',                      -- municipality_city
    'Metro Manila',                     -- province
    '1105',                             -- zip_code
    
    -- Medical Information
    'Allergic to penicillin',           -- medical_conditions
    'None',                             -- current_medications
    'O+',                               -- blood_type
    
    -- Emergency Contacts
    'Juan Santos',                      -- emergency_contact1_name
    'Father',                           -- emergency_contact1_relationship
    '09189876543',                      -- emergency_contact1_mobile
    'Ana Reyes',                        -- emergency_contact2_name
    'Friend',                           -- emergency_contact2_relationship
    '09176543210',                      -- emergency_contact2_mobile
    
    -- Family Background
    'Juan Santos',                      -- father_name
    58,                                 -- father_age
    'Engineer',                         -- father_occupation
    'Rosa Santos',                      -- mother_name
    55,                                 -- mother_age
    'Teacher',                          -- mother_occupation
    2,                                  -- number_of_siblings
    '2nd',                              -- position_in_family
    
    -- Educational Background
    'Manila Elementary School',         -- elementary_school
    '2007',                             -- elementary_year_graduated
    'With Honors',                      -- elementary_honors
    'Quezon City High School',          -- highschool_school
    '2011',                             -- highschool_year_graduated
    'Honor Roll',                       -- highschool_honors
    'University of the Philippines',    -- college_school
    'BS Nursing',                       -- college_course
    '2015',                             -- college_year_graduated
    'Cum Laude',                        -- college_honors
    
    -- Skills & Talents
    'Singing, Public Speaking, Arts',   -- talents
    'First Aid, CPR Certified, Basic Life Support, Disaster Response',  -- skills
    'English, Filipino, Tagalog, Basic Spanish',  -- languages_dialects
    
    -- Involvements
    'Barangay Youth Council',           -- involvement1_organization
    'Secretary',                        -- involvement1_position
    '2018-2020',                        -- involvement1_year
    
    -- Work Experience
    'St. Luke\'s Medical Center',      -- work_exp1_company
    'Staff Nurse',                      -- work_exp1_position
    '2015-Present',                     -- work_exp1_year
    
    -- Red Cross Experience
    'YES',                              -- rc_is_volunteer
    'January 2020',                     -- rc_month_year_started
    'YES',                              -- rc_has_maab
    'YES',                              -- rc_basic_orientation
    '2020',                             -- rc_basic_orientation_year
    'YES',                              -- rc_rc143_training
    '2020',                             -- rc_rc143_training_year
    'Disaster Response Training, Blood Donation Seminar, Community Health',  -- rc_other_trainings
    
    -- References
    'Dr. Michael Tan',                  -- reference1_name
    '09181112222',                      -- reference1_contact
    'St. Luke\'s Medical Center',      -- reference1_company
    'Chief Nurse',                      -- reference1_position
    'Prof. Linda Garcia',               -- reference2_name
    '09183334444',                      -- reference2_contact
    'University of the Philippines',    -- reference2_company
    'Nursing Professor',                -- reference2_position
    
    -- Waivers & Certifications
    'Maria Cruz Santos',                -- waiver_signatory_name
    'Quezon City, December 9, 2025',    -- waiver_date_place
    'Maria Cruz Santos',                -- policy_signatory_name
    'Quezon City, December 9, 2025',    -- policy_date_place
    'Maria Cruz Santos',                -- cert_signatory_name
    '2025-12-09',                       -- cert_date
    
    -- Account Details
    'volunteer',                        -- user_type
    'accepted',                         -- account_status
    'not deployed'                      -- status
);

-- Verify the insertion
SELECT 
    id,
    fullName,
    email,
    mobile_number,
    complete_address,
    blood_type,
    account_status,
    status
FROM users 
WHERE email = 'maria.santos@email.com';

-- ===============================================
-- Result: Should show the newly inserted user
-- ===============================================
