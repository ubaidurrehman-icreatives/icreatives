<?php
set_time_limit(0); // 0 means no time limit
require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";

// Set no time limit for the script
set_time_limit(0);

// Initialize Guzzle client
$client = new \GuzzleHttp\Client();

// API endpoint and token
$apiUrl = 'https://api.manatal.com/open/v3/contacts/';
$apiToken = '92e3967b096dc33e0f09df8c0a927ec0437d8942';
$pageSize = 99;
$outputFile = 'contacts.csv';

// Initialize CSV file
$csvFile = fopen($outputFile, 'w');
// Write the CSV header
fputcsv($csvFile, ['full_name', 'email', 'id']);

// Function to make API requests
function fetchContacts($client, $apiUrl, $apiToken, $page, $pageSize) {
    $response = $client->request('GET', $apiUrl, [
        'headers' => [
            'Authorization' => "Token $apiToken",
            'accept' => 'application/json',
        ],
        'query' => [
            'created_at__gte' => '2017-01-01',
            'page' => $page,
            'page_size' => $pageSize,
        ]
    ]);

    return json_decode($response->getBody(), true);
}

// Fetch the first page to determine the total count
$data = fetchContacts($client, $apiUrl, $apiToken, 1, $pageSize);
$totalCount = $data['count'];
$totalPages = ceil($totalCount / $pageSize);

// Loop through each page
for ($page = 1; $page <= $totalPages; $page++) {
    echo "Fetching page $page of $totalPages...\n";

    // Fetch the current page
    $data = fetchContacts($client, $apiUrl, $apiToken, $page, $pageSize);

    // Write data to CSV
    foreach ($data['results'] as $contact) {
        $fullName = $contact['full_name'] ?? '';
        $emailField = $contact['email'] ?? '';
        $id = $contact['id'] ?? '';

        // Extract the first email if there are multiple emails
        $emails = explode(',', $emailField);
        $email = trim($emails[0]);

        // Skip records with the specific email address
        if ($email === 'missing_contact_email@blindemail.com') {
            continue; // Skip to the next record
        }

        // Write the record to the CSV file
        fputcsv($csvFile, [$fullName, $email, $id]);
    }

    // Wait 5 seconds between requests to avoid API rate limits
    if ($page < $totalPages) {
        sleep(3);
    }
}

fclose($csvFile);

echo "Contacts successfully written to $outputFile\n";
?>
