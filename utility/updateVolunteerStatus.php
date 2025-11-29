<?php
include_once 'db.php';
include 'sendSms.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;
$status = $data['status'];
$reason = $data['reason'];
$mobile = $data['mobile'];
$volunteerName = $data['fullName'];

if (!$id || !$status) {
    echo "Invalid input.";
    exit;
}

$stmt = $conn->prepare("UPDATE users SET account_status = ?, reason = ? WHERE id = ?");
if ($stmt->execute([$status,$reason, $id])) {
     if ($data['status'] == 'accepted') {
        $username = "user_" . $id; 
        $password = bin2hex(random_bytes(4)); 

        $stmtInsertAccount = $conn->prepare("INSERT INTO account (username, password, user_id, user_type) VALUES (?, ?, ?, 'volunteer')");
        $stmtInsertAccount->bind_param('ssi', $username, $password, $id);
        $stmtInsertAccount->execute();
        if($status == "accepted"){
            $message = "Hi $volunteerName! Your volunteer request has been approved.
Username: $username
Password: $password
Login now at https://schizocarpic-tanya-precorrectly.ngrok-free.dev/Volunteer-webapp/html/login.php";
            sendMessage($mobile, $message);
        }
        $stmtInsertAccount->close();
    }
    echo "Volunteer application has been '$status'.";
} else {
    echo "Failed to update status.";
}
?>
