<?php
session_start();
include_once 'db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access"]);
    exit();
}

$userId = $_SESSION['user_id'];

// Validate POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit();
}

// Get form data
$deploymentId = isset($_POST['deployment_id']) ? intval($_POST['deployment_id']) : 0;
$eventId = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
$dateOfActivity = isset($_POST['date_of_activity']) ? $_POST['date_of_activity'] : '';
$hoursWorked = isset($_POST['hours_worked']) ? floatval($_POST['hours_worked']) : 0;
$activitiesPerformed = isset($_POST['activities_performed']) ? trim($_POST['activities_performed']) : '';
$challengesFaced = isset($_POST['challenges_faced']) ? trim($_POST['challenges_faced']) : null;
$outcomesAchieved = isset($_POST['outcomes_achieved']) ? trim($_POST['outcomes_achieved']) : null;
$recommendations = isset($_POST['recommendations']) ? trim($_POST['recommendations']) : null;

// Validate required fields
if ($deploymentId <= 0 || $eventId <= 0 || empty($dateOfActivity) || $hoursWorked <= 0 || empty($activitiesPerformed)) {
    echo json_encode(["success" => false, "message" => "Please fill in all required fields"]);
    exit();
}

// Validate hours worked
if ($hoursWorked < 0.5 || $hoursWorked > 24) {
    echo json_encode(["success" => false, "message" => "Hours worked must be between 0.5 and 24"]);
    exit();
}

// Verify deployment belongs to user
$verifyStmt = $conn->prepare("SELECT id FROM deployment WHERE id = ? AND user_id = ?");
$verifyStmt->bind_param("ii", $deploymentId, $userId);
$verifyStmt->execute();
$verifyResult = $verifyStmt->get_result();

if ($verifyResult->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Invalid deployment selection"]);
    $verifyStmt->close();
    exit();
}
$verifyStmt->close();

// Check if report already exists for this deployment
$checkStmt = $conn->prepare("SELECT id FROM activity_reports WHERE deployment_id = ? AND user_id = ?");
$checkStmt->bind_param("ii", $deploymentId, $userId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "You have already submitted a report for this deployment"]);
    $checkStmt->close();
    exit();
}
$checkStmt->close();

// Handle file uploads
$uploadedFiles = [];
$uploadDir = '../uploads/activity_reports/';

// Create directory if it doesn't exist
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_FILES['documents']) && !empty($_FILES['documents']['name'][0])) {
    $fileCount = count($_FILES['documents']['name']);
    
    if ($fileCount > 5) {
        echo json_encode(["success" => false, "message" => "Maximum 5 files allowed"]);
        exit();
    }
    
    for ($i = 0; $i < $fileCount; $i++) {
        if ($_FILES['documents']['error'][$i] === UPLOAD_ERR_OK) {
            $fileName = $_FILES['documents']['name'][$i];
            $fileTmp = $_FILES['documents']['tmp_name'][$i];
            $fileSize = $_FILES['documents']['size'][$i];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            // Validate file size (5MB max)
            if ($fileSize > 5 * 1024 * 1024) {
                echo json_encode(["success" => false, "message" => "File {$fileName} is too large. Maximum 5MB per file"]);
                exit();
            }
            
            // Validate file type
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
            if (!in_array($fileExt, $allowedExtensions)) {
                echo json_encode(["success" => false, "message" => "File type not allowed: {$fileExt}"]);
                exit();
            }
            
            // Generate unique filename
            $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExt;
            $targetPath = $uploadDir . $uniqueFileName;
            
            if (move_uploaded_file($fileTmp, $targetPath)) {
                $uploadedFiles[] = 'activity_reports/' . $uniqueFileName;
            }
        }
    }
}

$supportingDocs = !empty($uploadedFiles) ? json_encode($uploadedFiles) : null;

// Insert activity report
$stmt = $conn->prepare("
    INSERT INTO activity_reports 
    (deployment_id, user_id, event_id, hours_worked, date_of_activity, 
     activities_performed, challenges_faced, outcomes_achieved, recommendations, 
     supporting_documents, status, submitted_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
");

$stmt->bind_param(
    "iiidsssss",
    $deploymentId,
    $userId,
    $eventId,
    $hoursWorked,
    $dateOfActivity,
    $activitiesPerformed,
    $challengesFaced,
    $outcomesAchieved,
    $recommendations,
    $supportingDocs
);

if ($stmt->execute()) {
    $reportId = $stmt->insert_id;
    
    // Optional: Send notification to admins (implement later)
    // notifyAdminsNewReport($reportId);
    
    echo json_encode([
        "success" => true,
        "message" => "Activity report submitted successfully",
        "report_id" => $reportId
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error submitting report: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
