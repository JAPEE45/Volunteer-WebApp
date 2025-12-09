<?php
// Comprehensive Volunteer Registration Handler
// Philippine Red Cross - Volunteer Management System

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

include_once 'db.php';
include_once 'sendSms.php';

session_start();
header('Content-Type: application/json');

// Start output buffering
ob_start();

include_once 'db.php';

// Custom error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (ob_get_level()) ob_end_clean();
    echo json_encode([
        "success" => false,
        "message" => "Server error: " . $errstr
    ]);
    exit();
});

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit();
}

try {
    // I. PERSONAL INFORMATION
    $family_name = $_POST['family_name'] ?? null;
    $given_name = $_POST['given_name'] ?? null;
    $middle_name = $_POST['middle_name'] ?? null;
    $nick_name = $_POST['nick_name'] ?? null;
    $sex = $_POST['sex'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $age = $_POST['age'] ?? null;
    $birth_place = $_POST['birth_place'] ?? null;
    $religion = $_POST['religion'] ?? null;
    $height = $_POST['height'] ?? null;
    $weight = $_POST['weight'] ?? null;
    $civil_status = $_POST['civil_status'] ?? null;
    $spouse_name = $_POST['spouse_name'] ?? null;
    $contact_number_personal = $_POST['contact_number_personal'] ?? null;
    $number_of_children = $_POST['number_of_children'] ?? 0;
    $mobile_number = $_POST['mobile_number'] ?? null;
    $landline_number = $_POST['landline_number'] ?? null;
    $email = $_POST['email'] ?? null;
    
    // Address fields
    $house_no = $_POST['house_no'] ?? null;
    $street_block_lot = $_POST['street_block_lot'] ?? null;
    $district_barangay_village = $_POST['district_barangay_village'] ?? null;
    $municipality_city = $_POST['municipality_city'] ?? null;
    $province = $_POST['province'] ?? null;
    $zip_code = $_POST['zip_code'] ?? null;
    
    // II. MEDICAL HISTORY
    $medical_conditions = $_POST['medical_conditions'] ?? null;
    $current_medications = $_POST['current_medications'] ?? null;
    $blood_type = $_POST['blood_type'] ?? null;
    
    // Emergency Contact 1
    $emergency_contact1_name = $_POST['emergency_contact1_name'] ?? null;
    $emergency_contact1_relationship = $_POST['emergency_contact1_relationship'] ?? null;
    $emergency_contact1_landline = $_POST['emergency_contact1_landline'] ?? null;
    $emergency_contact1_mobile = $_POST['emergency_contact1_mobile'] ?? null;
    
    // Emergency Contact 2
    $emergency_contact2_name = $_POST['emergency_contact2_name'] ?? null;
    $emergency_contact2_relationship = $_POST['emergency_contact2_relationship'] ?? null;
    $emergency_contact2_landline = $_POST['emergency_contact2_landline'] ?? null;
    $emergency_contact2_mobile = $_POST['emergency_contact2_mobile'] ?? null;
    
    // III. FAMILY BACKGROUND
    $father_name = $_POST['father_name'] ?? null;
    $father_age = $_POST['father_age'] ?? null;
    $father_occupation = $_POST['father_occupation'] ?? null;
    $mother_name = $_POST['mother_name'] ?? null;
    $mother_age = $_POST['mother_age'] ?? null;
    $mother_occupation = $_POST['mother_occupation'] ?? null;
    $number_of_siblings = $_POST['number_of_siblings'] ?? null;
    $position_in_family = $_POST['position_in_family'] ?? null;
    
    // IV. EDUCATIONAL BACKGROUND
    $elementary_school = $_POST['elementary_school'] ?? null;
    $elementary_year_graduated = $_POST['elementary_year_graduated'] ?? null;
    $elementary_honors = $_POST['elementary_honors'] ?? null;
    $highschool_school = $_POST['highschool_school'] ?? null;
    $highschool_year_graduated = $_POST['highschool_year_graduated'] ?? null;
    $highschool_honors = $_POST['highschool_honors'] ?? null;
    $college_school = $_POST['college_school'] ?? null;
    $college_course = $_POST['college_course'] ?? null;
    $college_year_graduated = $_POST['college_year_graduated'] ?? null;
    $college_honors = $_POST['college_honors'] ?? null;
    $vocational_school = $_POST['vocational_school'] ?? null;
    $vocational_year_graduated = $_POST['vocational_year_graduated'] ?? null;
    $vocational_honors = $_POST['vocational_honors'] ?? null;
    $higher_studies_school = $_POST['higher_studies_school'] ?? null;
    $higher_studies_year_graduated = $_POST['higher_studies_year_graduated'] ?? null;
    $higher_studies_honors = $_POST['higher_studies_honors'] ?? null;
    
    // TALENTS AND SKILLS
    $talents = $_POST['talents'] ?? null;
    $skills = $_POST['skills'] ?? null;
    $languages_dialects = $_POST['languages_dialects'] ?? null;
    
    // V. SOCIO-CIVIC INVOLVEMENTS
    $involvement1_organization = $_POST['involvement1_organization'] ?? null;
    $involvement1_position = $_POST['involvement1_position'] ?? null;
    $involvement1_year = $_POST['involvement1_year'] ?? null;
    $involvement2_organization = $_POST['involvement2_organization'] ?? null;
    $involvement2_position = $_POST['involvement2_position'] ?? null;
    $involvement2_year = $_POST['involvement2_year'] ?? null;
    
    // VI. WORK EXPERIENCE
    $work_exp1_company = $_POST['work_exp1_company'] ?? null;
    $work_exp1_position = $_POST['work_exp1_position'] ?? null;
    $work_exp1_year = $_POST['work_exp1_year'] ?? null;
    $work_exp2_company = $_POST['work_exp2_company'] ?? null;
    $work_exp2_position = $_POST['work_exp2_position'] ?? null;
    $work_exp2_year = $_POST['work_exp2_year'] ?? null;
    
    // VII. RED CROSS EXPERIENCE
    $rc_is_volunteer = $_POST['rc_is_volunteer'] ?? 'NO';
    $rc_month_year_started = $_POST['rc_month_year_started'] ?? null;
    $rc_has_maab = $_POST['rc_has_maab'] ?? 'NO';
    $rc_maab_serial_no = $_POST['rc_maab_serial_no'] ?? null;
    $rc_maab_validity = $_POST['rc_maab_validity'] ?? null;
    $rc_basic_orientation = $_POST['rc_basic_orientation'] ?? 'NO';
    $rc_basic_orientation_year = $_POST['rc_basic_orientation_year'] ?? null;
    $rc_rc143_training = $_POST['rc_rc143_training'] ?? 'NO';
    $rc_rc143_training_year = $_POST['rc_rc143_training_year'] ?? null;
    $rc_other_trainings = $_POST['rc_other_trainings'] ?? null;
    $rc_training_dates = $_POST['rc_training_dates'] ?? null;
    
    // IX. REFERENCES
    $reference1_name = $_POST['reference1_name'] ?? null;
    $reference1_contact = $_POST['reference1_contact'] ?? null;
    $reference1_company = $_POST['reference1_company'] ?? null;
    $reference1_position = $_POST['reference1_position'] ?? null;
    $reference2_name = $_POST['reference2_name'] ?? null;
    $reference2_contact = $_POST['reference2_contact'] ?? null;
    $reference2_company = $_POST['reference2_company'] ?? null;
    $reference2_position = $_POST['reference2_position'] ?? null;
    
    // WAIVERS & CERTIFICATION
    $waiver_signatory_name = $_POST['waiver_signatory_name'] ?? null;
    $waiver_date_place = $_POST['waiver_date_place'] ?? null;
    $policy_signatory_name = $_POST['policy_signatory_name'] ?? null;
    $policy_date_place = $_POST['policy_date_place'] ?? null;
    $cert_signatory_name = $_POST['cert_signatory_name'] ?? null;
    $cert_date = $_POST['cert_date'] ?? null;
    
    // Handle file uploads
    $uploadedFiles = [];
    $uploadDir = "../uploads/";
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    if (isset($_FILES['documents']) && !empty($_FILES['documents']['name'][0])) {
        $fileCount = count($_FILES['documents']['name']);
        
        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['documents']['error'][$i] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES['documents']['name'][$i]);
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExt;
                $targetPath = $uploadDir . $uniqueFileName;
                
                if (move_uploaded_file($_FILES['documents']['tmp_name'][$i], $targetPath)) {
                    $uploadedFiles[] = $uniqueFileName;
                }
            }
        }
    }
    
    $documents = json_encode($uploadedFiles);
    
    // Insert into database
    $stmt = $conn->prepare("
        INSERT INTO users (
            family_name, given_name, middle_name, nick_name, sex, dob, age, birth_place, religion,
            height, weight, civil_status, spouse_name, contact_number_personal, number_of_children,
            mobile_number, landline_number, email,
            house_no, street_block_lot, district_barangay_village, municipality_city, province, zip_code,
            medical_conditions, current_medications, blood_type,
            emergency_contact1_name, emergency_contact1_relationship, emergency_contact1_landline, emergency_contact1_mobile,
            emergency_contact2_name, emergency_contact2_relationship, emergency_contact2_landline, emergency_contact2_mobile,
            father_name, father_age, father_occupation, mother_name, mother_age, mother_occupation,
            number_of_siblings, position_in_family,
            elementary_school, elementary_year_graduated, elementary_honors,
            highschool_school, highschool_year_graduated, highschool_honors,
            college_school, college_course, college_year_graduated, college_honors,
            vocational_school, vocational_year_graduated, vocational_honors,
            higher_studies_school, higher_studies_year_graduated, higher_studies_honors,
            talents, skills, languages_dialects,
            involvement1_organization, involvement1_position, involvement1_year,
            involvement2_organization, involvement2_position, involvement2_year,
            work_exp1_company, work_exp1_position, work_exp1_year,
            work_exp2_company, work_exp2_position, work_exp2_year,
            rc_is_volunteer, rc_month_year_started, rc_has_maab, rc_maab_serial_no, rc_maab_validity,
            rc_basic_orientation, rc_basic_orientation_year, rc_rc143_training, rc_rc143_training_year,
            rc_other_trainings, rc_training_dates,
            reference1_name, reference1_contact, reference1_company, reference1_position,
            reference2_name, reference2_contact, reference2_company, reference2_position,
            waiver_signatory_name, waiver_date_place, policy_signatory_name, policy_date_place,
            cert_signatory_name, cert_date,
            documents, account_status, status
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?,
            ?, 'pending', 'not deployed'
        )
    ");
    
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }
    
    $stmt->bind_param(
        "ssssssissdsssisssssssssssssssssssssiissiiississssssssssssssssssssssssssssssssssssssssss",
        $family_name, $given_name, $middle_name, $nick_name, $sex, $dob, $age, $birth_place, $religion,
        $height, $weight, $civil_status, $spouse_name, $contact_number_personal, $number_of_children,
        $mobile_number, $landline_number, $email,
        $house_no, $street_block_lot, $district_barangay_village, $municipality_city, $province, $zip_code,
        $medical_conditions, $current_medications, $blood_type,
        $emergency_contact1_name, $emergency_contact1_relationship, $emergency_contact1_landline, $emergency_contact1_mobile,
        $emergency_contact2_name, $emergency_contact2_relationship, $emergency_contact2_landline, $emergency_contact2_mobile,
        $father_name, $father_age, $father_occupation, $mother_name, $mother_age, $mother_occupation,
        $number_of_siblings, $position_in_family,
        $elementary_school, $elementary_year_graduated, $elementary_honors,
        $highschool_school, $highschool_year_graduated, $highschool_honors,
        $college_school, $college_course, $college_year_graduated, $college_honors,
        $vocational_school, $vocational_year_graduated, $vocational_honors,
        $higher_studies_school, $higher_studies_year_graduated, $higher_studies_honors,
        $talents, $skills, $languages_dialects,
        $involvement1_organization, $involvement1_position, $involvement1_year,
        $involvement2_organization, $involvement2_position, $involvement2_year,
        $work_exp1_company, $work_exp1_position, $work_exp1_year,
        $work_exp2_company, $work_exp2_position, $work_exp2_year,
        $rc_is_volunteer, $rc_month_year_started, $rc_has_maab, $rc_maab_serial_no, $rc_maab_validity,
        $rc_basic_orientation, $rc_basic_orientation_year, $rc_rc143_training, $rc_rc143_training_year,
        $rc_other_trainings, $rc_training_dates,
        $reference1_name, $reference1_contact, $reference1_company, $reference1_position,
        $reference2_name, $reference2_contact, $reference2_company, $reference2_position,
        $waiver_signatory_name, $waiver_date_place, $policy_signatory_name, $policy_date_place,
        $cert_signatory_name, $cert_date,
        $documents
    );
    
    if ($stmt->execute()) {
        $userId = $stmt->insert_id;
        
        // Create account with auto-generated credentials
        $username = 'user_' . $userId;
        $password = substr(md5(uniqid(rand(), true)), 0, 8);
        
        $accountStmt = $conn->prepare("INSERT INTO account (username, password, user_id, user_type) VALUES (?, ?, ?, 'volunteer')");
        $accountStmt->bind_param("ssi", $username, $password, $userId);
        $accountStmt->execute();
        $accountStmt->close();
        
        // Send SMS with account credentials
        $smsStatus = false;
        $smsMessage = '';
        if ($mobile_number) {
            $fullName = trim("$given_name $middle_name $family_name");
            $message = "Hi $fullName! Your Red Cross volunteer registration is complete.\n\nYour Login Credentials:\nUsername: $username\nPassword: $password\n\nLogin at: http://localhost/Volunteer-WebApp/login.php\n\nThank you for volunteering! - Philippine Red Cross";
            
            $smsResult = sendMessage($mobile_number, $message);
            $smsStatus = !str_contains($smsResult, 'Error');
            $smsMessage = $smsResult;
        }
        
        ob_end_clean();
        echo json_encode([
            "success" => true,
            "message" => "Registration submitted successfully!",
            "userId" => $userId,
            "username" => $username,
            "uploaded" => $uploadedFiles,
            "sms_sent" => $smsStatus,
            "sms_info" => $mobile_number ? "SMS sent to $mobile_number" : "No mobile number provided"
        ]);
    } else {
        throw new Exception($stmt->error);
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    ob_end_clean();
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
