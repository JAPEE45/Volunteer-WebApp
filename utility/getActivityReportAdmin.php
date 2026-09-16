<?php
session_start();
include_once "db.php";

// Check admin session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Report ID required']);
    exit();
}

$reportId = intval($_GET['id']);

// Get report with event details only
$query = "
    SELECT 
        ar.*,
        e.eventName,
        e.location,
        e.date as event_date,
        d.role
    FROM activity_reports ar
    JOIN events e ON ar.event_id = e.id
    LEFT JOIN deployment d ON ar.deployment_id = d.id
    WHERE ar.id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $reportId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Report not found']);
    exit();
}

$report = $result->fetch_assoc();
$stmt->close();

// Get volunteer details separately to handle different column names
$userStmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$userStmt->bind_param("i", $report['user_id']);
$userStmt->execute();
$userResult = $userStmt->get_result();
$userData = $userResult->fetch_assoc();
$userStmt->close();

// Build name from available columns
if (!empty($userData['fullName'])) {
    $report['volunteer_name'] = $userData['fullName'];
} else {
    $nameParts = array_filter([
        $userData['given_name'] ?? $userData['firstName'] ?? '',
        $userData['middle_name'] ?? $userData['middleName'] ?? '',
        $userData['last_name'] ?? $userData['lastName'] ?? ''
    ]);
    $report['volunteer_name'] = implode(' ', $nameParts) ?: 'Unknown';
}

// Get mobile number
$report['mobile'] = $userData['mobile_number'] ?? $userData['mobile'] ?? '';

$conn->close();

echo json_encode(['success' => true, 'report' => $report]);
?>
