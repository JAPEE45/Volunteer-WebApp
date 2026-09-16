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
    // Get all deployments for the user
    $stmt = $conn->prepare("
        SELECT 
            d.id as deployment_id,
            d.event_id,
            d.createdAt as deployment_date,
            e.eventName,
            e.location,
            e.date as event_date
        FROM deployment d
        JOIN events e ON d.event_id = e.id
        WHERE d.user_id = ?
        ORDER BY d.createdAt DESC
    ");
    
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $deployments = [];
    while ($row = $result->fetch_assoc()) {
        $deployments[] = [
            'deployment_id' => $row['deployment_id'],
            'event_id' => $row['event_id'],
            'eventName' => $row['eventName'],
            'location' => $row['location'],
            'event_date' => $row['event_date'],
            'deployment_date' => $row['deployment_date']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'deployments' => $deployments
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
