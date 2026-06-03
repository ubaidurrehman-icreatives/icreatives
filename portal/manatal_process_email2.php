<?php
session_start();
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;
use \PHPMailer\PHPMailer\SMTP;

$order = isset($_POST['orderID']) ? $_POST['orderID'] : "";
$client = isset($_POST['client']) ? $_POST['client'] === "1" : false;
// require_once "../random_compat/lib/random.php";
$company_id = $_REQUEST['company_id'];
$user = $_POST['user'];
$_SESSION['user'] = $user;

require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';
$mysqli = db();   

// echo "useremail = ".$user;

// check if it is a recruiter:

$strSQL = "SELECT name from ic_sales where LOWER(name) = '". strtolower($user)."'";

$result = mysqli_query($mysqli,$strSQL);

if (mysqli_num_rows($result) > 0) {
	// header("Location: /portal/manatal_client_portal_login.php/?user=$user");
	safe_redirect('/portal/manatal_client_portal_login.php');
	exit;
}

	if(!strpos($user,"@")) { // see if its an email address
	// username not recognized
		// header("Location:/portal/manatal_servicelogin.php?r=recognize".($order != "" ? "&orderID=$order" : ""));
		safe_redirect(
			"/portal/manatal_servicelogin.php?r=recognize" . ($order !== "" ? "&orderID=" . rawurlencode($order) : "")
		);
		exit;
	}


if(isset($_POST['user']) && $_POST['user'] != "" || isset($_SESSION['user'])) { // username entered (email/recruiter id)

// update contacts before we do anything 
	// everything updated within a certain time
	$after =  date("Y-m-d",strtotime('today - 2 days'));
	// echo "updates after ".$after;
	// require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
	 $client = new \GuzzleHttp\Client(['timeout'=>30,'connect_timeout'=>30]);
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/contacts/?updated_at__gte='.$after.' ', [
	// 	$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/'.$job.'/', [
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
		],
	]);


	$response->getBody();

	$responseStr = $response->getBody();
	$contacts = json_decode($responseStr, true);
	
	echo "<br>count= ". $contacts['count']."<BR>";
	
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
		$result = mysqli_query($mysqli,$query );
	}

// end update contacts
	$user = $_POST['user'];
	// echo "user email = ".$user."<br>";
	// Is user a Contact? let's check the ic_contacts sql table first
	$query = "select * from ic_contacts where email = '". $user . "'";
//	and icreativesportalaccess";
	$result = mysqli_query($mysqli,$query);
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

		// now search the match records to see if it is an candidate
		// To find the email address of a candidate, they must have  been or is on on an assignment before otherwise 
		// we cannot find a candidate by email address alone so we store the email in the ic-matches database

	$query = "select * from ic_matches where candidate_email = '". $user . "'";
	// if we want to start banning somone we can use this one instead.
	// $query = "select * from ic_matches where (icreativesportalaccess = 1 || icreativesportalaccess is null) AND candidate_email = '". $user . "'";

	$result = mysqli_query($mysqli,$query);
	$is_resource = false; // resource is a candidate

	while ($row = mysqli_fetch_array($result)) {
		$is_resource = true;
		// now ask manatal for 

		$portal_name =$row['candidate_name'];
		// $company_name =$peeps['firstRecord']['compaany']; // no need
		$full_name = explode(" ",$portal_name);
		$first_name=$full_name[0];
		$contact_email = $user;
		$_SESSION['first_name'] = $first_name;
	}

	
	$portal_name = $first_name;
	$_SESSION['company_id'] = $company_id;


	
	$_SESSION['company_name'] = $company_name;
	$_SESSION['contactID'] = $contact_id;
	$_SESSION['contact_email'] = $user;

		$query = "SELECT * FROM ic_password_reset_tickets ic WHERE closed = 1 AND contact_email = '" . $user . "'";  
		$result = mysqli_query($mysqli,$query );		  	
		echo $count = mysqli_num_rows($result); 
		// echo "count = ".$count;
		$been_there = false;

		if($count > 0){
			$been_there = true;
		}		
		
	}

	if(!$is_contact && !$is_resource) {

// header("Location: /portals/?&r=recognize");
		safe_redirect('/portal/manatal_servicelogin.php/?&r=recognize');
	exit;}

    if(!$client && $is_contact && $is_resource) {

	// header("Location:/choose-portal/?o=$order&user=$user&company_id=$company_id");
	safe_redirect('/choose-portal/?o='.$order.'&company_id='.$company_id);
      // header("Location:/choose-portal-account/?o=$order");
	        // header("Location: /choose-portal-account/?o=".$order."&user=".$user);
      exit;
	
    } else if(!$is_contact && $is_resource) {
		
      safe_redirect('/portal/manatal_talent_portal_signin.php/');
      // header("Location:/talent-portal/?user=".$user);
	  // header("Location:/portal-talent-login/?user=".$user);
      exit;
    }
	  else if ( $is_contact){

		safe_redirect('/portal/manatal_client_portal_login.php?o='.$order);
		// header("Location:/client-portal-login/?o=$order&user=$user");
		// header("Location:/client-portal-authenticate/?o=$order&user=$user");
        exit;
	  }
	  /*
	 else if(!is_null($user) || $user!=="" || empty($user) || !$been_there) { // password not created for employee

         header("Location:/new-client-portal-user/?user=$user&company_id=$company_id");
         exit;
	 } 
*/

      // close previously created password tickets for contact
        $query = "UPDATE ic_password_reset_tickets SET closed = 1, closed_at = '". date('Y-m-d H:i:s')."', close_reason = 'new ticket created' WHERE contact_id = " . $contact_id . " AND closed = 0";
		$result = mysqli_query($mysqli,$query );
		// echo $query;
		// exit();
        // create new ticket
		$hashed_token = hash('sha256',$token);
		$hashed_token = bin2hex($token);
		$new_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'). ' + 3 hours'));
        $query = "INSERT INTO ic_password_reset_tickets (contact_id, selector, token, created_at, expires_at,contact_email) VALUES ('".$contact_id."','".$selector."','".$hashed_token ."','".date('Y-m-d H:i:s')."','".$new_date."','".$user."')";
		// echo $query; 
		$result = mysqli_query($mysqli,$query );
		// exit();

        // build url with selector and validator
        $portal_url = sprintf('%s/create-portal-password/?%s', "https://".$_SERVER['SERVER_NAME'], http_build_query([
          'selector' => $selector,
          'validator' => bin2hex($token)
        ]));
		$_SESSION['portal_url'] = $portal_url;
		// echo "<br>portal url = ".$_SESSION['portal_url'];
		
		// check if username is an email associated  with a contact

		// Save Client Email to History
		if(!is_null($contact_id)) {

      		$firstName = $firstname;
      		$lastName = $lastname;
      		$divisionID = "";
      		$customerID = $company_id;
      		$contactID = $contact_id;
		    $contact_email = $email; 
			

				echo "<br>First Name: ".$firstname;
				echo "<br>Last Name: ".$lastname;
				echo "<br>email: ".$email;
				echo "<br>PW: ".$password;

			// more add history stuff here by SJC 05/26/2020
          	$txt_message = "

		We received a request to create an account to your portal. To continue, you will need to make a password. The link to create your password is below.

		If you did not make this request, you can ignore this email

		Here is your password creation link:

		". $portal_url. "	";
		
	
		/*		

     		$query = "EXEC HISTORY_INSERT @EventCode = 'PSR', @EventMethod = NULL, @Comment = '".addslashes($txt_message)."', @CustomerKey = '" . $customerID . "', @DivisionKey = '" . $divisionID . "', @ContactKey = '" . $contactID . "'";
      		odbc_exec($conn, $query);
			}
			// done adding history
		*/
	  }
		// find company owner email address

        // take to new-user
        // header("Location:/new-portal-user/?user=$user&company_id=$company_id");
        safe_redirect('/portal/manatal_change_portal_password.php/?company_id='.$company_id);
 
	// } // remove later
// username not recognized
// header("Location:/portal-login/?r=recognize".($order != "" ? "&orderID=$order" : ""));
safe_redirect('/portal/manatal_servicelogin.php/?r=recognize'.($order !== "" ? "&orderID=$order" : ""));
exit;
db_close();
?>
