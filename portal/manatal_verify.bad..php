<?php
// session_start();

$isLoggedIn = false;
$cookie_expiration_time = time() + (30*24*60*60); // 30 days from now
$persisted_cookie_expiration_time = time() + (3 * 30 * 24 * 60 * 60); // 3 months from now (4320 days)

if(!empty($_SESSION['user_id'])) {
  $isLoggedIn = true;
} else if(!empty($_COOKIE['client_login']) && !empty($_COOKIE["token"]) && !empty($_COOKIE["selector"])) {

  $isTokenVerified = false;
  $isExpiryDateVerified = false;
  $query = "SELECT  id,
                    contact_id,
                    token,
                    expires_at,
                    CASE WHEN expires_at > CURRENT_TIMESTAMP THEN 1 ELSE 0 END AS valid
            FROM ic_client_login_tickets
            WHERE selector = ?";
	$pstmt = $link->prepare($query);

	// Bind parameters
	$pstmt->bind_param("s",$_COOKIE['selector']);

	// Execute the query
	$pstmt->execute();
	$results = $pstmt->get_result();
	$numRows = $results->num_rows;

  // check if ticket exists
  if($numRows > 0) {
    $row = $results->fetch_assoc();
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
	  $query = "SELECT email,id,full_name FROM ic_contacts,organization WHERE id = ?";
	  
	  $pstmt = $link->prepare($query);

		// Bind parameters
		$pstmt->bind_param("s",$contactID);

		// Execute the query
		$pstmt->execute();

         $row = $results->fetch_assoc();
      $userID = $row['email'];
	  list($first_name,$last_name) = explode($row['full_name']);
      $firstName = $row['First_Name'];
      $lastName = $row['Last_Name'];
      $contactID = $row['id'];
      $customerID = $row['organization'];

      // Start session
      $_SESSION['user_id'] = htmlspecialchars($userID);
      $_SESSION['contactID'] = htmlspecialchars($contactID);
      $_SESSION['first_name'] = htmlspecialchars($firstName);
      $_SESSION['last_name'] = htmlspecialchars($lastName);
     // $_SESSION['division'] = htmlspecialchars($divisionID);
      $_SESSION['customer'] = htmlspecialchars($customerID);

      $cookie_expiration_time = time() + (30*24*60*60); // 30 days from now
      $persisted_cookie_expiration_time = time() + (3 * 30 * 24 * 60 * 60); // 3 months from now (4320 days)

      // Extend cookie's expiration
      setcookie("client_login", $userID, $persisted_cookie_expiration_time,"/");
      setcookie("selector", $_COOKIE['selector'], $cookie_expiration_time,"/");
      setcookie("token", $_COOKIE['token'], $cookie_expiration_time,"/");
    } else {
      $query = "UPDATE ic_client_login_tickets
                SET is_expired = 1
                WHERE id=?";
	 $pstmt = $link->prepare($query);

		// Bind parameters
		$pstmt->bind_param("s",$ticket_id);

		// Execute the query
		$pstmt->execute();

      $isLoggedIn = false;
    }
  }
}
if(!$isLoggedIn) {
		exit();
  // set_selector_cookie("selector", '', time() - 3600);
  // set_token_cookie("token", '', time() - 3600);
  setcookie("selector", '', time() - 3600);
  setcookie("token", '', time() - 3600);

  // take to client login if persisted user
  if(!empty($_COOKIE['client_login'])) {
    $user = $_COOKIE['client_login'];
    header("Location: /client-login/?user=$user".(!empty($_REQUEST['o']) ? "&o=".$_REQUEST['o'] : ""));
    exit;
  }

  // take to service login
  header("Location: /service-login");
  exit;
}
?>
