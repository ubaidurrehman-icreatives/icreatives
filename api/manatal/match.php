<!DOCTYPE html>
<html>
<head>
    <title>Popup Form</title>
<script type="text/javascript">
       function timedMsg() {
            var t = setTimeout("document.getElementById('myMsg').style.display='none';", 4000);
        }
        function closeWindowAfterSubmit() {
            setTimeout(function () { window.close(); }, 2000);
        }
</script>
<style>
    .button { display:inline-block; padding:7px 14px; border:none; background-color:#1976D2; color:#fff; font-size:12px; cursor:pointer; transition:background-color .3s; }
    .button:hover { background-color:#418DDA; }
    .icon { width:20px; height:20px; background:url('clipboard_icon.png') no-repeat; background-size:cover; cursor:pointer; }
</style>
<script>
function copyToClipboard(text) {
    var textarea = document.createElement("textarea");
    textarea.value = text;
    textarea.style.position = "fixed";
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand("copy");
    document.body.removeChild(textarea);
    var notification = document.createElement("div");
    notification.textContent = "Match Number copied to clipboard: " + text;
    notification.style.position = "fixed";
    notification.style.top = "50px";
    notification.style.left = "50%";
    notification.style.transform = "translateX(-50%)";
    notification.style.background = "#89CFF0";
    notification.style.padding = "10px";
    notification.style.borderRadius = "5px";
    document.body.appendChild(notification);
    setTimeout(function() { document.body.removeChild(notification); }, 2000);
}
</script>
<script>
function openCompanySmall(url) {
  window.open(
    url,
    'companyWin',
    'width=800,height=600,left=200,top=120,scrollbars=yes,resizable=yes,' +
    'toolbar=no,menubar=no,location=no,status=no'
  );
  return false; // prevent normal navigation
}
</script>
</head>
<body>
<?php
/*
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
 */
function generateDropdownOptions($userNames, $x) {
    $options = '';
    foreach ($userNames as $userName) {
        // Skip if no email or not from icreatives.com
        if (
            empty($userName['email']) ||
            stripos($userName['email'], '@icreatives.com') === false
        ) {
            continue;
        }

        $selected = ($x == $userName['display_name']) ? 'selected' : '';
        $options .= '<option value="' . htmlspecialchars($userName['display_name']) . '" ' . $selected . '>' 
                  . htmlspecialchars($userName['display_name']) . '</option>' . PHP_EOL;
    }
    return $options;
}


$sucess = false;
$matchId  = $_GET['match_id'];
$match_id = $_REQUEST['match_id'];

require_once  dirname(__DIR__) . '/../vendor/autoload.php';
require_once  dirname(__DIR__) . '/../db/token.php';
require_once  dirname(__DIR__) . '/../db/db.php';
//             'Authorization' => 'Token 92e3967b096dc33e0f09df8c0a927ec0437d8942',
$link = db();   


use GuzzleHttp\Exception\ClientException;
$client =  new \GuzzleHttp\Client(['timeout' => 5.0,'connect_timeout' => 3.0]);

// Load match
try {
    $response = $client->request('GET', 'https://api.manatal.com/open/v3/matches/'.$matchId.'/', [
        'headers' => [
          'Authorization' => $token,
            'accept' => 'application/json',
        ],
    ]);
    $match = json_decode($response->getBody(), true);
} catch (\GuzzleHttp\Exception\ClientException $e) {
    $body = (string) $e->getResponse()->getBody();
    $json = json_decode($body, true);
    $wait = 60;
    if (isset($json['detail']) && preg_match('/available in (\d+) seconds/', $json['detail'], $matches)) {
        $wait = $matches[1];
    }
    echo "<p> </p><p>The server is busy. Please wait {$wait} seconds. The page will reload automatically.</p>";
    echo "<script>setTimeout(() => { location.reload(); }, " . ($wait * 1000) . ");</script>";
    exit();
}

$job_id       = $match['job'];
$candidate_id = $match['candidate'];

// Company settings (A/P + PO requirement)
$sql    = "SELECT * FROM ic_company WHERE organization = '".$match['organization']."'";
$result = $link->query($sql);

if ($result->num_rows == 0) {
    // No matching records found, show custom alert and close window
    echo "<script type='text/javascript'>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = document.createElement('div');
                modal.style.position = 'fixed';
                modal.style.left = '50%';
                modal.style.top = '50%';
                modal.style.transform = 'translate(-50%, -50%)';
                modal.style.padding = '20px';
                modal.style.backgroundColor = '#89CFF0';
                modal.style.border = '1px solid #ccc';
                modal.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
                modal.style.width = '300px';
                modal.style.textAlign = 'center';
                modal.style.zIndex = '1000';
                var message = document.createElement('p');
                message.innerText = 'A/P person not set';
                message.style.margin = '0';
                message.style.padding = '10px 0';
                var button = document.createElement('button');
                button.innerText = 'Close';
                button.onclick = function() { document.body.removeChild(modal); window.close(); };
                modal.appendChild(message); modal.appendChild(button); document.body.appendChild(modal);
            });
          </script>";
		  mysql_close() ;
    exit();
}
$company      = $result->fetch_assoc();
$po_required  = (bool)$company['po_required'];
$drug  = (bool)$company['drug'];
$background  = (bool)$company['background'];
$drug_back_period  = (bool)$company['drug_back_period'];

// Candidate info

try {
	$response  = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/'.$candidate_id.'/', [
	'headers' => ['Authorization' => $token,'accept' => 'application/json'],
	]);
	$candidate = json_decode($response->getBody(), true);
} catch (\GuzzleHttp\Exception\ClientException $e) {
    $body = (string) $e->getResponse()->getBody();
    $json = json_decode($body, true);
    $wait = 60;
    if (isset($json['detail']) && preg_match('/available in (\d+) seconds/', $json['detail'], $matches)) {
        $wait = $matches[1];
    }
    echo "<p> </p><p>The server is busy. Please wait {$wait} seconds. The page will reload automatically.</p>";
    echo "<script>setTimeout(() => { location.reload(); }, " . ($wait * 1000) . ");</script>";
    exit();
}



// Job info
try {
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/'.$job_id.'/', [
	'headers' => ['Authorization' => $token,'accept' => 'application/json'],
	]);
	$job                  = json_decode($response->getBody(), true);
} catch (\GuzzleHttp\Exception\ClientException $e) {
    $body = (string) $e->getResponse()->getBody();
    $json = json_decode($body, true);
    $wait = 60;
    if (isset($json['detail']) && preg_match('/available in (\d+) seconds/', $json['detail'], $matches)) {
        $wait = $matches[1];
    }
    echo "<p> </p><p>The server is busy. Please wait {$wait} seconds. The page will reload automatically.</p>";
    echo "<script>setTimeout(() => { location.reload(); }, " . ($wait * 1000) . ");</script>";
    exit();
}




$hash                 = $job['hash'];
$poamount             = $job['custom_fields']['poamount'] ?? 0;
$ponumber             = trim((string)($job['custom_fields']['ponumber'] ?? '')); // <— normalized PO number
$timeapproveremail    = $job['custom_fields']['timeapproveremail'] ?? '';
$timeapproveremail_b  = $job['custom_fields']['timeapproveremail_b'] ?? '';

// GATE: block rate entry only if company requires PO and job has NO PO
$po_gate = ($po_required && $ponumber === ''); // TRUE means block


// Defaults
$drug_gate = true;
$background_gate = true;

// ----- REQUIRED CONFIGS FROM DB / API -----
$drug_required       = filter_var($company['drug'] ?? false, FILTER_VALIDATE_BOOLEAN);
$background_required = filter_var($company['background'] ?? false, FILTER_VALIDATE_BOOLEAN);

// months: make sure this is an INT (not bool). If you have different periods for drug/bg, split them.
$period_months = (int)($company['drug_back_period'] ?? 0);

// candidate-provided dates (may be empty or include time)
$drug_date_str       = (string)($candidate['custom_fields']['drug'] ?? '');
$background_date_str = (string)($candidate['custom_fields']['background'] ?? '');

// ----- HELPER: compute gate for a given requirement/date/period -----
// Gate rule: return FALSE (gate off) if today < (start_date + period months); otherwise TRUE.
function compute_gate(string $dateStr, bool $required, int $months): bool {
    if (!$required) return false; // not required => gate off

    // Normalize to YYYY-MM-DD (strip any time portion like 2025-08-01T00:00:00)
    $dateStr = substr(trim($dateStr), 0, 10);
    if ($dateStr === '' || $dateStr === '0000-00-00') return true; // no valid date => gate on

    $dt = DateTime::createFromFormat('Y-m-d', $dateStr);
    $valid = $dt && $dt->format('Y-m-d') === $dateStr;
    if (!$valid) return true; // bad format => gate on

    $today     = new DateTime('today');
    $windowEnd = (clone $dt)->add(new DateInterval('P' . max(0, $months) . 'M'));

    // If today is strictly earlier than windowEnd => still within validity window => gate OFF
    // If today is same day or after windowEnd => gate ON
    return !($today < $windowEnd);
}

// ----- FINAL GATES -----
$drug_gate       = compute_gate($drug_date_str,       $drug_required,       $period_months);
$background_gate = compute_gate($background_date_str, $background_required, $period_months);


// --- SYNC: ensure ic_matches.candidate matches API (handles merged candidates) ---
// Keep ic_matches.candidate aligned with the API after merges; don't touch email
$matchIdDb   = mysqli_real_escape_string($link, (string)$match['id']);
$newCandIdDb = mysqli_real_escape_string($link, (string)$match['candidate']);
$candEmailDb = mysqli_real_escape_string($link, (string)$candidate['email']);
$jobIdDb     = mysqli_real_escape_string($link, (string)$match['job']);


// This will make sure all timesheets have the correct employee id number if the candidate was merged.
if (!empty($newCandIdDb)) {
    mysqli_query($link, "
      UPDATE ic_timesheets 
         SET Employee_ID = '{$newCandIdDb}' 
       WHERE Email = '{$candEmailDb}' 
    ");
	// mysql_close() ;
}

/*
// This will make sure all timesheets have the correct email address number if the candidate's email was changed
if (!empty($candEmailDb)) {
    mysqli_query($link, "
      UPDATE ic_timesheets 
         SET Email = '{$candEmailDb}' 
       WHERE Employee_ID = '{$newCandIdDb}' 
    ");
	// mysql_close() ;
}
*/

if (!empty($ponumber)) {
    mysqli_query($link, "
      UPDATE ic_timesheets 
         SET po_number = '{$ponumber}' 
       WHERE Email = '{$candEmailDb}' 
         AND (AssignmentNumber = '{$jobIdDb}' OR Assignment_ID = '{$jobIdDb}')
    ");
	// mysql_close() ;
}


if (!empty($newCandIdDb)) {
    mysqli_query($link, "
      UPDATE ic_matches 
         SET candidate = '{$newCandIdDb}' 
       WHERE id = '{$matchIdDb}'  
    ");
	// mysql_close() ;
}

// --- END SYNC ---


// calculate how much of the PO has been spent
$query = "SELECT SUM(ROUND(ts.hours * ts.billrate,2)) AS spent FROM ic_timesheets ts  
          JOIN ic_matches m ON ts.AssignmentNumber = m.job AND ts.Employee_ID = m.candidate 
          WHERE TRIM(m.po_number) = '". trim($ponumber). "' AND (ts.void <> 1) ";
$sum   = mysqli_query($link, $query);
$rowS  = mysqli_fetch_array($sum);
$spent = $rowS['spent'] ?? 0;
// mysql_close();
// Company name / org
try {
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/organizations/'.$match['organization'].'/', [
	'headers' => ['Authorization' => $token,'accept' => 'application/json'],
	]);
	$organization = json_decode($response->getBody(), true);
} catch (\GuzzleHttp\Exception\ClientException $e) {
    $body = (string) $e->getResponse()->getBody();
    $json = json_decode($body, true);
    $wait = 60;
    if (isset($json['detail']) && preg_match('/available in (\d+) seconds/', $json['detail'], $matches)) {
        $wait = $matches[1];
    }
    echo "<p> </p><p>The server is busy. Please wait {$wait} seconds. The page will reload automatically.</p>";
    echo "<script>setTimeout(() => { location.reload(); }, " . ($wait * 1000) . ");</script>";
    exit();
}
	
	
	
$dnsb         = $organization['custom_fields']['dnsb'] ?? 0;

$banned = (is_array($dnsb) && !empty($dnsb) && in_array($candidate_id, $dnsb, true)) ? 1 : 0;

// add pop up code for DNSB share checkbox
if ($banned == 1) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var shareCheckbox = document.getElementById("share");
            if (shareCheckbox) {
                shareCheckbox.addEventListener("change", function(e) {
                    if (shareCheckbox.checked) {
                        alert("Share cannot be checked unless you remove the candidate from the Customer DNSB field");
                        shareCheckbox.checked = false;
                    }
                });
            }
        });
    </script>';
}

// Users (dropdown)
try {
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/users/', [
	'headers' => ['Authorization' => $token,'accept' => 'application/json'],
										]);
	$users = json_decode($response->getBody(), true);
} catch (\GuzzleHttp\Exception\ClientException $e) {
    $body = (string) $e->getResponse()->getBody();
    $json = json_decode($body, true);
    $wait = 60;
    if (isset($json['detail']) && preg_match('/available in (\d+) seconds/', $json['detail'], $matches)) {
        $wait = $matches[1];
    }
    echo "<p> </p><p>The server is busy. Please wait {$wait} seconds. The page will reload automatically.</p>";
    echo "<script>setTimeout(() => { location.reload(); }, " . ($wait * 1000) . ");</script>";
    exit();
}

function getUserIdByFullName($users, $fullName) {
    if (!isset($users['results']) || !is_array($users['results'])) return null;
    $fullName = trim(strtolower($fullName));
    foreach ($users['results'] as $user) {
        if (isset($user['display_name']) && strtolower(trim($user['display_name'])) === $fullName) {
            return $user['id'];
        }
    }
    return null;
}


// POST handler
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Read posted values ONCE
    $id            = $_POST["match_id"];
    $match_id      = $_POST["match_id"];
    $hash          = $_POST["hash"];
    $share         = $_POST["share"];
    $declined      = ($_POST["declined"] ?? 0);
    $bill_rate     = $_POST["bill_rate"] ?? '';
    $pay_rate      = $_POST["pay_rate"] ?? '';
    $po_number     = $_POST["po_number"] ?? '';
    $start_date    = $_POST["start_date"] ?? '';
    $end_date      = $_POST["end_date"] ?? '';
    $salary        = $_POST["salary"] ?? '';
    $fee_percent   = $_POST["fee_percent"] ?? '';
    $owner_1_name  = $_POST["owner_1_name"];
    $owner_1_percent = ( ($_POST["owner_1_percent"] == 0 || is_null($_POST["owner_1_percent"]))  
                      && ($_POST["owner_2_percent"] == 0 || is_null($_POST["owner_2_percent"]))  
                      && ($_POST["owner_3_percent"] == 0 || is_null($_POST["owner_3_percent"])) ) ? 100.00 : $_POST["owner_1_percent"];
    $owner_2_name  = $_POST["owner_2_name"];
    $owner_2_percent = $_POST["owner_2_percent"];
    $owner_3_name  = $_POST["owner_3_name"];
    $owner_3_percent = $_POST["owner_3_percent"];
    $candidate_id  = $_POST["candidate_id"];
    $candidate_name= $_POST['candidate_name'];
    $notes         = $_POST["notes"];
    $closed        = $_POST["closed"];
    $deactive_date = $_POST["deactive_date"];
    $is_active     = $_POST['is_active'];
    if ($closed <> 1) { $closed = 0; }

    if ($is_active == 0 && ($_POST['deactive_date'] == "0000-00-00" || empty($_POST['deactive_date']) )) {
        $deactive_date = date('Y-m-d'); $closed = 1;
    }
    if ($is_active == 1) {
        $deactive_date = "0000-00-00"; $closed = 0;
    }
    if (empty($_POST['deactive_date'])) { $deactive_date="0000-00-00"; }

    $closed_date = $_POST["closed_date"];
    if (empty($closed_date)) { $closed_date = "0000-00-00"; }
    $mass_email  = $_POST["mass_email"];
    $mass_text   = $_POST["mass_text"];
    $full_time   = $_POST["full_time"];

    // Pull job PO end date as you had
    if (isset($job['custom_fields']['poenddate']) && !is_null($job['custom_fields']['poenddate']) ) {
        $po_end_date = $job['custom_fields']['poenddate'];
    } else {
        $po_end_date = "0000-00-00";
    }

    // Calculate expires_at (today + 90 days)
    $expires_at = date('Y-m-d', strtotime('+90 days'));

    // Update match owner to owner_1_name (found candidate)
    if (isset($owner_1_name)) {
        // $userId = getUserIdByFullName($users, $owner_1_name);
        $userId = $candidate['owner'];
        if ($userId) {

			$client2 =  new \GuzzleHttp\Client(['timeout' => 5.0,'connect_timeout' => 3.0]);
			try {
				$response = $client2->request('PATCH', 'https://api.manatal.com/open/v3/matches/'.$match_id.'/', [
                'body' => '{"owner":'.$userId.'}',
                'headers' => [
                    'Authorization' => $token,
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ],
				]);
			} catch (\GuzzleHttp\Exception\ClientException $e) {
				$body = (string) $e->getResponse()->getBody();
				$json = json_decode($body, true);
				$wait = 60;
				if (isset($json['detail']) && preg_match('/available in (\d+) seconds/', $json['detail'], $matches)) {
					$wait = $matches[1];
				}
				echo "<p> </p><p>The server is busy. Please wait {$wait} seconds. The page will reload automatically.</p>";
				echo "<script>setTimeout(() => { location.reload(); }, " . ($wait * 1000) . ");</script>";
				exit();
			}
            // $response->getBody(); // not echoed to avoid stray output
        }
    }

    // ===== ENFORCEMENT: Only when company requires PO AND job has NO PO =====
    if ($po_gate) {
        // If any values are present, block save and alert
        $bill_f = floatval($bill_rate);
        $pay_f  = floatval($pay_rate);
        $salary_trim = trim((string)$salary);
        $salary_f = is_numeric($salary_trim) ? floatval($salary_trim) : 0.0;
        $salary_has_value = ($salary_trim !== '') && ($salary_f > 0 || !is_numeric($salary_trim));
        if ($bill_f > 0 || $pay_f > 0 || $salary_has_value) {
            echo "<script>alert('A PO number is required before entering Bill Rate, Pay Rate, or Salary. Please add a PO to the job first.');</script>";
            exit();
        }
        // Normalize to zero so nothing slips in
        $bill_rate = 0.00;
        $pay_rate  = 0.00;
        $salary    = '0.00';
    }
    // ===== END ENFORCEMENT =====

    // Insert / Update
    $sql = "INSERT INTO ic_matches (
        id, external_id, hash, share, declined, owner, organization, job, candidate, candidate_name, candidate_email,
        pay_group, file_number, department, creator, stage_id, stage_name, is_active, deactive_date,
        po_number, po_amount, po_end_date, po_note, ap_email, timeapproveremail, timeapproveremail_b,
        start_date, end_date, bill_rate, pay_rate, salary, fee_percent, owner_1_name, owner_1_percent,
        owner_2_name, owner_2_percent, owner_3_name, owner_3_percent, closed, closed_date, expires_at, notes,
        company_name, job_name, mass_email, full_time, portal_users, mass_text
    ) VALUES ('".
        $match['id']."', '".
        $match['external_id']."', '".
        $job['hash']."', '".
        $share."', '".
        $declined."', '".
        $match['owner']."', '".
        $match['organization']."', '".
        addslashes($match['job'])."', '".
        addslashes($match['candidate'])."', '".
        addslashes($candidate['full_name'])."', '".
        $candidate['email']."', '".
        $candidate['custom_fields']['paygroup']."', '".
        $candidate['custom_fields']['adpnumber']."', '".
        $candidate['custom_fields']['department']."', '".
        $match['creator']."', '".
        $match['stage']['id']."', '".
        addslashes($match['stage']['name'])."', ".
        $is_active.", '".
        $deactive_date."', '".
        addslashes($job['custom_fields']['ponumber'])."', ".
        $job['custom_fields']['poamount'].", '".
        $po_end_date ."', '".
        addslashes($job['custom_fields']['ponote'])."', '".
        addslashes($job['custom_fields']['apinvoiceemailcommadelimited'])."', '".
        addslashes($job['custom_fields']['timeapproveremail'])."', '".
        addslashes($job['custom_fields']['timeapproveremail_b'])."', '".
        $start_date."', '".
        $end_date."', ".
        $bill_rate.",".
        $pay_rate.", '".
        addslashes($salary)."', ".
        $fee_percent.", '".
        $owner_1_name."', ".
        $owner_1_percent.", '".
        $owner_2_name."', ".
        $owner_2_percent.", '".
        $owner_3_name."', ".
        $owner_3_percent.", ".
        $closed.", '".
        $closed_date."', '".
        $expires_at."', '".
        addslashes($notes)."', '".
        addslashes($organization['name'])."', '".
        addslashes($job['position_name'])."', ".
        $mass_email.",".
        $full_time.",'".
        implode(', ',(array)$job['custom_fields']['portalusers'])."',".
        $mass_text.")
    ON DUPLICATE KEY UPDATE 
        external_id = VALUES(external_id),
        hash = VALUES(hash),
        share = VALUES(share),
        declined = VALUES(declined),
        owner = VALUES(owner),
        organization = VALUES(organization),
        job = VALUES(job),
        candidate = VALUES(candidate),
        candidate_name = VALUES(candidate_name),
        candidate_email = VALUES(candidate_email),
        pay_group = VALUES(pay_group),
        file_number = VALUES(file_number),
        department = VALUES(department),
        creator = VALUES(creator),
        stage_id = VALUES(stage_id),
        stage_name = VALUES(stage_name),
        is_active = VALUES(is_active),
        deactive_date = VALUES(deactive_date),
        po_number = VALUES(po_number),
        po_amount = VALUES(po_amount),
        po_end_date = VALUES(po_end_date),
        po_note = VALUES(po_note),
        ap_email = VALUES(ap_email),
        timeapproveremail = VALUES(timeapproveremail),
        timeapproveremail_b = VALUES(timeapproveremail_b),
        start_date = VALUES(start_date),
        end_date = VALUES(end_date),
        bill_rate = VALUES(bill_rate),
        pay_rate = VALUES(pay_rate),
        salary = VALUES(salary),
        fee_percent = VALUES(fee_percent),
        owner_1_name = VALUES(owner_1_name),
        owner_1_percent = VALUES(owner_1_percent),
        owner_2_name = VALUES(owner_2_name),
        owner_2_percent = VALUES(owner_2_percent),
        owner_3_name = VALUES(owner_3_name),
        owner_3_percent = VALUES(owner_3_percent),
        closed = VALUES(closed), 
        closed_date = VALUES(closed_date), 
        expires_at = VALUES(expires_at), 
        notes = VALUES(notes),
        company_name = VALUES(company_name),
        job_name = VALUES(job_name),
        mass_email = VALUES(mass_email),
        full_time = VALUES(full_time),
        portal_users = VALUES(portal_users),
        mass_text = VALUES(mass_text)";

    $sql = str_replace(", ,",",0,",$sql);
    $sql = str_replace(",,",",0,",$sql);
    $sql = str_replace("''","NULL",$sql);
    $sql = str_replace(" = ,"," = NULL,",$sql);

    $sucess = false;
    if ($link->query($sql) === TRUE) {
        $sucess = true;
    } else {
        echo "Error: " . $sql . "<br>" . $link->error;
    }
// exit();
    echo '<script language="JavaScript" type="text/javascript">timedMsg()</script>';
    echo '<script language="JavaScript" type="text/javascript">window.close();</script>';
	// mysql_close() ;
    exit();
}

// Load existing row
$query = "select * from ic_matches where id = '".$match_id."'";
$SQLr  = mysqli_query($link,$query );
$row   = mysqli_fetch_array($SQLr);
$pre_closed    = $row['closed'] ?? 0;
$deactive_date = $row['deactive_date'] ?? "0000-00-00";
// mysql_close() ;
if (isset($row['declined']) && $row['declined'] == 1) {
    echo '<div style="color:#b22625; float:left; font-family: Arial; font-size:14px;"<br><strong>Dropped by Customer: &nbsp;</strong> </div>';
}
$closed      = $row['closed'] ?? 0;
$closed_date = $row['closed_date'] ?? '0000-00-00';
?>
<div style="float:left; font-family: Arial; font-size:14px;"><strong><?php echo $candidate['full_name']?></strong> (Match ID: <?php echo $matchId; ?>) &nbsp;&nbsp;</div>
<div style="float:left;" class="icon" onclick="copyToClipboard('<?php echo $matchId; ?>')"></div>

<div style="height:11px; clear:both; font-family: Arial; font-size:10px;"><br><strong>Position:</strong> <?php echo $job['position_name'] ?></div>
<div style="height:11px; padding:bottom:20px; font-family: Arial; font-size:10px;"><br><strong>Company:</strong> <?php echo $organization['name']; ?></div>
<p>

<form action="/api/manatal/match.php/?match_id=<?php echo $matchId; ?>" method="post">
    <input type="hidden" name="match_id" value="<?php echo $matchId; ?>">
    <input type="hidden" name="owner" value="<?php echo $match['owner']; ?>">
    <input type="hidden" name="candidate" value="<?php echo $match['candidate']; ?>">
    <input type="hidden" name="deactive_date" value="<?php echo $deactive_date; ?>">
    <input type="hidden" name="candidate_id" value="<?php echo $candidate['full_name']; ?>">
    <input type="hidden" name="hash" value="<?php echo $job['hash']; ?>">
    <input type="hidden" name="candidate_email" value="<?php echo $candidate['email']; ?>">
    <input type="hidden" name="candidate_name" value="<?php echo $candidate['full_name']; ?>">
    <input type="hidden" name="portal_users" value="<?php echo $job['custom_fields']['portalusers']; ?>">
    <input type="hidden" name="closed" value="<?php echo $closed; ?>">
    <input type="hidden" name="closed_date" value="<?php echo $closed_date; ?>">
    <input type="hidden" name="creator" value="<?php echo $match['creator']; ?>">
    <!-- JS gate flag -->
    <input type="hidden" id="po_gate_flag" value="<?php echo $po_gate ? '1' : '0'; ?>">
	<input type="hidden" id="drug_gate_flag" value="<?php echo $drug_gate ? '1' : '0'; ?>">
	<input type="hidden" id="background_gate_flag" value="<?php echo $background_gate ? '1' : '0'; ?>">
    <table style="font-family: Arial; font-size:14px;">
        <tr>
            <td>Last Viewed:</td>
            <td><?php echo ($row['last_viewed_date'] ?? '0000-00-00'). " - Count = ".($row['view_count'] ?? 0); ?></td>
        </tr>
        <tr>
            <?php echo "<td>Customer Rating:</td><td>".str_repeat("&#9733",($row['rating'] ?? 0)).str_repeat("&#9734;",(5-($row['rating'] ?? 0)))."</td>"; ?>
        </tr>
		<?php if(isset($ponumber) && $ponumber !== '') { ?>
        <tr>
            <td>PO Number: </td><td><?php echo ($ponumber ?? ''); ?><br></td>
        </tr>
        <tr>
            <td>PO Amount </td><td>$<?php echo isset($poamount) ? number_format($poamount,2) : ''; ?><br></td>
        </tr>
        <tr>
            <td>PO Spent </td>
            <?php 
                if ($spent > ($poamount*.90)) {
                    echo "<td style='color: red;'>$" . number_format($spent, 2) . "</td>";
                } else {
                    echo "<td>$" . number_format($spent, 2) . "</td>";
                }
            ?>
            <br>
            </td>
        </tr>
		<?php } ?>
<?php if ($po_gate) { ?>
  <tr>
    <td colspan="2" style="color:#b22625; ">
      <b><br>
        ALERT: A Purchase Order is required for rates
		<!-- - 
		<a href="https://www.icreatives.com/api/company.php?id=<?php echo urlencode($match['organization']); ?>&tab=description"
		onclick="return openCompanySmall(this.href)"
		style="color: red; text-decoration: underline;">
		View Company
		</a>  -->
      </br> </br></b>
    </td>
  </tr>
<?php } ?>


        <tr>
            <td>Pay Rate:</td>
            <td>
                <input type="number" step="0.01" name="pay_rate" data-type="currency"
                    value="<?php echo ($row['pay_rate'] === null) ? "0.00" : $row['pay_rate']; ?>"
                    <?php echo $po_gate ? 'disabled' : ''; ?>>
            </td>
        </tr>
        <tr>
            <td>Bill Rate:</td>
            <td>
                <input type="number" step="0.01" name="bill_rate" data-type="currency"
                    value="<?php echo ($row['bill_rate'] === null) ? "0.00" : $row['bill_rate']; ?>"
                    <?php echo $po_gate ? 'disabled' : ''; ?>>
            </td>
        </tr>
        <tr>
            <td>Salary:</td>
            <td>
                <input type="money" name="salary"
                    value="<?php echo ($row['salary'] ?? ''); ?>"
                    <?php echo $po_gate ? 'disabled' : ''; ?>>
            </td>
        </tr>

        <tr>
            <td>Fee Percent:</td>
            <td><input type="number" step="0.01" name="fee_percent" value="<?php echo $row['fee_percent'] ?>"><br></td>
        </tr>

        <tr>
            <td>Timesheet Status:</td>
            <td>
                <select name="is_active">
                    <option value="0" <?php if (empty($row['is_active']) || $row['is_active'] == 0) { echo "selected"; } ?>>Not Active</option>
                    <option value="1"  <?php if (!empty($row['is_active']) && $row['is_active'] == 1) { echo "selected"; } ?>>Active</option>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Bill Full_time:
                <input type="hidden" name="full_time" value="0">
                <input type="checkbox" name="full_time" value="1" <?php echo (($row['full_time'] ?? 0) == 1) ? 'checked' : ''; ?>>
            </td>
        </tr>

        <tr>
            <td>Portal Share</td>
            <td>
                <input type="hidden" name="share" value="0">
                <input type="checkbox" name="share" id="share" value="1" <?php echo (($row['share'] ?? 0)== 1) ? 'checked' : ''; ?>>
                <?php echo "Expires: ". ($row['expires_at'] ?? '0000-00-00'); ?>
            </td>
        </tr>

        <tr>
            <td>Candidate Owner:</td>
            <td>
                <?php $dropdownOptions = generateDropdownOptions(($users['results'] ?? 0),($row['owner_1_name'] ?? '')); ?>
                <select name="owner_1_name" required>
                    <option value="" <?php if(($row['owner_1_name'] ?? '')== ""){echo "selected";}?>></option>
                    <?php echo $dropdownOptions ; ?>
                </select><br>
            </td>
        </tr>
        <tr>
            <td>Candidate Owner %:</td><td><input type="number" step=0.01 name="owner_1_percent" value="<?php echo ($row['owner_1_percent'] ?? 0) ?>"><br></td>
        </tr>
        <tr>
            <td>Owner 2 Name:</td>
            <td>
                <?php $dropdownOptions = generateDropdownOptions(($users['results'] ?? 0),($row['owner_2_name'] ?? '')); ?>
                <select name="owner_2_name">
                    <option value="" <?php if(($row['owner_2_name'] ?? '')== ""){echo "selected";}?>></option>
                    <?php echo $dropdownOptions ; ?>
                </select><br>
            </td>
        </tr>
        <tr>
            <td>Owner 2 Percent:</td>
            <td><input type="number" step=0.01 name="owner_2_percent" value="<?php echo ($row['owner_2_percent'] ?? 0) ?>"><br></td>
        </tr>
        <tr>
            <td>Owner 3 Name:</td>
            <td>
                <?php $dropdownOptions = generateDropdownOptions(($users['results'] ?? 0),($row['owner_3_name'] ?? '')); ?>
                <select name="owner_3_name">
                    <option value="" <?php if(($row['owner_3_name'] ?? '')== ""){echo "selected";}?>></option>
                    <?php echo $dropdownOptions ; ?>
                </select><br>
            </td>
        </tr>
        <tr>
            <td>Owner 3 Percent:</td><td><input type="number" step=0.01 name="owner_3_percent" value="<?php echo ($row['owner_3_percent'] ?? '') ?>"><br></td>
        </tr>

        <tr>
            <td>Start Date:</td>
            <td><input type="date" name="start_date" value="<?php if($row['start_date'] == null){echo "";}else{ echo date("Y-m-d", strtotime($row['start_date']));} ?>" ><br></td>
        </tr>
        <tr>
            <td>End Date:</td>
            <td><input type="date" name="end_date" value="<?php if($row['end_date'] == null){echo "";}else{ echo date("Y-m-d", strtotime($row['end_date']));} ?>" ><br></td>
        </tr>
		<?php if  (!empty($row['declined']) && $row['declined'] == 1) {?>
		
		       <tr>
            <td>Clear Customer Declined</td>
            <td>
                <input type="hidden" name="declined" value="0">
                <input type="checkbox" name="declined" id="declined value="<?php echo ($row['declined'] ?? 0) ?>" <?php echo (($row['declined'] ?? 0) == 1) ? 'checked' : ''; ?>>
            </td>
        </tr>

		
		<?php } ?>
        <tr>
            <td>Mass Email:</td>
            <td>
                <select name="mass_email">
                    <option value=false <?php if(($row['mass_email'] ?? 0) == 0){ echo "selected";}?>>Clear</option>
                    <option value=true  <?php if(($row['mass_email'] ?? 0)== 1){ echo "selected";}?>>Sent</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Mass Text:</td>
            <td>
                <select name="mass_text">
                    <option value=false <?php if(($row['mass_text'] ?? 0) == 0){ echo "selected";}?>>Clear</option>
                    <option value=true  <?php if(($row['mass_text'] ?? 0) == 1){ echo "selected";}?>>Sent</option>
                </select>
            </td>
        </tr>

        <tr>
            <td><input type="submit" value="SAVE" class="button"></td>
            <td align="right"><input type="button" class="button" name="cancelvalue" value="CANCEL" onClick="window.close();"></td>
        </tr>

        <tr>
            <td>Customer Comments:</td>
            <td><?php echo ($row['customer_comments'] ?? '') ?></td>
        </tr>
    </table>
</form>

<script>
// Client-side hard stop when gate is ON (po_required && no PO number on job)
document.addEventListener('DOMContentLoaded', function () {
  var form = document.querySelector('form[action^="/api/manatal/match.php"]');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    var gateOn = document.getElementById('po_gate_flag')?.value === '1';
    if (!gateOn) return;

    var bill = parseFloat((form.querySelector('input[name="bill_rate"]')?.value || '').trim()) || 0;
    var pay  = parseFloat((form.querySelector('input[name="pay_rate"]')?.value  || '').trim()) || 0;
    var salaryRaw = (form.querySelector('input[name="salary"]')?.value || '').trim();
    var salaryVal = parseFloat(salaryRaw);
    var salaryHasValue = salaryRaw !== '' && (!isNaN(salaryVal) ? salaryVal > 0 : true);

    if (bill > 0 || pay > 0 || salaryHasValue) {
      alert('A PO number is required before entering Bill Rate, Pay Rate, or Salary. Please add a PO to the job first.');
      e.preventDefault();
      return false;
    }
  });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var form = document.querySelector('form[action^="/api/manatal/match.php"]');
  if (!form) return;

  var isActiveSelect = form.querySelector('select[name="is_active"]');
  if (!isActiveSelect) return;

  var drugGate = document.getElementById('drug_gate_flag')?.value === '1';
  var bgGate   = document.getElementById('background_gate_flag')?.value === '1';

  function gateMessage() {
    var parts = [];
    if (bgGate)   parts.push('a background check');
    if (drugGate) parts.push('a drug test');
    return 'You cannot set Timesheet Status to Active until the candidate has completed ' +
           (parts.length === 2 ? parts.join(' and ') : parts[0]) + '.';
  }

  // Let them select “Active” so the popup appears, then revert to Not Active.
  isActiveSelect.addEventListener('change', function () {
    if (this.value === '1' && (drugGate || bgGate)) {
      alert(gateMessage());
      this.value = '0'; // revert after showing message
    }
  });

  // Double-check on submit in case value was forced or set programmatically
  form.addEventListener('submit', function (e) {
    if (isActiveSelect.value === '1' && (drugGate || bgGate)) {
      alert(gateMessage());
      e.preventDefault();
      return false;
    }
  });
});
</script>


<p><br><p>
</body>
</html>
