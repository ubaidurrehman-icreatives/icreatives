<?php
ini_set('session.cookie_lifetime', 7776000); // 3 months in seconds
session_start();
?>
<?php
// Set session cookie parameters


// TODO: make secure session

require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';
$link = db();   


if (empty($_SESSION['users_arr'])) {

// Store all users (manatal users) in an array to be used for who gets emailed notifications
	// get email addresses from user diplay names in match records
	$client = new \GuzzleHttp\Client([ 'timeout' => 30, 'connect_timeout' => 10]);

	try {
		$response = $client->request('GET', 'https://api.manatal.com/open/v3/users/', [
	'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
	],
	]);
	$responseStr = $response->getBody();
	$users_arr = json_decode($responseStr, true);
	$_SESSION['users_arr'] = $users_arr ;
}catch (\GuzzleHttp\Exception\ClientException $e) {
    $statusCode = $e->getResponse()->getStatusCode();

    $body = (string) $e->getResponse()->getBody();
    $json = json_decode($body, true);

    // Default wait time
    $wait = 60;

    if (isset($json['detail']) && preg_match('/available in (\d+) seconds/', $json['detail'], $matches)) {
        $wait = $matches[1];
    }

    echo "<p>The server is busy (".$statusCode."). Please wait {$wait} seconds.</p>";
    echo "<script>setTimeout(() => { location.reload(); }, " . ($wait * 1000) . ");</script>";

    exit();
}
}

$query = "SELECT password, email FROM ic_sales WHERE UPPER(name) = UPPER(?)";
$pstmt = $link->prepare($query);
$name = $_POST['user'] ?? '';
$pstmt->bind_param("s", $name);
$pstmt->execute();
$results = $pstmt->get_result();

$row = $results->fetch_assoc();
$recruiter = $row !== null;   // true if a row was found

$password = $_REQUEST['password'];

$_SESSION['recruiter'] = $recruiter ?? '';
$_SESSION['user_pass'] = $row[0]['password'] ?? '';
$_SESSION['admin_name'] = $row[0]['email'] ?? '';
$post_password = $_REQUEST['password'] ?? '';
$company_id = $_SESSION['company_id'] ?? '';
$contactID = $_SESSION['contactID'] ?? '';

// require_once "./portal/random_compat/lib/random.php";

$order = isset($_POST['orderID']) ? $_POST['orderID'] : "";
$user = isset($_POST['user']) ? $_POST['user'] : "";

if(!$recruiter && ( isset($_REQUEST['user']) || $_REQUEST['user'] != "" || isset($_REQUEST['password']) || $_REQUEST['user'] != "")) { // all fields are set

	$password = $_REQUEST['password'];
	// if(strpos($user,"@") > 0 ) { // see if its a recruiter

		// get contact information = "N"
  
	// require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
	// $client = new \GuzzleHttp\Client();
  
	$query = "select * from ic_contacts where email = '".$user."'";
	$result = mysqli_query($link,$query);
	$count = mysqli_num_rows($result);
	$row = mysqli_fetch_array($result);
	$encrypted_password =  $row['encrypted_password'];
	$key = "4b14783a4b5848cd43b9fc7e4cf0fb6f";

	// echo "encrypted: ".base64_encode($encrypted_password);
	$ciphertext = $encrypted_password;
$key = "4b14783a4b5848cd43b9fc7e4cf0fb6f"; // Previously used in encryption 
$c = base64_decode($ciphertext); 
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
$iv = substr($c, 0, $ivlen); 
$hmac = substr($c, $ivlen, $sha2len=32); 
$ciphertext_raw = substr($c, $ivlen+$sha2len); 

$decrypted_password = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 

if (!empty($decrypted_password) && $decrypted_password == $password) {
	$company = $row['organization'];
	$contactID = $row['id'];
	// $_SESSION['contactID'] = $contactID;
	// $details = $arr["results"][0]["details"];
	$fullName = $row["full_name"];
	$company_name = $row["company_name"];
	$customerID = $row['organization'];
	echo " count = " . count($row) ;
	echo " full name = " . $fullName;
	
	


		// if($post_password == $password) { // password matches

		// Query for open jobs for dashboard expand numrecords later
/*
		$url = 'https://evoapi.tracker-rms.com/API/Partner/iCreatives/getICCompanyJobs';
		$jsondata = '{
			"trackerrms": {
				"getICCompanyJobs": {
					"credentials": {
						"username": "'.$_SESSION['admin_name'].'",
						"password": "'.$_SESSION['user_pass'].'",
						"oauthtoken": "",
						"apikey": "NAYpepDHctXB4atSW7Mp"
					},
					"instructions": {
							"companyid": "'.$company_id.'",
							"state": "Open",
							"numrecords": 3,
							"pagenum": 0,
							"sortfield": "publishdate",
							"sortdir": "desc",
							"searchtext": "",
							"jobdateopenfrom": "",
							"jobdateopento": "",
							"jobowneremail": "'.$_SESSION["user_id"].'"
						}
					}
				}
			}';

		// echo $url;
		// echo $jsondata;

	$result = sendcurl($jsondata,$url);
	 echo $result;

	$open_arr = json_decode($result, true);
	$_SESSION['open_arr'] = $open_arr;
	
	$open_count = $open_arr["count"];
		*/
      // start new session but why?
	  
	  				$username = $_SESSION['admin_name'];
				$password =$_SESSION['user_pass'];
				$users_arr =$_SESSION['users_arr'];
	  
      session_destroy();
      session_start();
      session_regenerate_id();
	  // $_SESSION['order_count'];
	  $_SESSION['user_pass'] = $password ;
	  $_SESSION['users_arr'] = $users_arr ;
	  $_SESSION['admin_name'] = $username;
	  // $_SESSION['closed_arr'] = $closed_arr; 
	  // $_SESSION['open_arr'] = $open_arr;
      $_SESSION['user_id'] = htmlspecialchars($_POST['user']);
      $_SESSION['contactID'] = htmlspecialchars($contactID);
      // $_SESSION['first_name'] = htmlspecialchars($firstName);
     //  $_SESSION['last_name'] = htmlspecialchars($lastName);
      // $_SESSION['division'] = htmlspecialchars($divisionID);
      // $_SESSION['customer'] = htmlspecialchars($customer);
      $_SESSION['customerID'] = htmlspecialchars($customerID);
	$_SESSION['user'] = $user;
	$_SESSION['company'] = $company;
	// $_SESSION['details'] = $details;
	// // $_SESSION['firstName'] = $firstName;
	// $_SESSION['lastName'] = $lastName;
	$_SESSION['fullName'] = $fullName;
	$_SESSION['company_name'] = $company_name;


      // Expire cookies if they exist
      if(!empty($_COOKIE['client_login']) && !empty($_COOKIE['token']) && !empty($_COOKIE['selector'])) {
        // Expire token for selector
        $query = "UPDATE ic_client_login_tickets
                  SET is_expired = 1, expires_at = (CASE WHEN expires_at > CURRENT_TIMESTAMP THEN CURRENT_TIMESTAMP ELSE expires_at END)
                  WHERE selector = '".  $_COOKIE['selector']   ."'";
		$result = mysqli_query($link,$query );		
      }

      // $cookie_expiration_time = time() + (30*24*60*60); // 30 days from now
      $cookie_expiration_time = time() + (3 * 30 * 24 * 60 * 60); // 3 months from now (4320 days)
      $persisted_cookie_expiration_time = time() + (3 * 30 * 24 * 60 * 60); // 3 months from now (4320 days)

      // generate selector and token for new cookie
      try {
        $selector = bin2hex(random_bytes(8));
        $token = bin2hex(random_bytes(32));
      } catch (TypeError $e) {
        // Well, it's an integer, so this IS unexpected.
        // header("Location: /portal/manatal_change_portal_password.php/?r=error");
        safe_redirect('portal/manatal_change_portal_password.php/?r=error');
        exit;
      } catch (Error $e) {
        // This is also unexpected because 32 is a reasonable integer.
       //  header("Location: /portal/manatal_change_portal_password.php/?r=error");
        safe_redirect('/portal/manatal_change_portal_password.php/?r=error');
        exit;
      } catch (Exception $e) {
        // If you get this message, the CSPRNG failed hard.
        // header("Location: /portal/manatal_change_portal_password.php/?r=error");
		safe_redirect('/portal/manatal_change_portal_password.php/?r=error');
        exit;
      }

      $hash_token = password_hash($token, PASSWORD_DEFAULT);

      $expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);

      // Create new login ticket
      $query = "INSERT INTO ic_client_login_tickets (selector, token, contact_ID, expires_at) VALUES ('".$selector."','". $hash_token."','". $contactID. "','".$expiry_date . "')";
	  $result = mysqli_query($link,$query );	

      setcookie("client_portal", $user, $persisted_cookie_expiration_time,"/");
      setcookie("selector", $selector, $cookie_expiration_time,"/");
      setcookie("token", $token, $cookie_expiration_time,"/");

      if($order != "") { // take directly to order if order_id is set
        // header("Location: /portal-view-order?o=".$order);
        safe_redirect('/portal/manatal_order_candidates.php?o='.$order);
        exit;
      } else { // default: take to dashboard
        // header("Location: /portal/manatal_client_portal_dashboard.php/");
        safe_redirect('manatal_client_portal_dashboard.php/');
        exit;
      }
    }

} 
  else { // check and validate if recruiter

		$hash = sha1($_POST['password']);
		// $strSQL = "SELECT 1 from ic_sales where LOWER(name) = '". strtolower($user)."'";
		$strSQL = "SELECT 1 from ic_sales where LOWER(name) = '". strtolower($user)."' AND password='". $hash."'";
		$result = mysqli_query($link,$strSQL);

		if (mysqli_num_rows($result) > 0) { // recruiter found
			// start recruiter session
			session_regenerate_id();
			$_SESSION['recruiter_id'] = $_POST['user'];
			$_SESSION['password'] = $_POST['password'];
			// header("Location: /portal/manatal_portal_choose_job.php/?user=$user");
			safe_redirect('/portal/manatal_portal_choose_job.php/?user='.$user);
			exit;
		}
    }


/*
}
else { // field not set
  header("Location: /client-portal-`board?r=fields".($order != "" ? "&o=$order" : ""));
  return;
}
*/	

// credentials were entered but incorrect
// header("Location: /client-portal-login?r=cred".($order != "" ? "&o=$order" : "").($user != "" ? "&user=$user" : ""));
safe_redirect('/portal/manatal_servicelogin.php?r=cred&o='.$order);
exit;
?>
