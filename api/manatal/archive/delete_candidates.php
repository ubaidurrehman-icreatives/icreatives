

<?php

ob_clean();
$datetime_1 = date("Y-m-d H:i:s");
ini_set('max_execution_time', 10000);
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

// everything updated withun a certain time
$after =  date("Y-m-d",strtotime('today - 5000 days'));
echo "updates after ".$after;

$page_num = 1;
$count = 100;

require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$client = new \GuzzleHttp\Client();

while ($page_num > 0 ) {
	
	// echo 'https://api.manatal.com/open/v3/contacts/?page='. $page_num .'&page_size=1';
	
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/?page=' . $page_num .'&page_size=99', [
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
		],
	]);


	$response->getBody();

	$responseStr = $response->getBody();
	$candidates = json_decode($responseStr, true);
	
	
	echo "<br>count= ". $candidates['count']."<BR>";

	
	// keep track of count to see if we should add a page
				echo "<br>next= ".$candidates['next']."<br>"; 
	If (is_null($candidates['next'])) {
			echo "<br>next= ".$candidates['next']; 
	}

	
 	echo "<br>";
	$page_count = $candidates['count'];
	
	
		// foreach($candidates["results"] as $contact) {
	
	for($x=0; $x<count($candidates['results']); $x++) {
		
		echo $x."-".$candidates['results'][$x]['id']."-".$candidates['results'][$x]['email']."<br>";
	
	}
	If (is_null($candidates['next'])) {
		$page_num = 0;
	} else {
		$page_num = $page_num + 1;
	}
	echo "<br><br><br>Page Number = ".$page_num."<br>";
	
	sleep(10);

}
$datetime_2 = date("Y-m-d H:i:s"); 
 
$from_time = strtotime($datetime_1); 
$to_time = strtotime($datetime_2); 
echo "<P>";
echo $diff_minutes = round(abs($from_time - $to_time) / 60,2). " minutes";
Echo "<br>Done";

?>