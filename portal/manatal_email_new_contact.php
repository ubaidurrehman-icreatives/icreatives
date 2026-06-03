<!DOCTYPE html>
<?php
session_start();
$user = $_SESSION['user'];
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());


$portal_url = $_SESSION['portal_url'];

$owner = $_SESSION['owner'];
$user = $_SESSION['user'];

$password = $_SESSION['user_pass'];

// Get Contact id & info from Contact Email
	$query = "select * from ic_contacts where email = '". $user . "' and icreativesportalaccess";
	$result = mysqli_query($link,$query);
	$row = mysqli_fetch_array($result);
	$contactID = $row['id'];
	
$_SESSION['contactID'] = $contactID;

// echo "owner = ". $_SESSION['owner'];
// echo "portal url = ".$_SESSION['portal_url'];
// echo "user = ". $_SESSION['user']; // User = Contact's  portal login email address
// echo "contactID = ".$_SESSION['contactID'];

// Find Contact Creator and Commision user for company to notify, (perhaps we can serch for jobs to notify other recruiters)

require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$client =  new \GuzzleHttp\Client();

// update contacts before we do anything remember to add this in the create password
	// everything updated within a certain time
	$after =  date("Y-m-d",strtotime('today - 3 days'));
	// echo "updates after ".$after;

//	$response = $client->request('GET', 'https://api.manatal.com/open/v3/contacts/?updated_at__gte=2022-09-01', [
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/contacts/?updated_at__gte='.$after, [
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
		],
	]);
// echo $response;


	$response->getBody();

	$responseStr = $response->getBody();
	$contacts = json_decode($responseStr, true);

	// echo "<br>count= ". $contacts['count']."<BR>";
	
	$page_count = $contacts['count'];
	
		// foreach($contacts["results"] as $contact) {
	
	for($x=0; $x<count($contacts['results']); $x++) {
		
		// echo $contacts['results'][$x]['full_name'] . "<br>";
		
		if(is_null($contacts['results'][$x]['custom_fields']['icreativesportalaccess'])){
			$portal = 0;
		} else {
			$portal = 1;
		}
		if(is_null($contacts['results'][$x]['custom_fields']['one_invoice_per_candidate'])){
			$one_invoice_per_candidate = 0;
		} else {
			$one_invoice_per_candidate = 1;
		}
	
		$query = "INSERT INTO ic_contacts (
		id, 
		full_name, 
		display_name, 
		email, 
		organization,
		icreativesportalaccess,
		accountspayable,
		address1,
		address2,
		city,
		state,
		postalcode,
		country,
		phone_number,
		ap_template,
		terms,
		one_invoice_per_candidate,
		created_at
		) 
		VALUES ('". addslashes($contacts['results'][$x]['id'])."', '".
		addslashes($contacts['results'][$x]['full_name'])."','".
		addslashes($contacts['results'][$x]['display_name'])."','".
		addslashes($contacts['results'][$x]['email'])."','".
		addslashes($contacts['results'][$x]['organization'])."','".
		$portal. "', '".
		$contacts['results'][$x]['custom_fields']['accountspayable']."', '".
		$contacts['results'][$x]['custom_fields']['streetaddress']."', '".
		$contacts['results'][$x]['custom_fields']['streetaddress_b']."', '".
		$contacts['results'][$x]['custom_fields']['city']."', '".
		$contacts['results'][$x]['custom_fields']['state']."', '".
		$contacts['results'][$x]['custom_fields']['postalcode']."', '".
		$contacts['results'][$x]['custom_fields']['country']."', '".
		$contacts['results'][$x]['phone_number']."', '".
		$contacts['results'][$x]['custom_fields']['ap_template']."', '".
		$contacts['results'][$x]['custom_fields']['terms']."', '".
		$contacts['results'][$x]['custom_fields']['invoicepercandidate'] ."', '".
		$contacts['results'][$x]['created_at']. "' 
		) 
		 ON DUPLICATE KEY UPDATE 
		full_name= VALUES(full_name),
		display_name=VALUES(display_name),
		email=VALUES(email), 
		organization= VALUES(organization), 
		icreativesportalaccess= VALUES(icreativesportalaccess), 
		accountspayable = VALUES(accountspayable),
		address1 = VALUES(address1), 
		address2 = VALUES(address2), 
		city = VALUES(city),
		state = VALUES(state),
		postalcode = VALUES(postalcode),
		country = VALUES(country),
		phone_number = VALUES(phone_number),
		ap_template = VALUES(ap_template),
		terms = VALUES(terms),
		one_invoice_per_candidate = VALUES(one_invoice_per_candidate),
		created_at = VALUES(created_at)"; 
		// $query = str_replace("''","'0'",$query);
		// echo $query."<br><br>";
		$result = mysqli_query($link,$query );
		// echo $query;
	}
// end update contacts
function getOwnerInfo($ownerid) {
	global $client;
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/users/'.$ownerid."/", [
		'headers' => [
			'Authorization' => $token,
			'accept' => 'application/json',
		],
	]);
	$response->getBody();
	$responseStr = $response->getBody();
	$user_arr = json_decode($responseStr, true);
	return $user_arr;
}

// get info from ic_contacts
// update all recently modified contacts
function getCompanyInfo($organization) {
	global $client;
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/organizations/'.$organization."/", [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
	]);
	$response->getBody();
	$responseStr = $response->getBody();
	$orginfo = json_decode($responseStr, true);
	return $orginfo;
}

$response = $client->request('GET', 'https://api.manatal.com/open/v3/contacts/'.$_SESSION['contactID'].'/', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);

	$response->getBody();
	$responseStr = $response->getBody();
	$contact_info = json_decode($responseStr, true);

// Get Contact Creator Email and Name
if(!empty($contact_info['owner'])) {
	$ownerid = getOwnerInfo($contact_info['owner']);
} else {
	// $ownerid  = getOwnerInfo($contact_info['owner']);
}

if(!empty($contact_info['creator']) && empty($contact_info['owner'])   ) {
	$ownerid = getOwnerInfo($contact_info['creator']);
} else {
	// $ownerid  = getOwnerInfo($contact_info['owner']);
}


if (empty($ownerid ) || !isset($ownerid )){
		header("Location:/portals/?r=recognize".($order != "" ? "&orderID=$order" : ""));
		exit;
	}
	$ContactCreatorName = $ownerInfo['full_name'];
	$ContactCreatorEmail = $ownerInfo['email'];
	$ContactCreatorNumber = $contact_info['creator'];
	

	
// Get Company Owner Email and Name
	$ContactOrganizationNumber = $contact_info['organization'];
	$CompanyInfo = getCompanyInfo($ContactOrganizationNumber);

	$CompanyOwnerNumber = $CompanyInfo['owner'];

	if ($CompanyOwnerNumber !=="" && !empty($CompanyOwnerNumber) && !is_null($CompanyOwnerNumber)) {
	$ownerInfo = getOwnerInfo($CompanyOwnerNumber);
	$CompanyOwnerName = $ownerInfo['full_name'];
	$CompanyOwnerEmail = $ownerInfo['email'];
	}

	// echo "CompanyOwnerEmail = ".$CompanyOwnerEmail;

		$_SESSION['owner'] = $CompanyOwnerEmail;
		// list($recruiter_first, $recruiter_last) = explode(' ', $owner);
		$recruiter_email = $CompanyOwnerEmail;
		$recruiter_name = $CompanyOwnerName ;
		$recruiter2_email = $ContactCreatorEmail;
		$recruiter2_name = $ContactCreatorName;
		// echo "<br>recruiter email - ".$recruiter_email;
		// echo $company_id = $contact_info['organization'];


   try {
          $selector = bin2hex(random_bytes(8));
          $token = random_bytes(32);
        } catch (TypeError $e) {
          // Well, it's an integer, so this IS unexpected.
          header("Location: /portal-login/?&r=error");
          exit;
        } catch (Error $e) {
          // This is also unexpected because 32 is a reasonable integer.
          header("Location: /portal-login/?r=error");
          exit;
        } catch (Exception $e) {
          // If you get this message, the CSPRNG failed hard.
          header("Location: /portal-login/?r=error");
          exit;
        }
		
	
        $query = "UPDATE ic_password_reset_tickets SET closed = 1, closed_at = '". date('Y-m-d H:i:s')."', close_reason = 'new ticket created' WHERE contact_id = " . $contactID . " AND closed = 0";
		$result = mysqli_query($link,$query );

        // create new ticket
		$hashed_token = hash('sha256',$token);
		$hashed_token = bin2hex($token);
		$new_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'). ' + 3 hours'));
        $query = "INSERT INTO ic_password_reset_tickets (contact_id, selector, token, created_at, expires_at,contact_email) VALUES ('".$contactID."','".$selector."','".$hashed_token ."','".date('Y-m-d H:i:s')."','".$new_date."','".$user."')";
		// echo $query; 
		$result = mysqli_query($link,$query );
		// exit();

        // build url with selector and validator
        $portal_url = sprintf('%s/create-client-password/?%s', "https://".$_SERVER['SERVER_NAME'], http_build_query([
          'selector' => $selector,
          'validator' => bin2hex($token)
        ]));
		$_SESSION['portal_url'] = $portal_url;
		// echo "<br>portal url = ".$_SESSION['portal_url'];


        // start email session

		require_once($_SERVER['DOCUMENT_ROOT']."/wp-includes/class-phpmailer.php");
		require_once($_SERVER['DOCUMENT_ROOT']."/wp-includes/PHPMailer/PHPMailer.php");
		require_once($_SERVER['DOCUMENT_ROOT']."/wp-includes/PHPMailer/Exception.php");
		require_once($_SERVER['DOCUMENT_ROOT']."/wp-includes/PHPMailer/SMTP.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
/*
        $mail = new PHPMailer(true);
				
          $mail->isSMTP();
          $mail->Host = 'smtp.1and1.com';
          $mail->SMTPAuth = true;
          $mail->Username = 'exchange@icreatives.co';
          $mail->Password = 'Call1888icreate!';
		
			$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
		// DKIM Setup
				$mail->DKIM_domain = 'icreatives.co';
				$mail->DKIM_selector = 'performa';
				$mail->DKIM_private = $_SERVER['DOCUMENT_ROOT'] . '/dkim_keys/ionos-icreatives-co-dkim-private-key.key'; // Replace with actual path
				$mail->DKIM_passphrase = ''; // If your key has a passphrase, enter it here
				$mail->DKIM_identity = 'exchange@icreatives.co'; // Typically same as From
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
  
		  // echo $_SESSION['user'];

          $mail->addAddress($_SESSION['user']);
		// copy recruiter aka user_owner in division master

			// echo " R2= ".$recruiter2_email;
			if ($recruiter_email !=="" && !empty($recruiter_email) && !is_null($recruiter_email)) { 
				echo $mail->addBcc($recruiter_email);
			}
			if ((isset($recruiter2_email) && !empty($recruiter2_email)) && $recruiter2_email !== $recruiter_email) { 
				echo $mail->addBcc($recruiter2_email);
			}	
			echo $mail->setFrom('exchange@icreatives.com','icreatives');
	      // $mail->setFrom($recruiter_email,'icreatives'); no longer working, it is randomly finding a recruiters's email address.

		$mail->addBcc("NewPortalAccount@blindemail.com");
		$mail->addBcc("stevenc@icreatives.com");
          // Content
          $mail->Subject = 'icreatives - Account setup';

          $message = "<p>We received a request to create an account to your portal. To continue, you will need to make a password. The link to create your password is below.<br>
                        If you did not make this request, you can ignore this email</p>
                      <p>Here is your password creation link:</br>
					  <a href = '". $portal_url . "'>".$portal_url."</a>";
                   
          $mail->MsgHTML($message);	
  
		if(!$mail->Send()) {
			   header("Location: /client-portal-login/?r=error");
				exit;
				echo "Mailer Error: " . $mail->ErrorInfo;
		}  


?>

