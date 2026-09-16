<?php
include_once 'db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Get total deployments count
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM deployment WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $totalDeployments = $result->fetch_assoc()['total'];
    
    // Get current deployment (most recent deployment)
    $stmt = $conn->prepare("
        SELECT 
            e.id,
            e.eventName,
            e.location,
            e.date as eventDate,
            d.createdAt as deploymentDate
        FROM deployment d
        JOIN events e ON d.event_id = e.id
        WHERE d.user_id = ?
        ORDER BY d.id DESC
        LIMIT 1
    ");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentDeployment = $result->fetch_assoc();
    
    // Get deployment history
    $stmt = $conn->prepare("
        SELECT 
            e.id,
            e.eventName,
            e.location,
            e.date as eventDate,
            d.createdAt as deploymentDate
        FROM deployment d
        JOIN events e ON d.event_id = e.id
        WHERE d.user_id = ?
        ORDER BY d.createdAt DESC
    ");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $deploymentHistory = [];
    while ($row = $result->fetch_assoc()) {
        $deploymentHistory[] = $row;
    }
    
    // Get user status
    $stmt = $conn->prepare("SELECT status FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userStatus = $result->fetch_assoc()['status'];
    
    // Calculate statistics
    $activeHours = $totalDeployments * 8; // Estimate 8 hours per deployment
    $missionsCompleted = $totalDeployments;
    $recognitionPoints = $totalDeployments * 50; // 50 points per deployment
    
    echo json_encode([
        'success' => true,
        'totalDeployments' => $totalDeployments,
        'activeHours' => $activeHours,
        'missionsCompleted' => $missionsCompleted,
        'recognitionPoints' => $recognitionPoints,
        'userStatus' => $userStatus,
        'currentDeployment' => $currentDeployment,
        'deploymentHistory' => $deploymentHistory
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
