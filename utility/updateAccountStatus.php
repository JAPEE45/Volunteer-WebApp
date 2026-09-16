<?php
include_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;
$status = $data['status'] ?? null;

if (!$id || !$status) {
    echo "Invalid input.";
    exit;
}

// If status is 'accepted', check if evaluation exists
if ($status === 'accepted') {
    $evalCheck = $conn->prepare("SELECT id, overall_rating, status FROM evaluations WHERE user_id = ?");
    $evalCheck->bind_param("i", $id);
    $evalCheck->execute();
    $evalResult = $evalCheck->get_result();
    
    if ($evalResult->num_rows === 0) {
        echo "Cannot accept volunteer: Evaluation required before acceptance";
        $evalCheck->close();
        $conn->close();
        exit;
    }
    
    $evaluation = $evalResult->fetch_assoc();
    
    // Check if evaluation passed (overall rating >= 3.0)
    if ($evaluation['status'] === 'failed') {
        echo "Cannot accept volunteer: Evaluation status is failed (rating: " . $evaluation['overall_rating'] . ")";
        $evalCheck->close();
        $conn->close();
        exit;
    }
    
    $evalCheck->close();
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
