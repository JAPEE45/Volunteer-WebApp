<?php
include_once 'db.php';

$sql = "SELECT eventName, date, location FROM events ORDER BY date ASC";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => $conn->error]);
    exit;
}

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

header('Content-Type: application/json');
echo json_encode($events);

$conn->close();
?>
