<?php
include_once 'db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["error" => "Missing ID"]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(["error" => "Volunteer not found"]);
}

$stmt->close();
$conn->close();
?>
