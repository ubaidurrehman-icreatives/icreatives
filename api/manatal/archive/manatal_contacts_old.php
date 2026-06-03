<?php

// ob_clean();
$datetime_1 = date("Y-m-d H:i:s");
ini_set('max_execution_time', 10000);

$page_num = $_REQUEST['page'];
$first_page = $_REQUEST['page'];

$page_num = 1;

if(empty($page_num)) { 
	echo "MISSING PAGE NUMBER https://www.icreatives.com/api/manatal/rosetta_1.php?page=XXXX";
	exit();
}

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

// everything updated withun a certain time
$after =  date("Y-m-d",strtotime('today - 5 days'));
echo "updates after ".$after;

// $page_num = 1;
$count = 100;

require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$client = new \GuzzleHttp\Client();

while ($page_num > 0 ) {
	
	// echo 'https://api.manatal.com/open/v3/contacts/?page='. $page_num .'&page_size=1';
	
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/contacts/?updated_at__gte='.$after.'&page='. $page_num .'&page_size=100', [
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
		],
	]);

	$response->getBody();

	$responseStr = $response->getBody();
	$contacts = json_decode($responseStr, true);
	
	
	echo "<br>count= ". $contacts['count']."<BR>";

	
	// keep track of count to see if we should add a page
				echo "<br>next= ".$contacts['next']."<br>"; 
	If (is_null($contacts['next'])) {
			echo "<br>next= ".$contacts['next']; 
	}

	
 	echo "<br> Page Count = ";
	echo $page_count = $contacts['count'];
	
	
		// foreach($contacts["results"] as $contact) {
	
	
	for($x=0; $x<count($contacts['results']); $x++) {
		
		// echo $contacts['results'][$x]['full_name'] . "<br>";
		
		if(is_null($contacts['results'][$x]['custom_fields']['icreativesportalaccess'])){
			$portal = 0;
		} else {
			$portal = 1;
		}
		if(is_null($contacts['results'][$x]['custom_fields']['invoicepercandidate'])){
			$invoicepercandidate= 0;
		} else {
			$invoicepercandidate= 1;
		}
		/*
		if(is_null($contacts['results'][$x]['custom_fields']['accountspayable'])){
			$accountspayable = 0;
		} else {
			$accountspayable = 1;
		}
		*/
		if($contacts['results'][$x]['custom_fields']['accountspayable'] == true) {
			$accountspayable = 1;
		} else {
			$accountspayable = 0;
		}
		
		list($name,$has_ap) = explode("-",$contacts['results'][$x]['full_name']);
		If(strpos($has_ap,'AP')) {$accountspayable = 1;}

		$query = "INSERT INTO ic_contacts (
		id, 
		full_name, 
		display_name, 
		email, 
		organization,
		icreativesportalaccess,
		accountspayable,
		address1,
		address2,
		city,
		state,
		postalcode,
		country,
		phone_number,
		ap_template,
		terms,
		one_invoice_per_candidate,
		created_at
		) 
		VALUES ('" . addslashes($contacts['results'][$x]['id']) . "', '".
		addslashes($contacts['results'][$x]['full_name'])."','".
		addslashes($contacts['results'][$x]['display_name'])."','".
		addslashes($contacts['results'][$x]['email'])."','".
		addslashes($contacts['results'][$x]['organization'])."',".
		$portal. ", ".
		$accountspayable . ", '".
		addslashes($contacts['results'][$x]['custom_fields']['streetaddress'])."', '".
		addslashes($contacts['results'][$x]['custom_fields']['streetaddress_b'])."', '".
		$contacts['results'][$x]['custom_fields']['city']."', '".
		$contacts['results'][$x]['custom_fields']['state']."', '".
		str_replace('Zip: ','',$contacts['results'][$x]['custom_fields']['postalcode'])."', '".
		$contacts['results'][$x]['custom_fields']['country']."', '".
		$contacts['results'][$x]['phone_number']."', '".
		$contacts['results'][$x]['custom_fields']['ap_template']."', '".
		$contacts['results'][$x]['custom_fields']['terms']."', ".
		$invoicepercandidate .", '".
		$contacts['results'][$x]['created_at']. "' 
		) 
		 ON DUPLICATE KEY UPDATE 
		 id = VALUES(id),
		full_name= VALUES(full_name),
		display_name=VALUES(display_name),
		email=VALUES(email), 
		organization= VALUES(organization), 
		icreativesportalaccess= VALUES(icreativesportalaccess), 
		accountspayable = VALUES(accountspayable),
		address1 = VALUES(address1), 
		address2 = VALUES(address2), 
		city = VALUES(city),
		state = VALUES(state),
		postalcode = VALUES(postalcode),
		country = VALUES(country),
		phone_number = VALUES(phone_number),
		ap_template = VALUES(ap_template),
		terms = VALUES(terms),
		one_invoice_per_candidate = VALUES(one_invoice_per_candidate),
		created_at = VALUES(created_at)"; 
		
		// $query = str_replace("''","'0'",$query);
		echo $contacts['results'][$x]['full_name']."<br><br>";
		$result = mysqli_query($link,$query );
	}
		echo "<br><br><br>Page Number = ".$page_num."<br>";
	If (is_null($contacts['next'])) {
		$page_num = 0;
	} else {
		if($page_num > $first_page+50) {exit();;}
		$page_num = $page_num + 1;
		echo " ".$contacts['next']." ";
	}

	sleep(5);
}

$datetime_2 = date("Y-m-d H:i:s"); 
 
 
$from_time = strtotime($datetime_1); 
$to_time = strtotime($datetime_2); 
echo "<P>";
echo $diff_minutes = round(abs($from_time - $to_time) / 60,2). " minutes";
Echo "<br>Done";

?>