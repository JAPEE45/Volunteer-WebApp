<?php
/**
 * Test script to verify submitActivityReport.php returns valid JSON
 * Run this in your browser: http://localhost/Volunteer-WebApp/test_submit_api.php
 */

echo "<h2>Testing Activity Report API</h2>";
echo "<p>Testing if the API returns valid JSON...</p>";

// Test 1: Check if API returns JSON for unauthenticated request
echo "<h3>Test 1: Unauthenticated Request</h3>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/Volunteer-WebApp/utility/submitActivityReport.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, []);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<strong>HTTP Code:</strong> " . $httpCode . "<br>";
echo "<strong>Response:</strong> <pre>" . htmlspecialchars($response) . "</pre>";

$json = json_decode($response, true);
if (json_last_error() === JSON_ERROR_NONE) {
    echo "<span style='color:green;'>✓ Valid JSON returned</span><br>";
    echo "<strong>Decoded:</strong> <pre>" . print_r($json, true) . "</pre>";
} else {
    echo "<span style='color:red;'>✗ Invalid JSON - Error: " . json_last_error_msg() . "</span><br>";
    echo "<strong>First 500 chars:</strong> <pre>" . htmlspecialchars(substr($response, 0, 500)) . "</pre>";
}

// Test 2: Check response headers
echo "<h3>Test 2: Response Headers</h3>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/Volunteer-WebApp/utility/submitActivityReport.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, []);
$response = curl_exec($ch);
curl_close($ch);

$headerSize = strpos($response, "\r\n\r\n");
$headers = substr($response, 0, $headerSize);
echo "<pre>" . htmlspecialchars($headers) . "</pre>";

if (strpos($headers, 'Content-Type: application/json') !== false) {
    echo "<span style='color:green;'>✓ JSON Content-Type header is set</span><br>";
} else {
    echo "<span style='color:red;'>✗ JSON Content-Type header NOT found</span><br>";
}

echo "<hr>";
echo "<p><strong>Note:</strong> If Test 1 shows 'Unauthorized access' with valid JSON, the API is working correctly!</p>";
?>
