

<?php

// ob_clean();
$datetime_1 = date("Y-m-d H:i:s");
ini_set('max_execution_time', 10000);

$page_num = 0;
$first_page = $page_num;

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

$page_num = 0;
// $count = 100;
$done = 0;
require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$client = new \GuzzleHttp\Client();


function Company_Name($company_id) {
	$client = new \GuzzleHttp\Client();

	$response = $client->request('GET', 'https://api.manatal.com/open/v3/organizations/'.$company_id.'/', [
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
		],
	]);

	$response->getBody();
	$responseStr = $response->getBody();
	$organization = json_decode($responseStr, true);
	$company_name = $organization['name'];
	
    return $company_name;
}





While ($done == 0) {
	$page_num = $page_num + 1;
		echo "Page Number = ".$page_num . "<br>";
		$response = $client->request('GET', 'https://api.manatal.com/open/v3/organizations/?page='. $page_num .'&page_size=100', [
			'headers' => [
			'Authorization' => $token,
			'accept' => 'application/json',
			],
		]);
		

	$response->getBody();

	$responseStr = $response->getBody();
	$organizations = json_decode($responseStr, true);
	
	
	// echo "<br>count= ". $contacts['count']."<BR>";
	echo "Page Number = ". $page_num ."<br>";
	
	// keep track of count to see if we should add a page
	echo "<br>next= ".$organizations['next']."<br>"; 
	If (is_null($organizations['next'])) {
			echo "<br>next= ".$organizations['next']; 
			$done = 1;
	}

	
 	// echo "<br> Page Count = ";
	// echo $page_count = $organizations['count'];
	
	for($x=0; $x<count($organizations['results']); $x++) {
		
		$company_name = $organizations['results'][$x]['name'];
		$company_id = $organizations['results'][$x]['id'];
		 
		
		$query = "UPDATE ic_contacts set company_name = '".$company_name."' 
				WHERE organization = '".$company_id. "'";

		// echo $query."<br><br>";
		// exit();
		$result = mysqli_query($link,$query );
		echo "<P>";
	}
	sleep(2);
}
$datetime_2 = date("Y-m-d H:i:s"); 
 
 
$from_time = strtotime($datetime_1); 
$to_time = strtotime($datetime_2); 
echo "<P>";
echo $diff_minutes = round(abs($from_time - $to_time) / 60,2). " minutes";
Echo "<br>Done";

?>