
<?php

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

if (!$link) {
    die('Connection failed: ' . mysqli_connect_error());
}

require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$client = new \GuzzleHttp\Client();
//Retrieve job information Load Guzzle for the Manatal API
/*
function encrypt_string($plaintext) {

$key = "4b14783a4b5848cd43b9fc7e4cf0fb6f";

// $key = 'YOUR_SALT_KEY'; // Previously generated safely, ie: openssl_random_pseudo_bytes 
 //$plaintext = "String to be encrypted"; 
 
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
$iv = openssl_random_pseudo_bytes($ivlen); 
$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true); 
 
// Encrypted string 
return base64_encode($iv.$hmac.$ciphertext_raw);
}
*/

function formatPhoneNumber($phoneNumber) {
	echo "CCCC".$phoneNumber;
    // Remove all non-numeric characters
    $phoneNumber = preg_replace("/[^0-9]/", "", $phoneNumber);

    // Check if the number starts with "1" and add "+1" if not
    if (strlen($phoneNumber) == 10) {
        $phoneNumber = "+1" . $phoneNumber;
    } elseif (strlen($phoneNumber) == 11 && $phoneNumber[0] != '1') {
        $phoneNumber = "+1" . substr($phoneNumber, 1);
    }
echo $phoneNumber;
    return $phoneNumber;
}


// taken from view invoice

Function decode($ciphertext) {
	// $ciphertext = str_replace(" ","+",$ciphertext);
	$key = "4b14783a4b5848cd43b9fc7e4cf0fb6f"; // Previously used in encryption 
	$c = base64_decode($ciphertext); 
	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
	$iv = substr($c, 0, $ivlen); 
	$hmac = substr($c, $ivlen, $sha2len=32); 
	$ciphertext_raw = substr($c, $ivlen+$sha2len); 
	echo $invnum = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
	return $invnum;
}


Function add_match($match_id,$mass_email,$mass_text) {
		global $link;
		$sql = "INSERT INTO ic_matches (
		id, 
		mass_email,
		mass_text,
		is_active) 
		VALUES ('". 
		$match_id."', 
		1,1,0) 
		ON DUPLICATE KEY UPDATE 
		mass_email = 1,
		mass_text = 1";
		
		echo "<br><br>SQL = " . $sql . "<br>";
		// $SQLr = mysqli_query($link,$sql )

		$sucess = false;
        if ($link->query($sql) === TRUE) {
			$sucess = true;
			//echo "<br>Saved";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
		
    }


Function is_mass($match_id) {
	$query = "SELECT mass_email, mass_text FROM ic_matches WHERE id = '".$match_id."'";
	global $link;
	echo $query;
	$rowSQL = mysqli_query($link,$query);
	$row = mysqli_fetch_array( $rowSQL );
	$mass_email = $row['mass_email'];
	$mass_text = $row['mass_text'];
	if ($mass_email == 1 || $mass_text == 1) {
		return true;
	} else {
		return false;
	}
}
/*
Function description($job_id) {
	global $client
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/'.$job_id.'/', [
	'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
	],
	]);

	$response->getBody();

	$responseStr = $response->getBody();
	$job_arr = json_decode($responseStr, true);

	$job_description = $job_arr['description'];
	return $job_description;
}
*/
Function candidate_info($candidate_id) {
	global $client;
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/'.$candidate_id.'/', [
	'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
	],
	]);


	$response->getBody();

	$responseStr = $response->getBody();
	$candidate_arr = json_decode($responseStr, true);
	echo $phone_number = formatPhoneNumber($candidate_arr['phone_number']);
echo "XXX";

	// echo "candidate phone = ".$phone_number;

	$array['phone'] = $phone_number;
	$array['email'] = $candidate_arr['email'];
	// echo "candidate email = ". $candidate_arr['email'];
	$array['fullname'] = $candidate_arr['full_name'];

	return $array;
}

Function match_info($match_id) {
	global $client;
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/matches/'.$match_id.'/', [
	'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
	],
	]);
	$response->getBody();

	$responseStr = $response->getBody();
	$match_arr = json_decode($responseStr, true);
	
	return $match_arr ;

}

Function job_info($match_id) {
	global $client;
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/matches/'.$match_id.'/', [
	'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
	],
	]);
	$response->getBody();

	$responseStr = $response->getBody();
	$match_arr = json_decode($responseStr, true);
	$job = $match_arr['job'];
echo "job= ".$job;
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/'.$job.'/', [
	'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
	],
	]);
	$response->getBody();

	$responseStr = $response->getBody();
	$job_arr = json_decode($responseStr, true);
	return $job_arr;
}


// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/PHPMailer.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/Exception.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/SMTP.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/class-phpmailer.php");

/* Authenticate to Mailserver */
// $mailserver = "{imap.gmail.com:993/debug/imap/ssl/novalidate-cert}INBOX";
$mailserver = "{imap.ionos.com:993/debug/imap/ssl}INBOX";

// Open USer Database and find a Branch to check by checking UDF1
// Next time we will add back branches (sjc 2022)

$name = "icreatives message";
// $email = "icreatives";
$forward = strtoupper($email);
$pw = "CallowayCab1!";

// echo "<br>$email = " . $email;

$mailuser = "text@icreative.com";
$mailpass = "CallowayCab1!";

    
/* Open Mailbox Stream */
$mbox = imap_open($mailserver, $mailuser, $mailpass) or die ("Couldn't connect to $mailserver");
// Connect to the SMTP server
echo " logged in <BR>";

$foldertest = imap_status($mbox, '{imap.ionos.com:993/imap/ssl}INBOX', SA_MESSAGES);

echo '<br>INBOX Messages=';
echo $foldertest->messages;
echo '<br>';


// Fetch emails

// $emails = $mail->search('TEXT "href=https://ext.manatal.com/candidates/"');

// Loop through fetched emails and process them
	$crit = 'ALL';
IF ($foldertest->messages > 0) {
	$header = imap_search($mbox, $crit);
	foreach ($header as $val) {
		$body = imap_body ($mbox, $val);
					
		$body = preg_replace('/[\x00-\x1F\x7F]/', '', $body);
		$body = str_replace("candid=ates","candidates",$body);
		$body = str_replace("candidat=es","candidates",$body);
		$body = str_replace("3D","",$body);
		// echo htmlentities($body);
		$header = imap_headerinfo($mbox, $val);
		echo $from = $header->from[0]->mailbox ."@". $header->from[0]->host;

		if (strpos($body, 'https://ext.manatal.com/candidates/') >0) {
			$from = $header->from[0]->mailbox ."@". $header->from[0]->host;
			
			// get password
			
			$p_query = "SELECT password from ic_sales where email = '". $from. "'";
			$result = mysqli_query($link,$p_query);
			$row_p = mysqli_fetch_array( $result );
			
			$row_p['password'];
			$password = decode($row_p['password']);
			
			// $pos = '<a contenteditable="false" target="_blank" href="https://ext.manatal.com/candidates/';
			// echo $body = quoted_printable_decode(imap_body ($mbox, $val));
			// $body = imap_body ($mbox, $val);
			$pos1 = 1;
			$offset = 0;
			//	look for strings like this: https://ext.manatal.com/candidates/58028801?mid=3D40512581

			$pattern = '/candidates\/(\d+)\?mid=(\d+)/';


			preg_match_all($pattern, $body, $matches, PREG_SET_ORDER);

			foreach ($matches as $match) {
				echo "Candidate ID = ";
				echo $candidate_id = $match[1];
				echo"Match id = ";
				echo $match_id = $match[2];
				echo"<br>";

				$match_arr = match_info($match_id);
					
					$job_arr = job_info($match_arr['id']);

					$candidate_arr = candidate_info($candidate_id);

					// Done Finding ids
							
					// Create a new PHPMailer instance
					$mail = new PHPMailer();
					// Configure SMTP or other mail settings
							$mail->IsSMTP(); // telling the class to use SMTP
							$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
							// 1 = errors and messages
							// 2 = messages only
							$mail->SMTPAuth   = true;                  // enable SMTP authentication
							$mail->Host       = 'smtp.office365.com';
							$mail->Username   = $from; // SMTP account username
							$mail->Password   = "Call1888icreate!";        // SMTP account password
							// $mail->Password   = $password;        // SMTP account passwordCallowayCab1!
							$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
							$mail->Port       = 587;  // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above		
							$mail->isHTML(true);                             // Set email format to HTML
							$mail->CharSet = "UTF-8";


							// Set sender and recipient addresses
							$mail->addBCC('text@blindemail.com');
							$mail->addBCC('stevenc@icreatives.com');
							$mail->setFrom($from, 'icreatives'); // fix once manatal configures our email
							// $mail->setFrom('text@icreative.com', 'icreatives');
							// $mail->AddReplyTo('text@icreative.com', 'icreatives');
							
							/*
							$full_name = $candidate_info['full_name'];
							$position_name = $job_info['jposition_name'];
							$email = $candidate_info['email'];
							$phone = $candidate_info['phone'];
							*/

							// Set email subject and body
							$mail->Subject = 'Pob position;' ;
							
							$mail->Body = 'Hello '. $candidate_arr['full_name'].',
							<p>Please let us know if you are interested in: <br>
								<p>Position: '.$job_arr['position_name'].', <bt>
								<p>Salary Min: '.$job_arr['custom_fields']['salarymin'].' - Max: '. $job_arr['custom_fields']['salarymax'].',<br> 
								<p>for more information: https://icreatives.com/job/'.$job_arr['id'].'<br>
								<p>If you are interested: Reply "YES"<br>
								<p>If you are NOT interested: Reply "NO"<br>
								<p>Reply "STOP" to no longer being contacted<br>';

								// $mail->AltBody = 'Please find the attached PDF Invoice.';
							// Send the email
							
							if (!empty($candidate_arr['phone']) && !empty($candidate_arr['email']) && !is_mass($match_id) ) {

								$mail->addAddress($candidate_arr['email']);
								$mail->addAddress($candidate_arr['phone'].'@e2s.messagemedia.com');
							
								if ($mail->send()) {
									add_match($match_id,$mass_email,$mass_text);
									echo 'Email sent successfully to: '.$candidate_arr['email']."<br>";
									echo 'And Text sent successfully to: '.$candidate_arr['phone'].'@pcsms.us<br>';
								} else {
									echo 'Email could not be sent.';
								}
							} else {
								echo "<br>no addresses to email or already sent one";
							}
						}
				
						// <a contenteditable="false" target="_blank" href="https://ext.manatal.com/candidates/
				
						// echo "Head = " . $head . "<BR>";
						// echo '<p>From: ' . $header->fromaddress . '<p>';
						// echo '<p>Email: ' . $header->senderaddress . '<p>';
						// echo '<p>Email Address: ' . $header->from[0]->mailbox ."@". $header->from[0]->host. '<p></br>';
						// echo "from: " . $from . "<br>";
						// echo "POS = " . $pos . "---<br>";
						// exit();
						// $result = imap_mail_copy($mbox, $val, 'Inbox/Waiting', CP_UID);
						// if ($pos !== false OR $from == "stevenc@icreatives") {
						if ($pos !== false ) {
							// imap_mail_move($mbox, $val,'Inbox/Complete');
							// imap_expunge($mbox); 
							// imap_delete($mbox, $val);
							echo "true<br>";
						} else {
							echo "<br />this should delete<br />";
							// imap_delete($mbox, $val); 
							map_mail_move($mbox, $val,'Trash');
							map_expunge($mbox); 
							$flstring='';
							echo "False<br>";
						}
					} 

	}
 
/* Close Mailbox Stream */
imap_close($mbox);

// include $_SERVER['DOCUMENT_ROOT']."/tracker-readres2.php";

		}
 
?>


<html>
  <head>
    <title>Delete Non applicant email and spam</title>
  </head>
  <body>   
    <div id='ResponseDiv'>    
    </div>
  </body>
</html>

