<?php 
    include_once 'db.php';
    $data = json_decode(file_get_contents("php://input"), true);
    if($data){
        $eventName = $data['name'];
        $location = $data['location'];
        $date = $data['dateTime'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];
        $stmt = $conn->prepare("INSERT INTO events (eventName, location, date, longitude, latitude) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $eventName, $location, $date,$longitude, $latitude);
        if($stmt->execute()){
            echo json_encode(["success"=>true]);
        }else{
            echo json_encode(["error"=>"no data"]);
        }
    }else{
        echo json_encode(["error"=>"no data"]);
    }

?>