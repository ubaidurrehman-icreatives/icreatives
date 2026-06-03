<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__DIR__) . '/../vendor/autoload.php';
require_once dirname(__DIR__) . '/../db/token.php';
require_once dirname(__DIR__) . '/../db/db.php';

set_time_limit(0);

// Ensure API token exists (supports different variable names)
$apiToken = $apiToken ?? $token ?? null;

if (!$apiToken) {
    die('API token not defined in token.php');
}

// Initialize Guzzle client
$client = new \GuzzleHttp\Client();

// API endpoints
$contactsApiUrl = 'https://api.manatal.com/open/v3/contacts/';
$usersApiUrl    = 'https://api.manatal.com/open/v3/users/';
$pageSize       = 99;

// CSV headers - forces download to local computer
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="contacts.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// Open output stream
$output = fopen('php://output', 'w');

// CSV header row
fputcsv($output, ['full_name', 'email', 'id', 'creator']);

/**
 * Generic paginated fetch
 */
function fetchPagedResults($client, $url, $apiToken, $page, $pageSize, $extraQuery = []) {
    $response = $client->request('GET', $url, [
        'headers' => [
            'Authorization' => "$apiToken",
            'accept'        => 'application/json',
        ],
        'query' => array_merge($extraQuery, [
            'page'      => $page,
            'page_size' => $pageSize,
        ])
    ]);

    return json_decode($response->getBody(), true);
}

/**
 * Build user lookup (ID => full name)
 */
function buildUserMap($client, $usersApiUrl, $apiToken, $pageSize = 99) {
    $userMap = [];

    $firstPage = fetchPagedResults($client, $usersApiUrl, $apiToken, 1, $pageSize);
    $totalCount = $firstPage['count'] ?? 0;
    $totalPages = max(1, ceil($totalCount / $pageSize));

    for ($page = 1; $page <= $totalPages; $page++) {
        $data = ($page === 1)
            ? $firstPage
            : fetchPagedResults($client, $usersApiUrl, $apiToken, $page, $pageSize);

        foreach ($data['results'] ?? [] as $user) {
            $userId = $user['id'] ?? null;

            $userName = trim(
                $user['full_name']
                ?? $user['display_name']
                ?? $user['email']
                ?? ''
            );

            if ($userId !== null) {
                $userMap[$userId] = $userName;
            }
        }

        if ($page < $totalPages) {
            sleep(1);
        }
    }

    return $userMap;
}

/**
 * Fetch contacts
 */
function fetchContacts($client, $contactsApiUrl, $apiToken, $page, $pageSize) {
    return fetchPagedResults(
        $client,
        $contactsApiUrl,
        $apiToken,
        $page,
        $pageSize,
        [
            'created_at__gte' => '2017-01-01',
        ]
    );
}

try {
    // Build user lookup once
    $userMap = buildUserMap($client, $usersApiUrl, $apiToken, $pageSize);

    // Get first page
    $data = fetchContacts($client, $contactsApiUrl, $apiToken, 1, $pageSize);
    $totalCount = $data['count'] ?? 0;
    $totalPages = max(1, ceil($totalCount / $pageSize));

    for ($page = 1; $page <= $totalPages; $page++) {

        if ($page > 1) {
            $data = fetchContacts($client, $contactsApiUrl, $apiToken, $page, $pageSize);
        }

        foreach ($data['results'] ?? [] as $contact) {

            $fullName   = $contact['full_name'] ?? '';
            $emailField = $contact['email'] ?? '';
            $id         = $contact['id'] ?? '';
            $creatorId  = $contact['creator'] ?? null;

            // Convert creator ID to name (blank if empty or not found)
            $creatorName = '';
            if ($creatorId && isset($userMap[$creatorId])) {
                $creatorName = $userMap[$creatorId];
            }

            // Use first non-placeholder email from comma-separated list
            $email = '';
            $emails = array_map('trim', explode(',', (string)$emailField));

            foreach ($emails as $candidateEmail) {
                if ($candidateEmail !== '' && strcasecmp($candidateEmail, 'missing_contact_email@blindemail.com') !== 0) {
                    $email = $candidateEmail;
                    break;
                }
            }

            // Skip only if no usable email found
            if ($email === '') {
                continue;
            }

            fputcsv($output, [$fullName, $email, $id, $creatorName]);
        }

        if ($page < $totalPages) {
            sleep(3);
        }
    }

} catch (\Exception $e) {
    fputcsv($output, ['ERROR', $e->getMessage()]);
}

fclose($output);
exit;
?>