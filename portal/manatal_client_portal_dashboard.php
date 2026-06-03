<html>
<head>
<?php
/*
 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
ini_set('session.cookie_lifetime', 7776000); // 3 months in seconds

session_start();
/*
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['post_snapshot'] = $_POST; // if you need it on GET
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?view=1', true, 303);
    exit;
}

*/

$contactID = $_SESSION['contactID'] ?? '';

if (!isset($_SESSION['users_arr'])) {
	  header("Location: /portal/manatal_client_portal_login.php");
		exit();
}
?>

<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>

<script>
window.addEventListener('message', function(e){
  if (e.origin !== 'https://www.icreatives.com') return;
  if (e.data && e.data.type === 'REFRESH_IFRAME') {
    location.replace(location.href.split('#')[0] + '?bf=' + Date.now());
  }
});

</script>


</head>
<body>

<?php

require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';
$link = db();   
/*
	$db   = "dbs14831214";
$dbuser = "dbu415258";
$pw   = 'pZCD@4ZCSgA$$E!';
$host = "db5018755071.hosting-data.io";

$link = mysqli_connect($host, $dbuser, $pw, $db) or die("Error: " . mysqli_error());
*/
// echo "OOO".$_REQUEST['id']."CCC";
// $_SESSION['contactID'] = $_REQUEST['id'];
function encrypt_string($plaintext) {
$key = "4b14783a4b5848cd43b9fc7e4cf0fb6f";
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
$iv = openssl_random_pseudo_bytes($ivlen); 
$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true); 
// Encrypted string 
return  base64_encode($iv.$hmac.$ciphertext_raw);
}

function calculateInterest($invoiceDate, $invoiceAmount, $termsInDays) {
    $interestRate = 0.18; // 18% interest rate

    // Calculate today's date
    $todayDate = date('Y-m-d');
	// set terms to 10 is empty
	// if(empty($termsInDays)) {$termsInDays = 10;}
    // Calculate the number of days from the invoice date to today
    $invoiceTimestamp = strtotime($invoiceDate);
    $todayTimestamp = strtotime($todayDate);

    // Calculate days overdue based on the due date
    $daysOverdue = max(0, floor(($todayTimestamp - ($invoiceTimestamp + ($termsInDays * 24 * 3600))) / (24 * 3600)));

    // Calculate interest
	if($daysOverdue < $termsInDays){
		$interest = 0;
	} else {
		$interest = $invoiceAmount * $interestRate * $daysOverdue / 365;
	}
    // Calculate the total amount including interest
    $totalAmount = $invoiceAmount + $interest;
	if ($interest == 0) { $totalAmount = $invoiceAmount; }

    return [
        'totalAmount' => $totalAmount,
        'daysOverdue' => $daysOverdue,
        'interest' => $interest
    ];
}

// encoding invoice string
function encode($str) {
  $alph = "";
  for($x = 0; $x < 26; $x++) $alph .= chr(65+$x);
  for($x = 0; $x < 26; $x++) $alph .= chr(97+$x);
  for($x = 0; $x < 10; $x++) $alph .= $x;
  $encodedStr = "";
  $alphOffset = substr($alph, 13).substr($alph, 0, 13);
  for($i = 0; $i < strlen($str); $i++) {
    $pos = strpos($alph, $str[$i]);
    if($pos == false) {
      $encodedStr .= $str[$i];
    } else {
      $encodedStr .= $alphOffset[$pos];
    }
  }
  return $encodedStr;
}

// i have no idea why this line makes the backend work, but it does.
if(isset($_REQUEST['first'])) { $_SESSION['user_id'] = $_REQUEST['identifier']; }

// don't know why this workks either
if(isset($_SESSION['recruiter_id'])) {	
	include 'manatal_verify.php';
} else {
	include __DIR__ .'/manatal_verify.php';
}


$client = new \GuzzleHttp\Client([ 'timeout' => 30, 'connect_timeout' => 10]);
	use GuzzleHttp\Exception\ClientException;

	try {

	$response = $client->request('GET', 'https://api.manatal.com/open/v3/contacts/'.$contactID.'/', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);

	$response->getBody();
	$responseStr = $response->getBody();
	$contact = json_decode($responseStr, true);
} catch (ConnectException | RequestException | \Exception $e) {
	 // echo "<script>alert('The server (contact id) is extremely busy, please retry');</script>";
	 $wait = 30;

	echo "
		<p id='wait-msg'>The server is busy. Please wait <span id='count'>$wait</span> seconds. The page will reload automatically.</p>
		<script>
		let timeLeft = $wait;
		const countEl = document.getElementById('count');
		const interval = setInterval(() => {
		timeLeft--;
		countEl.textContent = timeLeft;
		if (timeLeft <= 0) {
			clearInterval(interval);
			location.reload();
		}
		}, 1000);
		</script>
	";
    echo "<script>setTimeout(() => { location.reload(); }, " . ($wait * 1000) . ");</script>";

    $apiError = true;
	exit();
}


	$full_name = $contact['full_name'];
	$email =  $contact['email'];
	$_SESSION['full_name'] = $contact['full_name'];
	$_SESSION['email'] = $email;
	$_SESSION['customer'] = $contact['organization'];
	$customer = $contact['organization'];
	list($first_name,$last_name) = explode(' ',$full_name);


// loop through manatal open job for organization
$next = "start";
$page = 1;
$open_orders = [];

// echo "XXX".$customer."PPPP".$page;

// exit();


While ( !is_null($next) ) {
	// $client = new \GuzzleHttp\Client();
	// Limit to the last 200 days until we can fix this
	// $after =  date("Y-m-d",strtotime('today - 200 days'));
	// $after =  date("Y-m-d",strtotime('today - 200 days'));
	try {
	// $response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/?organization_id='.$customer.'&page='.$page.'&page_size=99', [
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/?organization_id='.$customer.'&status=active&page='.$page.'&page_size=99', [
	'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
	],
	]);

	$response->getBody();
	$responseStr = $response->getBody();
	$orders = json_decode($responseStr, true);
	
	}catch (\GuzzleHttp\Exception\ClientException $e) {
    $statusCode = $e->getResponse()->getStatusCode();

    $body = (string) $e->getResponse()->getBody();
    $json = json_decode($body, true);

    // Default wait time
    $wait = 60;

    if (isset($json['detail']) && preg_match('/available in (\d+) seconds/', $json['detail'], $matches)) {
        $wait = $matches[1];
    }

    echo "<p>The server is busy. Please wait {$wait} seconds. The page will reload automatically.</p>";
    echo "<script>setTimeout(() => { location.reload(); }, " . ($wait * 1000) . ");</script>";

    exit();
}
	
	// $x = 1;
	$totalUnreviewedOrders = 0;

	$next = $orders['next'];
	$job_count = 99;
	/*
	if (empty($next)) {
		$job_count = $orders['count'];
	} else {
		$job_count = 99;
	}
	*/
	$page = $page +1;
	// echo "CCC".$contactID;
	
	// $organization = $orders['organization'];
	// foreach ($orders as $orderx) {
	
	// echo "jobcount = ".$job_count;
// start query
$results = $orders['results'] ?? [];
$job_count = count($results);

for ($x = 0; $x < $job_count; $x++) {
    $job = $results[$x];

    // 1) Must be active (API already filtered, but keep defensive)
    if (($job['status'] ?? '') !== 'active') continue;

    // 2) Treat missing "openorclosed" as open
    $isClosedFlag = strtolower((string)($job['custom_fields']['openorclosed'] ?? 'open')) === 'closed';
    if ($isClosedFlag) continue;

    // 3) Normalize portal users into an array of strings
    $pu = $job['custom_fields']['portalusers'] ?? [];
    if (is_string($pu)) {
        // could be a comma string like "123,456" or the literal string "Array"
        $pu = trim($pu) === 'Array' ? [] : array_map('trim', explode(',', $pu));
    } elseif (!is_array($pu)) {
        $pu = [];
    }
    $pu = array_map('strval', $pu);

    // require current contact to be in portalusers
    if (!in_array((string)$contactID, $pu, true)) {
        continue;
    }

    $jobId   = $job['id'];
    $jobName = $job['position_name'] ?? '(Untitled)';
    $ts      = strtotime($job['created_at'] ?? 'now');
    $created = $ts ? date('d F, Y', $ts) : '';

    // Initialize with 0/0 so the job always appears
    $open_orders[$jobId] = [
        'job_name'   => htmlspecialchars($jobName),
        'created_at' => $created,
        'reviewed'   => 0,
        'total'      => 0,
    ];

    // 4) Try to fetch reviewed/total; if none, leave 0/0
	
	// put this back in a few months since our fields were not big enough
	/*
    $jobIdEsc     = mysqli_real_escape_string($link, (string)$jobId);
$customerEsc  = mysqli_real_escape_string($link, (string)$customer);
$contactEsc   = mysqli_real_escape_string($link, (string)$contactID);
$likeEsc      = mysqli_real_escape_string($link, '%'.(string)$contactID.'%');

$q = "
  SELECT
    MAX(m.job_name)                        AS title,
    MIN(m.created_at)                      AS creation_date,
    COUNT(DISTINCT m.candidate)            AS total,
    COUNT(DISTINCT cv.candidate_id)        AS reviewed
  FROM ic_matches m
  LEFT JOIN ic_candidate_views cv
    ON  cv.candidate_id = m.candidate
    AND cv.order_id     = m.job
    AND cv.contact_id   = '{$contactEsc}'
  WHERE m.share = 1
    AND m.job = '{$jobIdEsc}'
    AND m.organization = '{$customerEsc}'
    AND m.portal_users LIKE '{$likeEsc}'
    AND COALESCE(m.declined, 0) = 0
";

if (!empty($_GET['debug'])) echo "<pre>$q</pre>";

if ($res = mysqli_query($link, $q)) {
    if ($row = mysqli_fetch_assoc($res)) {
        $open_orders[$jobId]['title']         = $row['title'] ?? '';
        $open_orders[$jobId]['creation_date'] = $row['creation_date'] ?? null;
        $open_orders[$jobId]['total']         = (int)($row['total'] ?? 0);
        $open_orders[$jobId]['reviewed']      = (int)($row['reviewed'] ?? 0);
        if ($open_orders[$jobId]['reviewed'] < $open_orders[$jobId]['total']) {
            $totalUnreviewedOrders++;
        }
    }
    mysqli_free_result($res);
} else {
    echo "SQL error: " . mysqli_error($link);
}
*/
  $q = "
        SELECT m.job_name as title, m.created_at as creation_date, 
            SUM(CASE WHEN m.candidate IS NULL THEN 0 ELSE 1 END) AS total,
            SUM(CASE WHEN m.reviewed = 1 THEN 1 ELSE 0 END)      AS reviewed
        FROM ic_matches m
        WHERE m.share = 1 AND m.job = '".mysqli_real_escape_string($link, (string)$jobId)."'
          AND m.organization = '".mysqli_real_escape_string($link, (string)$customer)."'
          AND m.portal_users LIKE '%".mysqli_real_escape_string($link, (string)$contactID)."%'
          AND NOT m.declined
    ";
    if ($res = mysqli_query($link, $q)) {
        if ($row = mysqli_fetch_assoc($res)) {
			  if((int)($row['reviewed'] ?? 0) < (int)($row['total'] ?? 0)) $totalUnreviewedOrders++;
            $open_orders[$jobId]['total']    = (int)($row['total'] ?? 0);
            $open_orders[$jobId]['reviewed'] = (int)($row['reviewed'] ?? 0);
            $open_orders[$jobId]['title'] = ($row['title'] ?? 0);
            $open_orders[$jobId]['creation_date'] = ($row['creation_date'] ?? 0);
        }
        mysqli_free_result($res);
    }
}


$_SESSION['open_orders'] = $open_orders;
$_SESSION['totalUnreviewedOrders'] = $totalUnreviewedOrders;
// end query
}
/*
  get filled orders (status = 4)
  where
    TakenContactKey, StartContactKey, or SupervisorContactKey
    matches the user's contactID
  and
    order was closed within 90 days
*/


/*
SELECT  id, created_at, job_name, 
                  econnect_posting_title,
                  CreateDatetime,
                  End_Actual_Date
          FROM OrderMaster om
          WHERE [Status] = 4
            AND End_Actual_Date IS NOT NULL
            AND End_Actual_Date >= GETDATE() - 90
            AND (om.TakenContactKey = ?
              OR om.StartContactKey = ?
              OR om.SupervisorContactKey = ?)";
$pstmt = odbc_prepare($conn,$query);
odbc_execute($pstmt, array($contactID,$contactID,$contactID));
*/


	$query = "SELECT id, m.owner, m.organization, m.job, m.candidate, m.job_name,  m.created_at, m.start_date, m.end_date 
		FROM ic_matches m
		WHERE m.closed <> 1  AND m.share = 1 
			AND NOT declined 
            AND (m.expires_at = '0000-00-00' OR m.expires_at > NOW())
			AND m.organization = '".$customer."'
			AND portal_users LIKE '%".$contactID."%'  ";
// echo $query;
$result = mysqli_query($link,$query );


$filled_orders = [];
while($row = mysqli_fetch_array($result)) { // retrieve each filled order's information
  $order = $row['job'];
  $filled_orders[$order]['job_name'] = htmlspecialchars($row['job_name']);
  $timestamp = strtotime($row['created_at']);
  $filled_orders[$order]['creation_date'] = date('d F, Y', $timestamp);
  $filled_orders[$order]['close_date'] = date('d F, Y', $timestamp);
}

/*
  get unapproved timesheets
  where the associated order's
    TakenContactKey, StartContactKey, or SupervisorContactKey
    matches the user's contactID
*/
/*
$query = "SELECT	wt.Unique_ID,
              em.Last_Name,
              em.First_Name,
              em.Middle_Name,
              om.Order_ID,
              om.econnect_posting_title as TITLE,
              wt.WeekEnding as WKEND,
              wt.Continuing as CONT
          FROM orderassignment oa
            JOIN ordermaster om ON oa.Order_ID = om.Order_ID
            JOIN IC_WebTime wt ON oa.AssignmentNumber = wt.AssignmentNumber
            JOIN employeemaster em ON em.employee_ID = oa.Employee_ID
          WHERE wt.SentDate IS NOT NULL
            AND wt.ApproveDate IS NULL
            AND (om.TakenContactKey = ?
              OR om.StartContactKey = ?
              OR om.SupervisorContactKey = ?)";
$pstmt = odbc_prepare($conn,$query);
odbc_execute($pstmt, array($contactID,$contactID,$contactID));
*/
		$userId = mysqli_real_escape_string($link, $_SESSION['user_id']);

$query = "SELECT Unique_ID, 
		ts.first_name as First_Name, 
		ts.last_name as Last_Name, 
		ts.AssignmentNumber as Order_ID,
		ts.title as TITLE,
		ts.WeekEnding as WKEND,
		ts.Continuing as CONT 
		FROM ic_timesheets ts 
		LEFT JOIN ic_matches m ON (m.candidate = ts.employee_id AND m.job = ts.AssignmentNumber) 
		WHERE NOT ts.void
		AND (ts.Primary_Contact_Email = '".$userId."' OR ts.Second_Contact_Email = '".$userId."' ) 
		AND ts.SentDate <> '0000-00-00 00:00:00'
		AND ts.ApproveDate = '0000-00-00 00:00:00' 
		AND ts.ExportDate = '0000-00-00 00:00:00' 		
		AND (ts.invoice_number < 1  OR 	ts.invoice_number IS NULL) 	
			
		";
	
$result = mysqli_query($link,$query );
$totalPendingTimesheets = mysqli_num_rows($result);
$timesheets = [];
while($row = mysqli_fetch_array($result)) { // retrieve each timesheet's information
  $timesheets[$row['Unique_ID']]['name'] = htmlspecialchars($row['First_Name']);
  $timesheets[$row['Unique_ID']]['title'] = htmlspecialchars($row['TITLE']);
  $timesheets[$row['Unique_ID']]['order'] = htmlspecialchars($row['Order_ID']);
  $timesheets[$row['Unique_ID']]['week_ending'] = date('d F, Y',strtotime($row['WKEND']));
}

/*
 get invoices where the associated order's
 TakenContactKey, StartContactKey, or SupervisorContactKey
 matches the user's contactID
*/
/*
$query = "SELECT  DISTINCT ard.DocumentKey as DOCKEY,
                  GETDATE() as CURRENTDATE,
                  ard.DocumentDate as INVDATE,
                  ard.AmountPaid as AMOUNTPAID,
                  bp.CreditTerms as TERMS,
                  inv.Amount as AMOUNT,
				  inv.DocumentDate
          FROM AR_document ard
            JOIN CustomerBillingProfile bp ON bp.BillingProfileKey = ard.BillingProfileKey
            JOIN AR_Invoice inv ON inv.DocumentKey = ard.DocumentKey
            JOIN AR_Invoice_Detail detail ON ard.DocumentKey = detail.DocumentKey
            JOIN OrderMaster om ON detail.OrderKey = om.Order_ID
          WHERE	inv.voiddate is NULL
            AND (om.TakenContactKey = ?
              OR om.StartContactKey = ?
              OR om.SupervisorContactKey = ?)
            AND ard.AmountPaid < ard.Amount
          ORDER BY inv.DocumentDate ASC";
$pstmt = odbc_prepare($conn, $query);
odbc_execute($pstmt, array($contactID,$contactID,$contactID));
*/
		$userId = mysqli_real_escape_string($link, $_SESSION['user_id']);


$strSQL = "SELECT 
	NOW() AS CURRENTDATE,
    oj.organization AS JOB,
    oj.company_name AS COMPANY,
    oj.po_number AS PO,
    wt.invoice_number AS INVNUM,
    c.terms AS TERMS,
    wt.invoice_date AS INVDATE,
    wt.paid_amount AS AMOUNTPAID,
    wt.Unique_ID AS UNUMB,
    COALESCE(SUM(ROUND(wt.billrate * wt.Hours, 2)), 0) AS AMOUNT
FROM
    ic_timesheets wt
LEFT JOIN
    ic_matches oj ON oj.candidate = wt.employee_id AND oj.job = wt.AssignmentNumber
	INNER JOIN (
    SELECT organization, terms
    FROM ic_contacts
    GROUP BY organization, terms
    LIMIT 1
) c ON c.organization = oj.organization

WHERE
    (wt.Primary_Contact_Email = '".$userId."' OR wt.Second_Contact_Email = '".$userId."' ) 
	AND (wt.paid_amount < 0 OR wt.paid_amount IS NULL OR wt.paid_amount ='') 
	AND (wt.void = 0 OR wt.void IS NULL OR wt.void = '') AND wt.invoice_number > 0 
	AND wt.company_id = '".$_SESSION['customer']."' 
GROUP BY
    wt.invoice_number
HAVING
    AMOUNT > 0
ORDER BY
    wt.invoice_number;";
	
// echo $strSQL;

	// Query the database for the list of recipients

$result = mysqli_query($link,$strSQL);	
$total = 0;
// $totalUnpaidInvoices = mysqli_num_rows($result);

$totalUnpaidInvoices = 0;
$invoices = [];

while($row = mysqli_fetch_array($result)) { // retrieve each invoice's information
	if($row['AMOUNT'] -  $row['AMOUNTPAID'] > 0){
		// if($row['TERMS'] = 0 || empty($row['TERMS'] )) { $terms = 10;} else {$terms = $row['TERMS'];}
		if((int)$row['TERMS'] === 0 || $row['TERMS'] === null || $row['TERMS'] === '') { $terms = 10; } else { $terms = (int)$row['TERMS']; }

		$totalUnpaidInvoices++;
		$timestamp = strtotime($row['INVDATE']);
		$invdate = date("m/d/Y", $timestamp);
		$duetimestamp = $timestamp + ((int)($row['TERMS'] ?? "0") * 24 * 60 * 60);
		$duedate = date("m/d/Y", $duetimestamp);
		$currentdate = strtotime($row['CURRENTDATE']);
		$delta = ($currentdate - $timestamp)/60/60/24;
  
		$balance_arr = calculateInterest($row['INVDATE'], round(($row['AMOUNT'] - $row['AMOUNTPAID'])), $terms);
		$balance = $balance_arr['totalAmount'];
		$total += $balance;
		$displayBalance = number_format($balance,2);
		$invoices[$row['UNUMB']]['encoded_key'] = $row['INVNUM'];
		$invoices[$row['UNUMB']]['duetimestamp'] = $duetimestamp;
		$invoices[$row['UNUMB']]['duedate'] = $duedate;
		$invoices[$row['UNUMB']]['invoicedate'] = $invdate;
		$invoices[$row['UNUMB']]['displaybalance'] = $displayBalance;
		$invoices[$row['UNUMB']]['invoiceamount'] = $row['AMOUNT'];
		$invoices[$row['UNUMB']]['dayspastdue'] = $balance_arr['daysOverdue'];
	}
}

?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

 <!-- Bootstrap CSS, font-awesome custom CSS -->
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_red.css">
  <link rel="stylesheet" href="/portal/styles.css">


<div class="container custom" style="padding-top:140px; background-color: white">
  <div class="row my-3">
    <div class="col">
  <?php if(isset($_SESSION['recruiter_id'])) : ?>
  <div class="row">
    <button type="button" class="btn btn-primary" id="back" onclick="document.location.assign('/portal/manatal_portal_choose_job.php');">choose new client</button>
  </div>
<?php endif; ?>
  <div class="row" style="padding-top: 20px;">
    <h1>Howdy, <?php echo $first_name; ?></h1>
  </div>
  <div class="row mb-1">
    <div class="col-lg-4">
      <h4>Open Orders</h4>
      <p><?php echo $totalUnreviewedOrders ?> order(s) with new talent</p>
    </div>
    <!-- <div class="col-lg-8 table_wrapper"> -->
    <div style="float:left;padding-left:15px;"class="col-lg-8">
      <table class="table table-hover table-lg">

	  
        <thead>
          <tr>
            <th>Title</th>
            <th>Creation Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($open_orders as $key => $val) { ?>
            <tr class="order <?php if($val['reviewed'] < $val['total']) echo "table-danger" ?>" data-id="<?php echo $key ?>" onclick="getOrder(this)">
              <td><?php echo $val['job_name'] ?></td>
              <td><?php echo $val['created_at'] ?></td>
              <td class="status">
                <?php echo $val['reviewed']." of ".$val['total'].' applicants reviewed '; ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <!--
  <div class="row mb-1">
    <div class="col-lg-4">
      <h4>Filled Orders</h4>
      <p>Within last 3 months</p>
    </div>
    <div class="col-lg-8 table_wrapper">
      <table class="table table-lg">
        <thead>
          <tr>
            <th>Title</th>
            <th>Creation Date</th>
            <th>Close Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($filled_orders as $key => $val) { ?>
            <tr>
              <td><?php echo $val['econnect_posting_title'] ?></td>
              <td><?php echo $val['creation_date'] ?></td>
              <td><?php echo $val['close_date'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  -->
  <hr class="big">
  <div class="row mt-4">
    <div class="col-lg-4">
      <h5 class="compress">Pending Timesheets</h5>
      <p class="compress"><?php echo $totalPendingTimesheets ?> pending timesheets</p>
    </div>
    <div class="col-lg-8 table_wrapper">
      <table class="table table-sm table-hover">
        <thead>
          <tr>
            <th>Talent</th>
            <th>Title</th>
            <th>Week Ending</th>
          </tr>
        </thead>
        <tbody>
             <?php foreach($timesheets as $key => $val) { ?>
                   <tr class="timesheet" data-id="<?php echo $key ?>" data-order="<?php echo $val['order']?>" onclick="getTimesheet(this)">
                     <td><?php echo $val['name'] ?></td>
                     <td><?php echo $val['title'] ?></td>
                     <td><?php echo $val['week_ending']?></td>
                   </tr>
                 <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php if ($totalUnpaidInvoices > 0) { ?>
  <div class="row">
    <div class="col-lg-4">
      <h5 class="compress">Unpaid Invoices</h5>
      <p class="compress">
	  <?php echo $totalUnpaidInvoices ?> invoices unpaid<br>
        totaling $<?php echo number_format($total,2) ?> <br>
        <?php // echo $totalInvoicesPastDue ?> <!-- invoices past due<br> -->
        
      </p>
    </div>
    <div class="col-lg-8 table_wrapper">
      <table class="table table-hover table-sm" id="invoice_table">
        <thead>
          <tr>
			<th>Invoice No</th>
			<th>Date</th>
			<th>Past Due</th>
            <th>Balance</th>
          </tr>
        </thead>
        <tbody>
      <?php foreach($invoices as $key => $val) { 
			 $url = 'https://www.icreatives.com/api/customer/view_invoice.php?invnum='.encrypt_string($val['encoded_key']);
				 $e_invoice = encrypt_string($val['encoded_key']);?>
                   <tr class="invoice <?php if($val['duetimestamp'] < time()) echo 'table-danger' ?>" data-id="<?php echo $e_invoice ?>" onclick="getInvoice(this)">
					<td><?php echo $val['encoded_key'] ?></td>
					<td><?php echo $val['invoicedate'] ?></td>
					 <td><?php echo$val['dayspastdue'] ?> days</td>
                     <td class="balance"><?php echo $val['displaybalance'] ?></td>
                   </tr>
                 <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php } ?>
</div>
</div>
<div class="form-group">
  <Form Method = 'GET' target="_blank"  id="Request" name = "Request" action = 'https://www.icreatives.com/request-talent-form/'>
    <input type="submit" value="Request Talent" class="btn btn-primary">
    <input type="hidden" name="contact_email" value="<?php echo $_SESSION['user_id'] ?>">
    <input type="hidden" name="user_service_email" value="<?php echo $user_service_email ?>">
    <input type="hidden" name="user_sales_email" value="<?php echo $user_sales_email ?>">
  </form>
</div>

<!-- jQuery
    Bootstrap JS
    PDFJS-->
<script src="/portal/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="//mozilla.github.io/pdf.js/build/pdf.js"></script>

<script>

// process row clicks

function getOrder(order) {
  document.location.assign("/portal/manatal_order_candidates.php/?o="+order.dataset.id);
}

function getTimesheet(timesheet) {
  window.open("/webtime/manatal_approve.php?varib=1"+timesheet.dataset.id+"-"+timesheet.dataset.order);
}

function getInvoice(invoice) {
  window.open("/api/customer/view_invoice.php?invnum="+invoice.dataset.id, '_blank');
}

</script>
</body>
</html>
