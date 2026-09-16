<?php
// updateAccountStatus.php - now deletes account and user
header('Content-Type: text/plain');
require_once 'db.php';

// Read raw POST body
$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? $input['id'] : null;
$status = isset($input['status']) ? $input['status'] : null;

if (!$id) {
    http_response_code(400);
    echo 'Missing user ID';
    exit;
}

try {
    $stmt = $conn->prepare('SELECT account_id FROM users WHERE user_pk = ? OR user_id = ? LIMIT 1');
    $stmt->execute([$id, $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $account_id = $row ? $row['account_id'] : null;

    $stmt = $conn->prepare('DELETE FROM users WHERE user_pk = ? OR user_id = ?');
    $stmt->execute([$id, $id]);
    if ($account_id) {
        $stmt = $conn->prepare('DELETE FROM account WHERE account_id = ?');
        $stmt->execute([$account_id]);
    }

    echo 'Account and user deleted.';
} catch (Exception $e) {
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
}
