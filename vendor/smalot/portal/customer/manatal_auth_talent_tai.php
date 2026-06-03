<?php
session_start();
$link = mysqli_connect('localhost', 'TempBack', 'XE5Vx@54Pu1IRQXa','tempback') or die("Error: " . mysqli_error());

$query = "SELECT user_pass , email from ic_sales where Admin = 'admin';";
$result = mysqli_query($link,$query);
$row = mysqli_fetch_array($result);
$_SESSION['user_pass'] = $row['user_pass'];
$_SESSION['admin_name'] = $row['email'];

if(!isset($_REQUEST['user']) || $_REQUEST['user'] == "" || !isset($_REQUEST['password']) || $_REQUEST['user'] == "") {
  header("Location: /portal/customer/manatal_talent_portal_signin_tai.php/?r=fields".(isset($_REQUEST['user']) ? $_REQUEST['user'] : ""));
  return;
}
$user = strtolower($_REQUEST['user']);
$pass = $_REQUEST['password'];

// match record must be open or null
$query = "SELECT candidate_email,candidate,closed from ic_matches 
        WHERE (closed = 0 OR (closed = 1 AND closed_date >= DATE_SUB(NOW(), INTERVAL 14 DAY))) 
		AND LOWER(candidate_email) = '".$user."' ";
$result = mysqli_query($link,$query);
$row = mysqli_fetch_array($result);
$resource_id = $row['candidate'];

if (mysqli_num_rows($result) ==0) {
	header("Location: /no-active-assignment");
	return;
} 

require "/home/bitnami/vendor/autoload.php";
$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/'.$resource_id.'/', [

  'headers' => [
    'Authorization' => 'Token 71f589faea3a21564cd8e2ed4c6d81739cb36796',
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

if(!empty($data_pass)) {  

  if(trim($pass) === trim($data_pass)) {

    $cookie_name = "authentication_token";
    $cookie_value = "01235456789xyzABC";
    //setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
    setcookie($cookie_name, $cookie_value, time() + 60, "/"); // 60 seconds
    ?>    
    <script>
      window.onload = function() {        
        window.location.href = "<?php echo $_SESSION['returnUrl']; ?>";
      }
    </script>
    <?php
    return;
  } else {
    if (isset($_COOKIE['authentication_token'])) {
        unset($_COOKIE['authentication_token']);
        setcookie("authentication_token", "", time() - 3600);
    }
    header("Location: /portal/customer/manatal_talent_portal_signin_tai.php/?r=cred&user=$user");
    return;
  }
}
header("Location: /portal/customer/manatal_talent_portal_signin.php/?r=error&user=$user");
return;

?>
