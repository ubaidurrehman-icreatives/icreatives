<?php
// since it runs as a cron, we had to do a lot if "isset()" tests to avoid errors
$datetime_1 = date("Y-m-d H:i:s");
ini_set('max_execution_time', 10000);

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

// everything updated within a certain time
$after =  date("Y-m-d",strtotime('today - 1 days'));
echo "updates after ".$after;

$page_num = 1;
$count = 100;

require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$client = new \GuzzleHttp\Client();

	//$response =//  $client->request('GET', 'https://api.manatal.com/open/v3/contacts/?updated_at__gte='.$after.'&page='. $page_num .'&page_size=100', [
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/?updated_at__gte='.$after.'&page='. $page_num .'&page_size=100', [
	'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
	],
	]);

	$responseStr = $response->getBody();
	$jobs = json_decode($responseStr, true);
	
	echo "<br>count= ". $jobs['count']."<BR>";
	
	// keep track of count to see if we should add a page
				echo "<br>next= ".$jobs['next']."<br>"; 
	If (is_null($jobs['next'])) {
			echo "<br>next= ".$jobs['next']; 
	}

 	echo "<br> Page Count = ";
	echo $page_count = count($jobs['results']);
	
	
	for($x=0; $x<count($jobs['results']); $x++) {
		//  echo $jobs['results'][$x]['custom_fields']['portalusers'];
		if(isset($jobs['results'][$x]['custom_fields']['portalusers'])){
			$portalusers_array = implode(', ',(array)$jobs['results'][$x]['custom_fields']['portalusers']);
			$query = "UPDATE ic_matches set portal_users = '". $portalusers_array ."' WHERE job = '" . $jobs['results'][$x]['id'] . "'";
			echo $query."<br><br>";
		}

		// $result = mysqli_query($link,$query );
		echo "<P>";

	}

$datetime_2 = date("Y-m-d H:i:s"); 
 
 
$from_time = strtotime($datetime_1); 
$to_time = strtotime($datetime_2); 
echo "<P>";
echo $diff_minutes = round(abs($from_time - $to_time) / 60,2). " minutes";
Echo "<br>Done";
?>