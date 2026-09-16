<?php
session_start();

// Ensure output is JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

include_once 'db.php';
include_once 'sendSms.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit();
}

$reportId = isset($input['report_id']) ? intval($input['report_id']) : 0;
$status = isset($input['status']) ? $input['status'] : '';
$adminNotes = isset($input['admin_notes']) ? trim($input['admin_notes']) : '';
$reviewerId = $_SESSION['user_id'];

// Validate input
if ($reportId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid report ID']);
    exit();
}

if ($status !== 'approved' && $status !== 'rejected') {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit();
}

try {
    // Check if report exists and get volunteer details
    $checkStmt = $conn->prepare("
        SELECT ar.*, u.mobile_number, u.mobile, u.fullName, u.given_name, u.first_name, u.firstName 
        FROM activity_reports ar
        JOIN users u ON ar.user_id = u.id
        WHERE ar.id = ?
    ");
    $checkStmt->bind_param("i", $reportId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows === 0) {
        $checkStmt->close();
        echo json_encode(['success' => false, 'message' => 'Report not found']);
        exit();
    }
    
    $reportData = $result->fetch_assoc();
    $checkStmt->close();
    
    // Get volunteer name and mobile
    $volunteerName = $reportData['fullName'] ?? $reportData['given_name'] ?? $reportData['first_name'] ?? $reportData['firstName'] ?? 'Volunteer';
    $mobileNumber = $reportData['mobile_number'] ?? $reportData['mobile'] ?? '';

    // Update report status
    $updateStmt = $conn->prepare("
        UPDATE activity_reports 
        SET status = ?, 
            admin_notes = ?, 
            reviewed_by = ?, 
            reviewed_at = NOW(),
            updated_at = NOW()
        WHERE id = ?
    ");
    
    $updateStmt->bind_param("ssii", $status, $adminNotes, $reviewerId, $reportId);
    
    if ($updateStmt->execute()) {
        // Send SMS notification if report is approved and mobile number exists
        if ($status === 'approved' && !empty($mobileNumber)) {
            $smsMessage = "Good day {$volunteerName}! Your activity report has been APPROVED by the Philippine Red Cross. Thank you for your service!";
            sendMessage($mobileNumber, $smsMessage);
        }
        
        echo json_encode(['success' => true, 'message' => 'Report ' . $status . ' successfully']);
    } else {
        throw new Exception($updateStmt->error);
    }
    
    $updateStmt->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();
?>