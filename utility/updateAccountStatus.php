<?php
include_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;
$status = $data['status'] ?? null;

if (!$id || !$status) {
    echo "Invalid input.";
    exit;
}

$stmt = $conn->prepare("UPDATE users SET account_status = ? WHERE id = ?");
$stmt->bind_param('si', $status, $id);
if ($stmt->execute()) {
    echo "Account status updated to '$status'.";
} else {
    echo "Failed to update status: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
