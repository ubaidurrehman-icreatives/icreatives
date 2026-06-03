<?php
session_start();
$link = mysqli_connect('localhost', 'TempBack', 'XE5Vx@54Pu1IRQXa','tempback') or die("Error: " . mysqli_error());

if(!isset($_REQUEST['selector']) || !isset($_REQUEST['validator'])) {
  header("Location: /portal/customer/manatal_create_talent_portal_password2.php/?r=e");
  exit;
}

$selector = $_REQUEST['selector'];
$validator = $_REQUEST['validator'];

if(!isset($_REQUEST['password']) || !isset($_REQUEST['confirm'])) {
  header("Location: /portal/customer/manatal_create_talent_portal_password2.php/?selector=$selector&validator=$validator&r=f");
  exit;
}

$password = $_REQUEST['password'];
$confirmation = $_REQUEST['confirm'];

if($password !== $confirmation) {
  header("Location: /portal/customer/manatal_create_talent_portal_password2.php/?selector=$selector&validator=$validator&r=m");
  exit;
}

if(preg_match('/(?=.*[\W0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}/', $password) === FALSE) {
  header("Location: /create-talent-portal-password2/?selector=$selector&validator=$validator&r=r");
  exit;
}

// Get unexpired token with selector
$query = "SELECT  *
          FROM ic_password_reset_tickets ic
           
          WHERE ic.selector = '". $selector ."' 
          AND ic.closed = 0
          AND ic.expires_at >= NOW()";
		  
		$result = mysqli_query($link,$query );		  
	
	$count = mysqli_num_rows($result); 
	// echo "count = ".$count;

// Invalid selector
if($count == 0) {
  header("Location: /portal/customer/manatal_create_new_talent_password.php?r=e");
  exit;
}

$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$auth_token = $row['token']; // stored token
$user = $row['contact_email'];
$contactID = $row['contact_id'];

// hash user-given token
$calc = hash('sha256', hex2bin($validator));
$ticketID = $row['id'];

// Validate user-given token matches stored token after hash
// need to make more secure by fixing below
// if(hash_equals($calc, $auth_token)) {
if ($auth_token == $validator) { 

	
  // change password
// $pass = password_hash($password, PASSWORD_DEFAULT);


// First Find full name so we can patch
 require "/home/bitnami/vendor/autoload.php";
$client = new \GuzzleHttp\Client();
$response = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/'.$contactID.'/', [
  'headers' => [
    'Authorization' => 'Token 71f589faea3a21564cd8e2ed4c6d81739cb36796',
    'accept' => 'application/json',
  ],
]);

$response->getBody();

$responseStr = $response->getBody();
$candidate = json_decode($responseStr, true);
$full_name = $candidate['full_name'];
$candidate_id = $contactID;

// Now update password in manatal

$response = $client->request('PATCH', 'https://api.manatal.com/open/v3/candidates/'.$contactID.'/', [
  'body' => '{"custom_fields":{"password":"'.$password.'"},"full_name":"'.$full_name.'"}',
  'headers' => [
    'Authorization' => 'Token 71f589faea3a21564cd8e2ed4c6d81739cb36796',
    'accept' => 'application/json',
    'content-type' => 'application/json',
  ],
]);

$response->getBody();
$responseStr = $response->getBody();
$candidate = json_decode($responseStr, true);



		// echo " count= ".$count;

  // hash the password (uses BCRYPT as of 7/19/2019)

  // no rows updated
  if ($candidate['detail'] == "Not found") {
    header("Location: /portal/customer/manatal_create_new_talent_password.php/?selector=$selector&validator=$validator&r=e");
    exit;
  } else {
    // close password reset token

    $query = "UPDATE ic_password_reset_tickets SET closed = 1, closed_at = '".date('Y-m-d H:i:s')."', close_reason = 'password created successfully' WHERE contact_id = '". $contactID. "'";
	// echo $query;
	$result = mysqli_query($link,$query );
    // add history event in the future
	/*
    $query = "EXEC HISTORY_INSERT @EventCode = 'PCA', @EventMethod = 'E', @Comment = 'account created successfully', @CustomerKey = '".addslashes($customerID)."', @DivisionKey = '".addslashes($divisionID)."', @ContactKey = '".addslashes($contactID)."'";
    odbc_exec($conn, $query);
	*/
    session_start();
    session_regenerate_id();
    session_destroy();
    ?>
    <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
    <link rel="stylesheet" href="/portal/styles.css">

    <div class="container custom">
      <div class="row my-5">
        <div class="col">
            <div class="row" style="margin-top: 200px !important; margin-bottom:200px !important">
                <form action="/portal/customer/manatal_talent_portal_signin.php/" method="get">
                    <input type="hidden" name="user" value="<?php echo $user ?>">
                    <h3>Password created successfully.</h3>
                    <button type="submit" class="btn btn-primary" href="/portal/customer/manatal_talent_portal_signin.php/?user=<?php echo $user ?>">Login here</button>
                </form>
            </div>
        </div>
      </div>
    </div>
    <?php
  }
} else { // invalid validator
  header("Location: /portal/customer/manatal_create_new_talent_password.php/?&r=e");
  exit;
}
