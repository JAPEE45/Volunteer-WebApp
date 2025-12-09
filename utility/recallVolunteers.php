<?php
/**
 * Automatic Volunteer Recall System
 * This script checks for completed events and recalls (marks as available) 
 * volunteers whose deployments have ended.
 */

include_once 'db.php';
include_once 'sendSms.php';

function recallVolunteers($conn, $sendSmsNotification = false) {
    $recalled = [];
    $errors = [];
    $now = date('Y-m-d H:i:s');
    
    // Check if new columns exist
    $checkColumns = $conn->query("SHOW COLUMNS FROM events LIKE 'end_date'");
    $hasNewColumns = ($checkColumns && $checkColumns->num_rows > 0);
    
    if (!$hasNewColumns) {
        // New columns don't exist yet - return early
        return [
            'success' => true,
            'recalled_count' => 0,
            'recalled_volunteers' => [],
            'errors' => ['Database not updated yet. Run database_event_duration.sql first.'],
            'timestamp' => $now
        ];
    }
    
    // Find events that have ended but not marked completed
    $findEndedEvents = $conn->prepare("
        SELECT id, eventName, end_date, location 
        FROM events 
        WHERE end_date <= ? 
        AND (status != 'completed' OR status IS NULL)
    ");
    $findEndedEvents->bind_param("s", $now);
    $findEndedEvents->execute();
    $endedEvents = $findEndedEvents->get_result();
    
    while ($event = $endedEvents->fetch_assoc()) {
        $eventId = $event['id'];
        $eventName = $event['eventName'];
        $eventLocation = $event['location'];
        
        // Find volunteers deployed to this event
        $findDeployed = $conn->prepare("
            SELECT DISTINCT d.user_id, u.fullName, u.mobile_number as mobile
            FROM deployment d
            JOIN users u ON d.user_id = u.id
            WHERE d.event_id = ?
            AND u.status = 'deployed'
        ");
        $findDeployed->bind_param("i", $eventId);
        $findDeployed->execute();
        $deployedVolunteers = $findDeployed->get_result();
        
        while ($volunteer = $deployedVolunteers->fetch_assoc()) {
            $userId = $volunteer['user_id'];
            
            // Check for other active deployments
            $checkOther = $conn->prepare("
                SELECT d.id FROM deployment d
                JOIN events e ON d.event_id = e.id
                WHERE d.user_id = ? AND d.event_id != ?
                AND e.end_date > ? AND (e.status != 'completed' OR e.status IS NULL)
            ");
            $checkOther->bind_param("iis", $userId, $eventId, $now);
            $checkOther->execute();
            $otherResult = $checkOther->get_result();
            
            if ($otherResult->num_rows === 0) {
                // Recall volunteer
                $recall = $conn->prepare("UPDATE users SET status = 'not deployed' WHERE id = ?");
                $recall->bind_param("i", $userId);
                
                if ($recall->execute()) {
                    $recalled[] = [
                        'user_id' => $userId,
                        'fullName' => $volunteer['fullName'],
                        'event' => $eventName
                    ];
                    
                    // Optional SMS
                    if ($sendSmsNotification && !empty($volunteer['mobile'])) {
                        $msg = "Hi {$volunteer['fullName']}, Thank you for your service at {$eventName}! You are now available for new assignments. - Philippine Red Cross";
                        sendMessage($volunteer['mobile'], $msg);
                    }
                }
                $recall->close();
            }
            $checkOther->close();
        }
        $findDeployed->close();
        
        // Mark event completed
        $markDone = $conn->prepare("UPDATE events SET status = 'completed' WHERE id = ?");
        $markDone->bind_param("i", $eventId);
        $markDone->execute();
        $markDone->close();
    }
    $findEndedEvents->close();
    
    // Update active events
    $markActive = $conn->prepare("
        UPDATE events SET status = 'active' 
        WHERE date <= ? AND end_date > ? AND (status = 'upcoming' OR status IS NULL)
    ");
    $markActive->bind_param("ss", $now, $now);
    $markActive->execute();
    $markActive->close();
    
    return [
        'success' => true,
        'recalled_count' => count($recalled),
        'recalled_volunteers' => $recalled,
        'errors' => $errors,
        'timestamp' => $now
    ];
}

// Direct call
if (basename($_SERVER['PHP_SELF']) === 'recallVolunteers.php') {
    header('Content-Type: application/json');
    $sendSms = isset($_GET['sms']) && $_GET['sms'] === 'true';
    $result = recallVolunteers($conn, $sendSms);
    echo json_encode($result);
    $conn->close();
}
?>
