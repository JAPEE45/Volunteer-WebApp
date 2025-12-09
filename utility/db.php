<?php
// Suppress PHP errors for API calls
if (strpos($_SERVER['REQUEST_URI'], 'utility/') !== false || 
    isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    error_reporting(0);
    ini_set('display_errors', 0);
}

$host = "localhost";      
$user = "root";          
$pass = "";               
$dbname = "volunteer-web";  

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    // Return JSON error for API calls instead of HTML
    if (strpos($_SERVER['REQUEST_URI'], 'utility/') !== false || 
        isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        if (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode(["success" => false, "message" => "Database connection failed"]);
        exit();
    }
    die("Connection failed: " . $conn->connect_error);
}
