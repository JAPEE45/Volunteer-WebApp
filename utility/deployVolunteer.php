<?php
include_once 'db.php';

// Read raw JSON body
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['volunteerId']) || !isset($data['eventId'])) {
    echo json_encode(["error" => "Invalid or missing data"]);
    exit;
}

$volunteerId = $data['volunteerId'];
$eventId = $data['eventId'];

// Insert into deployment table
$stmt = $conn->prepare("INSERT INTO deployment (user_id, event_id) VALUES (?, ?)");
$stmt->bind_param("ii", $volunteerId, $eventId);

if ($stmt->execute()) {
    // Update user status to 'deployed'
    $updateStmt = $conn->prepare("UPDATE users SET status = 'deployed' WHERE id = ?");
    $updateStmt->bind_param("i", $volunteerId);
    $updateStmt->execute();
    $updateStmt->close();

    echo json_encode(["success" => true, "message" => "Volunteer deployed successfully"]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
