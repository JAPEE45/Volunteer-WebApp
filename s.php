<?php
// **IMPORTANT SECURITY NOTE:** // NEVER hardcode sensitive data like API codes or passwords directly into files. 
// Use Environment Variables or a configuration system instead.
// I'm using placeholders here to show you the format.

$endpoint = 'https://api.itexmo.com/api/message/itexmo'; // Standard message sending endpoint
$apicode  = 'YOUR_API_CODE_HERE'; // Replace with your actual ITEXMO API Code
$number   = '0917xxxxxxx'; // The recipient's number
$message  = 'Hello! This is a test from my new API project.';

// 1. Prepare the POST data array
$fields = array(
    'apicode' => $apicode,
    'number'  => $number,
    'message' => $message,
    // Add other fields like 'senderid' if you want a custom sender name
);

// 2. Initialize cURL
$ch = curl_init($endpoint);

// 3. Set cURL options for a POST request
curl_setopt($ch, CURLOPT_POST, true); // Set as POST request
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields)); // Encode data for POST
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string

// 4. Execute the cURL request
$response = curl_exec($ch);

// 5. Check for errors
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
} else {
    // 6. Print the response from the ITEXMO API (e.g., a status code)
    echo "ITEXMO Response: " . $response;
}

curl_close($ch);
?>