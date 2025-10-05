<?php
    include_once "db.php";
    $stmt = $conn->prepare("SELECT * FROM events");
    $stmt->execute();
    $result = $stmt->get_result();
    $res = [];
    while($r = $result->fetch_assoc()){
        $res[] = $r;
    }
    echo json_encode($res);

?>