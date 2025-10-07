<?php
    include_once 'db.php';
    $eventId = $_GET['eventId'];
    $stmt = $conn->prepare("DELETE FROM events WHERE id=?");
    $stmt->bind_param("i", $eventId);
    if($stmt->execute()){
            echo json_encode(['success'=>true]);
        
    }else{
        echo json_encode(['error'=>true]);
    }
$stmt->close();
$conn->close();
?>