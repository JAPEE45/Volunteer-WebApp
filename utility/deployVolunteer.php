<?php
include_once 'db.php';
include 'sendSms.php';

// Read raw JSON body
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['volunteerId']) || !isset($data['eventId'])) {
    echo json_encode(["error" => "Invalid or missing data"]);
    exit;
}

$volunteerId = $data['volunteerId'];
$eventId = $data['eventId'];
$role = isset($data['role']) ? $data['role'] : null;

// Check if already deployed to this specific event (exact duplicate)
$checkDuplicate = $conn->prepare("SELECT id FROM deployment WHERE user_id = ? AND event_id = ?");
$checkDuplicate->bind_param("ii", $volunteerId, $eventId);
$checkDuplicate->execute();
$duplicateResult = $checkDuplicate->get_result();

if ($duplicateResult->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Volunteer is already deployed to this event."]);
    $checkDuplicate->close();
    exit;
}
$checkDuplicate->close();

// Check if role column exists in deployment table
$checkColumn = $conn->query("SHOW COLUMNS FROM deployment LIKE 'role'");
$roleColumnExists = $checkColumn->num_rows > 0;

// Insert into deployment table (allows redeployment to different events)
if ($roleColumnExists && $role) {
    $stmt = $conn->prepare("INSERT INTO deployment (user_id, event_id, role) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $volunteerId, $eventId, $role);
} else {
    $stmt = $conn->prepare("INSERT INTO deployment (user_id, event_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $volunteerId, $eventId);
}

if ($stmt->execute()) {
    // Update user status to 'deployed'
    $updateStmt = $conn->prepare("UPDATE users SET status = 'deployed' WHERE id = ?");
    $updateStmt->bind_param("i", $volunteerId);
    $updateStmt->execute();
    $updateStmt->close();
    $dinfo = $conn->prepare("
        SELECT 
            u.fullName, 
            u.mobile_number as mobile, 
            e.location, 
            e.date, 
            e.eventName 
        FROM 
            deployment d
        JOIN 
            users u ON d.user_id = u.id
        JOIN 
            events e ON d.event_id = e.id
        WHERE 
            d.user_id = ? AND d.event_id = ?
    ");
    $dinfo->bind_param("ii", $volunteerId, $eventId);
    $dinfo->execute();
    $dinfoRes = $dinfo->get_result();
    $res = $dinfoRes->fetch_assoc();
    $dinfo->close();

    if ($res) {
        $mobile = $res['mobile'];
        $roleText = $role ? "\nRole: {$role}" : "";
        $content = "Hi {$res['fullName']}, Thank you for confirming your availability! You are now officially deployed for the following:
Location: {$res['location']}
Date: {$res['date']}
Event: {$res['eventName']}{$roleText}

Please bring your Red Cross ID and arrive on time. Stay safe and thank you for your service.";

        sendMessage($mobile, $content);
    }

    

    echo json_encode(["success" => true, "message" => "Volunteer deployed successfully", "role" => $role]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
