<?php

require_once "../random_compat/lib/random.php";

$link = mysqli_connect('localhost', 'TempBack', 'XE5Vx@54Pu1IRQXa','tempback') or die("Error: " . mysqli_error());

$query = "SELECT user_pass , email from ic_sales where Admin = 'admin';";
$result = mysqli_query($link,$query);
$row = mysqli_fetch_array($result);
$_SESSION['user_pass'] = $row['user_pass'];
$_SESSION['admin_name'] = $row['email'];

// get contact associated with email
$email = $_REQUEST['email'];
$query = "select * from ic_contacts where email = '". $email . "' and icreativesportalaccess=true";
	$result = mysqli_query($link,$query);
	$is_contact = false;
	while ($row = mysqli_fetch_array($result)) {
		$is_contact = true;
		$portal_name =$row['full_name'];
		$company_name =$row['organization'];
		$full_name = explode(" ",$row['full_name']);
		$first_name=$full_name[0];
		$contact_email = $email;
		$contact_id = $row['id'];
		$_SESSION['first_name'] = $first_name;
	}


		// now search the match records to see if it is an candidate
		// To find the email address of a candidate, they must have  been or is on on an assignment before otherwise 
		// we cannot find a candidate by email address alone so we store the email in the ic-matches database

	$query = "select * from ic_matches where candidate_email = '". $email . "'";

	$result = mysqli_query($link,$query);
	$is_resource = false; // resource is a candidate

	while ($row = mysqli_fetch_array($result)) {
		$is_resource = true;
		// now ask manatal for 

		$portal_name =$row['candidate_name'];
		// $company_name =$peeps['firstRecord']['compaany']; // no need
		$full_name = explode(" ",$portal_name);
		$first_name=$full_name[0];
		$contact_email = $email;
		$_SESSION['first_name'] = $first_name;
		$contactID = $row['candidate'];
	}
	
	// echo "ic contact: ".$is_contact;
	// echo "is_resource: ".$is_resource;

if(!$is_resource) { // contact not found, send back to forgot-password
  header("Location: /portal/customer/manatal_create_new_talent_password.php/?r=not_found");
  exit;
} 
  $selector = bin2hex(random_bytes(8));
  $token = random_bytes(32);
// generate forgot-password ticket selector and validator


try {
  $selector = bin2hex(random_bytes(8));
  $token = random_bytes(32);
} catch (TypeError $e) {
  // Well, it's an integer, so this IS unexpected.
  header("Location: /portal/customer/manatal_create_new_talent_password.php/?r=error");
  exit;
} catch (Error $e) {
  // This is also unexpected because 32 is a reasonable integer.
  header("Location: /portal/customer/manatal_create_new_talent_password.php/?r=error");
  exit;
} catch (Exception $e) {
  // If you get this message, the CSPRNG failed hard.
  header("Location: /portal/customer/manatal_create_new_talent_password.php/?r=error");
  exit;
}

$link = mysqli_connect('localhost', 'TempBack', 'XE5Vx@54Pu1IRQXa','tempback') or die("Error: " . mysqli_error());


// close any previously created password tickets
$query = "UPDATE ic_password_reset_tickets SET closed = 1, closed_at = NOW(), close_reason = 'new reset ticket created' WHERE contact_id = '". $contactID."' AND closed = 0";


$result = mysqli_query($link,$query );	

// create new password reset ticket
$sql = "INSERT INTO ic_password_reset_tickets (contact_email,contact_id, selector, token, created_at, expires_at) VALUES (?,?,?,?,NOW(),DATE_ADD(NOW(), INTERVAL 3 HOUR))";
$email. $contactID." ". $selector." ". bin2hex($token);




if($stmt = mysqli_prepare($link, $sql)) {
		mysqli_stmt_bind_param($stmt,'ssss',$email, $contactID, $selector, bin2hex($token));
		mysqli_stmt_execute($stmt);
	} else {
		echo "ERROR: Could not prepare query: $sql. " . mysqli_error($link);
		exit();
	}




// build url to send to contact
$url = sprintf('%s/portal/customer/manatal_create_talent_portal_password2.php/?%s', "https://".$_SERVER['SERVER_NAME'], http_build_query([
  'selector' => $selector,
  'validator' => bin2hex($token)
]));



		require_once($_SERVER['DOCUMENT_ROOT']."/class-phpmailer.php");
		require_once($_SERVER['DOCUMENT_ROOT']."/PHPMailer/PHPMailer.php");
		require_once($_SERVER['DOCUMENT_ROOT']."/PHPMailer/Exception.php");
		require_once($_SERVER['DOCUMENT_ROOT']."/PHPMailer/SMTP.php");


// send email to contact
$mail = new PHPMailer(true);
try {
  // Server settings
  // $mail->SMTPDebug = 3;
 	$mail             = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = "smtp.1and1.com"; // SMTP server
	// $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
	// 1 = errors and messages
	// 2 = messages only
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	// $mail->Host       = "smtpout.secureserver.net"; // sets the SMTP server
	$mail->Host       = "smtp.ionos.com"; // sets the SMTP server"; // sets the SMTP server
	$mail->Username   = "exchange@icreatives.com"; // SMTP account username
	$mail->Password   = "Call1888icreate!";        // SMTP account password
	$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
	$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

  // Recipients
  $mail->setFrom('exchange@icreatives.com','icreatives');
  $mail->addAddress($email);
  $mail->addBCC("stevenc@icreatives.com");
  $mail->addBCC("password_reset@blindemail.com");

  // Content
  $mail->Subject = 'icreatives - Password Reset';

  $message = "<p>We recieved a password reset request. The link to reset your password is below.
                If you did not make this request, you can ignore this email</p>
              <p>Here is your password reset link:</br>".
              sprintf('<a href="%s">%s</a></p>', $url, $url).
              "<p>Thanks!</p>";
  $mail->MsgHTML($message);
  echo $email;

  $mail->send();

  header("Location: /portal/customer/manatal_create_new_talent_password.php/?r=success&user=$email");
  exit;
} catch (Exception $e) {
  header("Location: /portal/customer/manatal_create_new_talent_password.php/?r=error&user=$email");
  exit;
}

?>
