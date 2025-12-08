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
$reportId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($reportId <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid report ID"]);
    exit();
}

// Get report details with event info
$stmt = $conn->prepare("
    SELECT 
        ar.*,
        e.eventName,
        e.location,
        e.date as event_date,
        d.role
    FROM activity_reports ar
    JOIN events e ON ar.event_id = e.id
    LEFT JOIN deployment d ON ar.deployment_id = d.id
    WHERE ar.id = ? AND ar.user_id = ?
");

$stmt->bind_param("ii", $reportId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $report = $result->fetch_assoc();
    echo json_encode([
        "success" => true,
        "report" => $report
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Report not found or access denied"
    ]);
}

$stmt->close();
$conn->close();
?>
