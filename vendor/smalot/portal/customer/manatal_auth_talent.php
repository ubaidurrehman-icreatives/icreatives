<?php
session_start();
$link = mysqli_connect('localhost', 'TempBack', 'XE5Vx@54Pu1IRQXa','tempback') or die("Error: " . mysqli_error());
//     'Authorization' => 'Token 71f589faea3a21564cd8e2ed4c6d81739cb36796',
require "/home/bitnami/vendor/autoload.php";
/* for icreatives only
$query = "SELECT user_pass , email from ic_sales where Admin = 'admin';";
$result = mysqli_query($link,$query);
$row = mysqli_fetch_array($result);
$_SESSION['user_pass'] = $row['user_pass'];
$_SESSION['admin_name'] = $row['email'];

if(!isset($_REQUEST['user']) || $_REQUEST['user'] == "" || !isset($_REQUEST['password']) || $_REQUEST['user'] == "") {
  header("Location: /talent-portal-login/?r=fields".(isset($_REQUEST['user']) ? $_REQUEST['user'] : ""));
  return;
}
*/
$user = strtolower($_REQUEST['user']);
$pass = $_REQUEST['password'];
$subscriber_id = $_SESSION['subscriber_id'];
// test if multiple match records with same email but different candidate id

 //   'Authorization' => 'Token '.$_SESSION["apiToken"].'',

$client = new \GuzzleHttp\Client([
    'timeout'         => 30,  // wait up to 10 seconds per attempt
    'connect_timeout' => 10
]);

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

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
			'Authorization' => 'Token '.$_SESSION["apiToken"].'',
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
		header("Location: /talent-portal/?r=error&user=$user");
		return;
	} else if ($results['count'] > 1) {
		echo "<center><p> <br> <p><h1> It seems you are in our system multiple times.</h1><br><h3>Please contact your recruiter to resolve this issue.</h3><br> 1-855-427-3284<br>1-855-i-creatives</center>";
		exit();
	}
	
$resource_id = $results['results'][0]['id'];
$data_pass = $results['results'][0]['custom_fields']['password'];
$resource_id = $results['results'][0]['id'];


// match record must be open or null
$user = strtolower($_REQUEST['user']);
$query = "SELECT candidate_email, candidate, closed 
FROM ic_matches 
WHERE 
  (closed = 0 OR (closed = 1 AND closed_date >= DATE_SUB(NOW(), INTERVAL 14 DAY)))
  AND candidate_email = '$user' AND subscriber_id = '$subscriber_id'" ;
  // AND candidate_email = '$user'" AND subscriber_id = '".$subscriber_id."'";
	//	AND LOWER(candidate_email) = '".$user."' AND candidate = '".$resource_id."'";
	
$result = mysqli_query($link,$query);


$row = mysqli_fetch_array($result);
// $resource_id = $row['candidate'];

if (mysqli_num_rows($result) ==0) {
	header("Location: /no-active-assignment");
	return;
} 



if(!empty($data_pass)) {


  if(trim($pass) === trim($data_pass)) {

	$isMob = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"));
	if($isMob){ 
    		echo ' <form action="/webtime/manatal_browse_1_mobile.php?varib='.$resource_id.'&EmpEmail='.$user.'" method="POST" name="talent_login">'; 
	}else{ 
   		echo ' <form action="/webtime/manatal_browse_1.php?varib='.$resource_id.'&EmpEmail='.$user.'" method="POST" name="talent_login">'; 
	}

    ?>
      <input type="hidden" name="login" value="<?php echo $user ?>">
      <input type="hidden" name="password" value="<?php echo $pass ?>">
      <input type="hidden" name="VARIB" value="<?php echo $resource_id ?>">
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
    header("Location: /portal/customer/manatal_talent_portal_signin.php/?r=cred&user=$user");
    return;
  }
}
header("Location: /portal/customer/manatal_talent_portal_signin.php/?r=error&user=$user");
return;

?>
