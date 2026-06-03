<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

session_start();

require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';


$link = db(); 

// show errors ASAP (helps while fixing 500s)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


  // update view information
require_once dirname(__DIR__) . '/PHPMailer/PHPMailer.php';
require_once dirname(__DIR__) . '/PHPMailer/Exception.php';
require_once dirname(__DIR__) . '/PHPMailer/SMTP.php';


function getOwnerInfo($ownerid) {
	$client = new \GuzzleHttp\Client([ 'timeout' => 30, 'connect_timeout' => 10]);
	try {
		$response = $client->request('GET', 'https://api.manatal.com/open/v3/users/'.$ownerid."/", [
		'headers' => [
			'Authorization' => $_SESSION['token'],
			'accept' => 'application/json',
		],
	]);
	$response->getBody();
	$responseStr = $response->getBody();
	$user_arr = json_decode($responseStr, true);
	return $user_arr;
	
} catch (ConnectException | RequestException | \Exception $e) {
	 // echo "<script>alert('I'm sorry, the server is extremely busy, please retry (owner id) ');</script>";
    $apiError = true;
	exit();
}

}

if(!isset($_SESSION['user_id'])) {
  session_regenerate_id();
  header("Location: /portal-client-login");
  safe_redirect('/portal/manatal_client_portal_login.php');
  exit;
}

	$client = new \GuzzleHttp\Client([ 'timeout' => 30, 'connect_timeout' => 10]);
	
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/contacts/'.$_SESSION['contactID'].'/', [
	'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
	],
	]);
	$response->getBody();
	$responseStr = $response->getBody();
	$contact_arr = json_decode($responseStr, true);
	$contact_name = $contact_arr['full_name'];

$contactID = $_SESSION['contactID'];
$customerID = $_SESSION['customer'];
$submital = $_POST['s'];
$employeeID = $_POST['e'];
$candidate_id = $_POST['c'];
$candidate_num = $_POST['candidateNum'];
$orderID = $_POST['o'];
$assignmentID = $_POST['a'];
if (empty($_POST['rate'])){
		$rating = 0;
} else {
	$rating = $_POST['rate'];
}

	
$declined_comments = addslashes($_POST['decline_comments']);
(isset($_POST['set_interview']) && $_POST['set_interview'] == 1) ? $set_interview = 1 : $set_interview = 0;
isset($_POST['decline']) && !$set_interview ? $declined = 1 : $declined = 0;
$interview_options = $_POST['interview_option'];
// $date = $_POST['interview_date'];
$rawDate = $_POST['interview_date'] ?? '';
$dt = $rawDate ? DateTime::createFromFormat('F, d Y H:i', $rawDate) : false;
if ($dt instanceof DateTime) {
    $date = $dt->format('Y-m-d H:i:s');
} else {
    $date = "";  // or a sentinel like "0000-00-00 00:00:00"
}

$additional_dates = $_POST['additional_date'];

$mediums = [];
foreach($_POST as $key => $val) {
  if (strpos($key, 'medium_include') === 0) {
    array_push($mediums, $val);
  }
}
$medium_info = $_POST['medium_info'];
$comments = addslashes($_POST['comments']);
$interview_comments = addslashes($_REQUEST['interview_comments']);


$query = "SELECT id, candidate_name, company_name, candidate, job_name, owner, owner_1_name, owner_2_name, owner_3_name FROM ic_matches 
WHERE job = ? AND candidate = ?";
//'".$employeeID."'";
$pstmt = $link->prepare($query);

// Bind parameters
$pstmt->bind_param("ss", $orderID, $employeeID);

// Execute the query
$pstmt->execute();
$results = $pstmt->get_result();

$row = $results->fetch_assoc();

$candidate_name = $row['candidate_name'];
$customer_name = $row['company_name'];
$candidate_id = $row['candidate'];
$owner = $row['owner'];
$job_name = $row['job_name'];
$owner_info = getOwnerInfo($row['owner']);
$owner_email = $owner_info['email'];
$match_id = $row['id'];
$owner_1_name = $row['owner_1_name'];
$owner_2_name = $row['owner_2_name'];
$owner_3_name = $row['owner_3_name'];

// Close the statement and connection
$pstmt->close();
// $link->close();

// get email addresses from user diplay names in match records

	$client = new \GuzzleHttp\Client([ 'timeout' => 30, 'connect_timeout' => 10]);
	
$response = $client->request('GET', 'https://api.manatal.com/open/v3/users/', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);
	$responseStr = $response->getBody();
	$users_arr = json_decode($responseStr, true);
	
	for($x=0; $x<count($users_arr['results']); $x++) {
		if($users_arr['results'][$x]['display_name'] == $owner_1_name){$owner_1_email = $users_arr['results'][$x]['email'];}
		if($users_arr['results'][$x]['display_name'] == $owner_2_name){$owner_2_email = $users_arr['results'][$x]['email'];}
		if($users_arr['results'][$x]['display_name'] == $owner_3_name){$owner_3_email = $users_arr['results'][$x]['email'];}
	}

// format date for sql (odbc sucks)
if(!$set_interview || $interview_options != 1 || $date=="") {
  $formatted_date = "0000-00-00 00:00:00";
} else {
  $timestamp = $date;
  if($timestamp != "0000-00-00 00:00:00") {
    $formatted_date = date($timestamp);

  } else {
    $timestamp = DateTime::createFromFormat('F, d Y H:i', $date);
    $formatted_date = $timestamp->format('Y-m-d H:i:s');
	
}
}
$alternate_dates = [];

foreach($additional_dates as $alt_date) {
  if($alt_date == "" || is_null($alt_date)) {
    array_push($alternate_dates, "0000-00-00");
  } else {
    $alt_timestamp = strtotime($alt_date);
    if($alt_timestamp != "") {
      $alt_formatted_date = date('Y-m-d H:i:s', $alt_timestamp);
    } else {
      $alt_timestamp = DateTime::createFromFormat('F, d Y H:i', $alt_date);
      $alt_formatted_date = $alt_timestamp->format('Y-m-d H:i:s');
    }
    array_push($alternate_dates, $alt_formatted_date);
  }
}

if(isset($_SESSION['recruiter_id'])) {
  $next_candidate = ($candidate_num+1)%sizeof($_SESSION['candidates']);
  header("Location: /portal/rate-candidate/?cand=$next_candidate&o=$orderID");
 safe_redirect("/portal/rate-candidate/?cand=".$next_candidate."&o=".$orderID);
  exit;
}

// MySQLi query

$query = "UPDATE ic_matches 
          SET rating = ".$rating.",
              interview_time = '".$formatted_date."',
              customer_comments = '".$comments."',
              schedule_interview = CASE WHEN schedule_interview = 1 THEN 1 ELSE ".$set_interview." END,
              interview_comments = '".$interview_comments."',
              reviewed = 1,
              declined = ".$declined.",
              declined_comments = '".$declined_comments."',
              alternate_date_1 = '".$alternate_dates[0]."',
              alternate_date_2 = '".$alternate_dates[1]."',
              alternate_date_3 = '".$alternate_dates[2]."'
          WHERE id = '".$match_id."';";


 $result = mysqli_query($link,$query);

/*
// we need to fix soon!
echo $query = "UPDATE ic_matches 
          SET rating = ?,
              interview_time = ?,
              customer_comments = ?,
              schedule_interview = CASE WHEN schedule_interview = 1 THEN 1 ELSE ? END,
              interview_comments = ?,
              reviewed = 1,
              declined = ?,
              declined_comments = ?,
              alternate_date_1 = ?,
              alternate_date_2 = ?,
              alternate_date_3 = ? 
          WHERE id = ?";

$pstmt = $link->prepare($query);

// Check for prepare error
if (!$pstmt) {
    die("Error during prepare: " . $link->error);
}

// Bind parameters
$pstmt->bind_param("idsisisddds", 
    $rating, 
    $formatted_date, 
    $comments, 
    $set_interview, 
    $interview_comments, 
    $declined, 
    $declined_comments, 
    $alternate_dates[0], 
    $alternate_dates[1], 
    $alternate_dates[2], 
    $match_id);

// Check for bind_param error
if (!$pstmt->bind_param) {
   die("Error during bind_param: " . $pstmt->error);
}

$pstmt->execute();

// Check for execute error
if ($pstmt->errno) {
    die("Error during execute: " . $pstmt->error);
}
// Close the statement and connection
$pstmt->close();

echo "XXX".$rating;
echo "XXX".$formatted_date;
echo "XXX".$comments; 
echo "XXX".$set_interview;
echo "XXX".$interview_comments;
echo "XXX".$declined;
echo "XXX".$declined_comments;
echo "XXX".$alternate_dates[0]; 
echo "XXX".$alternate_dates[1]; 
echo "XXX".$alternate_dates[2]; 
echo "XXX".$match_id;

// Execute the query
exit();
*/

/*
$query = "DELETE FROM ic_InterviewMediumRequest
          WHERE candidate_id = '$candidate_id'";
		  $result = mysqli_query($link,$query);

$pstmt = $link->prepare($query);
$pstmt->bind_param("s",$candidate_id);
$pstmt->execute();


for($i = 0; $i < sizeof($mediums); $i++) {
  $query = "INSERT INTO ic_InterviewMediumRequest (candidate_id, medium_id, info)
            VALUES (?,?,?)";
  $pstmt = $link->prepare($query);
  $pstmt->bind_param("sss",$candidate_id, $mediums[$i], $medium_info[$mediums[$i] - 1]);
  $pstmt->execute();
}
*/
for ($i = 0; $i < count($mediums); $i++) {
    $mid  = (int)$mediums[$i];
    $idx  = max(0, $mid - 1);
    $info = $medium_info[$idx] ?? '';

    $cid_esc  = mysqli_real_escape_string($link, $candidate_id);
    $info_esc = mysqli_real_escape_string($link, $info);

    $query = "INSERT INTO ic_InterviewMediumRequest (candidate_id, medium_id, info)
              VALUES ('$cid_esc', $mid, '$info_esc') ";
    // echo $query, "\n"; // debug
    $result = mysqli_query($link, $query);
    if (!$result) {
        die("Insert failed: " . mysqli_error($link));
    }
}

// Get customer info
/*
// perhaps make $contact a sesion? $contact['full_name'];
$full_name = $_SESSION['full_name'];
$contact_email =	$_SESSION['email'];
$customer_id = $_SESSION['customer']
 */
// Get recruiter/candidate info for submital

$mail = new PHPMailer(true);

if($declined) {
  try {
    // Initialize PHPMailer and server settings
	/*
    $mail = new PHPMailer(true);

	// $mail->Debugoutput = 'html';
	// $mail->SMTPDebug  = 3;                     // enables SMTP debug information (for testing)
	// 1 = errors and messages
	// 2 = messages only

    $mail->isSMTP();
    $mail->Host = 'smtp.ionos.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'exchange@icreatives.co';
    $mail->Password = 'Call1888icreate!';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
	$mail->SMTPAutoTLS = true;               // lets PHPMailer upgrade if possible
	$mail->Timeout = 30; 
	
			$mail->isHTML(true);
            $mail->CharSet = "UTF-8";
            $mail->DKIM_domain = 'icreatives.co';
            $mail->DKIM_selector = 'performa';
            $mail->DKIM_private = $_SERVER['DOCUMENT_ROOT'] . '/dkim_keys/ionos-icreatives-co-dkim-private-key.key';
            $mail->DKIM_passphrase = '';
            $mail->DKIM_identity = 'exchange@icreatives.co';
	*/
		 				$mail = new PHPMailer();
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
    $mail->setFrom('exchange@icreatives.com', 'icreatives');
    
    if (!empty($owner_1_email)) {
      $mail->addAddress($owner_1_email);
    } else {
      $mail->addAddress($owner_email);
    }
    if (!empty($owner_2_email)) {
      $mail->addAddress($owner_2_email);
    }
    if (!empty($owner_3_email)) {
      $mail->addAddress($owner_3_email);
    }
    
    $mail->addBCC('jobcomp2@blindemail.com');
    $mail->addBCC('stevenc@icreatives.com');

    // Content
    $mail->Subject = "icreatives - declined candidate: $candidate_name";    
    $mail_message = "
<html>
<body>
<p>$customer_name declined $candidate_name!</p>
<ul>
  <li>Candidate name: <a href='https://app.manatal.com/candidates/$candidate_id'>$candidate_name</a></li>
  <li>Client Contact name: <a href='https://app.manatal.com/contacts/$contactID'>$contact_name</a></li>
  <li>Job Title: <a href='https://app.manatal.com/jobs/$orderID'>$job_name</a></li>
  <li>Comments: $comments</li>
  <li>Rating: $rating</li>
  <li>Declined Comments: $declined_comments</li>
</ul>
</body>
</html>";
    $mail->MsgHTML($mail_message);

    // Log before sending
    error_log("Sending declined email for candidate: $candidate_name");
    $mail->send();
    // Log after sending
    error_log("Email sent successfully for candidate: $candidate_name");
  } catch (Exception $e) {
    error_log("Email sending failed: " . $e->getMessage());
    echo "There was an error processing this request.";
    return;
  }


  // Add history event to match notes
  $mail_message = preg_replace('/[^a-zA-Z0-9_\-#;:&()<> ]/', '', $mail_message);
  $mail_message = addslashes($mail_message);
  $response = $client->request('POST', 'https://api.manatal.com/open/v3/matches/'.$match_id.'/notes/', [
    'body' => '{"info":"'.$mail_message.'"}',
    'headers' => [
      'Authorization' => $token,
      'accept' => 'application/json',
      'content-type' => 'application/json',
    ],
  ]);

  // Update match stage
  $currentDateTime = new DateTime('now', new DateTimeZone('UTC'));
  $formattedTime = $currentDateTime->format('Y-m-d\TH:i:s.u\Z');

	$client = new \GuzzleHttp\Client([ 'timeout' => 30, 'connect_timeout' => 10]);
	
	// echo $formattedTime."matchid= ".$match_id;

	try {
  $response = $client->request('PATCH', 'https://api.manatal.com/open/v3/matches/'.$match_id.'/', [
    'body' => '{"stage":{"id":1599306},"dropped_at":"'.$formattedTime.'"}',
    'headers' => [
      'Authorization' => $token,
      'accept' => 'application/json',
      'content-type' => 'application/json',
    ],
  ]);
  } catch (ConnectException | RequestException | \Exception $e) {
	 // echo "<script>alert('The server (match id) is extremely busy, please retry');</script>";
    // $apiError = true;
	// exit();
}
 
  // Stop portal sharing
  $query = "UPDATE ic_matches SET share = 0 WHERE id = ?";
  $pstmt = $link->prepare($query);
  $pstmt->bind_param("s", $match_id);
  $pstmt->execute();
  $pstmt->close();

// $link->close();

} else if($set_interview) {
  try {
    // Server settings
    // $mail->SMTPDebug = 3;
		/*
    $mail->isSMTP();
    $mail->Host = 'smtp.1and1.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'exchange@icreatives.com';
    $mail->Password = 'Call1888icreate!';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
	$mail->SMTPAutoTLS = true;               // lets PHPMailer upgrade if possible
	$mail->Timeout = 30; 

			$mail->isHTML(true);
            $mail->CharSet = "UTF-8";
            $mail->DKIM_domain = 'icreatives.co';
            $mail->DKIM_selector = 'performa';
            $mail->DKIM_private = $_SERVER['DOCUMENT_ROOT'] . '/dkim_keys/ionos-icreatives-co-dkim-private-key.key';
            $mail->DKIM_passphrase = '';
            $mail->DKIM_identity = 'exchange@icreatives.co';
	*/
			 	$mail = new PHPMailer();
				/*
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
				*/
					 				$mail = new PHPMailer();
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
	$mail->setFrom('exchange@icreatives.com', 'icreatives');
	if(!empty($owner_1_email)){$mail->addAddress($owner_1_email);
	} else {$mail->addAddress($owner_email) ?? "";}
	if(!empty($owner_2_email)){$mail->addAddress($owner_2_email)?? "";}
	if(!empty($owner_3_email)){$mail->addAddress($owner_3_email) ?? "";}
	$mail->addBCC('jobcomp2@blindemail.com');
	$mail->addBCC('stevenc@icreatives.com');

    // Content
    $mail->Subject = "icreatives - Interview Request for ".$row['candidate_name']."!";
    $mail_message = "
<html>
<body>
<p>".$customer_name." wants to meet with ".$candidate_name."!</p>
<ul>
  <li>Candidate name: <a href = 'https://app.manatal.com/candidates/`$candidate_id`'>`$candidate_name`</a></li>
  <li>Requester's name: <a href='https://app.manatal.com/candidates/`$contactID`'></a>".$contact_name."</li>
  <li>Requester's Contact ID: ".$contactID."</li>
  <li>Order ID: <a href='https://app.manatal.com/jobs/`$orderID`'>$orderID</a></li>
  <li>Interview date: ".(is_null($date) ? "ASAP" : $date)."</li>
  <li>Additional dates:</li>
";

foreach($additional_dates as $alt_date) {
  if($alt_date != "") $mail_message .= "\t$alt_date<br>";
}

$mail_message .= "
  <li>Comments: ".$comments."</li>
  <li>Rating: ".$rating."</li>
  <li>Decline Comment: ".$declined_comments."</li>  
  <li>Interview Comment: ".$interview_comments."</li>   
  <li>Phone: ".$medium_info[0]."</li>  
  <li>Teams: ".$medium_info[1]."</li>  
  <li>Facetime: ".$medium_info[2]."</li>  
  <li>GoToMeeting: ".$medium_info[3]."</li>  
  <li>Zoom: ".$medium_info[4]."</li>  
  <li>On-site: ".$medium_info[5]."</li>  
</ul>
</body>
</html>";

    $mail->MsgHTML($mail_message);
    $mail->send();
		//$mail_message = strip_tags($mail_message);
		$mail_message = preg_replace('/[^a-zA-Z0-9_\-#;:&()<> ]/', '',  $mail_message);
		$mail_message = addslashes($mail_message);

	// Add a note to the match
	// add history event to match notes

	$client = new \GuzzleHttp\Client([ 'timeout' => 30, 'connect_timeout' => 10]);
  $response = $client->request('POST', 'https://api.manatal.com/open/v3/matches/'.$match_id.'/notes/', [
  'body' => '{"info":"'.$mail_message.'"}',
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
    'content-type' => 'application/json',
  ],
	]);

  } catch (Exception $e) {
    echo "There was an error processing this request.";
		// echo "XXX".$owner_1_email."xxx".$owner_email;
    return;
  }

  // add history event
  /*
  $query = "EXEC HISTORY_INSERT @EventCode = 'PIR', @EventMethod = NULL, @Comment = '".str_replace("'","''","An interview was requested for ".$candidate_name." by ".$contact_name."
  Interview date: ".(is_null($date) ? "ASAP" : $date)."
  Rating: ".$rating."
  Comments: ".$comments."
  
  Mediums:
  ");
  */
  /*
  	// add history event to match notes
//$mail_message = strip_tags($mail_message);
		$mail_message = preg_replace('/[^a-zA-Z0-9_\-#;:&()<> ]/', '',  $mail_message);
		$mail_message = addslashes($mail_message);
		$client = new \GuzzleHttp\Client();
  $response = $client->request('POST', 'https://api.manatal.com/open/v3/matches/'.$match_id.'/notes/', [
  'body' => '{"info":"'.$mail_message.'"}',
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
    'content-type' => 'application/json',
  ],
	]);
*/
	$medium_query   = "SELECT medium_id, name FROM ic_InterviewMediums";
	$medium_result  = mysqli_query($link, $medium_query);
  $medium_names = [];
  while($row = mysqli_fetch_assoc($medium_result)) {
    $medium_names[$row['medium_id']] = $row['name'];
  }

$divisionID = ""; // division is no longer used:
// start query
// $mediums = array of selected medium IDs (e.g., [7, 2, 5])
// $medium_names = [id => "Teams", ...]
// $medium_info is an array aligned to your IDs (you used [$medium - 1] previously)

if (!isset($link)) { throw new \RuntimeException('DB link missing'); }

$stmt = $link->prepare(
  "INSERT INTO ic_InterviewMediumRequest (candidate_id, medium_id, info) VALUES (?, ?, ?)"
);
if (!$stmt) {
  throw new \RuntimeException('Prepare failed: ' . $link->error);
}

$inserted = 0;
foreach ($mediums as $mediumId) {
  // get the entered value for this medium
  $value = $medium_info[$mediumId - 1] ?? '';   // keep your existing indexing scheme
  $value = trim((string)$value);
  if ($value === '') {
    continue; // nothing to save for this medium
  }

  // bind and insert
  $candidateIdInt = (int)$candidateID;          // ensure types
  $mediumIdInt    = (int)$mediumId;
  $stmt->bind_param('iis', $candidateIdInt, $mediumIdInt, $value);

  if (!$stmt->execute()) {
    error_log("Insert failed for medium {$mediumIdInt}: " . $stmt->error);
  } else {
    $inserted++;
  }
}
$stmt->close();

// Optional: build a human-readable summary string (for email/logs), NOT for SQL
$lines = [];
foreach ($mediums as $mediumId) {
  $value = trim((string)($medium_info[$mediumId - 1] ?? ''));
  if ($value === '') continue;
  $label = $medium_names[$mediumId] ?? ("Medium {$mediumId}");
  $lines[] = "{$label}: {$value}";
}
$summary = implode("\n", $lines);

// For debugging (do not concatenate SQL here):
// echo nl2br(htmlspecialchars($summary));

  
  // end query
} else {

// add email to recruiter SJC 09/11/2020
$mail = new PHPMailer();
    // Recipients
	$mail->setFrom('exchange@icreatives.com','icreatives');
	if(!empty($owner_1_email)){$mail->addAddress($owner_1_email);
	} else {$mail->addAddress($owner_email);}
	if(!empty($owner_2_email)){$mail->addAddress($owner_2_email);}
	if(!empty($owner_3_email)){$mail->addAddress($owner_3_email);}
	$mail->addBCC('jobcomp2@blindemail.com');
	$mail->addBCC('stevenc@icreatives.com');
/*
    $mail->isSMTP();
    $mail->Host = 'smtp.1and1.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'exchange@icreatives.co';
    $mail->Password = 'Call1888icreate!';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
	$mail->SMTPAutoTLS = true;               // lets PHPMailer upgrade if possible
	$mail->Timeout = 30;                     // optional, sane timeout
	
				
			$mail->isHTML(true);
            $mail->CharSet = "UTF-8";
            $mail->DKIM_domain = 'icreatives.co';
            $mail->DKIM_selector = 'performa';
            $mail->DKIM_private = $_SERVER['DOCUMENT_ROOT'] . '/dkim_keys/ionos-icreatives-co-dkim-private-key.key';
            $mail->DKIM_passphrase = '';
            $mail->DKIM_identity = 'exchange@icreatives.co';
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

	$mail->Timeout = 30;                     // optional, sane timeout
    // Recipients
    $mail->setFrom('exchange@icreatives.com','icreatives');
    // $mail->addAddress($email);
    // $mail->addBCC('jobcomp2@blindemail.com');

    // Content
    $mail->Subject = "icreatives - Customer rated or commented candidate ".$row['candidate_name'];
    $mail_message = "
<html>
<body>
<p>$contact_name from $customer_name something changed $candidate_name!</p>
<ul>
  <li>Candidate name: <a href = 'https://app.manatal.com/candidates/`$candidate_id`'>`$candidate_name`</a></li>
  <li>Client Contact name: <a href = 'https://app.manatal.com/contacts/`$contactID`'>`$contact_name`</a></li>
  <li>Job Title: <a href='https://app.manatal.com/jobs/`$orderID`'>$job_name</a></li>
  <li>Comments: $comments</li>
  <li>Rating: $rating</li>
  <li>Declined Comments: $declined_comments</li>
</ul>

</body>
</html>";
    $mail->MsgHTML($mail_message);
    $mail->send();
// end email to recruiter

// xxx

  // add history event
  /*
  $query = "EXEC HISTORY_INSERT @EventCode = 'PSE', @EventMethod = NULL, @Comment = '".str_replace("'","''","Changes were made to candidate ".$candidate_name." by ".$contact_name."
  Rating: ".$rating."
  Comments: ".$comments)."', @EmployeeKey = '".addslashes($employeeID)."', @CustomerKey = '".addslashes($customerID)."', @DivisionKey = '".addslashes($divisionID)."', @ContactKey = '".addslashes($contactID)."', @OrderKey = '".addslashes($orderID)."', @AssignmentKey = '".addslashes($assignmentID)."'";
  odbc_exec($conn, $query);
  */
  
  	// Add a note to the match
	// add history event to match notes
		//$mail_message = strip_tags($mail_message);
		$mail_message = preg_replace('/[^a-zA-Z0-9_\-#;:&()<> ]/', '',  $mail_message);
		$mail_message = addslashes($mail_message);

	$client = new \GuzzleHttp\Client([ 'timeout' => 30, 'connect_timeout' => 10]);
  $response = $client->request('POST', 'https://api.manatal.com/open/v3/matches/'.$match_id.'/notes/', [
  'body' => '{"info":"'.$mail_message.'"}',
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
    'content-type' => 'application/json',
  ],
	]);

}

$next_candidate = ($candidate_num)%sizeof($_SESSION['candidates']);
// header("Location: /portal/rate-candidate/?cand=$next_candidate&o=$orderID".($set_interview ? "&i=1" : "") );
safe_redirect("/portal/manatal_rate_candidate.php/?cand=".$next_candidate."&o=".$orderID.($set_interview ? "&i=1" : "") );
exit;

?>