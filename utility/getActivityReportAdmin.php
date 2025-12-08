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

// Get report with volunteer and event details
$query = "
    SELECT 
        ar.*,
        u.fullName as volunteer_name,
        u.mobile,
        e.eventName,
        e.location,
        e.date as event_date,
        d.role
    FROM activity_reports ar
    JOIN users u ON ar.user_id = u.id
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
$conn->close();

echo json_encode(['success' => true, 'report' => $report]);
?>
