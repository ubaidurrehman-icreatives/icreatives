<?php
session_start();
if(!isset($_SESSION['user_id'])) {
  session_regenerate_id();
  echo "Log back in to continue";
  return;
}

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

require_once "../random_compat/lib/random.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$sharer = $_SESSION['contactID'];
$candidate = $_REQUEST['c'];
$recipient_email = $_REQUEST['email'];
$order = $_REQUEST['o'];
$return = [];

require_once($_SERVER['DOCUMENT_ROOT']."/wp-includes/class-phpmailer.php");
require_once($_SERVER['DOCUMENT_ROOT']."/wp-includes/PHPMailer/PHPMailer.php");
require_once($_SERVER['DOCUMENT_ROOT']."/wp-includes/PHPMailer/Exception.php");
require_once($_SERVER['DOCUMENT_ROOT']."/wp-includes/PHPMailer/SMTP.php");


// Get contact/customer info
$query = "SELECT  *FROM ic_contact WHERE id = '`$sharer`'";
$result = mysqli_query($link,$query);
$row = mysqli_fetch_array($result);

$contactID = $row['id'];
$sharer_name = $row['full_name'];
$sharer_email = $row['email'];
$customerID = $row['organization'];
$company_name = $row['company_name'];

// Get order recruiter/candidate info for submital
$query = "SELECT * from ic_matches
          WHERE candidate = '`$candidate`' AND job = '`$order`'";
$result = mysqli_query($link,$query);
$row = mysqli_fetch_array($result);

$candidate_name = $row['candidate_name'];
$employeeID = $row['candidate'];
$assignmentID = $row['job'];
$orderID = $row['job'];
$job_name = $row['job_name'];
$match_id = $row['id'];

$owner_email = $owner_info['email'];
$owner_1_name = $row['owner_1_name'];
$owner_2_name = $row['owner_2_name'];
$owner_3_name = $row['owner_3_name'];

// Close the statement and connection
$pstmt->close();
// $link->close();

// Get email addresses of all recruiters involved
// get email addresses from user diplay names in match records

$client = new \GuzzleHttp\Client();
	
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


// Get email addresses of all recruiters involved


// send email to recruiter notifying about share
$mail = new PHPMailer(true);
try {

    // Recipients
	if(!empty($owner_1_email)){$mail->addAddress($owner_1_email);
	} else {$mail->addAddress($owner_email);}
	if(!empty($owner_2_email)){$mail->addAddress($owner_2_email);}
	if(!empty($owner_3_email)){$mail->addAddress($owner_3_email);}
	$mail->addBCC('jobcomp2@blindemail.com');
	$mail->addBCC('stevenc@icreatives.com');
  // Server settings
  // $mail->SMTPDebug = 3;
/*
    // Initialize PHPMailer and server settings
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.1and1.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'exchange@icreatives.co';
    $mail->Password = 'Call1888icreate!';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
*/
	 			// $mail = new PHPMailer();
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

  // Content
  $mail->Subject = "icreatives - $sharer_name shared a candidate";
  $mail_message = "
  <html>
  <body>
  <p>$sharer_name from $division_name of $customer_name shared $candidate_name's info with $recipient_email</p>
  <ul>
    <li>$sharer_name's Contact ID: $sharer</li>
    <li>Sharer email: $sharer_email</li>
    <li>Employee ID: $employeeID</li>
    <li>Job order: $order</li>
    <li>Job title: $job_name</li>
  </ul>

  </body>
  </html>";
  $mail->MsgHTML($mail_message);
  $mail->send();
} catch (Exception $e) {
  $return = ['e' => 'mail'];
  echo json_encode($return);
  return;
}

// $query = "EXEC HISTORY_INSERT @EventCode = 'PSS', @EventMethod = 'E', @Comment = '".str_replace("'","''",$candidate_name." was shared by $sharer_name to the email address $recipient_email")."', @EmployeeKey = '".addslashes($employeeID)."', @CustomerKey = '".addslashes($customerID)."', @DivisionKey = '".addslashes($divisionID)."', @ContactKey = '".addslashes($contactID)."', @OrderKey = '".addslashes($orderID)."', @AssignmentKey = '".addslashes($assignmentID)."'";
// odbc_exec($conn, $query);

// create share ticket selector and validator

require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
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

}

// create new share ticket (expires in 1 week)
$query = "INSERT INTO ic_share_candidate_tickets(
creator_contact_id, 
candidate_id, 
recipient_email, 
selector, 
token, 
created_at, 
expires_at, 
order_id, 
employee_id
) VALUES($sharer, $candidate, $recipient_email, $selector, hash('sha256', $token),GETDATE(),DATEADD(WEEK,1,GETDATE()),$order, $employeeID)";
$result = mysqli_query($link,$query);

try {
  // Server settings
  // $mail->SMTPDebug = 3;
  $mail->isSMTP();
  $mail->Host = 'smtp.1and1.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'exchange@icreatives.co';
  $mail->Password = 'Call1888icreate!';
  $mail->SMTPSecure = 'tls';
  $mail->Port = 25;

  // Recipients
  $mail->setFrom('exchange@icreatives.com','icreatives');
  $mail->addAddress($recipient_email);
  $mail->addBCC('jobcomp2@blindemail.com');

  // Content
  $mail->Subject = "icreatives - $sharer_name sent you a candidate";
  $hex_token = bin2hex($token);
  $mail_message = "
  <html>
  <body>
  <p>$sharer_name would like you to take a look at $candidate_name for the position $econnect_posting_title.</p>

  <p>
  You can find their information here:<br>
  https://www.icreatives.com/preview-candidate/?d=$selector&v=$hex_token
  </p>
  </body>
  </html>";
  $mail->MsgHTML($mail_message);
  $mail->send();
} catch (Exception $e) {
  $return = ['e' => 'mail'];
  echo json_encode($return);
  return;
}

$return['r'] = 'success';

echo json_encode($return);
return;

?>
