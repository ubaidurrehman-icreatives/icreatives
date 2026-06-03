<?php
session_start();
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';
$link = db();   

?>
<html>
<head>
  <!-- Bootstrap CSS, font-awesome custom CSS -->
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_red.css">
  <link rel="stylesheet" href="/portal/styles.css">
</head>
<body>
  <div style="padding-top:150px;" class="container custom" id="candidates">

<?php

$query = "SELECT user_pass , email from ic_sales where Admin = 'admin';";
$result = mysqli_query($link,$query);
$row = mysqli_fetch_array($result);
$_SESSION['user_pass'] = $row['user_pass'];
$_SESSION['admin_name'] = $row['email'];

$user = strtolower($_REQUEST['user']) ?? strtolower($_SESSION['user']);
$_SESSION['user'] = $user;


if(!isset($_SESSION['user']) || $_SESSION['user'] == "" || !isset($_REQUEST['password']) || $_SESSION['user'] == "") {
  header("Location: /portal/manatal_talent_portal_signin.php/?r=fields".(isset($_SESSION['user']) ? $_SESSION['user'] : ""));
  return;
}


$pass = $_REQUEST['password'];
// test if multiple match records with same email but different candidate id
// $client =  new \GuzzleHttp\Client();


$client = new \GuzzleHttp\Client([
    'timeout'         => 30,  // wait up to 10 seconds per attempt
    'connect_timeout' => 10
]);

$candidate_email = str_replace("@","%40",$user);

$url = "https://api.manatal.com/open/v3/candidates/?email=" . $candidate_email;

$maxRetries = 4;
$attempts = 0;
$responseData = null;
$errorMessage = '';

while ($attempts < $maxRetries) {
    try {
        $attempts++;
        
        $response = $client->request('GET', $url, [
			'headers' => [
			'Authorization' => $token,
			'Accept'        => 'application/json',
		],
			'timeout' => 30,          // increased from 10 to 30 seconds
			'connect_timeout' => 10   // still fail fast if it can't connect
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);
        break; // success → exit loop

    } catch (\Exception $e) {
        // After first timeout, show message to user
        if ($attempts == 1) {
            echo "<p><strong>Our server is unusually slow, please wait…</strong></p>";
            flush(); // push message immediately to browser
        }

        if ($attempts >= $maxRetries) {
            $errorMessage = "There seems to be a problem with the sever, please try later";
        }
    }
}


if (!isset($response)) {
    echo "<p><strong>There seems to be a problem with the server, please try again later.</strong></p>";
    exit;
}

$responseStr = $response->getBody();
$results = json_decode($responseStr, true);


// echo $results['count'] ;





	if ($results['count'] == 0) {
		// remember to add mobile
		header("Location: /portal/manatal_talent_portal_signin.php/?r=error&user=$user");
		return;
	} else if ($results['count'] > 1) {
		echo "<center><p> <br> <p><h1> It seems you are in our system multiple times.</h1><br><h3>Please contact your recruiter to resolve this issue.</h3><br> 1-855-427-3284<br>1-855-i-creatives</center>";
		exit();
	}
	
$resource_id = $results['results'][0]['id'];
$data_pass = $results['results'][0]['custom_fields']['password'];


$_SESSION['resource_id'] = $resource_id;
// match record must be open or null
$query = "SELECT candidate_email,candidate,closed from ic_matches 
        WHERE (closed = 0 OR (closed = 1 AND closed_date >= DATE_SUB(NOW(), INTERVAL 14 DAY))) 
		AND LOWER(candidate_email) = '".$user."'";
	//	AND LOWER(candidate_email) = '".$user."' AND candidate = '".$resource_id."'";
	
$result = mysqli_query($link,$query);
$row = mysqli_fetch_array($result);
// $resource_id = $row['candidate'];

if (mysqli_num_rows($result) ==0) {
	echo "no Active Assignments";
	// header("Location: /no-active-assignment");
	return;
} 



/* we may no longer need this, especially if ID has changed
$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/'.$resource_id.'/', [

  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);

$response->getBody();

	$responseStr = $response->getBody();
	$candidate = json_decode($responseStr, true);
	// echo $resource_id;
	$data_pass = $candidate['custom_fields']['password'];
	$resource_id = $candidate['id'];

	$status = ""; // no status in manatal and we cannot read tags
*/
if(!empty($data_pass)) {


  if(trim($pass) === trim($data_pass)) {

	if (preg_match('/Mobile|Android|iP(hone|od|ad)|IEMobile|BlackBerry|Opera Mini/i', $_SERVER['HTTP_USER_AGENT'])) {
		echo '<form action="/webtime/manatal_browse_1_mobile.php/" method="POST" name="talent_login">';
	} else {
		echo '<form action="/webtime/manatal_browse_1.php/" method="POST" name="talent_login">';
	}
    ?>
    <!-- <form action="/talent-landing" method="POST" name="talent_login"> -->
 
      <input type="hidden" name="login" value="<?php echo $user ?>">
      <input type="hidden" name="password" value="<?php echo $pass ?>">
      <input type="hidden" name="VARIB" value="<?php echo $resource_id ?>">
      <input type="hidden" name="EMAIL" value="<?php echo $user ?>">
	    <input type="hidden" name="resource_id" value="<?php echo $resource_id ?>">
    </form>
	<!-- what is this below?-->
    <script>
      window.onload = function() {
        document.forms['talent_login'].submit();
      }
    </script>
    <?php
    return;
  } else {
    header("Location: /portal/manatal_talent_portal_signin.php/?r=cred");
    return;
  }
}
header("Location: /portal/manatal_talent_portal_signin.php/?r=error&user=$user");
return;
?>
</div>
</body>
</html>