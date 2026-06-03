<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
set_time_limit(0);
date_default_timezone_set('America/New_York');

$autoloadCandidates = [
    __DIR__ . '/vendor/autoload.php',
    dirname(__DIR__) . '/vendor/autoload.php',
    dirname(__DIR__) . '/../vendor/autoload.php',
];

$autoloadLoaded = false;
foreach ($autoloadCandidates as $autoloadFile) {
    if (file_exists($autoloadFile)) {
        require_once $autoloadFile;
        $autoloadLoaded = true;
        break;
    }
}

if (!$autoloadLoaded) {
    die('Could not find vendor/autoload.php. Update the autoload path near the top of this file.');
}

require_once dirname(__DIR__) . '/db/token.php';
require_once dirname(__DIR__) . '/db/db.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

const CM_CLIENT_ID   = '31634bafba7c0d7fe39d65d9b2b9a1aa';
const CM_API_KEY     = '0UF2JJXSzbdGnZ8Bx2ZJUvceNVokvBLgT2r01xsYbaE5G3iXxPBUKJj/ylJUB8MST492OvxcHybweJhVSMWjmZVq+LqlZobGGm5pXTucpXG9HKkD+KFKANolIMnvS0dttK0ez4L8PtCcw+mrDfZHpg==';
const CAMPAIGNS_TO_SHOW = 10;
const PAGE_SIZE = 1000;
const CONTACT_PAGE_SIZE = 99;
const REQUEST_TIMEOUT = 120;
const MANATAL_PATCH_REQUESTS_PER_MINUTE = 2;
const CACHE_PAGE_SLEEP_SECONDS = 3;
const CF_OPENS        = 'opens';
const CF_CLICKS       = 'clicks';
const CF_UNSUBSCRIBE  = 'unsubscribe';
const CF_CAMPAIGNDATE = 'campaigndate';
const CF_CAMPAIGNNAME = 'campaignname';
const DEFAULT_TEST_EMAIL = 'peter.pp@blindemail.com';
const CACHE_TABLE = 'cm_manatal_contact_cache';
const PLACEHOLDER_EMAIL = 'missing_contact_email@blindemail.com';
 
$apiToken = $apiToken ?? $token ?? null;
if (!$apiToken) {
    die('API token not defined in token.php');
}

/** @var mysqli $db */
$db = db();
if (!$db instanceof mysqli) {
    die('db() did not return a mysqli connection.');
}
$db->set_charset('utf8mb4');

$minSecondsPerPatchRequest = 60 / MANATAL_PATCH_REQUESTS_PER_MINUTE;
$perContactSeconds = $minSecondsPerPatchRequest; // sync only patches Manatal

$cm = new Client([
    'base_uri' => 'https://api.createsend.com/api/v3.3/',
    'auth'     => [CM_API_KEY, 'x'],
    'headers'  => ['Accept' => 'application/json'],
    'timeout'  => REQUEST_TIMEOUT,
]);

$manatal = new Client([
    'base_uri' => 'https://api.manatal.com/open/v3/',
    'headers'  => [
        'Authorization' => (strpos((string)$apiToken, 'Token ') === 0) ? (string)$apiToken : 'Token ' . (string)$apiToken,
        'Accept'        => 'application/json',
        'Content-Type'  => 'application/json',
        'Connection'    => 'close',
    ],
    'timeout' => REQUEST_TIMEOUT,
    'connect_timeout' => 3.0,
    'curl' => [
        CURLOPT_MAXCONNECTS => 1,
		CURLOPT_FORBID_REUSE => true,
        CURLOPT_FRESH_CONNECT => true,
    ],
]);

function h($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function normalizeEmail(string $email): string
{
    return strtolower(trim($email));
}

function normalizeDate(?string $value): string
{
    if (!$value) {
        return date('Y-m-d');
    }
    $ts = strtotime($value);
    return $ts === false ? date('Y-m-d') : date('Y-m-d', $ts);
}

function jsonResponse(array $data): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_SLASHES);
    exit;
}

function ensureCacheTable(mysqli $db): void
{
    $sql = "CREATE TABLE IF NOT EXISTS `" . CACHE_TABLE . "` (
        `email` varchar(255) NOT NULL,
        `manatal_contact_id` int NOT NULL,
        `full_name` varchar(255) DEFAULT NULL,
        `display_name` varchar(255) DEFAULT NULL,
        `organization_id` int DEFAULT NULL,
        `custom_fields_json` mediumtext DEFAULT NULL,
        `creator` int DEFAULT NULL,
        `manatal_updated_at` varchar(64) DEFAULT NULL,
        `refreshed_at` datetime NOT NULL,
        PRIMARY KEY (`email`),
        KEY `idx_manatal_contact_id` (`manatal_contact_id`),
        KEY `idx_refreshed_at` (`refreshed_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if (!$db->query($sql)) {
        throw new RuntimeException('Could not create cache table: ' . $db->error);
    }
}

function cmGet(Client $client, string $uri, array $query = []): array
{
    $response = $client->get($uri, ['query' => $query]);
    $json = json_decode((string)$response->getBody(), true);
    return is_array($json) ? $json : [];
}

function manatalGet(Client $client, string $uri, array $query = []): array
{
    $response = $client->get($uri, ['query' => $query]);
    $json = json_decode((string)$response->getBody(), true);
    return is_array($json) ? $json : [];
}

function manatalPatch(Client $client, string $uri, array $body): array
{
    $response = $client->patch($uri, ['json' => $body]);
    $json = json_decode((string)$response->getBody(), true);
    return is_array($json) ? $json : [];
}

function getThrottleWaitSeconds(Throwable $e): ?float
{
    $response = method_exists($e, 'getResponse') ? $e->getResponse() : null;
    if (!$response || (int)$response->getStatusCode() !== 429) {
        return null;
    }

    $retryAfter = $response->getHeaderLine('Retry-After');
    if ($retryAfter !== '' && is_numeric($retryAfter)) {
        return max(1.0, (float)$retryAfter);
    }

    $body = (string)$response->getBody();
    if (preg_match('/available in\s+(\d+)\s+second/i', $body, $m)) {
        return max(1.0, (float)$m[1]);
    }

    return 2.0;
}

class ManatalThrottleException extends RuntimeException
{
    public float $waitSeconds;

    public function __construct(float $waitSeconds, string $message = 'Manatal rate limit reached')
    {
        parent::__construct($message);
        $this->waitSeconds = $waitSeconds;
    }
}

function sleepSeconds(float $seconds): void
{
    if ($seconds <= 0) {
        return;
    }
    usleep((int)round($seconds * 1000000));
}

function getRecentCampaigns(Client $cm, string $clientId, int $limit = CAMPAIGNS_TO_SHOW): array
{
    $data = cmGet($cm, "clients/{$clientId}/campaigns.json", [
        'sent' => 'true',
        'page' => 1,
        'pagesize' => $limit,
    ]);
    $raw = $data['Results'] ?? $data['results'] ?? $data;
    if (!is_array($raw)) {
        return [];
    }

    $campaigns = [];
    foreach ($raw as $row) {
        if (!is_array($row)) {
            continue;
        }
        $id = (string)($row['CampaignID'] ?? $row['CampaignId'] ?? $row['campaignid'] ?? $row['campaign_id'] ?? '');
        if ($id === '') {
            continue;
        }
        $campaigns[] = [
            'id' => $id,
            'name' => (string)($row['Name'] ?? $row['name'] ?? 'Untitled Campaign'),
            'sent_date' => (string)($row['SentDate'] ?? $row['sent_date'] ?? $row['Date'] ?? ''),
        ];
    }
    return $campaigns;
}

function getCampaignListsAndSegments(Client $cm, string $campaignId): array
{
    $data = cmGet($cm, "campaigns/{$campaignId}/listsandsegments.json");
    $lists = $data['Lists'] ?? $data['lists'] ?? [];
    $normalizedLists = [];
    foreach ($lists as $row) {
        if (!is_array($row)) {
            continue;
        }
        $normalizedLists[] = [
            'list_id' => (string)($row['ListID'] ?? $row['ListId'] ?? $row['listid'] ?? $row['list_id'] ?? ''),
            'name' => (string)($row['Name'] ?? $row['name'] ?? 'Unnamed List'),
        ];
    }
    return ['lists' => $normalizedLists];
}

function getCampaignSummary(Client $cm, string $campaignId): array
{
    return cmGet($cm, "campaigns/{$campaignId}/summary.json");
}

function getCampaignEventMap(Client $cm, string $campaignId, string $endpoint): array
{
    $map = [];
    $page = 1;
    do {
        $data = cmGet($cm, "campaigns/{$campaignId}/{$endpoint}.json", [
            'page' => $page,
            'pagesize' => PAGE_SIZE,
        ]);
        $results = $data['Results'] ?? $data['results'] ?? [];
        foreach ($results as $row) {
            if (!is_array($row)) {
                continue;
            }
            $email = normalizeEmail((string)($row['EmailAddress'] ?? $row['email'] ?? ''));
            if ($email === '') {
                continue;
            }
            $map[$email] = ($map[$email] ?? 0) + 1;
        }
        $pages = (int)($data['NumberOfPages'] ?? $data['number_of_pages'] ?? 1);
        $pages = max(1, $pages);
        $page++;
    } while ($page <= $pages);
    return $map;
}

function getCampaignRecipients(Client $cm, string $campaignId): array
{
    $rows = [];
    $page = 1;
    do {
        $data = cmGet($cm, "campaigns/{$campaignId}/recipients.json", [
            'page' => $page,
            'pagesize' => PAGE_SIZE,
            'orderfield' => 'email',
            'orderdirection' => 'asc',
        ]);
        $results = $data['Results'] ?? $data['results'] ?? [];
        foreach ($results as $row) {
            if (!is_array($row)) {
                continue;
            }
            $email = normalizeEmail((string)($row['EmailAddress'] ?? $row['email'] ?? ''));
            if ($email === '') {
                continue;
            }
            $rows[$email] = [
                'email' => $email,
                'list_id' => (string)($row['ListID'] ?? $row['ListId'] ?? $row['listid'] ?? $row['list_id'] ?? ''),
                'name' => (string)($row['Name'] ?? $row['name'] ?? ''),
            ];
        }
        $pages = (int)($data['NumberOfPages'] ?? $data['number_of_pages'] ?? 1);
        $pages = max(1, $pages);
        $page++;
    } while ($page <= $pages);
    return array_values($rows);
}

function buildSelectedAudience(Client $cm, string $campaignId, string $selectedListId): array
{
    $summary = getCampaignSummary($cm, $campaignId);
    $recipients = getCampaignRecipients($cm, $campaignId);
    $opens = getCampaignEventMap($cm, $campaignId, 'opens');
    $clicks = getCampaignEventMap($cm, $campaignId, 'clicks');
    $unsubs = getCampaignEventMap($cm, $campaignId, 'unsubscribes');

    $campaignName = (string)($summary['Name'] ?? $summary['name'] ?? 'Untitled Campaign');
    $campaignDate = normalizeDate((string)($summary['SentDate'] ?? $summary['sent_date'] ?? $summary['Date'] ?? ''));

    $audience = [];
    foreach ($recipients as $recipient) {
        $listId = (string)($recipient['list_id'] ?? '');
        if ($selectedListId !== '' && $listId !== $selectedListId) {
            continue;
        }
        $email = $recipient['email'];
        $audience[] = [
            'email' => $email,
            'name' => (string)($recipient['name'] ?? ''),
            'list_id' => $listId,
            'campaignname' => $campaignName,
            'campaigndate' => $campaignDate,
            'opens' => (int)($opens[$email] ?? 0),
            'clicks' => (int)($clicks[$email] ?? 0),
            'unsubscribe' => isset($unsubs[$email]),
        ];
    }

    usort($audience, fn($a, $b) => strcmp($a['email'], $b['email']));
    return $audience;
}

function firstUsableEmail(string $emailField): string
{
    $emails = array_map('trim', explode(',', $emailField));
    foreach ($emails as $candidateEmail) {
        if ($candidateEmail === '') {
            continue;
        }
        if (strcasecmp($candidateEmail, PLACEHOLDER_EMAIL) === 0) {
            continue;
        }
        return normalizeEmail($candidateEmail);
    }
    return '';
}

function cacheUpsertContact(mysqli $db, array $contact): void
{
    $email = firstUsableEmail((string)($contact['email'] ?? ''));
    if ($email === '') {
        return;
    }

    $stmt = $db->prepare(
        "INSERT INTO `" . CACHE_TABLE . "`
        (`email`, `manatal_contact_id`, `full_name`, `display_name`, `organization_id`, `custom_fields_json`, `creator`, `manatal_updated_at`, `refreshed_at`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE
            `manatal_contact_id` = VALUES(`manatal_contact_id`),
            `full_name` = VALUES(`full_name`),
            `display_name` = VALUES(`display_name`),
            `organization_id` = VALUES(`organization_id`),
            `custom_fields_json` = VALUES(`custom_fields_json`),
            `creator` = VALUES(`creator`),
            `manatal_updated_at` = VALUES(`manatal_updated_at`),
            `refreshed_at` = NOW()"
    );
    if (!$stmt) {
        throw new RuntimeException('Prepare failed: ' . $db->error);
    }

    $contactId = (int)($contact['id'] ?? 0);
    $fullName = (string)($contact['full_name'] ?? '');
    $displayName = (string)($contact['display_name'] ?? '');
    $organizationId = isset($contact['organization']) && is_numeric((string)$contact['organization']) ? (int)$contact['organization'] : null;
    $customFieldsJson = json_encode($contact['custom_fields'] ?? [], JSON_UNESCAPED_SLASHES);
    $creator = isset($contact['creator']) && is_numeric((string)$contact['creator']) ? (int)$contact['creator'] : null;
    $updatedAt = (string)($contact['updated_at'] ?? '');

    $stmt->bind_param('sissiiss', $email, $contactId, $fullName, $displayName, $organizationId, $customFieldsJson, $creator, $updatedAt);
    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        throw new RuntimeException('Execute failed: ' . $err);
    }
    $stmt->close();
}

function fetchContactsPage(Client $client, string $apiToken, int $page, int $pageSize): array
{
    return manatalGet($client, 'contacts/', [
        'page' => $page,
        'page_size' => $pageSize,
        'created_at__gte' => '2017-01-01',
    ]);
}

function stateDir(): string
{
    $dir = sys_get_temp_dir() . '/cm_manatal_sync_state';
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    return $dir;
}

function stateFile(string $jobId): string
{
    return stateDir() . '/job_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $jobId) . '.json';
}

function saveState(string $jobId, array $state): void
{
    file_put_contents(stateFile($jobId), json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
}

function loadState(string $jobId): ?array
{
    $file = stateFile($jobId);
    if (!is_file($file)) {
        return null;
    }
    $json = json_decode((string)file_get_contents($file), true);
    return is_array($json) ? $json : null;
}

function deleteState(string $jobId): void
{
    $file = stateFile($jobId);
    if (is_file($file)) {
        @unlink($file);
    }
}

function cacheRefreshStateFile(): string
{
    return stateDir() . '/cache_refresh_state.json';
}

function saveCacheRefreshState(array $state): void
{
    file_put_contents(cacheRefreshStateFile(), json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
}

function loadCacheRefreshState(): ?array
{
    $file = cacheRefreshStateFile();
    if (!is_file($file)) {
        return null;
    }
    $json = json_decode((string)file_get_contents($file), true);
    return is_array($json) ? $json : null;
}

function deleteCacheRefreshState(): void
{
    $file = cacheRefreshStateFile();
    if (is_file($file)) {
        @unlink($file);
    }
}

function estimateDurationText(int $contacts, float $secondsPerContact): string
{
    $seconds = (int)ceil($contacts * $secondsPerContact);
    $hours = intdiv($seconds, 3600);
    $minutes = intdiv($seconds % 3600, 60);
    $secs = $seconds % 60;
    $parts = [];
    if ($hours > 0) { $parts[] = $hours . 'h'; }
    if ($minutes > 0) { $parts[] = $minutes . 'm'; }
    if ($secs > 0 || !$parts) { $parts[] = $secs . 's'; }
    return implode(' ', $parts);
}

function getCacheStats(mysqli $db): array
{
    $result = $db->query("SELECT COUNT(*) AS cnt, MAX(refreshed_at) AS latest FROM `" . CACHE_TABLE . "`");
    if (!$result) {
        return ['count' => 0, 'latest' => null];
    }
    $row = $result->fetch_assoc() ?: ['cnt' => 0, 'latest' => null];
    $result->free();
    return ['count' => (int)($row['cnt'] ?? 0), 'latest' => $row['latest'] ?? null];
}

function findCachedContact(mysqli $db, string $email): ?array
{
    $stmt = $db->prepare("SELECT manatal_contact_id, custom_fields_json FROM `" . CACHE_TABLE . "` WHERE email = ? LIMIT 1");
    if (!$stmt) {
        throw new RuntimeException('Prepare failed: ' . $db->error);
    }
    $stmt->bind_param('s', $email);
    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        throw new RuntimeException('Execute failed: ' . $err);
    }
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    $stmt->close();
    return $row ?: null;
}

function updateCachedCustomFields(mysqli $db, string $email, array $customFields): void
{
    $json = json_encode($customFields, JSON_UNESCAPED_SLASHES);
    $stmt = $db->prepare("UPDATE `" . CACHE_TABLE . "` SET custom_fields_json = ?, refreshed_at = NOW() WHERE email = ?");
    if (!$stmt) {
        throw new RuntimeException('Prepare failed: ' . $db->error);
    }
    $stmt->bind_param('ss', $json, $email);
    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        throw new RuntimeException('Execute failed: ' . $err);
    }
    $stmt->close();
}

ensureCacheTable($db);

$action = $_POST['ajax_action'] ?? $_GET['ajax_action'] ?? '';

if ($action === 'start_cache_refresh') {
    try {
        $truncateFirst = !empty($_POST['truncate_cache']);
        if ($truncateFirst) {
            if (!$db->query("TRUNCATE TABLE `" . CACHE_TABLE . "`")) {
                throw new RuntimeException('Could not clear cache table: ' . $db->error);
            }
        }

        $firstPage = fetchContactsPage($manatal, (string)$apiToken, 1, CONTACT_PAGE_SIZE);
        $count = (int)($firstPage['count'] ?? 0);
        $totalPages = max(1, (int)ceil($count / CONTACT_PAGE_SIZE));

        $state = [
            'status' => 'running',
            'started_at' => time(),
            'finished_at' => null,
            'truncate_first' => $truncateFirst,
            'current_page' => 1,
            'total_pages' => $totalPages,
            'total_contacts' => $count,
            'cached_rows' => 0,
            'page_cached_rows' => 0,
            'logs' => ['[' . date('H:i:s') . '] Cache refresh initialized.'],
            'next_allowed_at' => microtime(true),
            'first_page_data' => $firstPage,
        ];
        saveCacheRefreshState($state);

        jsonResponse([
            'ok' => true,
            'state' => $state,
            'stats' => getCacheStats($db),
        ]);
    } catch (Throwable $e) {
        jsonResponse(['ok' => false, 'message' => 'Could not start cache refresh: ' . $e->getMessage()]);
    }
}

if ($action === 'cache_refresh_step') {
    try {
        $state = loadCacheRefreshState();
        if (!$state) {
            jsonResponse(['ok' => false, 'message' => 'Cache refresh job not found.']);
        }
        if (($state['status'] ?? '') === 'done') {
            jsonResponse(['ok' => true, 'state' => $state, 'stats' => getCacheStats($db)]);
        }

        $now = microtime(true);
        if ($now < (float)($state['next_allowed_at'] ?? 0)) {
            $wait = (float)$state['next_allowed_at'] - $now;
            jsonResponse(['ok' => true, 'wait' => $wait, 'state' => $state, 'stats' => getCacheStats($db)]);
        }

        $page = (int)($state['current_page'] ?? 1);
        $totalPages = (int)($state['total_pages'] ?? 1);
        if ($page > $totalPages) {
            $state['status'] = 'done';
            $state['finished_at'] = time();
            saveCacheRefreshState($state);
            jsonResponse(['ok' => true, 'state' => $state, 'stats' => getCacheStats($db)]);
        }

        $data = ($page === 1 && !empty($state['first_page_data']) && is_array($state['first_page_data']))
            ? $state['first_page_data']
            : fetchContactsPage($manatal, (string)$apiToken, $page, CONTACT_PAGE_SIZE);

        $pageCachedRows = 0;
        foreach (($data['results'] ?? []) as $contact) {
            if (!is_array($contact)) {
                continue;
            }
            $email = firstUsableEmail((string)($contact['email'] ?? ''));
            if ($email === '') {
                continue;
            }
            cacheUpsertContact($db, $contact);
            $pageCachedRows++;
        }

        $state['cached_rows'] = (int)($state['cached_rows'] ?? 0) + $pageCachedRows;
        $state['page_cached_rows'] = $pageCachedRows;
        $state['logs'][] = '[' . date('H:i:s') . '] Cached page ' . $page . ' of ' . $totalPages . ' (' . $pageCachedRows . ' usable contacts).';
        if (count($state['logs']) > 200) {
            $state['logs'] = array_slice($state['logs'], -200);
        }

        $state['current_page'] = $page + 1;
        unset($state['first_page_data']);
        if ($page >= $totalPages) {
            $state['status'] = 'done';
            $state['finished_at'] = time();
            $state['next_allowed_at'] = microtime(true);
            $state['logs'][] = '[' . date('H:i:s') . '] Cache refresh complete.';
        } else {
            $state['next_allowed_at'] = microtime(true) + CACHE_PAGE_SLEEP_SECONDS;
        }

        saveCacheRefreshState($state);
        jsonResponse(['ok' => true, 'state' => $state, 'stats' => getCacheStats($db)]);
    } catch (Throwable $e) {
        jsonResponse(['ok' => false, 'message' => 'Cache refresh failed: ' . $e->getMessage()]);
    }
}

if ($action === 'cancel_cache_refresh') {
    deleteCacheRefreshState();
    jsonResponse(['ok' => true]);
}

if ($action === 'start_sync') {
    try {
        $campaignId = trim((string)($_POST['campaign_id'] ?? ''));
        $listId = trim((string)($_POST['list_id'] ?? ''));
        $testMode = !empty($_POST['test_mode']);
        $testEmail = normalizeEmail((string)($_POST['test_email'] ?? DEFAULT_TEST_EMAIL));
        $startRecord = max(1, (int)($_POST['start_record'] ?? 1));
        $startEmail = normalizeEmail((string)($_POST['start_email'] ?? ''));

        if ($campaignId === '') {
            jsonResponse(['ok' => false, 'message' => 'Choose a campaign first.']);
        }

        $audience = buildSelectedAudience($cm, $campaignId, $listId);
        if ($testMode) {
            $audience = array_values(array_filter($audience, fn($row) => $row['email'] === $testEmail));
        }

        if ($startEmail !== '') {
            $found = false;
            $filtered = [];
            foreach ($audience as $row) {
                if (!$found && strcmp($row['email'], $startEmail) >= 0) {
                    $found = true;
                }
                if ($found) {
                    $filtered[] = $row;
                }
            }
            $audience = $filtered;
        } elseif ($startRecord > 1) {
            $audience = array_slice($audience, $startRecord - 1);
        }

        if (!$audience) {
            jsonResponse(['ok' => false, 'message' => 'No recipients matched the selected campaign/list/start position/test-email.']);
        }

        $jobId = bin2hex(random_bytes(16));
        $state = [
            'job_id' => $jobId,
            'created_at' => time(),
            'started_at' => time(),
            'finished_at' => null,
            'status' => 'running',
            'campaign_id' => $campaignId,
            'list_id' => $listId,
            'test_mode' => $testMode,
            'test_email' => $testEmail,
            'start_record' => $startRecord,
            'start_email' => $startEmail,
            'cursor' => 0,
            'total' => count($audience),
            'processed' => 0,
            'updated' => 0,
            'no_match' => 0,
            'failed' => 0,
            'logs' => [],
            'current_email' => '',
            'next_allowed_at' => microtime(true),
            'audience' => $audience,
        ];
        saveState($jobId, $state);

        jsonResponse([
            'ok' => true,
            'job_id' => $jobId,
            'total' => count($audience),
            'eta_text' => estimateDurationText(count($audience), $perContactSeconds),
            'message' => 'Sync initialized.',
        ]);
    } catch (Throwable $e) {
        jsonResponse(['ok' => false, 'message' => 'Could not start sync: ' . $e->getMessage()]);
    }
}

if ($action === 'sync_step') {
    try {
        $jobId = trim((string)($_POST['job_id'] ?? $_GET['job_id'] ?? ''));
        if ($jobId === '') {
            jsonResponse(['ok' => false, 'message' => 'Missing job ID.']);
        }
        $state = loadState($jobId);
        if (!$state) {
            jsonResponse(['ok' => false, 'message' => 'Sync job not found.']);
        }
        if (($state['status'] ?? '') === 'done') {
            jsonResponse(['ok' => true, 'state' => $state]);
        }

        $now = microtime(true);
        if ($now < (float)($state['next_allowed_at'] ?? 0)) {
            $wait = (float)$state['next_allowed_at'] - $now;
            jsonResponse(['ok' => true, 'wait' => $wait, 'state' => $state]);
        }

        $idx = (int)($state['cursor'] ?? 0);
        $audience = $state['audience'] ?? [];
        if (!isset($audience[$idx])) {
            $state['status'] = 'done';
            $state['finished_at'] = time();
            saveState($jobId, $state);
            jsonResponse(['ok' => true, 'state' => $state]);
        }

        $row = $audience[$idx];
        $email = (string)$row['email'];
        $state['current_email'] = $email;
        $logLine = '';

        try {
            $cached = findCachedContact($db, $email);
            if (!$cached || empty($cached['manatal_contact_id'])) {
                $state['no_match']++;
                $logLine = 'No local cache match: ' . $email;
            } else {
                $contactId = (int)$cached['manatal_contact_id'];
                $existingCustomFields = json_decode((string)($cached['custom_fields_json'] ?? '{}'), true);
                if (!is_array($existingCustomFields)) {
                    $existingCustomFields = [];
                }

                $existingCustomFields[CF_OPENS] = (string)$row['opens'];
                $existingCustomFields[CF_CLICKS] = (string)$row['clicks'];
                $existingCustomFields[CF_UNSUBSCRIBE] = (bool)$row['unsubscribe'];
                $existingCustomFields[CF_CAMPAIGNDATE] = (string)$row['campaigndate'];
                $existingCustomFields[CF_CAMPAIGNNAME] = (string)$row['campaignname'];

                try {
                    manatalPatch($manatal, 'contacts/' . $contactId . '/', [
                        'custom_fields' => $existingCustomFields,
                    ]);
                } catch (Throwable $e) {
                    $wait = getThrottleWaitSeconds($e);
                    if ($wait !== null) {
                        $state['next_allowed_at'] = microtime(true) + $wait;
                        $state['logs'][] = '[' . date('H:i:s') . '] Throttled by Manatal while processing ' . $email . '. Waiting ' . rtrim(rtrim(number_format($wait, 1, '.', ''), '0'), '.') . 's before retrying.';
                        if (count($state['logs']) > 200) {
                            $state['logs'] = array_slice($state['logs'], -200);
                        }
                        saveState($jobId, $state);
                        jsonResponse(['ok' => true, 'wait' => $wait, 'state' => $state]);
                    }
                    throw $e;
                }

                updateCachedCustomFields($db, $email, $existingCustomFields);
                $state['updated']++;
                $logLine = 'Updated: ' . $email;
            }
        } catch (Throwable $e) {
            $state['failed']++;
            $logLine = 'Failed: ' . $email . ' | ' . $e->getMessage();
        }

        $state['processed']++;
        $state['cursor'] = $idx + 1;
        $state['logs'][] = '[' . date('H:i:s') . '] ' . $logLine;
        if (count($state['logs']) > 200) {
            $state['logs'] = array_slice($state['logs'], -200);
        }
        $state['next_allowed_at'] = microtime(true) + $minSecondsPerPatchRequest;
        if ($state['processed'] >= $state['total']) {
            $state['status'] = 'done';
            $state['finished_at'] = time();
            $state['current_email'] = '';
        }
        saveState($jobId, $state);
        jsonResponse(['ok' => true, 'state' => $state]);
    } catch (Throwable $e) {
        jsonResponse(['ok' => false, 'message' => 'Step failed: ' . $e->getMessage()]);
    }
}

if ($action === 'cancel_sync') {
    $jobId = trim((string)($_POST['job_id'] ?? ''));
    if ($jobId !== '') {
        deleteState($jobId);
    }
    jsonResponse(['ok' => true]);
}

$campaigns = [];
$campaignLists = [];
$selectedCampaignId = trim((string)($_POST['campaign_id'] ?? ''));
$selectedListId = trim((string)($_POST['list_id'] ?? ''));
$testMode = !empty($_POST['test_mode']);
$testEmail = (string)($_POST['test_email'] ?? DEFAULT_TEST_EMAIL);
$startRecord = max(1, (int)($_POST['start_record'] ?? 1));
$startEmail = (string)($_POST['start_email'] ?? '');
$previewError = '';
$previewData = null;
$cacheStats = getCacheStats($db);

try {
    $campaigns = getRecentCampaigns($cm, CM_CLIENT_ID);
    if ($selectedCampaignId !== '') {
        $campaignLists = getCampaignListsAndSegments($cm, $selectedCampaignId)['lists'];
    }
    if (isset($_POST['preview'])) {
        if ($selectedCampaignId === '') {
            $previewError = 'Please choose a campaign.';
        } else {
            $audience = buildSelectedAudience($cm, $selectedCampaignId, $selectedListId);
            if ($testMode) {
                $audience = array_values(array_filter($audience, fn($row) => $row['email'] === normalizeEmail($testEmail)));
            }
            if (!$testMode) {
                if ($startEmail !== '') {
                    $found = false;
                    $filtered = [];
                    foreach ($audience as $row) {
                        if (!$found && strcmp($row['email'], normalizeEmail($startEmail)) >= 0) {
                            $found = true;
                        }
                        if ($found) {
                            $filtered[] = $row;
                        }
                    }
                    $audience = $filtered;
                } elseif ($startRecord > 1) {
                    $audience = array_slice($audience, $startRecord - 1);
                }
            }

            $cacheMatches = 0;
            foreach ($audience as $row) {
                if (findCachedContact($db, (string)$row['email'])) {
                    $cacheMatches++;
                }
            }

            $previewData = [
                'total' => count($audience),
                'cache_matches' => $cacheMatches,
                'sample' => array_slice($audience, 0, 15),
                'eta_text' => estimateDurationText($cacheMatches, $perContactSeconds),
                'requests_per_minute' => MANATAL_PATCH_REQUESTS_PER_MINUTE,
                'worst_case_requests' => $cacheMatches,
            ];
        }
    }
} catch (Throwable $e) {
    $previewError = $e->getMessage();
}
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Campaign Monitor → Manatal Cached Sync</title>
<style>
:root{--bg:#f5f7fb;--card:#fff;--text:#1f2937;--muted:#6b7280;--line:#e5e7eb;--primary:#1d4ed8;--primary2:#2563eb;--green:#15803d;--red:#b91c1c;--amber:#b45309}
*{box-sizing:border-box} body{margin:0;font-family:Arial,Helvetica,sans-serif;background:var(--bg);color:var(--text)}
.wrap{max-width:1180px;margin:0 auto;padding:24px}.hero{background:linear-gradient(135deg,var(--primary),var(--primary2));color:#fff;border-radius:18px;padding:24px 28px;box-shadow:0 10px 30px rgba(37,99,235,.18)}
.hero h1{margin:0 0 8px;font-size:28px}.hero p{margin:0;opacity:.95}.grid{display:grid;grid-template-columns:repeat(12,1fr);gap:18px;margin-top:20px}.card{background:var(--card);border:1px solid var(--line);border-radius:18px;padding:20px;box-shadow:0 8px 24px rgba(15,23,42,.05)}
.col-7{grid-column:span 7}.col-5{grid-column:span 5}.col-12{grid-column:span 12}label{display:block;font-weight:700;font-size:14px;margin-bottom:8px}input[type=text],input[type=number],select{width:100%;padding:12px 14px;border:1px solid #cbd5e1;border-radius:12px;font-size:15px;background:#fff}
.row{display:grid;grid-template-columns:1fr 1fr;gap:14px}.checkrow{display:flex;align-items:center;gap:10px;margin-top:14px}.btns{display:flex;gap:12px;flex-wrap:wrap;margin-top:18px}button{border:0;border-radius:12px;padding:12px 18px;font-weight:700;cursor:pointer}.btn-primary{background:var(--primary2);color:#fff}.btn-secondary{background:#eef2ff;color:#1e3a8a}.btn-danger{background:#fee2e2;color:#991b1b}.btn-green{background:#dcfce7;color:#166534}
.badges{display:flex;gap:10px;flex-wrap:wrap;margin-top:10px}.badge{display:inline-block;padding:8px 10px;border-radius:999px;font-size:12px;font-weight:700}.b-blue{background:#dbeafe;color:#1d4ed8}.b-amber{background:#fef3c7;color:#92400e}.b-green{background:#dcfce7;color:#166534}
.kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}.kpi{padding:16px;border:1px solid var(--line);border-radius:14px;background:#fafafa}.kpi .v{font-size:24px;font-weight:800}.kpi .l{font-size:12px;color:var(--muted);margin-top:4px}
.table-wrap{overflow:auto}.table{width:100%;border-collapse:collapse}.table th,.table td{padding:10px 12px;border-bottom:1px solid var(--line);text-align:left;font-size:14px}.table th{background:#f8fafc}.progress-shell{height:18px;background:#e5e7eb;border-radius:999px;overflow:hidden}.progress-bar{height:100%;width:0%;background:linear-gradient(90deg,#2563eb,#60a5fa);transition:width .35s ease}.progress-meta{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-top:14px}.meta{padding:12px;border:1px solid var(--line);border-radius:12px;background:#fafafa}.meta .n{font-size:22px;font-weight:800}.meta .t{font-size:12px;color:var(--muted);margin-top:4px}.log{margin-top:16px;background:#0f172a;color:#e2e8f0;border-radius:14px;padding:14px;height:280px;overflow:auto;font-family:Consolas,monospace;font-size:13px;white-space:pre-wrap}.muted{color:var(--muted)}.error{color:var(--red);font-weight:700}.hidden{display:none}@media (max-width:900px){.col-7,.col-5,.col-12{grid-column:span 12}.kpis,.progress-meta,.row{grid-template-columns:1fr 1fr}}@media (max-width:640px){.kpis,.progress-meta,.row{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="wrap">
    <div class="hero">
        <h1>Campaign Monitor → Manatal Cached Sync</h1>
        <p>Refresh a local cache of Manatal contacts first, then run the campaign sync using only Manatal PATCH requests during the live sync.</p>
        <div class="badges">
            <span class="badge b-blue">Live sync pace: <?= h((string)MANATAL_PATCH_REQUESTS_PER_MINUTE) ?> patches/min</span>
            <span class="badge b-amber">Cache refresh uses paged reads</span>
            <span class="badge b-green">Resume by record or email</span>
        </div>
    </div>

    <div class="grid">
        <div class="card col-5">
            <h3 style="margin-top:0">Step 1: Refresh Manatal cache</h3>
            <p class="muted">This pulls Manatal contacts into MySQL so the live sync does not need email lookups.</p>
            <div class="kpis">
                <div class="kpi"><div class="v" id="cacheCount"><?= h((string)$cacheStats['count']) ?></div><div class="l">Cached contacts</div></div>
                <div class="kpi"><div class="v" id="cacheLatest"><?= h($cacheStats['latest'] ? date('m/d H:i', strtotime((string)$cacheStats['latest'])) : '—') ?></div><div class="l">Last refresh</div></div>
                <div class="kpi"><div class="v"><?= h((string)CONTACT_PAGE_SIZE) ?></div><div class="l">Contacts per page</div></div>
                <div class="kpi"><div class="v"><?= h((string)CACHE_PAGE_SLEEP_SECONDS) ?>s</div><div class="l">Sleep between pages</div></div>
            </div>
            <div class="checkrow"><input type="checkbox" id="truncate_cache" value="1"><label for="truncate_cache" style="margin:0;">Clear cache before refresh</label></div>
            <div class="btns">
                <button type="button" class="btn-green" id="refreshCacheBtn">Refresh Manatal Cache</button>
                <button type="button" class="btn-danger hidden" id="cancelCacheBtn">Cancel Cache Refresh</button>
            </div>
            <div class="log" id="cacheLog">Cache is ready when you are.</div>
        </div>

        <div class="card col-7">
            <form method="post" id="configForm">
                <h3 style="margin-top:0">Step 2: Sync campaign using cache</h3>
                <div class="row">
                    <div>
                        <label for="campaign_id">Campaign</label>
                        <select name="campaign_id" id="campaign_id" onchange="this.form.submit()">
                            <option value="">Select a campaign</option>
                            <?php foreach ($campaigns as $campaign): ?>
                                <option value="<?= h($campaign['id']) ?>" <?= $selectedCampaignId === $campaign['id'] ? 'selected' : '' ?>>
                                    <?= h($campaign['name']) ?><?= $campaign['sent_date'] ? ' | ' . h(normalizeDate($campaign['sent_date'])) : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="list_id">Mailing list used by this campaign</label>
                        <select name="list_id" id="list_id">
                            <option value="">All lists in this campaign</option>
                            <?php foreach ($campaignLists as $list): ?>
                                <option value="<?= h($list['list_id']) ?>" <?= $selectedListId === $list['list_id'] ? 'selected' : '' ?>><?= h($list['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-top:14px;">
                    <div>
                        <label for="start_record">Start at record number</label>
                        <input type="number" min="1" id="start_record" name="start_record" value="<?= h((string)$startRecord) ?>">
                    </div>
                    <div>
                        <label for="start_email">Or start at email</label>
                        <input type="text" id="start_email" name="start_email" value="<?= h($startEmail) ?>" placeholder="leave blank unless resuming by email">
                    </div>
                </div>
                <div class="row" style="margin-top:14px;">
                    <div>
                        <label for="test_email">Test email write target</label>
                        <input type="text" id="test_email" name="test_email" value="<?= h($testEmail) ?>">
                    </div>
                    <div class="checkrow" style="padding-top:26px;">
                        <input type="checkbox" id="test_mode" name="test_mode" value="1" <?= $testMode ? 'checked' : '' ?>>
                        <label for="test_mode" style="margin:0;">Test mode: only process the single email above</label>
                    </div>
                </div>
                <div class="btns">
                    <button class="btn-secondary" type="submit" name="preview" value="1">Preview</button>
                    <button class="btn-primary" type="button" id="startBtn">Run Sync</button>
                    <button class="btn-danger hidden" type="button" id="cancelBtn">Cancel</button>
                </div>
                <?php if ($previewError): ?><div style="margin-top:14px" class="error"><?= h($previewError) ?></div><?php endif; ?>
            </form>
        </div>

        <div class="card col-12">
            <h3 style="margin-top:0">What this changes</h3>
            <p class="muted">The live sync updates only the five campaign custom fields. All other custom fields are preserved from the local cache snapshot and sent back unchanged during the patch.</p>
            <div class="badges">
                <span class="badge b-blue">opens</span>
                <span class="badge b-blue">clicks</span>
                <span class="badge b-blue">unsubscribe</span>
                <span class="badge b-blue">campaigndate</span>
                <span class="badge b-blue">campaignname</span>
            </div>
        </div>

        <?php if ($previewData): ?>
        <div class="card col-12" id="previewSection">
            <div class="kpis">
                <div class="kpi"><div class="v"><?= h((string)$previewData['total']) ?></div><div class="l">Recipients after filters</div></div>
                <div class="kpi"><div class="v"><?= h((string)$previewData['cache_matches']) ?></div><div class="l">Found in local cache</div></div>
                <div class="kpi"><div class="v"><?= h((string)$previewData['worst_case_requests']) ?></div><div class="l">Worst-case Manatal patches</div></div>
                <div class="kpi"><div class="v"><?= h($previewData['eta_text']) ?></div><div class="l">Estimated runtime</div></div>
            </div>
            <h3>Preview sample</h3>
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Email</th><th>Opens</th><th>Clicks</th><th>Unsubscribe</th><th>Campaign</th><th>Date</th></tr></thead>
                    <tbody>
                    <?php foreach ($previewData['sample'] as $row): ?>
                        <tr>
                            <td><?= h($row['email']) ?></td>
                            <td><?= h((string)$row['opens']) ?></td>
                            <td><?= h((string)$row['clicks']) ?></td>
                            <td><?= $row['unsubscribe'] ? 'true' : 'false' ?></td>
                            <td><?= h($row['campaignname']) ?></td>
                            <td><?= h($row['campaigndate']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <div class="card col-12" id="progressCard">
            <h3 style="margin-top:0">Live sync progress</h3>
            <div class="progress-shell"><div class="progress-bar" id="progressBar"></div></div>
            <div class="progress-meta">
                <div class="meta"><div class="n" id="metaProcessed">0</div><div class="t">Processed</div></div>
                <div class="meta"><div class="n" id="metaUpdated">0</div><div class="t">Updated</div></div>
                <div class="meta"><div class="n" id="metaNoMatch">0</div><div class="t">No local cache match</div></div>
                <div class="meta"><div class="n" id="metaFailed">0</div><div class="t">Failed</div></div>
                <div class="meta"><div class="n" id="metaEta">—</div><div class="t">ETA</div></div>
            </div>
            <div style="margin-top:14px"><strong>Current email:</strong> <span id="currentEmail">—</span></div>
            <div class="log" id="logBox">Waiting to start sync…</div>
        </div>
    </div>
</div>
<script>
let activeJobId = null;
let pollTimer = null;
let syncStartedAt = null;

function formDataFromConfig() {
    const form = document.getElementById('configForm');
    const fd = new FormData();
    fd.append('campaign_id', form.campaign_id.value);
    fd.append('list_id', form.list_id.value);
    fd.append('test_email', form.test_email.value);
    fd.append('start_record', form.start_record.value || '1');
    fd.append('start_email', form.start_email.value || '');
    if (form.test_mode.checked) fd.append('test_mode', '1');
    return fd;
}

function appendLog(targetId, text) {
    const box = document.getElementById(targetId);
    box.textContent = text;
    box.scrollTop = box.scrollHeight;
}

function formatDuration(seconds) {
    seconds = Math.max(0, Math.round(seconds));
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = seconds % 60;
    const parts = [];
    if (h) parts.push(h + 'h');
    if (m) parts.push(m + 'm');
    if (s || !parts.length) parts.push(s + 's');
    return parts.join(' ');
}

function updateProgress(state) {
    const total = Number(state.total || 0);
    const processed = Number(state.processed || 0);
    const updated = Number(state.updated || 0);
    const noMatch = Number(state.no_match || 0);
    const failed = Number(state.failed || 0);
    const pct = total > 0 ? (processed / total) * 100 : 0;

    document.getElementById('progressBar').style.width = pct.toFixed(2) + '%';
    document.getElementById('metaProcessed').textContent = processed + ' / ' + total;
    document.getElementById('metaUpdated').textContent = updated;
    document.getElementById('metaNoMatch').textContent = noMatch;
    document.getElementById('metaFailed').textContent = failed;
    document.getElementById('currentEmail').textContent = state.current_email || '—';

    let etaText = '—';
    if (syncStartedAt && processed > 0 && total > processed) {
        const elapsed = (Date.now() - syncStartedAt) / 1000;
        const avg = elapsed / processed;
        etaText = formatDuration(avg * (total - processed));
    } else if (state.status === 'done') {
        etaText = 'Done';
    }
    document.getElementById('metaEta').textContent = etaText;

    const logs = Array.isArray(state.logs) ? state.logs : [];
    appendLog('logBox', logs.length ? logs.join("\n") : 'No activity yet.');
}

async function pollStep(delayMs = 300) {
    if (!activeJobId) return;
    clearTimeout(pollTimer);
    pollTimer = setTimeout(async () => {
        const fd = new FormData();
        fd.append('ajax_action', 'sync_step');
        fd.append('job_id', activeJobId);
        const res = await fetch(location.href, { method: 'POST', body: fd, credentials: 'same-origin' });
        const data = await res.json();
        if (!data.ok) {
            appendLog('logBox', 'Error: ' + (data.message || 'Unknown error'));
            document.getElementById('cancelBtn').classList.add('hidden');
            activeJobId = null;
            document.getElementById('startBtn').disabled = false;
            return;
        }
        if (data.state) updateProgress(data.state);
        if (data.state && data.state.status === 'done') {
            activeJobId = null;
            document.getElementById('cancelBtn').classList.add('hidden');
            document.getElementById('startBtn').disabled = false;
            return;
        }
        const wait = data.wait ? Math.max(250, Math.ceil(data.wait * 1000)) : 250;
        pollStep(wait);
    }, delayMs);
}

let activeCacheRefresh = false;
let cachePollTimer = null;

function updateCacheRefresh(state, stats) {
    const currentPage = Number(state.current_page || 1);
    const totalPages = Number(state.total_pages || 1);
    const completedPages = Math.min(totalPages, Math.max(0, currentPage - 1));
    const pct = totalPages > 0 ? Math.round((completedPages / totalPages) * 100) : 0;
    const logs = Array.isArray(state.logs) ? state.logs : [];
    let header = 'Cache refresh progress: ' + completedPages + ' / ' + totalPages + ' pages (' + pct + '%)' +
        '\nContacts reported by Manatal: ' + (state.total_contacts || 0) +
        '\nUsable contacts cached this run: ' + (state.cached_rows || 0);
    if (state.status === 'done') {
        header += '\nStatus: complete';
    } else {
        header += '\nStatus: running';
    }
    appendLog('cacheLog', header + '\n\n' + (logs.length ? logs.join("\n") : 'No activity yet.'));
    if (stats) {
        document.getElementById('cacheCount').textContent = stats.count || 0;
        document.getElementById('cacheLatest').textContent = stats.latest ? stats.latest.substring(5,16).replace('T',' ') : '—';
    }
}

async function pollCacheRefresh(delayMs = 300) {
    if (!activeCacheRefresh) return;
    clearTimeout(cachePollTimer);
    cachePollTimer = setTimeout(async () => {
        const fd = new FormData();
        fd.append('ajax_action', 'cache_refresh_step');
        const res = await fetch(location.href, { method: 'POST', body: fd, credentials: 'same-origin' });
        const data = await res.json();
        if (!data.ok) {
            appendLog('cacheLog', 'Error: ' + (data.message || 'Cache refresh failed'));
            document.getElementById('refreshCacheBtn').disabled = false;
            document.getElementById('cancelCacheBtn').classList.add('hidden');
            activeCacheRefresh = false;
            return;
        }
        if (data.state) updateCacheRefresh(data.state, data.stats || null);
        if (data.state && data.state.status === 'done') {
            activeCacheRefresh = false;
            document.getElementById('refreshCacheBtn').disabled = false;
            document.getElementById('cancelCacheBtn').classList.add('hidden');
            return;
        }
        const wait = data.wait ? Math.max(250, Math.ceil(data.wait * 1000)) : 250;
        pollCacheRefresh(wait);
    }, delayMs);
}

document.getElementById('refreshCacheBtn').addEventListener('click', async () => {
    const fd = new FormData();
    fd.append('ajax_action', 'start_cache_refresh');
    if (document.getElementById('truncate_cache').checked) fd.append('truncate_cache', '1');
    appendLog('cacheLog', 'Initializing Manatal cache refresh…');
    document.getElementById('refreshCacheBtn').disabled = true;
    document.getElementById('cancelCacheBtn').classList.remove('hidden');
    const res = await fetch(location.href, { method: 'POST', body: fd, credentials: 'same-origin' });
    const data = await res.json();
    if (!data.ok) {
        appendLog('cacheLog', 'Error: ' + (data.message || 'Could not start cache refresh'));
        document.getElementById('refreshCacheBtn').disabled = false;
        document.getElementById('cancelCacheBtn').classList.add('hidden');
        return;
    }
    activeCacheRefresh = true;
    if (data.state) updateCacheRefresh(data.state, data.stats || null);
    pollCacheRefresh(100);
});

document.getElementById('cancelCacheBtn').addEventListener('click', async () => {
    const fd = new FormData();
    fd.append('ajax_action', 'cancel_cache_refresh');
    await fetch(location.href, { method: 'POST', body: fd, credentials: 'same-origin' });
    activeCacheRefresh = false;
    clearTimeout(cachePollTimer);
    appendLog('cacheLog', document.getElementById('cacheLog').textContent + '\nCancelled.');
    document.getElementById('refreshCacheBtn').disabled = false;
    document.getElementById('cancelCacheBtn').classList.add('hidden');
});

document.getElementById('startBtn').addEventListener('click', async () => {
    const fd = formDataFromConfig();
    if (!fd.get('campaign_id')) {
        alert('Choose a campaign first.');
        return;
    }
    const preview = document.getElementById('previewSection');
    if (preview) preview.style.display = 'none';
    document.getElementById('progressCard').scrollIntoView({behavior:'smooth', block:'start'});
    fd.append('ajax_action', 'start_sync');
    appendLog('logBox', 'Initializing sync…');
    document.getElementById('startBtn').disabled = true;
    const res = await fetch(location.href, { method: 'POST', body: fd, credentials: 'same-origin' });
    const data = await res.json();
    if (!data.ok) {
        if (preview) preview.style.display = '';
        appendLog('logBox', 'Error: ' + (data.message || 'Could not start sync'));
        document.getElementById('startBtn').disabled = false;
        return;
    }
    activeJobId = data.job_id;
    syncStartedAt = Date.now();
    document.getElementById('cancelBtn').classList.remove('hidden');
    appendLog('logBox', 'Sync started. Estimated runtime: ' + (data.eta_text || '—'));
    pollStep(100);
});

document.getElementById('cancelBtn').addEventListener('click', async () => {
    if (!activeJobId) return;
    const fd = new FormData();
    fd.append('ajax_action', 'cancel_sync');
    fd.append('job_id', activeJobId);
    await fetch(location.href, { method: 'POST', body: fd, credentials: 'same-origin' });
    activeJobId = null;
    clearTimeout(pollTimer);
    appendLog('logBox', document.getElementById('logBox').textContent + '\nCancelled.');
    document.getElementById('cancelBtn').classList.add('hidden');
    document.getElementById('startBtn').disabled = false;
});
</script>
</body>
</html>
