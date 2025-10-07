<?php
    include_once 'db.php';

    $stmt = $conn->query("SELECT * FROM events");
    $res = $stmt->fetch_all(MYSQLI_ASSOC);
    echo json_encode($res);
    $stmt->close();
    $conn->close();

?>