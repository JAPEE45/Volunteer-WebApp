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
    // Get all deployment data with event locations
    $stmt = $conn->prepare("
        SELECT 
            d.id as deployment_id,
            d.user_id,
            d.event_id,
            d.createdAt as deployment_date,
            u.firstName,
            u.middleName,
            u.lastName,
            u.mobile,
            u.status as user_status,
            e.eventName,
            e.location,
            e.date as event_date,
            e.latitude,
            e.longitude
        FROM deployment d
        JOIN users u ON d.user_id = u.id
        JOIN events e ON d.event_id = e.id
        WHERE d.user_id = ?
        ORDER BY d.createdAt DESC
    ");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $allDeployments = [];
    $currentDeployment = null;
    $completedDeployments = [];
    
    while ($row = $result->fetch_assoc()) {
        $deploymentData = [
            'deployment_id' => $row['deployment_id'],
            'event_id' => $row['event_id'],
            'name' => trim($row['firstName'] . ' ' . $row['middleName'] . ' ' . $row['lastName']),
            'eventName' => $row['eventName'],
            'location' => $row['location'],
            'lat' => floatval($row['latitude']),
            'lng' => floatval($row['longitude']),
            'deployment_date' => $row['deployment_date'],
            'event_date' => $row['event_date'],
            'mobile' => $row['mobile'] ?: 'N/A',
            'user_status' => $row['user_status']
        ];
        
        $allDeployments[] = $deploymentData;
        
        // Determine if this is current deployment (most recent and user is deployed)
        if ($currentDeployment === null && $row['user_status'] === 'deployed') {
            $currentDeployment = $deploymentData;
        }
        
        // Check if event date has passed (completed)
        $eventDate = new DateTime($row['event_date']);
        $today = new DateTime();
        if ($eventDate < $today || $row['user_status'] !== 'deployed') {
            $completedDeployments[] = $deploymentData;
        }
    }
    
    // Get statistics
    $totalDeployments = count($allDeployments);
    $currentDeploymentCount = $currentDeployment ? 1 : 0;
    $completedCount = count($completedDeployments);
    
    // Get user status
    $stmt = $conn->prepare("SELECT status FROM users WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $userStatus = $result->fetch_assoc()['status'];
    
    echo json_encode([
        'success' => true,
        'allDeployments' => $allDeployments,
        'currentDeployment' => $currentDeployment,
        'completedDeployments' => $completedDeployments,
        'statistics' => [
            'total' => $totalDeployments,
            'current' => $currentDeploymentCount,
            'completed' => $completedCount,
            'userStatus' => $userStatus
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
