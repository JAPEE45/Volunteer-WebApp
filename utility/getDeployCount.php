<?php
include_once 'db.php';
$sql = "SELECT 
            SUM(CASE WHEN status = 'deployed' THEN 1 ELSE 0 END) AS deployed,
            SUM(CASE WHEN status = 'not deployed' THEN 1 ELSE 0 END) AS not_deployed
        FROM users";

$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    $response = [
        'deployed' => (int)$row['deployed'],
        'not deployed' => (int)$row['not_deployed']
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Failed to fetch data']);
}

$conn->close();
?>
