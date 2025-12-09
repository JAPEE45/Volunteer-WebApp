<?php
// DEBUG VERSION - Shows actual errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Set JSON header
header('Content-Type: application/json');

try {
    include_once 'db.php';
    
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "message" => "Unauthorized access", "debug" => "No session"]);
        exit();
    }
    
    $userId = $_SESSION['user_id'];
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(["success" => false, "message" => "Invalid request method"]);
        exit();
    }
    
    // Get form data
    $deploymentId = isset($_POST['deployment_id']) ? intval($_POST['deployment_id']) : 0;
    $eventId = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
    
    echo json_encode([
        "success" => false, 
        "message" => "Debug mode", 
        "data" => [
            "userId" => $userId,
            "deploymentId" => $deploymentId,
            "eventId" => $eventId,
            "POST" => $_POST,
            "FILES" => isset($_FILES['documents']) ? "Files present" : "No files"
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Exception: " . $e->getMessage(),
        "trace" => $e->getTraceAsString()
    ]);
}
