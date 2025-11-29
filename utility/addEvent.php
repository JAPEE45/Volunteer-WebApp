<?php 
    include_once 'db.php';
    $data = json_decode(file_get_contents("php://input"), true);
    if($data){
        $eventName = $data['name'];
        $location = $data['location'];
        $date = $data['dateTime'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];
        $duration = isset($data['duration']) ? intval($data['duration']) : 8;
        
        // Calculate end_date based on start date + duration
        $startDateTime = new DateTime($date);
        $endDateTime = clone $startDateTime;
        $endDateTime->add(new DateInterval('PT' . $duration . 'H'));
        $endDate = $endDateTime->format('Y-m-d H:i:s');
        
        // Determine initial status
        $now = new DateTime();
        if ($startDateTime > $now) {
            $status = 'upcoming';
        } elseif ($endDateTime > $now) {
            $status = 'active';
        } else {
            $status = 'completed';
        }
        
        // Check if new columns exist
        $checkColumns = $conn->query("SHOW COLUMNS FROM events LIKE 'duration'");
        
        if ($checkColumns && $checkColumns->num_rows > 0) {
            // New columns exist - use full insert
            $stmt = $conn->prepare("INSERT INTO events (eventName, location, date, longitude, latitude, duration, end_date, status) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssiss", $eventName, $location, $date, $longitude, $latitude, $duration, $endDate, $status);
        } else {
            // Old schema - use basic insert
            $stmt = $conn->prepare("INSERT INTO events (eventName, location, date, longitude, latitude) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss", $eventName, $location, $date, $longitude, $latitude);
        }
        
        if($stmt->execute()){
            echo json_encode([
                "success" => true,
                "event_id" => $conn->insert_id,
                "end_date" => $endDate,
                "status" => $status
            ]);
        }else{
            echo json_encode(["error" => $stmt->error]);
        }
    }else{
        echo json_encode(["error"=>"no data"]);
    }

?>