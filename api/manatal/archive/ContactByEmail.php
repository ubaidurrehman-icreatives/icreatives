<?php

// Replace 'YOUR_API_KEY' with your actual API key from Manatal.com
$apiKey = $token;
$email = 'steven@cohen.email'; // Replace with the email address of the contact you want to find

// API endpoint URL
$apiUrl = 'https://api.manatal.com/v3/contacts';

// Set up the request parameters
$params = [
    'api_key' => $apiKey,
    'email' => $email,
];

// Create a URL-encoded query string from the parameters
$queryString = http_build_query($params);

// Final API URL with the query string
$requestUrl = $apiUrl . '?' . $queryString;

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $requestUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the cURL session and get the API response
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
}

// Close the cURL session
curl_close($ch);

// Parse the JSON response
$data = json_decode($response, true);

// Check if the API call was successful
if (isset($data['success']) && $data['success'] === true) {
    // Contact found
    $contact = $data['data'];
    // Handle the contact data as needed
    print_r($contact);
} else {
    // API call failed or contact not found
    echo 'Contact not found or API call failed.';
}
