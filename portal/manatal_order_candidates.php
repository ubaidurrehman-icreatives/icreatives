<?php session_start(); ?>
<html>
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
<script>
(function(){
  var PARENT='https://www.icreatives.com';
  function send(){try{if(top!==self)parent.postMessage({type:'IFRAME_URL',href:location.href},PARENT)}catch(e){}}
  addEventListener('load',send);
  (function(){
    var p=history.pushState,r=history.replaceState;
    history.pushState=function(){var x=p.apply(this,arguments);send();return x};
    history.replaceState=function(){var x=r.apply(this,arguments);send();return x};
    addEventListener('hashchange',send);
  })();
  addEventListener('pageshow',function(e){ if(e.persisted){ send(); }});
})();</script>
</head>
<body>

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (empty($_SESSION['users_arr']) || empty($_SESSION['open_orders']) ) {
	  header("Location: /portal/manatal_client_portal_login.php");
		exit();
}



require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';
$link = db();  

	use GuzzleHttp\Exception\ClientException;

// encrypt invoice number
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
// include './portal/db.php';
include __DIR__.'/manatal_verify.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if(!isset($_REQUEST['o'])) {
  header("Location: /portal/manatal_client_portal_dashboard.php");
  exit;
}

// $divisionID = $_SESSION['division'];
$contactID = $_SESSION['contactID'];
$customerID = $_SESSION['customer'];
$contact_name = $_SESSION['full_name'] ?? '';

$current_order = $_REQUEST['o'];
// if(!isset($_SESSION['recruiter_id'])) {
  // we probably should not be updating until we view a candidate

	$query = "SELECT * FROM ic_matches WHERE share = 1 AND job = '". $current_order ."' AND portal_users LIKE '%".$contactID."%'  ";	

	$result = mysqli_query($link,$query);
	$row_cnt = mysqli_num_rows($result);

  // update view information
  require_once("../PHPMailer/PHPMailer.php");
  require_once("../PHPMailer/Exception.php");
  require_once("../PHPMailer/SMTP.php");

  if($row_cnt > 0) {
	$row = mysqli_fetch_array($result);
    $submital = $row['candidate_name'];
    $first_view = is_null($row['first_viewed_date']);
	$owner_id = $row['owner'];
    $owner_1_name = $row['owner_1_name'];
    $owner_2_name= $row['owner_2_name'];
    $owner_3_name = $row['owner_3_name'];
    $customer_name = $row['company_name'];
	$match_id = $row['id'];
	$title = $row['job_name'];
	$candidate_id = $row['candidate']; 
	$candidate_name = $row['candidate_name']; 
	$reviewed = $row['reviewed']; 

      $should_send_email = true;
	  
// no longer needed, we load this in the client_login program.
/*
	// get email addresses from user diplay names in match records
	$client = new \GuzzleHttp\Client([ 'timeout' => 30, 'connect_timeout' => 10]);

	try {
		$response = $client->request('GET', 'https://api.manatal.com/open/v3/users/', [
	'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
	],
	]);
	$responseStr = $response->getBody();
	$users_arr = json_decode($responseStr, true);
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
*/


$users_arr = $_SESSION['users_arr'];   // from client_login program
	for($x=0; $x<count($users_arr['results']); $x++) {
		if($users_arr['results'][$x]['id'] == $owner_id){$owner_email = $users_arr['results'][$x]['email'];}
		if($users_arr['results'][$x]['display_name'] == $owner_1_name){$owner_1_email = $users_arr['results'][$x]['email'];}
		if($users_arr['results'][$x]['display_name'] == $owner_2_name){$owner_2_email = $users_arr['results'][$x]['email'];}
		if($users_arr['results'][$x]['display_name'] == $owner_3_name){$owner_3_email = $users_arr['results'][$x]['email'];}
	}

      // add history event
      // $query = "EXEC HISTORY_INSERT @EventCode = 'PSR', @EventMethod = NULL, @Comment = '".str_replace("'","''",addslashes($contact_name)." viewed the candidates from submital ".addslashes($submital))."', @CustomerKey = '".addslashes($customerID)."', @DivisionKey = '".addslashes($divisionID)."', @ContactKey = '".addslashes($contactID)."'";
      // odbc_exec($conn, $query);
	  


      if($should_send_email && !isset($_SESSION['recruiter_id'])) {
	  
        $mail = new PHPMailer(true);
        try {
            // Recipients
         $mail->setFrom('exchange@icreatives.com','icreatives');
		if(!empty($owner_1_email)){$mail->addAddress($owner_1_email);
			} else {$mail->addAddress($owner_email);}
		if(!empty($owner_2_email)){$mail->addAddress($owner_2_email);}
		if(!empty($owner_3_email)){$mail->addAddress($owner_3_email);}
		$mail->addBCC('jobcomp2@blindemail.com');
		$mail->addBCC('stevenc@icreatives.com');
          // Server settings
          // $mail->SMTPDebug = 3;
		  /*
          $mail->isSMTP();
          $mail->Host = 'smtp.1and1.com';
          $mail->SMTPAuth = true;
          $mail->Username = 'exchange@icreatives.co';
          $mail->Password = 'Call1888icreate!';
          $mail->SMTPSecure = 'tls';
	
		$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
		$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
*/

			
				$mail->IsSMTP(); // telling the class to use SMTP
				// $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
				// 1 = errors and messages
				// 2 = messages only
				$mail->SMTPAuth   = true;                  // enable SMTP authentication
				// $mail->Host       = "smtp.1and1.com"; // sets the SMTP server
				$mail->Host       = 'smtp.office365.com';
				$mail->Username   = "exchange@icreatives.com"; // SMTP account username
				$mail->Password   = "Call1888icreate!";        // SMTP account password
				$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
				$mail->Port       = 587;  // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above		
				$mail->isHTML(true);                             // Set email format to HTML
				$mail->CharSet = "UTF-8";




          // Recipients
          $mail->setFrom('exchange@icreatives.com','icreatives');
          $mail->addBCC('jobcomp2@blindemail.com');

          // Content
          $mail->Subject = "icreatives - We got a bite!";
          $mail_message = "
          <html>
          <body>
          <p>Submital $title was viewed by $contact_name from $customer_name!</p>
		  
		<ul>
			<li>Job Order: <a href = 'https://app.manatal.com/jobs/`$current_order`'>`$title`</a></li>
			<li>Requester's name: <a href='https://app.manatal.com/candidates/`$contactID`'></a>".$contact_name."</li>
			<li>Requester's Contact ID: ".$contactID."</li>
			<li>Order ID: <a href='https://app.manatal.com/jobs/`current_order'>$current_order</a></li>
		</ul>


          </body>
          </html>";
          $mail->MsgHTML($mail_message);
          // $mail->send();
		  $match_id;

		  	$mail_message = preg_replace('/[^a-zA-Z0-9_\-#;:&()<> ]/', '',  $mail_message);
			$mail_message = addslashes($mail_message);
			$client = new \GuzzleHttp\Client();
			$response = $client->request('POST', 'https://api.manatal.com/open/v3/jobs/'.$current_order.'/notes/', [
		'body' => '{"info":"'.$mail_message.'"}',
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
		'content-type' => 'application/json',
		],
		]);
		
		
		  
        } catch (Exception $e) {
		/*
          echo "There was an error displaying this page.";
		  echo $e;
		  echo "XXX";
		  echo $owner_1_email;
		  echo $owner_2_email;
		  echo $owner_3_email;
		  echo $owner_email;
          return;
		  */
        }
      }
    // }
}


// }
$_SESSION['candidates'] = [];

// get order information
$query = "SELECT job_name FROM ic_matches
          WHERE job = '".$current_order."'";

$result = mysqli_query($link,$query );
$row = mysqli_fetch_array($result);
// if should not be able to see
if(empty($row)) {
  header("Location: /portal/manatal_client_portal_dashboard.php");
  exit;
}
$econnect_posting_title = htmlspecialchars($row['job_name']);

// get candidates submitted to order
	$query = "SELECT  m.candidate,
                  m.job,
                  m.reviewed,
                  m.rating,
                  m.id,
                  m.declined,
                  m.schedule_interview,
                  m.interview_time,
                  m.candidate_name,
                  m.created_at,
				  m.bill_rate,
				  m.salary,
				  m.closed
          FROM ic_matches m
          WHERE m.job = '".$current_order."' AND m.share = 1 AND declined <> 1 
          ORDER BY m.job ASC, m.reviewed ASC, m.schedule_interview ASC, m.created_at DESC";
		  // , candidates.weight DESC";

$result = mysqli_query($link,$query );

$candidates = [];

while($row = mysqli_fetch_array($result)) { // retrieve each candidate's info
 

 array_push($_SESSION['candidates'], array('candidate_id' => $row['candidate'], 'submital' => $row['id'], 'assignment_id' => $row['job']));
  $timestamp = strtotime($row['created_at']);
  $candidates[$row['candidate']]['date_received'] = date('d F, Y', $timestamp);
  $candidates[$row['candidate']]['submital'] = htmlspecialchars($row['id']);
  list($first_name,$lastname) = explode(" ",$row['candidate_name']);
  $candidates[$row['candidate']]['name'] = htmlspecialchars($first_name);
  $candidates[$row['candidate']]['bill'] = sprintf('$%.2f',htmlspecialchars($row['bill_rate'] ?? 0));
  $candidates[$row['candidate']]['salary'] = htmlspecialchars($row['salary'] ?? 0);
  $candidates[$row['candidate']]['reviewed'] = htmlspecialchars($row['reviewed'] ?? 0);
  $candidates[$row['candidate']]['rating'] = htmlspecialchars($row['rating'] ?? 0);
  if($row['schedule_interview']) {
    $candidates[$row['candidate']]['interview'] = $row['interview_time'] == "0000-00-00 00:00:00"  ? "ASAP" : date('d F, Y', strtotime($row['interview_time']));
  } else {
    $candidates[$row['candidate']]['interview'] = is_null($row['declined']) || !$row['declined'] ? "" : "Declined";
  }
}
/*
 get open orders where
 TakenContactKey, StartContactKey, or SupervisorContactKey
 matches the user's contactID
*/
/*
$query = "SELECT  m.job,
                  m.job_name,
                  m.created_at,
                  sum(CASE WHEN m.candidate IS NULL THEN 0 ELSE 1 END) total,
                  sum(CASE WHEN m.reviewed = 1 THEN 1 ELSE 0 END) reviewed
          FROM ic_matches m 
          WHERE
            (m.expires_at = '0000-00-00' OR m.expires_at > NOW()) AND NOT declined 
		  AND share AND organization = '".$customerID."'    
			 AND m.portal_users LIKE '%".$contactID."%' 
          GROUP BY m.job 
          ORDER BY 
            CASE WHEN sum(CASE WHEN m.reviewed = 1 THEN 1 ELSE 0 END) < sum(CASE WHEN m.candidate IS NULL THEN 0 ELSE 1 END) then 1 else 0 END DESC,
            CASE WHEN sum(CASE WHEN m.reviewed = 1 THEN 1 ELSE 0 END) > 0 THEN 1 else 0 END DESC,
            m.id DESC";
			
$result = mysqli_query($link,$query );
$orderCount = mysqli_num_rows($result);
$totalUnreviewedOrders = 0;
$open_orders = [];
while($row = mysqli_fetch_array($result)) { // retrieve each open order's information
  if($row['reviewed'] < $row['total']) $totalUnreviewedOrders++;
  $open_order = htmlspecialchars($row['job']);
  $open_orders[$open_order]['reviewed'] = htmlspecialchars($row['reviewed']);
  $open_orders[$open_order]['total'] = htmlspecialchars($row['total']);
  $open_orders[$open_order]['title'] = htmlspecialchars($row['job_name']);
  $open_orders[$open_order]['reviewed'] = htmlspecialchars($row['reviewed']);
  $timestamp = strtotime($row['created_at']);
  $open_orders[$open_order]['creation_date'] = date('d F, Y', $timestamp);
}
*/
// create new open order counter
$open_orders = $_SESSION['open_orders'];
$totalUnreviewedOrders = $_SESSION['totalUnreviewedOrders'];
$orderCount = count($open_orders);


/*
 get timesheets where the associated order's
 TakenContactKey, StartContactKey, or SupervisorContactKey
 matches the user's contactID
*/
$query = "SELECT	ts.Unique_ID,
              ts.Last_Name,
              ts.first_name,
              ts.AssignmentNumber,
              ts.title as TITLE,
              ts.WeekEnding as WKEND,
              ts.Continuing as CONT
          FROM ic_timesheets ts 
          WHERE ts.SentDate <> '0000-00-00 00:00:00' 
            AND ts.ApproveDate = '0000-00-00 00:00:00' 
			AND (ts.Primary_Contact_Email = '".$_SESSION['user_id']."' OR ts.Second_Contact_Email = '".$_SESSION['user_id']."' ) 
			AND ts.invoice_number < 1  
            AND ts.company_id = '".$customerID."'";
			
$result = mysqli_query($link,$query );

$totalPendingTimesheets = mysqli_num_rows($result);
$timesheets = [];
$i = 0;
while($row = mysqli_fetch_array($result)) { // retrieve each timesheet's information
  $timesheets[$row['Unique_ID']]['name'] = htmlspecialchars($row['first_name']);
  $timesheets[$row['Unique_ID']]['title'] = htmlspecialchars($row['TITLE']);
  $timesheets[$row['Unique_ID']]['order'] = htmlspecialchars($row['AssignmentNumber']);
  $timesheets[$row['Unique_ID']]['week_ending'] = date('d F, Y',strtotime($row['WKEND']));
}

// encode invoice string
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

/*
 get invoices where the associated order's
 TakenContactKey, StartContactKey, or SupervisorContactKey
 matches the user's contactID
*/
// $strSQL = $strSQL . "LEFT JOIN ic_matches oj ON (oj.candidate = wt.employee_id AND oj.job = wt.AssignmentNumber) ";
// $strSQL = $strSQL . "LEFT JOIN ic_contacts c ON (c.email = oj.ap_email ) ";
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
    COALESCE(SUM(wt.billrate * wt.Hours), 0) AS AMOUNT
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
    (wt.Primary_Contact_Email = '".$_SESSION['user_id']."' OR wt.Second_Contact_Email = '".$_SESSION['user_id']."' ) 
	AND (wt.paid_amount < 1 OR wt.paid_amount IS NULL OR wt.paid_amount ='') 
	AND (wt.void = 0 OR wt.void IS NULL OR wt.void = '') AND wt.invoice_number > 0
GROUP BY
    wt.invoice_number
HAVING
    AMOUNT > 0
ORDER BY
    wt.invoice_number;";

	// Query the database for the list of recipients

$result = mysqli_query($link,$strSQL);	
$total = 0;
// $totalUnpaidInvoices = mysqli_num_rows($result);
$totalUnpaidInvoices = 0;
$invoices = [];
while($row = mysqli_fetch_array($result)) { // retrieve each invoice's information
	if($row['AMOUNT'] -  $row['AMOUNTPAID'] > 0){
		if($row['TERMS'] = 0 || empty($row['TERMS'] )) { $terms = 10;} else {$terms = $row['TERMS'];}

		$totalUnpaidInvoices++;
		$timestamp = strtotime($row['INVDATE']);
		$invdate = date("m/d/Y", $timestamp);
		$duetimestamp = $timestamp + ($row['TERMS']*24*60*60);
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
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="/portal/bootstrap-4.3.1-dist/js/bootstrap.bundle.min.js"></script>
  <!-- Bootstrap CSS, font-awesome custom CSS --> 
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css"> 
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.css"> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> 
  <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_red.css"> 
  <link rel="stylesheet" href="/portal/styles.css">
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


  <div class="container custom" style="padding-top: 100px; background-color: white">
    <div class="row my-3">
      <div class="col">
    <div class="row mb-3">
      <button type="button" class="btn btn-primary" id="back" onclick="document.location.assign('/portal/manatal_client_portal_dashboard.php/');">back to dashboard</button>
    </div>
    <div class="row">
      <div class="col">
        <h1>
          <?php echo $econnect_posting_title ?>
        </h1>
        <table class="table table-hover table-lg">
          <thead>
            <tr>
              <th>Candidate</th>
              <th>Reviewed</th>
              <th>Date Received</th>
              <th>Rate</th>
              <th>Interview Request</th>
              <th>Rating</th>
            </tr>
          </thead>
          <tbody>
            <?php if(sizeof($candidates) == 0) echo "no candidates were found" ?>
            <?php foreach($candidates as $key => $val) { ?>
			
              <tr class="candidate <?php if($val['reviewed'] == 0) echo "table-danger" ?>" data-order="<?php echo $current_order ?>" onclick="document.location.assign('/portal/manatal_rate_candidate.php/?cand='+(this.rowIndex - 1)+'&o=<?php echo $current_order ?>');">
                <th><?php echo $val['name'] ?></th>
				<td><?php if($val['reviewed']==1){echo "&check;" ;} ?></td>
                <td><?php echo $val['date_received'] ?></td>
		<td><?php
			IF ($val['bill'] != "$0.00") {echo $val['bill']  . "/hr "; }
			IF ($val['salary'] != "") {echo "$".number_format($val['salary'],2)  . "/yr "; }
		?></td>
                <td><?php echo $val['interview'] ?></td>
                <td class="rating">
                  <div class="star_group">
                    <?php
                    for($i = 0; $i < 5; $i++) {
                      if($i < $val['rating']) {
                        echo '<span class="star_label_small selected">★</span>';
                      } else {
                        echo '<span class="star_label_small">★</span>';
                      }
                    }
                    ?>
                  </div>
                </td>
              </tr>
			  
            <?php } ?>
          </tbody>
         </table>
       </div>
     </div>
     <div class="row mt-5">
     </div>
	 <?php if ($orderCount > 0) { ?>
     <div id="accordion" class="mt-5">
       <div class="card">
         <div class="card-header" id="orders" data-toggle="collapse" data-target="#collapseOrders" aria-expanded="false" aria-controls="collapseOrders">
           <div class="row">
             <div class="col">
               <h6 class="mb-0">
                 <span style="color: gray">+</span>
                 Open Orders
               </h6>
             </div>
             <div class="col text-right" id="OrderDetail">
               <?php echo $totalUnreviewedOrders ?> order(s) with new talent
             </div>
           </div>
         </div>
         <div class="collapse" id="collapseOrders" aria-labelledby="orders" data-parent="#accordion">
           <div class="card-body">
             <div class="table_wrapper">
               <table class="table table-hover table-sm">
                 <thead>
                   <tr>
                     <th>Title</th>
                     <th>Creation Date</th>
                     <th>Status</th>
                   </tr>
                 </thead>
                 <tbody>
				 <?php  // echo print_r($open_orders); ?>
                   <?php foreach($open_orders as $key => $val) { ?>
                     <tr class="order <?php if($val['reviewed'] < $val['total']) echo "table-danger" ?>" data-id="<?php echo $key ?>" onclick="getOrder(this)">
                       <td><?php echo $val['job_name'] ?></td>
                       <td><?php echo $val['created_at'] ?></td>
                       <td class="status">
                         <?php
                         if($val['reviewed'] < $val['total']) {
                           echo $val['reviewed']." of ".$val['total']." applicants reviewed";
                         } else {
                           echo "Fully reviewed";
                         } ?>
                       </td>
                     </tr>
                   <?php } ?>
                 </tbody>
               </table>
             </div>
         </div>
       </div>
     </div>
     </div>
	 <?php } ?>
     <div class="card">
       <div class="card-header" id="timesheets" data-toggle="collapse" data-target="#collapseTimesheets" aria-expanded="false" aria-controls="collapseTimesheets">
         <div class="row">
           <div class="col">
             <h6 class="mb-0">
               <span style="color: gray">+</span>
               Pending Timesheets
             </h6>
           </div>
           <div class="col text-right" id="TimesheetDetail">
             <?php echo $totalPendingTimesheets ?> pending timesheets
           </div>
         </div>
       </div>
       <div class="collapse" id="collapseTimesheets" aria-labelledby="timesheets" data-parent="#accordion">
         <div class="card-body">
           <div class="table_wrapper">
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
       </div>
     </div>
     <div class="card">
       <div class="card-header" id="invoices" data-toggle="collapse" data-target="#collapseInvoices" aria-expanded="false" aria-controls="collapseInvoices">
         <div class="row">
           <div class="col">
             <h6 class="mb-0">
               <span style="color: gray">+</span>
               Unpaid Invoices
             </h6>
           </div>
           <div class="col text-right" id="InvoiceDetail">
             <?php echo $totalUnpaidInvoices ?> unpaid invoices
           </div>
         </div>
       </div>
       <div class="collapse" id="collapseInvoices" aria-labelledby="invoices" data-parent="#accordion">
         <div class="card-body">
           <div class="table_wrapper">
             <table class="table table-sm">
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
				  $url = '/api/customer/view_invoice.php?invnum='.encrypt_string($val['encoded_key']);
				 $e_invoice = encrypt_string($val['encoded_key']);?>
                   <tr class="invoice <?php if($val['duetimestamp'] < time()) echo 'table-danger' ?>" data-id="<?php echo $e_invoice ?>" onclick="getInvoice(this)">
					<td><?php echo $val['encoded_key'] ?></td>
					<td><?php echo $val['invoicedate'] ?></td>
					 <td><?php echo$val['dayspastdue'] ?></td>
                     <td class="balance"><?php echo $val['displaybalance'] ?></td>
                   </tr>
                 <?php } ?>
               </tbody>
             </table>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>
</div>
</div>

<!-- Bootstrap JS -->
<script src="/portal/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>

<script>


// process row clicks

function getCandidate(candidate) {
  document.location.assign('/portal/manatal_rate_candidate.php/?cand='+(candidate.rowIndex - 1)+'&o='+candidate.dataset.order);
}

function getOrder(order) {
  document.location.assign("/portal/manatal_order_candidates.php/?o="+order.dataset.id);
}

function getTimesheet(timesheet) {
  window.open("/webtime/manatal_approve.php&varib=1"+timesheet.dataset.id+"-"+timesheet.dataset.order);
}

function getInvoice(invoice) {
  window.open("/api/view_invoice.php?invnum="+invoice.dataset.id, '_blank');
}

</script>
</body>
</html>
