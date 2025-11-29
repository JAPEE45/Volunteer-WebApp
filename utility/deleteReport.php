<?php
include_once 'db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Get report ID from POST
$data = json_decode(file_get_contents('php://input'), true);
$report_id = isset($data['report_id']) ? intval($data['report_id']) : 0;

if ($report_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid report ID']);
    exit();
}

try {
    // Get report details first
    $stmt = $conn->prepare("SELECT file_path, user_id FROM reports WHERE id = ?");
    $stmt->bind_param('i', $report_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Report not found']);
        exit();
    }
    
    $report = $result->fetch_assoc();
    
    // Check if the report belongs to the current user
    if ($report['user_id'] != $user_id) {
        echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
        exit();
    }
    
    // Delete the file
    if (file_exists($report['file_path'])) {
        unlink($report['file_path']);
    }
    
    // Delete from database
    $stmt = $conn->prepare("DELETE FROM reports WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $report_id, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Report deleted successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Failed to delete report from database'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
