<?php

require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";

// use GuzzleHttp\Client;


$client = new \GuzzleHttp\Client();


Function candidate_from_match($cand) {
	$candcall = $client->request('GET', 'https://api.manatal.com/open/v3/matches/?stage__in=142142', [
	'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
	],
	]);

	echo $candStr = $candcall->getBody();
	$cand_arr = json_decode($candStr, true);
	


// you can comma delinieate stage numbers++
// Hired - 142142
// 142136 = New
// 142137 = Interested

$response = $client->request('GET', 'https://api.manatal.com/open/v3/matches/?stage__in=142142', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);

echo $response->getBody();
$responseStr = $response->getBody();

$matches = json_decode($responseStr, true);
 	echo "<br>";
 
 foreach($matches["results"] as $match) {
	echo $match["id"];
		echo "<br>";
	echo $candidate = $match['candidate'];
		echo "<br>";
	echo $match['stage']['name'];
		echo "<br>";
	
 }


?>