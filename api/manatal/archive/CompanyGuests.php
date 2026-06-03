<?php
// require 'vendor/autoload.php';
// require_once('vendor/autoload.php');
require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";


// $client = new \GuzzleHttp\Client();

  //  'Authorization' => $token,

// Include Composer's autoloader


use GuzzleHttp\Client;

// Replace 'YOUR_API_KEY' with your actual Manatal API key
$apiKey = $token;


// Base API URL for Manatal API v3.0
$apiBaseUrl = 'https://api.manatal.com/api/v3/';

// Organization ID of the organization you want to retrieve candidates for
$organizationId = '100541';

// Initialize Guzzle HTTP client
$client = new Client([
    'base_uri' => $apiBaseUrl,
    'headers' => [
        'Authorization' => 'Bearer ' . $apiKey,
    ],
]);

// API endpoint for getting candidates for the organization
$endpoint = 'candidates';

// Parameters for filtering candidates by organization
$params = [
    'query' => [
        'organization' => $organizationId,
    ],
];

try {
    // Send GET request to the Manatal API
    $response = $client->request('GET', $endpoint, $params);

    // Get the response body
    $responseBody = $response->getBody()->getContents();

    // Parse the JSON response into an array
    $candidates = json_decode($responseBody, true);

    // Now, $candidates contains the array of candidates for the specified organization
    // You can loop through the $candidates array to access candidate information
    foreach ($candidates as $candidate) {
        // Access candidate data, e.g., $candidate['id'], $candidate['name'], etc.
        // ...
    }
} catch (Exception $e) {
    // Handle any errors that occur during the API request
    echo 'Error: ' . $e->getMessage();
}

?>