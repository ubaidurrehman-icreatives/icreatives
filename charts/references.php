<?php
declare(strict_types=1);

/**
 * reference_leads.php
 * View + filter + download CSV for leads in `ic_reference`.
 */

require_once __DIR__ . '/../db/db.php';
$link = db();

// ----------------------------
// Helpers
// ----------------------------
function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

function normalize_like(?string $v): ?string {
    $v = trim((string)$v);
    return $v === '' ? null : $v;
}

function normalize_date(?string $v): ?string {
    $v = trim((string)$v);
    if ($v === '') return null;
    // Expect YYYY-MM-DD
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) return null;
    return $v;
}

// ----------------------------
// Inputs (filters)
// ----------------------------
$q_candidate        = normalize_like($_GET['candidate'] ?? null);
$q_candidate_name   = normalize_like($_GET['candidate_name'] ?? null);
$q_ref_name         = normalize_like($_GET['referencename'] ?? null);
$q_ref_email        = normalize_like($_GET['referenceemail'] ?? null);
$q_ref_phone        = normalize_like($_GET['referencephone'] ?? null);
$q_recruiter_name   = normalize_like($_GET['recruiter_name'] ?? null);
$q_recruiter_id     = normalize_like($_GET['recruiter_id'] ?? null);

$date_from          = normalize_date($_GET['date_from'] ?? null);
$date_to            = normalize_date($_GET['date_to'] ?? null);

$download           = (isset($_GET['download']) && $_GET['download'] === '1');

// Pagination
$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = (int)($_GET['per_page'] ?? 50);
if (!in_array($perPage, [25, 50, 100, 250, 500], true)) $perPage = 50;
$offset  = ($page - 1) * $perPage;

// Sorting (whitelist)
$sort = (string)($_GET['sort'] ?? 'timestamp');
$dir  = strtoupper((string)($_GET['dir'] ?? 'DESC')) === 'ASC' ? 'ASC' : 'DESC';

$allowedSort = [
    'id' => 'id',
    'candidate' => 'candidate',
    'candidate_name' => 'candidate_name',
    'referencename' => 'referencename',
    'referenceemail' => 'referenceemail',
    'recruiter_name' => 'recruiter_name',
    'recruiter_id' => 'recruiter_id',
    'timestamp' => '`timestamp`',
];

$sortSql = $allowedSort[$sort] ?? '`timestamp`';

// ----------------------------
// Build WHERE clause (prepared)
// ----------------------------
$where = [];
$types = '';
$params = [];

$addLike = function(string $col, ?string $val) use (&$where, &$types, &$params) {
    if ($val === null) return;
    $where[] = "$col LIKE ?";
    $types  .= 's';
    $params[] = '%' . $val . '%';
};

$addEq = function(string $col, ?string $val) use (&$where, &$types, &$params) {
    if ($val === null) return;
    $where[] = "$col = ?";
    $types  .= 's';
    $params[] = $val;
};

$addLike('candidate', $q_candidate);
$addLike('candidate_name', $q_candidate_name);
$addLike('referencename', $q_ref_name);
$addLike('referenceemail', $q_ref_email);
$addLike('referencephone', $q_ref_phone);
$addLike('recruiter_name', $q_recruiter_name);
$addLike('recruiter_id', $q_recruiter_id);

// Date filter on timestamp (inclusive range by day)
if ($date_from !== null) {
    $where[] = "`timestamp` >= ?";
    $types  .= 's';
    $params[] = $date_from . ' 00:00:00';
}
if ($date_to !== null) {
    $where[] = "`timestamp` <= ?";
    $types  .= 's';
    $params[] = $date_to . ' 23:59:59';
}

$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

// ----------------------------
// CSV download (no pagination)
// ----------------------------
if ($download) {
    $sql = "
        SELECT id, candidate, candidate_name, referenceurl, referencename, referenceemail,
               referencephone, referencerelationship, recruiter_name, recruiter_id, `timestamp`
        FROM ic_reference
        $whereSql
        ORDER BY $sortSql $dir
    ";

    $stmt = $link->prepare($sql);
    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $res = $stmt->get_result();

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="ic_reference_leads_' . date('Y-m-d_His') . '.csv"');

    $out = fopen('php://output', 'w');

    // Excel-friendly UTF-8 BOM (optional; comment out if you don't want it)
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

    fputcsv($out, [
        'id','candidate','candidate_name','referenceurl','referencename','referenceemail',
        'referencephone','referencerelationship','recruiter_name','recruiter_id','timestamp'
    ]);

    while ($row = $res->fetch_assoc()) {
        // If you have any "0000-00-00 00:00:00" values, blank them for readability
        if (($row['timestamp'] ?? '') === '0000-00-00 00:00:00') $row['timestamp'] = '';
        fputcsv($out, $row);
    }

    fclose($out);
    exit;
}

// ----------------------------
// Count total rows (for pagination)
// ----------------------------
$countSql = "SELECT COUNT(*) AS cnt FROM ic_reference $whereSql";
$countStmt = $link->prepare($countSql);
if ($types !== '') {
    $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$total = (int)($countStmt->get_result()->fetch_assoc()['cnt'] ?? 0);
$totalPages = max(1, (int)ceil($total / $perPage));
if ($page > $totalPages) $page = $totalPages;

// ----------------------------
// Fetch paged rows
// ----------------------------
$sql = "
    SELECT id, candidate, candidate_name, referenceurl, referencename, referenceemail,
           referencephone, referencerelationship, recruiter_name, recruiter_id, `timestamp`
    FROM ic_reference
    $whereSql
    ORDER BY $sortSql $dir
    LIMIT ? OFFSET ?
";

$stmt = $link->prepare($sql);

// bind filters + limit/offset
$types2  = $types . 'ii';
$params2 = $params;
$params2[] = $perPage;
$params2[] = $offset;

$stmt->bind_param($types2, ...$params2);
$stmt->execute();
$res = $stmt->get_result();

// Build querystring helper preserving filters
function build_qs(array $overrides = []): string {
    $base = $_GET;
    foreach ($overrides as $k => $v) {
        if ($v === null) unset($base[$k]);
        else $base[$k] = $v;
    }
    return http_build_query($base);
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reference Leads</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; margin: 16px; }
    .filters { display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end; padding:12px; border:1px solid #ddd; border-radius:10px; }
    .filters label { display:block; font-size:12px; color:#333; margin-bottom:4px; }
    .filters input, .filters select { padding:7px 9px; font-size:14px; }
    .btn { padding:8px 12px; border:1px solid #333; background:#fff; cursor:pointer; border-radius:8px; text-decoration:none; color:#000; display:inline-block; }
    .btn.primary { background:#111; color:#fff; border-color:#111; }
    table { width:100%; border-collapse:collapse; margin-top:14px; }
    th, td { border:1px solid #e2e2e2; padding:8px; font-size:13px; vertical-align:top; }
    th { background:#f6f6f6; text-align:left; }
    .muted { color:#666; font-size:12px; }
    .pager { margin-top:12px; display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
    .nowrap { white-space:nowrap; }
  </style>
</head>
<body>

<h2 style="margin:0 0 8px 0;">Reference Leads</h2>
<div class="muted">Table: <b>ic_reference</b> • Total: <b><?= (int)$total ?></b></div>

<form class="filters" method="get" action="">
  <div>
    <label>Candidate ID</label>
    <input name="candidate" value="<?= h((string)($_GET['candidate'] ?? '')) ?>" placeholder="112280387">
  </div>
  <div>
    <label>Candidate Name</label>
    <input name="candidate_name" value="<?= h((string)($_GET['candidate_name'] ?? '')) ?>" placeholder="Jane Doe">
  </div>
  <div>
    <label>Reference Name</label>
    <input name="referencename" value="<?= h((string)($_GET['referencename'] ?? '')) ?>" placeholder="ref1">
  </div>
  <div>
    <label>Reference Email</label>
    <input name="referenceemail" value="<?= h((string)($_GET['referenceemail'] ?? '')) ?>" placeholder="ref1@...">
  </div>
  <div>
    <label>Recruiter Name</label>
    <input name="recruiter_name" value="<?= h((string)($_GET['recruiter_name'] ?? '')) ?>" placeholder="Steven Cohen">
  </div>
  <div>
    <label>Date From</label>
    <input name="date_from" value="<?= h((string)($_GET['date_from'] ?? '')) ?>" placeholder="YYYY-MM-DD">
  </div>
  <div>
    <label>Date To</label>
    <input name="date_to" value="<?= h((string)($_GET['date_to'] ?? '')) ?>" placeholder="YYYY-MM-DD">
  </div>

  <div>
    <label>Per Page</label>
    <select name="per_page">
      <?php foreach ([25,50,100,250,500] as $n): ?>
        <option value="<?= $n ?>" <?= $perPage===$n ? 'selected' : '' ?>><?= $n ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label>Sort</label>
    <select name="sort">
      <?php foreach ($allowedSort as $k => $_): ?>
        <option value="<?= h($k) ?>" <?= $sort===$k ? 'selected' : '' ?>><?= h($k) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div>
    <label>Dir</label>
    <select name="dir">
      <option value="DESC" <?= $dir==='DESC' ? 'selected' : '' ?>>DESC</option>
      <option value="ASC"  <?= $dir==='ASC'  ? 'selected' : '' ?>>ASC</option>
    </select>
  </div>

  <div style="display:flex; gap:8px;">
    <button class="btn primary" type="submit">Filter</button>
    <a class="btn" href="?">Reset</a>
    <a class="btn" href="?<?= h(build_qs(['download'=>'1','page'=>null])) ?>">Download CSV</a>
  </div>
</form>

<table>
  <thead>
    <tr>
      <th class="nowrap">ID</th>
      <th class="nowrap">Candidate</th>
      <th>Candidate Name</th>
      <th>Reference</th>
      <th>Reference Email</th>
      <th>Reference Phone</th>
      <th>Relationship</th>
      <th>Recruiter</th>
      <th class="nowrap">Timestamp</th>
    </tr>
  </thead>
  <tbody>
  <?php if ($res->num_rows === 0): ?>
    <tr><td colspan="9">No results.</td></tr>
  <?php else: ?>
    <?php while ($r = $res->fetch_assoc()): ?>
      <?php
        $ts = (string)($r['timestamp'] ?? '');
        if ($ts === '0000-00-00 00:00:00') $ts = '';
      ?>
      <tr>
        <td class="nowrap"><?= (int)$r['id'] ?></td>
        <td class="nowrap"><?= h((string)$r['candidate']) ?></td>
        <td><?= h((string)$r['candidate_name']) ?></td>
        <td>
          <div><b><?= h((string)$r['referencename']) ?></b></div>
          <div class="muted"><?= h((string)$r['referenceurl']) ?></div>
        </td>
        <td><?= h((string)$r['referenceemail']) ?></td>
        <td class="nowrap"><?= h((string)$r['referencephone']) ?></td>
        <td><?= h((string)$r['referencerelationship']) ?></td>
        <td>
          <div><?= h((string)$r['recruiter_name']) ?></div>
          <div class="muted">ID: <?= h((string)$r['recruiter_id']) ?></div>
        </td>
        <td class="nowrap"><?= h($ts) ?></td>
      </tr>
    <?php endwhile; ?>
  <?php endif; ?>
  </tbody>
</table>

<div class="pager">
  <span class="muted">Page <?= (int)$page ?> of <?= (int)$totalPages ?></span>

  <?php if ($page > 1): ?>
    <a class="btn" href="?<?= h(build_qs(['page'=>1])) ?>">First</a>
    <a class="btn" href="?<?= h(build_qs(['page'=>$page-1])) ?>">Prev</a>
  <?php endif; ?>

  <?php if ($page < $totalPages): ?>
    <a class="btn" href="?<?= h(build_qs(['page'=>$page+1])) ?>">Next</a>
    <a class="btn" href="?<?= h(build_qs(['page'=>$totalPages])) ?>">Last</a>
  <?php endif; ?>
</div>

</body>
</html>
