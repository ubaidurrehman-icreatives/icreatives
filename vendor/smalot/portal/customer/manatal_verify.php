<?php

$link = mysqli_connect('localhost', 'TempBack', 'XE5Vx@54Pu1IRQXa','tempback') or die("Error: " . mysqli_error());

$isLoggedIn = false;
$cookie_expiration_time = time() + (3 * 30*24*60*60); // 3 * 30 days from now
$persisted_cookie_expiration_time = time() + (3 * 30 * 24 * 60 * 60); // 3 months from now (4320 days)

if(!empty($_SESSION['user_id'])) {


  $isLoggedIn = true;
  
  if(!isset($_SESSION['recruiter'])){$_SESSION['recruiter'] = 0;}
  
  	if($_SESSION['recruiter'] == 1) {
		
		$query = "SELECT  id FROM ic_contacts WHERE email = '".$_SESSION['user_id']."' AND icreativesportalaccess = 1";
		$result = mysqli_query($link,$query );	
		echo $rowCount = mysqli_num_rows($result);

		if($rowCount > 0) {
			$row = mysqli_fetch_array($result) ;
			$contactID = $row['id'];
			$_SESSION['contactID'] = $contactID;
		} else {
			// take to service login
			header("Location: /portal-choose-job/?user=".$_SESSION['user_id']);
			exit;
		}
	}

} else if((!empty($_COOKIE['client_login']) || !empty($_COOKIE['client_portal'])) && !empty($_COOKIE["token"]) && !empty($_COOKIE["selector"])) {

  $isTokenVerified = false;
  $isExpiryDateVerified = false;
  // echo $_SESSION['recruiter'];

	$query = "SELECT  id,
                    contact_id,
                    token,
                    expires_at,
                    CASE WHEN expires_at > CURRENT_TIMESTAMP THEN 1 ELSE 0 END AS valid
            FROM ic_client_login_tickets ";
			if(isset($_SESSION['recruiter']) && $_SESSION['recruiter'] == 1) {
				$query .= "WHERE contact_id = '".$contactID."'";	
			} else {
				$query .= "WHERE selector = '".$_COOKIE['selector']."'";
			}

	$result = mysqli_query($link,$query );	
	$rowCount = mysqli_num_rows($result);

  // check if ticket exists
  if($rowCount > 0) {
    $row = mysqli_fetch_array($result) ;
    $ticket_id = $row['id'];
    $contactID = $row['contact_id'];

    // Validate cookie password with token password
    if(password_verify($_COOKIE['token'], $row['token'])) {
      $isTokenVerified = true;
    }

    // Validate expirery date is greater than current date
    if($row['valid']) {
      $isExpiryDateVerified = true;
    }

    if($isTokenVerified && $isExpiryDateVerified) {
      $isLoggedIn = true;

      // Get client information for session
  /*
			$details = $arr["results"][0]["details"];
			$userID = $details["email"]; //email address
			$firstName = $details["firstname"];
			$lastName = $details["lastname"];
			$company = $details["companyname"];
			$_SESSION['company_name'] = $company;
			
			// $contact_id = $arr["results"][0]["id"];
			// $divisionID = $row['Division_ID'];
			// $customerID = $row['Customer_ID'];
			// echo "company = ". $company;
			// echo "contact id = ". $contactID;



					
// echo "company name = ".$company_name;
// echo "customerID = ".$customerID;
// exit();

*/
	$query = "SELECT  *
          FROM ic_contacts
          WHERE email = '". $_COOKIE['client_portal'] ."'";
		  $result = mysqli_query($link,$query );
	      $row = mysqli_fetch_array($result);

			// $details = $arr["results"][0]["details"];
			$userID = $row["email"]; //email address
			$contactID = $row['id'];
			// list($first_name,$last_name) = explode(" ",$row['full_name']);
			// $_SESSION['first_name'] = $first_name;
			// $lastName = $lastName;
			$company = $row["company_name"];
			$customerID = $row["organization"];
			$company_name = $company;
			
      // Start session
      $_SESSION['user_id'] = htmlspecialchars($userID);
      $_SESSION['contactID'] = htmlspecialchars($contactID);
      // $_SESSION['first_name'] = htmlspecialchars($firstName);
      // $_SESSION['last_name'] = htmlspecialchars($lastName);
      // $_SESSION['division'] = htmlspecialchars($divisionID);
      $_SESSION['customer'] = htmlspecialchars($customerID);
	  $_SESSION['company_name'] = htmlspecialchars($company_name);
      $cookie_expiration_time = time() + (3 * 30*24*60*60); // 3 * 30 days from now
      $persisted_cookie_expiration_time = time() + (3 * 30 * 24 * 60 * 60); // 3 months from now (4320 days)

      // Extend cookie's expiration
	  $userID = $_COOKIE['client_portal'];
      setcookie("client_login", $userID, $persisted_cookie_expiration_time,"/");
      setcookie("selector", $_COOKIE['selector'], $cookie_expiration_time,"/");
      setcookie("token", $_COOKIE['token'], $cookie_expiration_time,"/");
    } else {
      $query = "UPDATE ic_client_login_tickets
                SET is_expired = 1
                WHERE id='".$ticket_id."'";
		$result = mysqli_query($link,$query );	
		$isLoggedIn = false;
    }
  }
}

if(!$isLoggedIn) {

  // set_selector_cookie("selector", '', time() - 3600);
  // set_token_cookie("token", '', time() - 3600);
  setcookie("selector", '', time() - 3600);
  setcookie("token", '', time() - 3600);

  // take to client login if persisted user
  if(!empty($_COOKIE['client_login'])) {
    $user = $_COOKIE['client_login'];
	// may be wrong
    header("Location: /client-portal-home/?user=$user".(!empty($_REQUEST['o']) ? "&o=".$_REQUEST['o'] : ""));
    exit;
  }

  // take to service login
  header("Location: /portals");
  exit;

}

?>