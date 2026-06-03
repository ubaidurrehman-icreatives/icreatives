<?php

session_start();
$order = isset($_POST['orderID']) ? $_POST['orderID'] : "";
$client = isset($_POST['client']) ? $_POST['client'] === "1" : false;

include './db.php';
require_once "../random_compat/lib/random.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if(isset($_POST['user']) && $_POST['user'] != "") { // username entered (email/recruiter id)
  $user = $_POST['user'];

  // check if username is an email associated  with a contact
	$user = $_POST['user'];
	// echo "user email = ".$user."<br>";
	// Is user a Contact? let's check the ic_contacts sql table first
	$query = "select * from ic_contacts where email = '". $user . "' and icreativesportalaccess";
	$result = mysqli_query($link,$query);
	$is_contact = false;
	while ($row = mysqli_fetch_array($result)) {
		$is_contact = true;
		$portal_name =$row['full_name'];
		$company_name =$row['organization'];
		$full_name = explode(" ",$row['full_name']);
		$first_name=$full_name[0];
		$contact_email = $user;
		$contact_id = $row['id'];
		$_SESSION['first_name'] = $first_name;
	}


    if(!$client && !is_null($row['id']) && !is_null($row['Employee_ID'])) {
      header("Location: /choose-account/?o=$order&user=$user");
      exit;
    } else if(!$client && !is_null($row['Employee_ID'])) {
      header("Location: /talent-login/?user=$user");
      exit;
    } else if(!is_null($row['Contact_ID'])) {
      $contactID = $row['Contact_ID'];
      if(is_null($row['ContactPassword'])) { // password not created for contact
        // generate password-creation ticket validator and selector
        try {
          $selector = bin2hex(random_bytes(8));
          $token = random_bytes(32);
        } catch (TypeError $e) {
          // Well, it's an integer, so this IS unexpected.
          header("Location: /service-login/?&r=error");
          exit;
        } catch (Error $e) {
          // This is also unexpected because 32 is a reasonable integer.
          header("Location: /service-login/?r=error");
          exit;
        } catch (Exception $e) {
          // If you get this message, the CSPRNG failed hard.
          header("Location: /service-login/?r=error");
          exit;
        }

        // close previously created password tickets for contact
        $query = "UPDATE ic_password_reset_tickets SET closed = 1, closed_at = GETDATE(), close_reason = 'new ticket created' WHERE contact_id = ? AND closed = 0";
        $pstmt = odbc_prepare($conn,$query);
        odbc_execute($pstmt, array($contactID));

        // create new ticket
        $query = "INSERT INTO ic_password_reset_tickets (contact_id, selector, token, created_at, expires_at) VALUES (?,?,?,GETDATE(),DATEADD(HOUR, 3, GETDATE()))";
        $pstmt = odbc_prepare($conn, $query);
        odbc_execute($pstmt, array($contactID, $selector, hash('sha256',$token)));

        // build url with selector and validator
        $url = sprintf('%s/create-password/?%s', "https://".$_SERVER['SERVER_NAME'], http_build_query([
          'selector' => $selector,
          'validator' => bin2hex($token)
        ]));


	// Save Client Email to History
  	if(!is_null($row['Contact_ID'])) {
  		$query = "SELECT  cm.Contact_ID,
                    cm.InternetPassword as ContactPassword,
		    cm.Division_ID,
                    cm.First_Name,
                    cm.Last_Name,
		    dm.Division_ID,
			dm.User_Service,
			dm.user_sales,
		    dm.Customer_ID,
			up.InternetSMTPEmail as recruiter_email
            	FROM ContactMaster cm
	   	JOIN DivisionMaster dm ON cm.division_id = dm.Division_ID
		JOIN OrderMaster om ON om.Division_ID = dm.Division_ID
		JOIN CFG_USERPROFILE up ON up.User_ID = om.User_Taken
           	WHERE cm.InActive = '0' and  cm.InternetSMTPEmail = ?";


  		$pstmt = odbc_prepare($conn, $query);
  		odbc_execute($pstmt, array($user));
		$row = odbc_fetch_array($pstmt);

      		$firstName = $row['First_Name'];
      		$lastName = $row['Last_Name'];
      		$divisionID = $row['Division_ID'];
      		$customerID = $row['Customer_ID'];
      		$contactID = $row['Contact_ID'];
		      $recruiter_email = $row['recruiter_email']; // $row['InternetSMTPEmail']; // This should almost definitely be $row['recruiter_email']

 		// more add history stuff here by SJC 05/26/2020
          	$txt_message = "

		We received a request to create an account to your portal. To continue, you will need to make a password. The link to create your password is below.

		If you did not make this request, you can ignore this email

		Here is your password creation link:

		". $url. "	";

     		$query = "EXEC HISTORY_INSERT @EventCode = 'PSR', @EventMethod = NULL, @Comment = '".addslashes($txt_message)."', @CustomerKey = '" . $customerID . "', @DivisionKey = '" . $divisionID . "', @ContactKey = '" . $contactID . "'";
      		odbc_exec($conn, $query);
	}
	// done adding history

        require_once("../PHPMailer/PHPMailer.php");
        require_once("../PHPMailer/Exception.php");
        require_once("../PHPMailer/SMTP.php");

        // send email to client
        $mail = new PHPMailer(true);
        try {
          // Server settings
          // $mail->SMTPDebug = 3;
		  /*
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
				
				$mail->IsSMTP(); // telling the class to use SMTP
				// $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
				// 1 = errors and messages
				// 2 = messages only
				$mail->SMTPAuth   = true;                  // enable SMTP authentication
				$mail->Host       = "smtp.1and1.com"; // sets the SMTP server
				// $mail->Host       = 'smtp.office365.co';
				$mail->Username   = "exchange@icreatives.com"; // SMTP account username
				$mail->Password   = "Call1888icreate!";        // SMTP account password
				$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
				$mail->Port       = 587;  // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above		
				$mail->isHTML(true);                             // Set email format to HTML
				$mail->CharSet = "UTF-8";


          // Recipients

          $mail->addAddress($user);
		// copy recruiter aka user_owner in division master
	  if (strpos($recruiter_email, "@") !== false) {
              $mail->addBcc($recruiter_email);
			   $mail->setFrom('exchange@icreatives.com','icreatives');
	      // $mail->setFrom($recruiter_email,'icreatives'); no longer working, it is randomly finding a recruiters's email address.
	  } else {
	      $mail->setFrom('no.reply@icreatives.com','icreatives');
	  }
	  $mail->addBcc("NewPortalAccount@blindemail.com");
          // Content
          $mail->Subject = 'icreatives - Account setup';

          $message = "<p>We received a request to create an account to your portal. To continue, you will need to make a password. The link to create your password is below.<br>
                        If you did not make this request, you can ignore this email</p>
                      <p>Here is your password creation link:</br>".
                      sprintf('<a href="%s">%s</a></p>', $url, $url);
          $mail->MsgHTML($message);
          $mail->send();
        } catch (Exception $e) {
          header("Location: /service-login/?r=error");
          exit;
        }

        // take to new-user
        header("Location: /new-user/?user=$user");
        exit;
      } else { // fully setup client, take to client login
        header("Location: /client-login/?o=$order&user=$user");
        exit;
      }
    }
  } else { // check if username is a recruiter id
    $query = "SELECT 1 FROM CFG_USERPROFILE
              WHERE User_ID = ?
              AND IsCommAllowed > 0";
    $pstmt = odbc_prepare($conn,$query);
    odbc_execute($pstmt, array($user));
    if(odbc_num_rows($pstmt) > 0) { // recruiter id found
      header("Location: /client-login/?o=$order&user=$user");
      exit;
    }
  }
} else { // error: field not set
  header("Location: /service-login/?r=fields".($order != "" ? "&orderID=$order" : ""));
  exit;
}

// username not recognized
header("Location:/service-login/?r=recognize".($order != "" ? "&orderID=$order" : ""));
exit;
?>
