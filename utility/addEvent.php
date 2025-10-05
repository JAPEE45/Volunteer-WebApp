<?php 
    include_once 'db.php';
    $data = json_decode(file_get_contents("php://input"), true);
    if($data){
        $eventName = $data['eventName'];
        $location = $data['location'];
        $date = $data['date'];
        $stmt = $conn->prepare("INSERT INTO events (eventName, location, date) VALUES (?,?,?)");
        $stmt->bind_param("sss", $eventName, $location, $date);
        if($stmt->execute()){
            echo json_encode(["success"=>true]);
        }else{
            echo json_encode(["error":"no data"]);
        }
    }else{
        echo json_encode(["error":"no data"]);
    }

?>