<?php

ob_clean();
$datetime_1 = date("Y-m-d H:i:s");
ini_set('max_execution_time', 10000);
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());


$_REQUEST['po_number']



// you can comma delinieate stage numbers++
// Hired - 142142
// 142136 = New
// 142137 = Interested

require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$client = new \GuzzleHttp\Client();

		
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/matches/?stage__in=142142', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);


	$response->getBody();

	echo $responseStr = $response->getBody();
	$matches = json_decode($responseStr, true);
	
	
	echo "<br>count= ". $matches['count']."<BR>";

	
	// keep track of count to see if we should add a page
				echo "<br>next= ".$matches['next']."<br>"; 
	If (is_null($matches['next'])) {
			echo "<br>next= ".$matches['next']; 
	}

	
 	echo "<br>";
	$page_count = $matches['count'];
		// foreach($matches["results"] as $contact) {
	
	for($x=0; $x<count($matches['results']); $x++) {
		
		$query = "INSERT INTO ic_matches_temp (
		id, 
		first_name, 
		last_name,
		full_name,
		owner, 
		email, 
		organization,
		icreativesportalaccess) 
		VALUES ('". addslashes($matches['results'][$x]['id'])."', '".
		addslashes($matches['results'][$x]['full_name'])."','".
		addslashes($matches['results'][$x]['display_name'])."','".
		addslashes($matches['results'][$x]['email'])."','".
		addslashes($matches['results'][$x]['organization'])."',".
		$portal. ")  
		ON DUPLICATE KEY UPDATE 
		full_name='".addslashes($matches['results'][$x]['full_name'])."', 
		display_name='".addslashes($matches['results'][$x]['display_name'])."', 
		email='".addslashes($matches['results'][$x]['email'])."', 
		organization='".addslashes($matches['results'][$x]['organization'])."', 
		icreativesportalaccess=".$portal.";"; 
		
		echo $query."<br><br>";
		$result = mysqli_query($link,$query );

	}
	If (is_null($matches['next'])) {
		$page_num = 0;
	} else {
		$page_num = $page_num + 1;
	}
	echo "<br><br><br>Page Number = ".$page_num."<br>";

}
$datetime_2 = date("Y-m-d H:i:s"); 
 
$from_time = strtotime($datetime_1); 
$to_time = strtotime($datetime_2); 
echo "<P>";
echo $diff_minutes = round(abs($from_time - $to_time) / 60,2). " minutes";
Echo "<br>Done";

?>