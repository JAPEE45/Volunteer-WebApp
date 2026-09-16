<?php
include_once 'db.php';

// Check if role column exists in deployment table
$checkColumn = $conn->query("SHOW COLUMNS FROM deployment LIKE 'role'");
$roleColumnExists = $checkColumn->num_rows > 0;

$roleSelect = $roleColumnExists ? ", d_role.role AS deploymentRole" : "";
$roleJoin = $roleColumnExists ? "
LEFT JOIN (
    SELECT d3.user_id, d3.role
    FROM deployment d3
    WHERE d3.id = (
        SELECT d4.id FROM deployment d4 
        WHERE d4.user_id = d3.user_id 
        ORDER BY d4.createdAt DESC, d4.id DESC 
        LIMIT 1
    )
) d_role ON d_role.user_id = u.id
" : "";

$sql = "
SELECT 
    u.id,
    u.fullName,
    u.account_status,
    u.status,
    u.age,
    u.district_barangay_village as address,
    u.mobile_number as mobile,
    e.location AS deployedLocation,
    e.eventName
    {$roleSelect}
FROM users u
LEFT JOIN (
    SELECT d1.user_id, d1.event_id
    FROM deployment d1
    WHERE d1.id = (
        SELECT d2.id FROM deployment d2 
        WHERE d2.user_id = d1.user_id 
        ORDER BY d2.createdAt DESC, d2.id DESC 
        LIMIT 1
    )
) latest_deployment ON latest_deployment.user_id = u.id
LEFT JOIN events e ON latest_deployment.event_id = e.id
{$roleJoin}
WHERE u.user_type = 'volunteer'
GROUP BY u.id
ORDER BY u.fullName ASC;
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
