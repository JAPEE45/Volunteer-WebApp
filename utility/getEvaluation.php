<?php
header('Content-Type: application/json');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

include 'db.php';

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM evaluations WHERE user_id = ? ORDER BY evaluation_date DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $evaluation = $result->fetch_assoc();
    echo json_encode(['success' => true, 'evaluation' => $evaluation]);
} else {
    echo json_encode(['success' => false, 'message' => 'No evaluation found']);
}

$stmt->close();
$conn->close();
?>
