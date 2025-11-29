<?php
include_once 'db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if files were uploaded
if (!isset($_FILES['reports']) || empty($_FILES['reports']['name'][0])) {
    echo json_encode(['success' => false, 'error' => 'No files uploaded']);
    exit();
}

// Get form data
$report_title = isset($_POST['report_title']) ? trim($_POST['report_title']) : '';
$report_description = isset($_POST['report_description']) ? trim($_POST['report_description']) : '';
$deployment_id = isset($_POST['deployment_id']) ? intval($_POST['deployment_id']) : null;
$event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : null;

// Validate required fields
if (empty($report_title)) {
    echo json_encode(['success' => false, 'error' => 'Report title is required']);
    exit();
}

// Create upload directory if it doesn't exist
$upload_dir = '../uploads/reports/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$uploaded_files = [];
$errors = [];

// Process each file
$file_count = count($_FILES['reports']['name']);

for ($i = 0; $i < $file_count; $i++) {
    if ($_FILES['reports']['error'][$i] !== UPLOAD_ERR_OK) {
        $errors[] = "Error uploading file: " . $_FILES['reports']['name'][$i];
        continue;
    }

    $file_name = $_FILES['reports']['name'][$i];
    $file_tmp = $_FILES['reports']['tmp_name'][$i];
    $file_size = $_FILES['reports']['size'][$i];
    $file_type = $_FILES['reports']['type'][$i];
    
    // Get file extension
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Validate file type
    $allowed_extensions = ['pdf', 'doc', 'docx'];
    if (!in_array($file_ext, $allowed_extensions)) {
        $errors[] = "Invalid file type for $file_name. Only PDF, DOC, and DOCX are allowed.";
        continue;
    }
    
    // Validate file size (max 10MB)
    if ($file_size > 10 * 1024 * 1024) {
        $errors[] = "File $file_name is too large. Maximum size is 10MB.";
        continue;
    }
    
    // Generate unique file name
    $unique_name = uniqid() . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file_name);
    $file_path = $upload_dir . $unique_name;
    
    // Move uploaded file
    if (move_uploaded_file($file_tmp, $file_path)) {
        // Insert into database
        try {
            $stmt = $conn->prepare("
                INSERT INTO reports 
                (user_id, deployment_id, event_id, report_title, report_description, 
                 file_name, file_path, file_size, file_type, status, submission_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
            ");
            
            $stmt->bind_param(
                'iiissssss',
                $user_id,
                $deployment_id,
                $event_id,
                $report_title,
                $report_description,
                $file_name,
                $file_path,
                $file_size,
                $file_ext
            );
            
            if ($stmt->execute()) {
                $uploaded_files[] = [
                    'id' => $conn->insert_id,
                    'name' => $file_name,
                    'size' => $file_size,
                    'type' => $file_ext
                ];
            } else {
                $errors[] = "Database error for $file_name: " . $stmt->error;
                unlink($file_path); // Delete file if database insert fails
            }
            
        } catch (Exception $e) {
            $errors[] = "Exception for $file_name: " . $e->getMessage();
            unlink($file_path); // Delete file if error occurs
        }
    } else {
        $errors[] = "Failed to move uploaded file: $file_name";
    }
}

// Return response
if (count($uploaded_files) > 0) {
    echo json_encode([
        'success' => true,
        'message' => count($uploaded_files) . ' report(s) uploaded successfully',
        'uploaded_files' => $uploaded_files,
        'errors' => $errors
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Failed to upload any files',
        'details' => $errors
    ]);
}
?>
