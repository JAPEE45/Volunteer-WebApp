<?php
include_once 'db.php';
include_once 'sendSms.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get all form fields - mapped to new column names
    $given_name = $_POST['firstName'] ?? $_POST['given_name'] ?? null;
    $middle_name = $_POST['middleName'] ?? $_POST['middle_name'] ?? null;
    $family_name = $_POST['lastName'] ?? $_POST['family_name'] ?? null;
    $birth_place = $_POST['birthPlace'] ?? $_POST['birth_place'] ?? null;
    $sex = $_POST['sex'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $religion = $_POST['religion'] ?? null;
    $height = $_POST['height'] ?? null;
    $weight = $_POST['weight'] ?? null;
    $civil_status = $_POST['civilStatus'] ?? $_POST['civil_status'] ?? null;
    $spouse_name = $_POST['spouse'] ?? $_POST['spouse_name'] ?? null;
    $number_of_children = $_POST['children'] ?? $_POST['number_of_children'] ?? 0;
    $mobile_number = $_POST['mobile'] ?? $_POST['mobile_number'] ?? null;
    $landline_number = $_POST['landline'] ?? $_POST['landline_number'] ?? null;
    $complete_address = $_POST['address'] ?? null; // Store in one of the address fields
    $medical_conditions = $_POST['health'] ?? $_POST['medical_conditions'] ?? null;
    $current_medications = $_POST['medication'] ?? $_POST['current_medications'] ?? null;
    $blood_type = $_POST['bloodType'] ?? $_POST['blood_type'] ?? null;
    $elementary_school = $_POST['elementary'] ?? $_POST['elementary_school'] ?? null;
    $elementary_year_graduated = $_POST['elemYearGrad'] ?? $_POST['elementary_year_graduated'] ?? null;
    $highschool_school = $_POST['highSchool'] ?? $_POST['highschool_school'] ?? null;
    $highschool_year_graduated = $_POST['hsYearGrad'] ?? $_POST['highschool_year_graduated'] ?? null;
    $college_school = $_POST['college'] ?? $_POST['college_school'] ?? null;
    $college_year_graduated = $_POST['collegeYearGrad'] ?? $_POST['college_year_graduated'] ?? null;
    $higher_studies_school = $_POST['postGrad'] ?? $_POST['higher_studies_school'] ?? null;
    $higher_studies_year_graduated = $_POST['postGradYear'] ?? $_POST['higher_studies_year_graduated'] ?? null;
    $skills = $_POST['skills'] ?? null;
    $languages_dialects = $_POST['languages'] ?? $_POST['languages_dialects'] ?? null;
    $involvement1_organization = $_POST['involvements'] ?? $_POST['involvement1_organization'] ?? null;
    $work_exp1_company = $_POST['company'] ?? $_POST['work_exp1_company'] ?? null;
    $work_exp1_position = $_POST['position'] ?? $_POST['work_exp1_position'] ?? null;
    $work_exp1_year = $_POST['workDates'] ?? $_POST['work_exp1_year'] ?? null;
    $rc_is_volunteer = ($_POST['redCrossMember'] ?? 'NO') === 'Yes' ? 'YES' : 'NO';
    $rc_has_maab = ($_POST['membershipType'] ?? '') ? 'YES' : 'NO';
    $rc_other_trainings = $_POST['trainings'] ?? $_POST['rc_other_trainings'] ?? null;
    $reference1_name = $_POST['refName'] ?? $_POST['reference1_name'] ?? null;
    $reference1_contact = $_POST['refContact'] ?? $_POST['reference1_contact'] ?? null;
    $age = $_POST['age'] ?? null;

    // File upload handling
    $uploadDir = "../uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadedFiles = [];
    if (!empty($_FILES['documents']['name'][0])) {
        foreach ($_FILES['documents']['tmp_name'] as $index => $tmpName) {
            $fileName = basename($_FILES['documents']['name'][$index]);
            $targetPath = $uploadDir . $fileName;
            if (move_uploaded_file($tmpName, $targetPath)) {
                $uploadedFiles[] = $fileName;
            }
        }
    }

    // Convert uploaded file names to JSON or comma-separated string
    $documents = json_encode($uploadedFiles);

    // Insert using new column names
   $stmt = $conn->prepare("INSERT INTO users (
    given_name, middle_name, family_name, birth_place, sex, dob, religion,
    height, weight, civil_status, spouse_name, number_of_children, mobile_number, landline_number,
    district_barangay_village, medical_conditions, current_medications, blood_type,
    elementary_school, elementary_year_graduated, highschool_school, highschool_year_graduated,
    college_school, college_year_graduated, higher_studies_school, higher_studies_year_graduated,
    skills, languages_dialects, involvement1_organization, work_exp1_company, work_exp1_position,
    work_exp1_year, rc_is_volunteer, rc_has_maab, rc_other_trainings,
    reference1_name, reference1_contact, age, documents
) VALUES (" . str_repeat('?,', 38) . "?)");

$stmt->bind_param(
    str_repeat('s', 39),
    $given_name, $middle_name, $family_name, $birth_place, $sex, $dob,
    $religion, $height, $weight, $civil_status, $spouse_name, $number_of_children,
    $mobile_number, $landline_number, $complete_address, $medical_conditions,
    $current_medications, $blood_type, $elementary_school, $elementary_year_graduated,
    $highschool_school, $highschool_year_graduated, $college_school, $college_year_graduated,
    $higher_studies_school, $higher_studies_year_graduated, $skills, $languages_dialects,
    $involvement1_organization, $work_exp1_company, $work_exp1_position, $work_exp1_year,
    $rc_is_volunteer, $rc_has_maab, $rc_other_trainings, $reference1_name,
    $reference1_contact, $age, $documents
);


    if ($stmt->execute()) {
        $userId = $stmt->insert_id;
        
        // Create account with auto-generated credentials
        $username = 'user_' . $userId;
        $password = substr(md5(uniqid(rand(), true)), 0, 8); // 8 character random password
        
        $accountStmt = $conn->prepare("INSERT INTO account (username, password, user_id, user_type) VALUES (?, ?, ?, 'volunteer')");
        $accountStmt->bind_param("ssi", $username, $password, $userId);
        $accountStmt->execute();
        $accountStmt->close();
        
        // Send SMS with account credentials
        if ($mobile_number) {
            $fullName = trim("$given_name $middle_name $family_name");
            $message = "Hi $fullName! Your Red Cross volunteer registration is complete.\n\nYour Login Credentials:\nUsername: $username\nPassword: $password\n\nLogin at: http://localhost/Volunteer-WebApp/login.php\n\nThank you for volunteering! - Philippine Red Cross";
            
            $smsResult = sendMessage($mobile_number, $message);
            
            echo json_encode([
                "success" => true, 
                "uploaded" => $uploadedFiles,
                "userId" => $userId,
                "username" => $username,
                "sms_sent" => !str_contains($smsResult, 'Error'),
                "sms_response" => $smsResult
            ]);
        } else {
            echo json_encode([
                "success" => true, 
                "uploaded" => $uploadedFiles,
                "userId" => $userId,
                "username" => $username,
                "sms_sent" => false,
                "message" => "Registration successful but no mobile number provided for SMS"
            ]);
        }
    } else {
        echo json_encode(["error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
