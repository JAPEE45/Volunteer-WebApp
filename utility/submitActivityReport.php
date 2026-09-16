<?php
// Error handling: catch errors but return them as JSON
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display to browser
ini_set('log_errors', 1);

// Custom error handler to catch PHP errors
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (ob_get_level()) ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode([
        "success" => false,
        "message" => "Server error occurred",
        "error" => $errstr,
        "file" => basename($errfile),
        "line" => $errline
    ]);
    exit();
});

// Start session
session_start();

// Start output buffering to catch any accidental output
ob_start();

// Set JSON header immediately
header('Content-Type: application/json');

// Include database connection with error handling
try {
    include_once 'db.php';
} catch (Exception $e) {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Database connection error: " . $e->getMessage()]);
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Unauthorized access"]);
    exit();
}

$userId = $_SESSION['user_id'];

// Validate POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_end_clean();
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
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Please fill in all required fields"]);
    exit();
}

// Validate hours worked
if ($hoursWorked < 0.5 || $hoursWorked > 24) {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Hours worked must be between 0.5 and 24"]);
    exit();
}

// Verify deployment belongs to user
try {
    $verifyStmt = $conn->prepare("SELECT id FROM deployment WHERE id = ? AND user_id = ?");
    if (!$verifyStmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }
    $verifyStmt->bind_param("ii", $deploymentId, $userId);
    $verifyStmt->execute();
    $verifyResult = $verifyStmt->get_result();

    if ($verifyResult->num_rows === 0) {
        ob_end_clean();
        echo json_encode(["success" => false, "message" => "Invalid deployment selection"]);
        $verifyStmt->close();
        exit();
    }
    $verifyStmt->close();
} catch (Exception $e) {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    exit();
}

// Check if report already exists for this deployment
try {
    $checkStmt = $conn->prepare("SELECT id FROM activity_reports WHERE deployment_id = ? AND user_id = ?");
    if (!$checkStmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }
    $checkStmt->bind_param("ii", $deploymentId, $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        ob_end_clean();
        echo json_encode(["success" => false, "message" => "You have already submitted a report for this deployment"]);
        $checkStmt->close();
        exit();
    }
    $checkStmt->close();
} catch (Exception $e) {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    exit();
}

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
        ob_end_clean();
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
                ob_end_clean();
                echo json_encode(["success" => false, "message" => "File {$fileName} is too large. Maximum 5MB per file"]);
                exit();
            }
            
            // Validate file type
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
            if (!in_array($fileExt, $allowedExtensions)) {
                ob_end_clean();
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
try {
    $stmt = $conn->prepare("
        INSERT INTO activity_reports 
        (deployment_id, user_id, event_id, hours_worked, date_of_activity, 
         activities_performed, challenges_faced, outcomes_achieved, recommendations, 
         supporting_documents, status, submitted_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
    ");
    
    if (!$stmt) {
        throw new Exception("Failed to prepare insert statement: " . $conn->error);
    }

    $stmt->bind_param(
        "iiidssssss",
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
        
        ob_end_clean();
        echo json_encode([
            "success" => true,
            "message" => "Activity report submitted successfully",
            "report_id" => $reportId
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
        "message" => "Error submitting report: " . $e->getMessage()
    ]);
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
