<?php
include_once 'db.php';

// Return account rows joined with user info when available
$sql = "SELECT
    a.id AS account_id,
    a.username,
    a.password,
    a.user_id,
    a.createdAt,
    a.user_type AS account_type,
    a.lastLogin,
    u.id AS user_pk,
    u.fullName,
    u.mobile,
    u.address,
    u.account_status,
    u.status
FROM account a
LEFT JOIN users u ON a.user_id = u.id WHERE u.user_type = 'volunteer'";

$result = $conn->query($sql);

if (!$result) {
        echo json_encode(["error" => $conn->error]);
        exit;
}

$rows = $result->fetch_all(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($rows);

$conn->close();
?>
