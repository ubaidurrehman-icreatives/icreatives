<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
set_time_limit(0);
date_default_timezone_set('America/New_York');

require_once dirname(__DIR__) . '/db/db.php';

/** @var mysqli $db */
$db = db();
if (!$db instanceof mysqli) {
    die('db() did not return a mysqli connection.');
}
$db->set_charset('utf8mb4');

const CACHE_TABLE = 'cm_manatal_contact_cache';

/**
 * Convert numeric-looking strings into real numbers recursively.
 * This helps Manatal receive numbers as numbers instead of text.
 */
function normalizeNumericValues($value)
{
    if (is_array($value)) {
        foreach ($value as $k => $v) {
            $value[$k] = normalizeNumericValues($v);
        }
        return $value;
    }

    if (!is_string($value)) {
        return $value;
    }

    $trimmed = trim($value);
    if ($trimmed === '') {
        return $value;
    }

    // Integer
    if (preg_match('/^-?\d+$/', $trimmed)) {
        return (int)$trimmed;
    }

    // Decimal
    if (preg_match('/^-?\d+\.\d+$/', $trimmed)) {
        return (float)$trimmed;
    }

    return $value;
}

/**
 * Remove fields that should be nulled out by omission in the import.
 */
function stripFields(array $customFields): array
{
    unset($customFields['clicks'], $customFields['opens']);
    return $customFields;
}

$filename = 'manatal_custom_fields_export_' . date('Y-m-d_H-i-s') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$out = fopen('php://output', 'w');
if ($out === false) {
    die('Could not open output stream.');
}

// CSV header
fputcsv($out, ['id', 'custom_fields']);

$sql = "
    SELECT
        manatal_contact_id,
        custom_fields_json
    FROM `" . CACHE_TABLE . "`
    WHERE manatal_contact_id IS NOT NULL
      AND manatal_contact_id <> 0
";

$result = $db->query($sql);
if (!$result) {
    fclose($out);
    die('Query failed: ' . $db->error);
}

while ($row = $result->fetch_assoc()) {
    $contactId = (int)($row['manatal_contact_id'] ?? 0);
    if ($contactId <= 0) {
        continue;
    }

    $customFields = json_decode((string)($row['custom_fields_json'] ?? ''), true);

    if (!is_array($customFields)) {
        $customFields = [];
    }

    // Remove fields you want Manatal to null out on import
    $customFields = stripFields($customFields);

    // Convert numeric strings to numbers
    $customFields = normalizeNumericValues($customFields);

    // Encode compact JSON for the CSV cell
    $customFieldsJson = json_encode($customFields, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    fputcsv($out, [$contactId, $customFieldsJson]);
}

$result->free();
fclose($out);
exit;