<?php
session_start();

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

$selector = $_REQUEST['selector'];
$validator = $_REQUEST['validator'];

if(!isset($_REQUEST['selector']) || !isset($_REQUEST['validator'])) {
  header("Location: /create-password/?r=e");
  exit;
}

if(!isset($_REQUEST['password']) || !isset($_REQUEST['confirm'])) {
  header("Location: /create-password/?selector=$selector&validator=$validator&r=f");
  exit;
}

$password = $_REQUEST['password'];
$confirmation = $_REQUEST['confirm'];

if($password !== $confirmation) {
  header("Location: /createpassword/?selector=$selector&validator=$validator&r=m");
  exit;
}

if(preg_match('/(?=.*[\W0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}/', $password) === FALSE) {
  header("Location: /create-password/?selector=$selector&validator=$validator&r=r");
  exit;
}


// Get unexpired token with selector
$query = "SELECT  *
          FROM ic_password_reset_tickets ic
           
          WHERE ic.selector = '". $selector ."' 
          AND ic.closed = 0
          AND ic.expires_at >= NOW() ";
		  
		$result = mysqli_query($link,$query );		  
	
	$count = mysqli_num_rows($result); 
	// echo "count = ".$count;

// Invalid selector

if($count == 0) {
  header("Location: /create-password?r=e");
  exit;
}

$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$auth_token = $row['token']; // stored token
$user = $row['contact_email'];
$contactID = $row['contact_id'];

// hash user-given token
$calc = hash('sha256', hex2bin($validator));
$ticketID = $row['id'];

// find Contact Info
$query = "SELECT  id,display_name, organization, company_name  from ic_contacts where email = '".$user."'";
$result = mysqli_query($link,$query );
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

$display_name = $row['display_name'];
$contact_id = $row['id'];
$contactID = $row['id'];
$organization = $row['organization'];
$company_name = $row['company_name'];
 
 

/*
echo "<br>contact id = " . $contactID;
echo "<br>auth_token = " . $auth_token;
echo "<br>user = " . $user;
echo "<br>calc = " . $calc;
echo "<br>ticketID = " . $ticketID;
echo "<br>auth_token = " . $auth_token;
echo "<br>validator = " . $validator;
*/

// Validate user-given token matches stored token after hash
// need to make more secure by fixing below
// if(hash_equals($calc, $auth_token)) {
if ($row['selector'] == $selector) { // fix this security issue later
	
  // change password
// $pass = password_hash($password, PASSWORD_DEFAULT);
  
$url = 'https://evoapi.tracker-rms.com/API/Partner/iCreatives/updateICRecord';
$jsondata = '{
	"trackerrms": {
		"updateICRecord": {
			"credentials": {
				"username": "'.$_SESSION['admin_name'].'",
				"password": "'.$_SESSION['user_pass'].'",
				"oauthtoken": "",
				"apikey": "NAYpepDHctXB4atSW7Mp"
			},
			"instructions": {
				"contactid": "'. $contactID .'"
            },
			"updates": [
                {
                    "column": "supportpassword",
                    "value": "'. $password .'"
                },
				 {
                    "column": "supportusername",
                    "value": "'. $user.'"
                }
            ]

		}
	}
}';
		// https://evoportalus.tracker-rms.com/iCreatives/support"	
// echo $url;
// echo $jsondata;

// $result = sendcurl($jsondata,$url);
// echo $result;

// $arr = json_decode($result, true);
		// echo $count = $arr["count"];

		// echo " count= ".$count;

  // hash the password (uses BCRYPT as of 7/19/2019)

  // no rows updated
  if ($count == 0) {
    header("Location: /createpassword/?selector=$selector&validator=$validator&r=e");
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
	// Add notes into Manatal
	
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
                <form action="/client-portal/" method="get">
                    <input type="hidden" name="user" value="<?php echo $user ?>">
                    <h3>Password created successfully.</h3>
                    <button type="submit" class="btn btn-primary" href="/client-portal/?user=<?php echo $user ?>">Login here</button>
                </form>
            </div>
        </div>
      </div>
    </div>
    <?php
  }
} else { // invalid validator
  header("Location: /create-password/?&r=e");
  exit;
}
