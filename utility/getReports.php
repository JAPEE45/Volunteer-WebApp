<?php
include_once 'db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Get all reports for the user
    $stmt = $conn->prepare("
        SELECT 
            r.id,
            r.user_id,
            r.deployment_id,
            r.event_id,
            r.report_title,
            r.report_description,
            r.file_name,
            r.file_path,
            r.file_size,
            r.file_type,
            r.status,
            r.submission_date,
            r.review_date,
            r.comments,
            e.eventName,
            e.location as event_location,
            e.date as event_date
        FROM reports r
        LEFT JOIN events e ON r.event_id = e.id
        WHERE r.user_id = ?
        ORDER BY r.submission_date DESC
    ");
    
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = [
            'id' => $row['id'],
            'deployment_id' => $row['deployment_id'],
            'event_id' => $row['event_id'],
            'report_title' => $row['report_title'],
            'report_description' => $row['report_description'],
            'file_name' => $row['file_name'],
            'file_path' => $row['file_path'],
            'file_size' => $row['file_size'],
            'file_type' => $row['file_type'],
            'status' => $row['status'],
            'submission_date' => $row['submission_date'],
            'review_date' => $row['review_date'],
            'comments' => $row['comments'],
            'event_name' => $row['eventName'],
            'event_location' => $row['event_location'],
            'event_date' => $row['event_date']
        ];
    }
    
    // Get statistics
    $total_reports = count($reports);
    
    // Get reports submitted this month
    $current_month = date('Y-m');
    $month_reports = 0;
    foreach ($reports as $report) {
        if (strpos($report['submission_date'], $current_month) === 0) {
            $month_reports++;
        }
    }
    
    // Get pending reports count
    $pending_count = 0;
    foreach ($reports as $report) {
        if ($report['status'] === 'pending') {
            $pending_count++;
        }
    }
    
    // Get approved reports count
    $approved_count = 0;
    foreach ($reports as $report) {
        if ($report['status'] === 'approved') {
            $approved_count++;
        }
    }
    
    echo json_encode([
        'success' => true,
        'reports' => $reports,
        'statistics' => [
            'total' => $total_reports,
            'month' => $month_reports,
            'pending' => $pending_count,
            'approved' => $approved_count
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
