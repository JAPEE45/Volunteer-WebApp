<?php
// ===============================================
// SMS ENDPOINT DIAGNOSTIC & TEST SCRIPT
// Tests SMS functionality and provides debugging info
// Date: December 9, 2025
// ===============================================

header('Content-Type: application/json');

// Test configuration
$testNumber = "09171234567"; // Change to your actual mobile number
$testMessage = "Test SMS from Red Cross Volunteer System - " . date('Y-m-d H:i:s');

// Current SMS endpoint
$currentEndpoint = "https://prorestoration-enrico-worrisome.ngrok-free.dev/send_sms";

echo json_encode([
    "test_info" => [
        "timestamp" => date('Y-m-d H:i:s'),
        "current_endpoint" => $currentEndpoint,
        "test_number" => $testNumber,
        "test_message" => $testMessage
    ]
], JSON_PRETTY_PRINT) . "\n\n";

// Include the SMS function
include_once 'sendSms.php';

echo "=== STARTING SMS TEST ===\n\n";

// Test 1: Check if function exists
echo "1. Checking if sendMessage function exists... ";
if (function_exists('sendMessage')) {
    echo "✓ PASS\n";
} else {
    echo "✗ FAIL - Function not found!\n";
    exit;
}

// Test 2: Check if cURL is enabled
echo "2. Checking if cURL is enabled... ";
if (function_exists('curl_init')) {
    echo "✓ PASS\n";
} else {
    echo "✗ FAIL - cURL not enabled in PHP!\n";
    exit;
}

// Test 3: Test endpoint connectivity
echo "3. Testing endpoint connectivity...\n";
$ch = curl_init($currentEndpoint);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 5,
    CURLOPT_NOBODY => true, // HEAD request
    CURLOPT_FOLLOWLOCATION => true,
]);
curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    echo "   ✗ FAIL - Connection error: $curlError\n";
    echo "   → The ngrok URL might have expired or changed\n";
    echo "   → Please update the URL in utility/sendSms.php\n\n";
} else {
    echo "   HTTP Status: $httpCode\n";
    if ($httpCode == 404 || $httpCode == 405) {
        echo "   ✓ Endpoint is reachable (expecting POST)\n";
    } elseif ($httpCode >= 500) {
        echo "   ⚠ Server error - endpoint might have issues\n";
    } else {
        echo "   ✓ Endpoint is reachable\n";
    }
}

// Test 4: Try sending actual SMS
echo "\n4. Attempting to send test SMS...\n";
$result = sendMessage($testNumber, $testMessage);

echo "   Response: " . json_encode($result) . "\n";

if (strpos($result, 'Error') !== false || strpos($result, 'error') !== false) {
    echo "   ✗ FAIL - SMS send failed\n";
    echo "\n=== TROUBLESHOOTING TIPS ===\n";
    echo "1. Check if ngrok tunnel is still running\n";
    echo "2. Verify the ngrok URL hasn't changed\n";
    echo "3. Check if the SMS backend server is running\n";
    echo "4. Verify your mobile number format (should start with 09)\n";
    echo "5. Check if the SMS provider has credits/balance\n";
} else {
    echo "   ✓ SUCCESS - SMS sent!\n";
    echo "   Check your phone: $testNumber\n";
}

echo "\n=== TEST COMPLETE ===\n\n";

// Additional diagnostic info
echo "=== SYSTEM INFO ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "cURL Version: " . curl_version()['version'] . "\n";
echo "Server Time: " . date('Y-m-d H:i:s') . "\n";

echo "\n=== CONFIGURATION ===\n";
echo "To update the SMS endpoint:\n";
echo "1. Get your new ngrok URL from the ngrok terminal\n";
echo "2. Edit: utility/sendSms.php\n";
echo "3. Update line 3 with new URL\n";
echo "4. Example: \$url = \"https://your-new-url.ngrok-free.dev/send_sms\";\n";
?>
