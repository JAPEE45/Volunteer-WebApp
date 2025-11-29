<?php
header('Content-Type: application/json');
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

include 'db.php';

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$user_id = $input['user_id'] ?? null;
$physical_fitness = $input['physical_fitness'] ?? 0;
$communication_skills = $input['communication_skills'] ?? 0;
$teamwork = $input['teamwork'] ?? 0;
$reliability = $input['reliability'] ?? 0;
$comments = $input['comments'] ?? '';
$evaluator_id = $_SESSION['user_id'];

// Validate required fields
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

// Calculate overall rating
$overall_rating = ($physical_fitness + $communication_skills + $teamwork + $reliability) / 4;

// Determine status (passed if overall >= 3.0)
$status = $overall_rating >= 3.0 ? 'passed' : 'failed';

// Check if evaluation already exists
$checkStmt = $conn->prepare("SELECT id FROM evaluations WHERE user_id = ?");
$checkStmt->bind_param("i", $user_id);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    // Update existing evaluation
    $stmt = $conn->prepare("UPDATE evaluations SET 
        evaluator_id = ?, 
        physical_fitness = ?, 
        communication_skills = ?, 
        teamwork = ?, 
        reliability = ?, 
        overall_rating = ?, 
        comments = ?, 
        status = ?,
        evaluation_date = CURRENT_TIMESTAMP
        WHERE user_id = ?");
    
    $stmt->bind_param("idddddssi", 
        $evaluator_id,
        $physical_fitness, 
        $communication_skills, 
        $teamwork, 
        $reliability, 
        $overall_rating, 
        $comments, 
        $status,
        $user_id
    );
} else {
    // Insert new evaluation
    $stmt = $conn->prepare("INSERT INTO evaluations 
        (user_id, evaluator_id, physical_fitness, communication_skills, teamwork, reliability, overall_rating, comments, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("iiddddds", 
        $user_id, 
        $evaluator_id,
        $physical_fitness, 
        $communication_skills, 
        $teamwork, 
        $reliability, 
        $overall_rating, 
        $comments, 
        $status
    );
}

if ($stmt->execute()) {
    echo json_encode([
        'success' => true, 
        'message' => 'Evaluation saved successfully',
        'overall_rating' => $overall_rating,
        'status' => $status
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save evaluation: ' . $stmt->error]);
}

$stmt->close();
$checkStmt->close();
$conn->close();
?>
