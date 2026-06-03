<?php include 'manatal_header.php'; ?>
<?php
// ic_matches_search_delete.php
// Search & delete in ic_matches using on-page search terms (no file upload)

// --- DB bootstrap ---
require_once __DIR__ . '/../db/db.php';
$link = db();

// --- Helpers ---
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function is_numlike($s){ return preg_match('/^-?\d+(\.\d+)?$/', $s); }
function parse_terms($s){
    // Split on newlines, commas, tabs, semicolons, or pipes
    $parts = preg_split('/[\r\n,\t;|]+/', (string)$s);
    $terms = [];
    foreach ($parts as $p) {
        $t = trim($p, " \t\n\r\0\x0B\"'");
        if ($t !== '') $terms[] = $t;
    }
    return array_values(array_unique($terms));
}

// Columns to SEARCH (everything except created_at & stage_name)
$searchableColumns = [
    'id','external_id','hash','owner','organization','job','candidate','candidate_name','candidate_email',
    'pay_group','file_number','department','job_name','company_name','creator','stage_id',
    // 'stage_name', // excluded
    'is_active','deactive_date','hired_at','interview_at','offer_at','dropped_at',
    // 'created_at', // excluded
    'updated_at','po_number','po_amount','po_end_date','po_note','start_date','end_date',
    'bill_rate','pay_rate','salary','full_time','fee_percent',
    'owner_1_name','owner_1_percent','owner_2_name','owner_2_percent','owner_3_name','owner_3_percent',
    'notes','customer_comments','interview_comments','closed','closed_date','invoice_template','reviewed',
    'mass_email','mass_text','ap_email','portal_users','share','first_viewed_date','last_viewed_date',
    'last_viewed_by','view_count','rating','schedule_interview','interview_time','declined','declined_comments',
    'DisplayCustomResume','expires_at','alternate_date_1','alternate_date_2','alternate_date_3',
    'timeapproveremail','timeapproveremail_b'
];

// Numeric-like columns where we’ll use "=" instead of LIKE when the term looks numeric
$numericish = [
    'id','external_id','owner','organization','job','candidate','is_active','full_time','closed','reviewed',
    'mass_email','mass_text','share','view_count','rating','schedule_interview','declined','DisplayCustomResume',
    'bill_rate','pay_rate','salary','fee_percent','owner_1_percent','owner_2_percent','owner_3_percent','po_amount'
];

$deleteMsg = '';
$rows = [];
$terms = [];
$search_input = $_POST['q'] ?? '';

// Handle DELETE
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action'] ?? '') === 'delete') {
    $ids = array_unique(array_filter(array_map('trim', $_POST['delete_ids'] ?? [])));
    // safety: numeric string up to 10 (matches typical PK patterns you use)
    $ids = array_values(array_filter($ids, fn($v)=>preg_match('/^\d{1,10}$/', $v)));
    if ($ids) {
        $ph = implode(',', array_fill(0, count($ids), '?'));
        $stmt = mysqli_prepare($link, "DELETE FROM ic_matches WHERE id IN ($ph)");
        if ($stmt) {
            $types = str_repeat('s', count($ids));
            mysqli_stmt_bind_param($stmt, $types, ...$ids);
            mysqli_stmt_execute($stmt);
            $affected = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            $deleteMsg = "Deleted $affected record(s).";
        } else {
            $deleteMsg = "Error preparing delete: ".h(mysqli_error($link));
        }
    } else {
        $deleteMsg = 'No valid rows selected to delete.';
    }
}

// Handle SEARCH
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action'] ?? '') === 'search') {
    $terms = parse_terms($search_input);
    if ($terms) {
        $orClauses = [];
        foreach ($terms as $t) {
            $esc = mysqli_real_escape_string($link, $t);
            $perTerm = [];
            foreach ($searchableColumns as $col) {
                if (in_array($col, $numericish, true) && is_numlike($t)) {
                    $perTerm[] = "`$col` = {$esc}";
                } else {
                    $perTerm[] = "`$col` LIKE '%{$esc}%'";
                }
            }
            if ($perTerm) $orClauses[] = '(' . implode(' OR ', $perTerm) . ')';
        }
        $sql = "SELECT id, candidate_name, candidate, job_name, job, organization, company_name, stage_name, created_at
                FROM ic_matches";
        if ($orClauses) $sql .= " WHERE " . implode(' OR ', $orClauses);
        $sql .= " ORDER BY created_at DESC LIMIT 500";
        $result = mysqli_query($link, $sql);
        if ($result) {
            while ($r = mysqli_fetch_assoc($result)) $rows[] = $r;
            mysqli_free_result($result);
        } else {
            $deleteMsg = "Search error: ".h(mysqli_error($link));
        }
    } else {
        $deleteMsg = "Please enter at least one search term.";
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Search & Delete ic_matches</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{font-family:Arial,Helvetica,sans-serif;margin:20px;}
.container{max-width:1100px;margin:0 auto;}
h1{font-size:20px;margin:0 0 12px;}
.note{background:#f4f6f8;padding:10px 12px;border:1px solid #e1e5ea;border-radius:6px;margin:10px 0;}
form.search{display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin:10px 0;}
input[type="text"],textarea{padding:8px;border:1px solid #ccc;border-radius:6px;font-size:14px;min-width:280px;}
textarea{min-height:70px;width:100%;}
button{cursor:pointer;padding:8px 12px;border-radius:6px;border:1px solid #ccc;background:#fff;}
button.primary{background:#0b5fff;color:#fff;border-color:#0b5fff;}
button.danger{background:#b22625;color:#fff;border-color:#b22625;}
.small{font-size:12px;color:#666;}
table{border-collapse:collapse;width:100%;margin-top:12px;}
th,td{border:1px solid #ddd;padding:8px;font-size:13px;vertical-align:top;}
th{background:#fafafa;position:sticky;top:0;}
.actions{display:flex;gap:8px;align-items:center;margin-top:10px;flex-wrap:wrap;}
tfoot td{background:#fafafa;}
a[target="_blank"]{text-decoration:none;}
</style>
<script>
function toggleAll(src){
  document.querySelectorAll('input[name="delete_ids[]"]').forEach(cb=>cb.checked = src.checked);
}
function confirmDelete(){
  const n = document.querySelectorAll('input[name="delete_ids[]"]:checked').length;
  if(!n){ alert('Please select at least one row to delete.'); return false; }
  return confirm('Are you sure you want to PERMANENTLY delete '+n+' record(s) from ic_matches?');
}
</script>
</head>
<body>
<div class="container">
  <h1>Search &amp; Delete <em>ic_matches</em></h1>

  <?php if ($deleteMsg): ?>
    <div class="note"><?=h($deleteMsg)?></div>
  <?php endif; ?>

  <div class="note">
    Enter one or more terms (IDs, names, emails, job titles, etc.).  
    Separate by new lines, commas, tabs, semicolons, or pipes.  
    Searches all fields <strong>except</strong> <code>created_at</code> and <code>stage_name</code>. Shows up to 500 rows.
  </div>

  <form method="post" class="search">
    <input type="hidden" name="action" value="search">
    <textarea name="q" placeholder="e.g. 12345&#10;Jane Doe&#10;Front End Developer&#10;ACME Co"><?=h($search_input)?></textarea>
    <button type="submit" class="primary">Search</button>
    <span class="small">Tip: Numeric terms match numeric-like columns with exact “=”. Everything else uses LIKE.</span>
  </form>

  <?php if ($rows): ?>
    <form method="post" onsubmit="return confirmDelete();">
      <input type="hidden" name="action" value="delete">
      <div class="actions">
        <button type="submit" class="danger">Delete Selected</button>
        <label><input type="checkbox" onclick="toggleAll(this)"> Select all</label>
        <span class="small"><?=count($rows)?> row(s) shown</span>
      </div>

      <table>
        <thead>
          <tr>
            <th style="width:36px;"></th>
            <th>ID</th>
            <th>Candidate Name</th>
            <th>Candidate</th>
            <th>Job Name</th>
            <th>Job</th>
            <th>Organization</th>
            <th>Company Name</th>
            <th>Stage Name</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): 
            $id  = $r['id'];
            $cand= $r['candidate'];
            $org = $r['organization'];
            $job = $r['job'];
          ?>
          <tr>
            <td><input type="checkbox" name="delete_ids[]" value="<?=h($id)?>"></td>
            <td><?=h($id)?></td>
            <td><?=h($r['candidate_name'])?></td>
            <td>
              <?php if ($cand !== null && $cand !== ''): ?>
                <a href="https://app.manatal.com/candidates/<?=h($cand)?>" target="_blank" rel="noopener"><?=h($cand)?></a>
              <?php else: ?>
                <?=h($cand)?>
              <?php endif; ?>
            </td>
            <td><?=h($r['job_name'])?></td>
            <td>
              <?php if ($job !== null && $job !== ''): ?>
                <a href="https://app.manatal.com/jobs/<?=h($job)?>?tab=candidates" target="_blank" rel="noopener"><?=h($job)?></a>
              <?php else: ?>
                <?=h($job)?>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($org !== null && $org !== ''): ?>
                <a href="https://app.manatal.com/clients/<?=h($org)?>?tab=description" target="_blank" rel="noopener"><?=h($org)?></a>
              <?php else: ?>
                <?=h($org)?>
              <?php endif; ?>
            </td>
            <td><?=h($r['company_name'])?></td>
            <td><?=h($r['stage_name'])?></td>
            <td><?=h($r['created_at'])?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="10">
              <div class="actions">
                <button type="submit" class="danger">Delete Selected</button>
                <label><input type="checkbox" onclick="toggleAll(this)"> Select all</label>
              </div>
            </td>
          </tr>
        </tfoot>
      </table>
    </form>
  <?php elseif($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action'] ?? '')==='search'): ?>
    <div class="note">No rows matched your terms.</div>
  <?php endif; ?>
</div>
</body>
</html>
