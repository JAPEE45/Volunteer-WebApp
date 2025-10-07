<?php
include_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get all form fields
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $fullName = $_POST['fullName'];
    $birthPlace = $_POST['birthPlace'];
    $sex = $_POST['sex'];
    $dob = $_POST['dob'];
    $religion = $_POST['religion'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $civilStatus = $_POST['civilStatus'];
    $spouse = $_POST['spouse'];
    $children = $_POST['children'];
    $mobile = $_POST['mobile'];
    $landline = $_POST['landline'];
    $address = $_POST['address'];
    $health = $_POST['health'];
    $medication = $_POST['medication'];
    $bloodType = $_POST['bloodType'];
    $elementary = $_POST['elementary'];
    $elemYearGrad = $_POST['elemYearGrad'];
    $highSchool = $_POST['highSchool'];
    $hsYearGrad = $_POST['hsYearGrad'];
    $college = $_POST['college'];
    $collegeYearGrad = $_POST['collegeYearGrad'];
    $postGrad = $_POST['postGrad'];
    $postGradYear = $_POST['postGradYear'];
    $skills = $_POST['skills'];
    $languages = $_POST['languages'];
    $involvements = $_POST['involvements'];
    $company = $_POST['company'];
    $position = $_POST['position'];
    $workDates = $_POST['workDates'];
    $redCrossMember = $_POST['redCrossMember'];
    $membershipType = $_POST['membershipType'];
    $trainings = $_POST['trainings'];
    $refName = $_POST['refName'];
    $refContact = $_POST['refContact'];
    $age = $_POST['age'];

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

    // Example insert query
   $stmt = $conn->prepare("INSERT INTO users (
    firstName, middleName, lastName, fullName, birthPlace, sex, dob, religion,
    height, weight, civilStatus, spouse, children, mobile, landline, address,
    health, medication, bloodType, elementary, elemYearGrad, highSchool, hsYearGrad,
    college, collegeYearGrad, postGrad, postGradYear, skills, languages,
    involvements, company, position, workDates, redCrossMember, membershipType,
    trainings, refName, refContact, age, documents
) VALUES (" . str_repeat('?,', 39) . "?)");

$stmt->bind_param(
    str_repeat('s', 40),
    $firstName, $middleName, $lastName, $fullName, $birthPlace, $sex, $dob,
    $religion, $height, $weight, $civilStatus, $spouse, $children, $mobile,
    $landline, $address, $health, $medication, $bloodType, $elementary,
    $elemYearGrad, $highSchool, $hsYearGrad, $college, $collegeYearGrad,
    $postGrad, $postGradYear, $skills, $languages, $involvements, $company,
    $position, $workDates, $redCrossMember, $membershipType, $trainings,
    $refName, $refContact, $age, $documents
);


    if ($stmt->execute()) {
        echo json_encode(["success" => true, "uploaded" => $uploadedFiles]);
    } else {
        echo json_encode(["error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
