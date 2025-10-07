<?php
include_once 'db.php';

$sql = "
SELECT 
  u.fullName, 
  u.account_status, 
  u.id, 
  u.status, 
  u.age,
  u.address,
  u.mobile,
  e.location AS deployedLocation,
  e.eventName
FROM users u
LEFT JOIN deployment d ON d.user_id = u.id
LEFT JOIN events e ON d.event_id = e.id
WHERE u.user_type = 'volunteer'
";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => $conn->error]);
    exit;
}

$volunteers = $result->fetch_all(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($volunteers);

$conn->close();
?>
