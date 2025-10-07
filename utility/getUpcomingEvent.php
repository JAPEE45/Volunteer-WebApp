<?php
include_once 'db.php';

$currentDate = new DateTime();
$monthsBefore = 4;
$monthsAfter = 1;

$months = [];
for ($i = -$monthsBefore; $i <= $monthsAfter; $i++) {
    $month = (clone $currentDate)->modify("$i month");
    $months[$month->format('M')] = 0;
}

$startDate = (clone $currentDate)->modify("-$monthsBefore month")->format('Y-m-01');
$endDate = (clone $currentDate)->modify("+$monthsAfter month")->format('Y-m-t');

$sql = "SELECT date FROM events WHERE date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $eventMonth = date('M', strtotime($row['date']));
    if (isset($months[$eventMonth])) {
        $months[$eventMonth]++;
    }
}

echo json_encode([$months]);

$stmt->close();
$conn->close();
?>
