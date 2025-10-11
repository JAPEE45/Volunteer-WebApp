<?php
include_once 'db.php';
include 'generatePasswordAndUsername.php';
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;
$status = "accepted";

if (!$id || !$status) {
    echo "Invalid input.";
    exit;
}

$stmt = $conn->prepare("UPDATE users SET account_status = ? WHERE id = ?");
if ($stmt->execute([$status, $id])) {
    $username = generateUsername();
    $password = generatePassword();
    $ss = $conn->prepare("INSERT INTO account (username, password, user_id) VALUES (?,?,?)");
    $ss->execute([$username,$password, $id]);    
    echo "Volunteer status updated to '$status'.";
} else {
    echo "Failed to update status.";
}
?>
