<?php
/**
 * Contact Form Leads - list + filters + CSV export
 *
 * Filters:
 *  - start_date (YYYY-MM-DD) on LogDate (inclusive)
 *  - end_date   (YYYY-MM-DD) on LogDate (inclusive)
 *  - recruiter  (string) or "ALL"
 *  - export=1   => CSV download using same filters
 */

require_once dirname(__DIR__) . '/db/db.php';
$link = db();
if (!$link) {
  http_response_code(500);
  echo "Database connection failed.";
  exit;
}
mysqli_set_charset($link, 'utf8mb4');

/**
 * Helpers
 */
function get_param(string $key, $default = '') {
  return isset($_GET[$key]) ? $_GET[$key] : $default;
}

function is_valid_date($s) {
  if (!is_string($s) || $s === '') return false;
  $d = DateTime::createFromFormat('Y-m-d', $s);
  return $d && $d->format('Y-m-d') === $s;
}

function clean_csv($v) {
  if ($v === null) return '';
  $v = (string)$v;
  $v = html_entity_decode($v, ENT_QUOTES | ENT_HTML5, 'UTF-8');
  $v = strip_tags($v);
  $v = preg_replace("/\r\n|\r|\n/", " ", $v);
  $v = preg_replace("/\s+/", " ", $v);
  return trim($v);
}

function clean_display($v) {
  return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Turn a URL like:
 * https://www.icreatives.com/creative-staffing/new-haven-connecticut/
 * into:
 * New Haven Connecticut
 */
function city_from_url($url) {
  if (!$url || !is_string($url)) return '';

  $path = parse_url($url, PHP_URL_PATH);
  if (!$path) return '';

  $path = trim($path, '/');
  if ($path === '') return '';

  $parts = explode('/', $path);
  if (count($parts) < 2) return '';

  // Prefer city slug after /creative-staffing/
  $slug = '';
  $idx = array_search('creative-staffing', $parts, true);
  if ($idx !== false && isset($parts[$idx + 1]) && $parts[$idx + 1] !== '') {
    $slug = $parts[$idx + 1];
  } else {
    // fallback to last path segment
    $slug = end($parts);
  }

  if (!$slug) return '';

  $slug = trim($slug, '/');
  $slug = preg_replace('/\?.*$/', '', $slug);
  $slug = str_replace(['-', '_'], ' ', $slug);
  $slug = preg_replace('/\s+/', ' ', $slug);
  $slug = trim($slug);

  if ($slug === '') return '';

  return ucwords($slug);
}

/**
 * Read filters
 */
$start_date = get_param('start_date', '');
$end_date   = get_param('end_date', '');
$recruiter  = get_param('recruiter', 'ALL');
$export     = get_param('export', '0') === '1';
$hide_spam  = get_param('hide_spam', '1'); // default checked

if (!is_valid_date($start_date)) $start_date = '';
if (!is_valid_date($end_date))   $end_date = '';

/**
 * Build WHERE clause safely
 */
$where  = [];
$types  = '';
$params = [];

if ($start_date !== '') {
  $where[] = "LogDate >= ?";
  $types .= 's';
  $params[] = $start_date;
}
if ($end_date !== '') {
  $where[] = "LogDate <= ?";
  $types .= 's';
  $params[] = $end_date;
}
if ($recruiter !== '' && strtoupper($recruiter) !== 'ALL') {
  $where[] = "Recruiter = ?";
  $types .= 's';
  $params[] = $recruiter;
}

if ($hide_spam === '1') {
  $where[] = "(Recruiter IS NULL OR Recruiter <> 'SPAM')";
}

$where_sql = count($where) ? (" WHERE " . implode(" AND ", $where)) : "";

/**
 * Recruiter dropdown values
 */
$recruiters = [];
$rec_sql = "SELECT DISTINCT Recruiter FROM ic_contact_form WHERE Recruiter IS NOT NULL AND Recruiter <> '' ORDER BY Recruiter";
if ($rec_res = mysqli_query($link, $rec_sql)) {
  while ($row = mysqli_fetch_assoc($rec_res)) {
    $recruiters[] = $row['Recruiter'];
  }
  mysqli_free_result($rec_res);
}

/**
 * Base SELECT used by both HTML and CSV
 */
$select_sql = "SELECT
  Number        AS number,
  Recruiter     AS recruiter,
  LastName      AS LastName,
  Company       AS Company,
  Phone         AS Phone,
  email         AS email,
  LogDate       AS LogDate,
  Accepted      AS Accepted,
  PositionInfo  AS PositionInfo,
  Source        AS Source,
  url           AS url,
  pagetitle     AS pagetitle,
  referrer      AS referrer,
  worktype      AS worktype,
  Comment      AS Comment 
FROM ic_contact_form
$where_sql
ORDER BY Number DESC";

/**
 * CSV export
 */
if ($export) {
  while (ob_get_level()) { ob_end_clean(); }

  $filename = "contact-form-leads";
  if ($start_date) $filename .= "_from-" . $start_date;
  if ($end_date)   $filename .= "_to-" . $end_date;
  if ($recruiter && strtoupper($recruiter) !== 'ALL') {
    $filename .= "_recruiter-" . preg_replace('/[^a-zA-Z0-9_\-]/', '', $recruiter);
  }
  $filename .= ".csv";

  header('Content-Type: text/csv; charset=UTF-8');
  header('Content-Disposition: attachment; filename="' . $filename . '"');
  header('Pragma: no-cache');
  header('Expires: 0');

  $out = fopen('php://output', 'w');

  fputcsv($out, [
    'Number',
    'Recruiter',
    'LastName',
    'Company',
    'Phone',
    'Email',
    'LogDate',
    'Accepted',
    'PositionInfo',
    'Source',
    'CityPage',
    'URL',
    'PageTitle',
    'Referrer',
    'worktype',
    'Comment'
  ]);

  $stmt = mysqli_prepare($link, $select_sql);
  if (!$stmt) {
    fputcsv($out, ['ERROR', 'Could not prepare statement']);
    fclose($out);
    exit;
  }

  if ($types !== '') {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
  }

  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);

  while ($r = mysqli_fetch_assoc($res)) {
    $city_page = city_from_url($r['url']);

    fputcsv($out, [
      clean_csv($r['number']),
      clean_csv($r['recruiter']),
      clean_csv($r['LastName']),
      clean_csv($r['Company']),
      clean_csv($r['Phone']),
      clean_csv($r['email']),
      clean_csv($r['LogDate']),
      clean_csv($r['Accepted']),
      clean_csv($r['PositionInfo']),
      clean_csv($r['Source']),
      clean_csv($city_page),
      clean_csv($r['url']),
      clean_csv($r['pagetitle']),
      clean_csv($r['referrer']),
      clean_csv($r['worktype']),
    ]);
  }

  mysqli_stmt_close($stmt);
  fclose($out);
  exit;
}

/**
 * Normal page view
 */
$rows = [];
$stmt = mysqli_prepare($link, $select_sql);
if ($stmt) {
  if ($types !== '') {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
  }
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  while ($r = mysqli_fetch_assoc($res)) {
    $r['CityPage'] = city_from_url($r['url']);
    $rows[] = $r;
  }
  mysqli_stmt_close($stmt);
}

$qs = [
  'start_date' => $start_date,
  'end_date'   => $end_date,
  'recruiter'  => $recruiter,
  'hide_spam'  => $hide_spam,
  'export'     => '1'
];
$export_url = htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query($qs), ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact Form Leads</title>
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      margin: 16px;
    }
    .filters {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      align-items: flex-end;
      margin-bottom: 14px;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 8px;
    }
    .filters label {
      display: block;
      font-size: 12px;
      color: #333;
      margin-bottom: 4px;
    }
    .filters input,
    .filters select {
      padding: 6px 8px;
      font-size: 14px;
    }
    .filters .btn {
      padding: 7px 10px;
      font-size: 14px;
      cursor: pointer;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      border-bottom: 1px solid #eee;
      padding: 8px 6px;
      text-align: left;
      font-size: 14px;
      vertical-align: top;
    }
    th {
      background: #f7f7f7;
      position: sticky;
      top: 0;
      white-space: nowrap;
    }
    td {
      word-break: break-word;
    }
    .muted {
      color: #666;
      font-size: 12px;
    }
    .right {
      margin-left: auto;
    }
    .url-cell {
      max-width: 260px;
    }
    .title-cell {
      max-width: 220px;
    }
    .ref-cell {
      max-width: 220px;
    }
  </style>
</head>
<body>

<h2 style="margin:0 0 10px 0;">Contact Form Leads</h2>
<div class="muted" style="margin-bottom:10px;">
  Filter by LogDate and Recruiter, or export the same results to CSV.
</div>

<form class="filters" method="get" action="">
  <div>
    <label for="start_date">Start date (LogDate)</label>
    <input type="date" id="start_date" name="start_date" value="<?php echo clean_display($start_date); ?>">
  </div>

  <div>
    <label for="end_date">End date (LogDate)</label>
    <input type="date" id="end_date" name="end_date" value="<?php echo clean_display($end_date); ?>">
  </div>

  <div>
    <label for="recruiter">Recruiter</label>
    <select id="recruiter" name="recruiter">
      <?php
      $sel = (strtoupper($recruiter) === 'ALL' || $recruiter === '') ? 'selected' : '';
      echo '<option value="ALL" ' . $sel . '>All recruiters</option>';
      foreach ($recruiters as $rec) {
        $isSel = ($rec === $recruiter) ? 'selected' : '';
        echo '<option value="' . clean_display($rec) . '" ' . $isSel . '>' . clean_display($rec) . '</option>';
      }
      ?>
    </select>
  </div>
  <div>
	<label>
		<input type="checkbox" name="hide_spam" value="1"
		<?php // echo ($hide_spam === '1') ? 'checked' : ''; ?>>
				Hide SPAM
	</label>
	</div>

  <div>
    <button class="btn" type="submit">Apply Filters</button>
  </div>

  <div class="right">
    <a class="btn" href="<?php echo $export_url; ?>" style="text-decoration:none;border:1px solid #ccc;border-radius:6px;display:inline-block;">
      Download CSV
    </a>
  </div>
</form>

<table>
  <thead>
    <tr>
      <th>Number</th>
      <th>Recruiter</th>
      <th>Last Name</th>
      <th>Company</th>
      <th>Phone</th>
      <th>Email</th>
      <th>Log Date</th>
      <th>Accepted</th>
      <th>Position</th>
      <th>Source</th>
      <th>City Page</th>
      <th>URL</th>
      <th>Page Title</th>
      <th>Referrer</th>
      <th>Work Type</th>
      <th>Commment</th>
    </tr>
  </thead>
  <tbody>
    <?php if (count($rows) === 0): ?>
      <tr>
        <td colspan="17" class="muted">No records found for these filters.</td>
      </tr>
    <?php else: ?>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?php echo clean_display($r['number']); ?></td>
          <td><?php echo clean_display($r['recruiter']); ?></td>
          <td><?php echo clean_display($r['LastName']); ?></td>
          <td><?php echo clean_display($r['Company']); ?></td>
          <td><?php echo clean_display($r['Phone']); ?></td>
          <td><?php echo clean_display($r['email']); ?></td>
          <td><?php echo clean_display($r['LogDate']); ?></td>
          <td><?php echo clean_display($r['Accepted']); ?></td>
          <td><?php echo clean_display($r['PositionInfo']); ?></td>
          <td><?php echo clean_display($r['Source']); ?></td>
          <td><?php echo clean_display($r['CityPage']); ?></td>
          <td class="url-cell"><?php echo clean_display($r['url']); ?></td>
          <td class="title-cell"><?php echo clean_display($r['pagetitle']); ?></td>
          <td class="ref-cell"><?php echo clean_display($r['referrer']); ?></td>
          <td><?php echo clean_display($r['worktype']); ?></td>
          <td><?php echo clean_display($r['Comment']); ?></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

</body>
</html>