<?php
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
 
 use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use GuzzleHttp\Exception\ClientException;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) .  '/db/token.php';
require_once dirname(__DIR__, 2) .  '/db/db.php';
$link = db();  
// echo $token;


//Retrieve job information Load Guzzle for the Manatal API

function formatPhoneNumber($phoneNumber) {
    // Remove all non-numeric characters
    $phoneNumber = preg_replace("/[^0-9]/", "", $phoneNumber);

    // Check if the number starts with "1" and add "+1" if not
    if (strlen($phoneNumber) == 10) {
        $phoneNumber = "+1" . $phoneNumber;
    } elseif (strlen($phoneNumber) == 11 && $phoneNumber[0] != '1') {
        $phoneNumber = "+1" . substr($phoneNumber, 1);
    }
/// echo $phoneNumber;
    return $phoneNumber;
}

Function add_match($match_id) {
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
		// UNCOMMENT BELOW WHEN READY
		// echo "<br><br>SQL = " . $sql . "<br>";
		// $SQLr = mysqli_query($link,$sql )

		$sucess = false;
        if ($link->query($sql) === TRUE) {
			$sucess = true;
			echo "<br>Saved";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
		
    }

Function is_mass($match_id) {
	$query = "SELECT mass_email, mass_text FROM ic_matches WHERE id = '".$match_id."'";
	global $link;
	// echo $query;
	$rowSQL = mysqli_query($link,$query);
	$row = mysqli_fetch_array( $rowSQL );
	$mass_email = $row['mass_email'] ?? '';
	$mass_text = $row['mass_text'] ?? '' ;
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
$client =  new \GuzzleHttp\Client(['timeout' => 5.0,'connect_timeout' => 3.0]);
Function candidate_info($candidate_id) {
	global $client;
	global $token;
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/'.$candidate_id.'/', [
	'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
	],
	]);


	$response->getBody();

	$responseStr = $response->getBody();
	$candidate_arr = json_decode($responseStr, true);
	$phone_number = formatPhoneNumber($candidate_arr['phone_number']);
// echo "XXX";



	// echo "candidate phone = ".$phone_number;

	$array['phone'] = $phone_number;
	$array['email'] = $candidate_arr['email'];
	// echo "candidate email = ". $candidate_arr['email'];
	$array['full_name'] = $candidate_arr['full_name'];
	$array['donottext'] = $candidate_arr['custom_fields']['donottext'] ?? '';

	return $array;
}

Function match_info($match_id) {
	global $client;
		global $token;
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
		global $token;
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
require_once dirname(__DIR__,2) . '/PHPMailer/PHPMailer.php';
require_once dirname(__DIR__,2) . '/PHPMailer/Exception.php';
require_once dirname(__DIR__,2) . '/PHPMailer/SMTP.php';

$name = "icreatives message";

/* Authenticate to Mailserver */
$mailserver = "{imap.ionos.com:993/imap/ssl}INBOX";
$mailuser   = "text@icreativesstaffing.com";
$mailpass   = "CallowayCab!";

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
		$from = $header->from[0]->mailbox ."@". $header->from[0]->host;

		if (strpos($body, 'https://ext.manatal.com/candidates/') >0) {
			$from = $header->from[0]->mailbox ."@". $header->from[0]->host;
			
			// get sender's user id:
			
			// $client = new \GuzzleHttp\Client();


			$response = $client->request('GET', 'https://api.manatal.com/open/v3/users/', [
			'headers' => [
				'Authorization' => $token,
				'accept' => 'application/json',
				],
			]);
			$responseStr = $response->getBody();
			$data = json_decode($responseStr, true);
			foreach ($data['results'] as $user) {
				if ($user['email'] == $from) {
					$userId = $user['id'];
					break; 
				}
			}

			// Your ClickSend API credentials


			// Admin credentials
			$username = 'accounting_mail@icreatives.com';
			$apiKey   = 'EF1467DA-FBAE-1181-7966-A75523EE11B4'; // this is the actual API key			
			$email    = $from; // Subaccount to find

			// Make the API request to list subaccounts
			$curl = curl_init();

			curl_setopt_array($curl, [
				CURLOPT_URL => 'https://rest.clicksend.com/v3/subaccounts',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => [
					'Authorization: Basic ' . base64_encode("$username:$apiKey"),
					'Content-Type: application/json'
				],
			]);

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);


			// Handle the response
			if ($err) {
				echo "cURL Error #: " . $err;
			} else {
				$json = json_decode($response, true);

				if (isset($json['data']['data'])) {
					$subaccounts = $json['data']['data'];

					foreach ($subaccounts as $account) {
						if (isset($account['email']) && strtolower($account['email']) === strtolower($email)) {
							$api_key = $account['api_key'];
							// echo "✅ API Key for {$email}: {$api_key}\n";
						break;
						}
					}
				} else {
					echo "❌ No subaccount data found.\n";
				}
			}

			$pos1 = 1;
			$offset = 0;
			//	look for strings like this: https://ext.manatal.com/candidates/58028801?mid=3D40512581

			$pattern = '/candidates\/(\d+)\?mid=(\d+)"/';

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
				
				// echo var_dump($candidate_arr);

					// Done Finding ids
							
					// Create a new PHPMailer instance
				$mail = new PHPMailer();
	
				$mail->IsSMTP(); // telling the class to use SMTP
				// $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
				// 1 = errors and messages
				// 2 = messages only
				$mail->SMTPAuth   = true;                  // enable SMTP authentication
				$mail->Host       = "smtp.1and1.com"; // sets the SMTP server
				$mail->Username   = "exchange@icreatives.com"; // SMTP account username
				$mail->Password   = "Call1888icreate!";        // SMTP account password
				$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
				$mail->Port       = 587;  // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above		
				$mail->isHTML(true);                             // Set email format to HTML
				$mail->CharSet = "UTF-8";
				// DKIM Setup
				$mail->DKIM_domain = 'icreatives.com';
				$mail->DKIM_selector = 'performa';
				$mail->DKIM_private = $_SERVER['DOCUMENT_ROOT'] . '/dkim_keys/ionos-icreatives-dkim-private-key.key'; // Replace with actual path
				$mail->DKIM_passphrase = ''; // If your key has a passphrase, enter it here
				$mail->DKIM_identity = 'exchange@icreatives.com'; // Typically same as From

				// Set sender and recipientn email addresses

							// Set sender and recipient addresses
							// $mail->addBCC('text@blindemail.com');
							$mail->addBCC('stevenc@icreatives.com');
							$mail->setFrom($from, 'icreatives'); // fix once manatal configures our email
							$mail->AddReplyTo($from);
							
							/*
							$full_name = $candidate_info['full_name'];
							$position_name = $job_info['jposition_name'];
							$email = $candidate_info['email'];
							$phone = $candidate_info['phone'];
							*/

							// Set email subject and body
							$mail->Subject = 'job position;' ;
							$mail->Body = 'Hello '. $candidate_arr['full_name'].',
								<p>Please let us know if you are still interested in: <br>
								<p>Position: '.$job_arr['position_name'].', <br>
								<p>for more information: https://icreatives.com/job/'.$job_arr['id'].'-'.$match_id.'-'.$userId.'<br>
								<p>If you are interested: Reply "YES"<br>
								<p>If you are NOT interested: Reply "NO"<br>
								<p>Reply "STOP" to no longer being contacted<br>';

							// Send the email
					
							$phoneOnly = "+1". preg_replace(
								['/^\+1/', '/\D/'], // Patterns
								['', ''],           // Replacements
								$candidate_arr['phone'] // Subject
								);
							// echo "text to sms= ".$phoneOnly."<br";
							
							// if (!empty($candidate_arr['phone']) || !empty($candidate_arr['email']) && !is_mass($match_id) ) {
							
							if (!empty($candidate_arr['email']) && !is_mass($match_id) ) {
								$mail->addAddress($candidate_arr['email']);					
								if ($mail->send()) {
									// add_match($match_id);
									echo 'Email sent successfully to: '.$candidate_arr['email']."<br>";
									echo 'And Text sent successfully to: '.$phoneOnly.'@sms.clicksend.com<br>';
								} else {
									echo 'Email could not be sent.';
								}
							} else {
								echo "<br>no addresses to email or already sent one";

							}
							// send the text
							// echo "text = ".$candidate_arr['custom_fields']['donottext'];
							// exit();   
							// if (!empty($candidate_arr['phone']) && !is_mass($match_id)  ) {
							// if (!empty($candidate_arr['phone']) && !is_mass($match_id) && (!isset($candidate_arr['custom_fields']['donottext']) || $candidate_arr['custom_fields']['donottext'] == false) ) {
							if (!empty($candidate_arr['phone']) && !is_mass($match_id) && empty($candidate_arr['donottext']) ) {
								// get first name
								list($first_name) = explode(' ', trim($candidate_arr['full_name']));	
								$body = "Hi " .$first_name.
								", You applied to: ".substr($job_arr['position_name'],0,25). " - ".
								" https://icreatives.com/job/". $job_arr['id']."-".$match_id."-".$userId." - ";

							$body = $body. "Still interested? Reply YES or NO, " .
							"STOP for no more texts.";
					
							// $to = "+19545296291";
							// exit();
						
							$data = [
								'messages' => [
									[
									'source' => 'php',
									'from' => 'icreatives', // optional
									'body' => $body	,
									'to' => $phoneOnly ,
									'schedule' => null
									]
								]
							];
				
							$curl = curl_init();

							curl_setopt_array($curl, [
							CURLOPT_URL => "https://rest.clicksend.com/v3/sms/send",
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => "",
							CURLOPT_MAXREDIRS => 10,
							CURLOPT_TIMEOUT => 30,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => "POST",
							CURLOPT_POSTFIELDS => json_encode($data),
							CURLOPT_HTTPHEADER => [
								 "Authorization: Basic " . base64_encode($email . ':' . $api_key),
								"Content-Type: application/json"
								],
							]);
							
							$response = curl_exec($curl);
							$err = curl_error($curl);
							curl_close($curl);

							if ($err) {
								echo "cURL Error #: " . $err;
							} else {
								echo $response;
							}
							
							// Move Candidate to stage "Email or Texted"
							
							// $client = new \GuzzleHttp\Client();

					$response = $client->request('PATCH', 'https://api.manatal.com/open/v3/matches/'.$match_id.'/', [
					'body' => '{"stage":{"id":910956}}',
					'headers' => [
					'Authorization' => $token,
					'accept' => 'application/json',
					'content-type' => 'application/json',
					],
				]);

				$response->getBody();
							// uncomment when live
							add_match($match_id);
							
							}
						}
			
					
							imap_mail_move($mbox, $val,'Inbox/Completed');
							imap_expunge($mbox); 
							// imap_delete($mbox, $val);
							echo "Email Moved to Completed Folder<br>";

					} else {
							echo "<br />this should delete<br />";
							imap_delete($mbox, $val); 
							imap_mail_move($mbox, $val,'Trash');
							imap_expunge($mbox); 
							$flstring='';
							echo "Email Moved to Trash<br>";
					}
	}
 
/* Close Mailbox Stream */
imap_close($mbox);
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

