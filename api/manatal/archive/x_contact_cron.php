<?php
// since it runs as a cron, we had to do a lot if "isset()" tests to avoid errors
$datetime_1 = date("Y-m-d H:i:s");
ini_set('max_execution_time', 10000);

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

$page_num = "1";
$first_page = $page_num;

if(empty($page_num)) { 
	echo "MISSING PAGE NUMBER https://www.icreatives.com/api/manatal/rosetta_1.php?page=XXXX";
	exit();
}

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

// everything updated within a certain time
$after =  date("Y-m-d",strtotime('today - 1 days'));
// $after =  date("Y-m-d",strtotime('today'));
echo "updates after ".$after;

$page_num = 1;
$count = 100;

require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$client = new \GuzzleHttp\Client();
	
	// echo 'https://api.manatal.com/open/v3/contacts/?page='. $page_num .'&page_size=1';

	$response = $client->request('GET', 'https://api.manatal.com/open/v3/contacts/?updated_at__gte='.$after.'&page='. $page_num .'&page_size=100', [
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
		],
	]);

	echo $response->getBody();

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
		
		if(isset($contacts['results'][$x]['custom_fields']['icreativesportalaccess']) && $contacts['results'][$x]['custom_fields']['icreativesportalaccess'] ){
			$portal = 1;
		} else {
			$portal = 0;
		}
		
		if(isset($contacts['results'][$x]['custom_fields']['invoicepercandidate']) && $contacts['results'][$x]['custom_fields']['invoicepercandidate']){
			$one_invoice_per_candidate= 1;
		} else {
			$one_invoice_per_candidate= 0;
		}
		
		// echo "ap = " .$contacts['results'][$x]['custom_fields']['accountspayable'];
		echo "<br>";
		
		if(isset($contacts['results'][$x]['custom_fields']['accountspayable']) && $contacts['results'][$x]['custom_fields']['accountspayable']){
			$accountspayable = 1;
		} else {
			$accountspayable = 0;
		}
		if(isset($contacts['results'][$x]['custom_fields']['displayfullnameoninvoice']) && $contacts['results'][$x]['custom_fields']['displayfullnameoninvoice']){
			$displayfullnameoninvoice = 1;
		} else {
			$displayfullnameoninvoice = 0;
		}
		$org_id = $contacts['results'][$x]['organization'];
		$org_name = Company_Name($org_id);
		/*
		// if(isset($contacts['results'][$x]['full_name'])) { 
		// 	list($name,$has_ap) = explode("-",$contacts['results'][$x]['full_name']);
		// } else {
		// 	$name = "";
		//	$has_ap = "";
		// }
		*/
		// If(strpos($contacts['results'][$x]['full_name'],'AP')) {$accountspayable = 1;}

		
		if (isset($contacts['results'][$x]['custom_fields']['streetaddress'])) {
			$streetaddress = addslashes($contacts['results'][$x]['custom_fields']['streetaddress']);
		}else{
			$streetaddress = "";
		}
		if (isset($contacts['results'][$x]['custom_fields']['streetaddress_b'])) {
			$streetaddress_b = addslashes($contacts['results'][$x]['custom_fields']['streetaddress_b']);
		}else{
			$streetaddress_b = "";
		}
		if (isset($contacts['results'][$x]['custom_fields']['postalcode'])) {
			$postalcode = str_replace('Zip: ','',$contacts['results'][$x]['custom_fields']['postalcode']);
		}else{
			$postalcode = "";
		}
		if (isset($contacts['results'][$x]['custom_fields']['country'])) {
			$country = $contacts['results'][$x]['custom_fields']['country'];
		}else{
			$country = "";
		}
		if (isset($contacts['results'][$x]['custom_fields']['ap_template'])) {
			$ap_template = $contacts['results'][$x]['custom_fields']['ap_template'];
		}else{
			$ap_template = "";
		}
		if (isset($contacts['results'][$x]['custom_fields']['terms'])) {
			$terms = $contacts['results'][$x]['custom_fields']['terms'];
		}else{
			$terms = "";
		}
		if (isset($contacts['results'][$x]['custom_fields']['billingcycle'])) {
			$billingcycle = $contacts['results'][$x]['custom_fields']['billingcycle'];
		}else{
			$billingcycle = "";
		}
		if (isset($contacts['results'][$x]['custom_fields']['city'])) {
			$city = $contacts['results'][$x]['custom_fields']['city'];
		}else{
			$city = "";
		}
		if (isset($contacts['results'][$x]['custom_fields']['state'])) {
			$state = $contacts['results'][$x]['custom_fields']['state'];
		}else{
			$state = "";
		}
		

		$query = "INSERT INTO ic_contacts (
		id, 
		full_name, 
		display_name, 
		email, 
		organization,
		company_name,
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
		billing_cycle,
		one_invoice_per_candidate,
		full_name_on_invoice,
		created_at
		) 
		VALUES ('" . addslashes($contacts['results'][$x]['id']) . "', '".
		addslashes($contacts['results'][$x]['full_name'])."','".
		addslashes($contacts['results'][$x]['display_name'])."','".
		addslashes($contacts['results'][$x]['email'])."','".
		addslashes($contacts['results'][$x]['organization'])."','".
		addslashes($org_name)."',".
		$portal. ", ".
		$accountspayable . ", '".
		$streetaddress."', '".
		$streetaddress_b."', '".
		$city."', '".
		$state."', '".
		$postalcode."', '".
		$country."', '".
		$contacts['results'][$x]['phone_number']."', '".
		$ap_template."', '".
		$terms."', '".
		$billingcycle."', ".
		$one_invoice_per_candidate. ", ".
		$displayfullnameoninvoice . ", '".
		$contacts['results'][$x]['created_at']. "' 
		) 
		 ON DUPLICATE KEY UPDATE 
		full_name= VALUES(full_name),
		display_name=VALUES(display_name),
		email=VALUES(email), 
		organization= VALUES(organization),
		company_name= VALUES(company_name),			
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
		billing_cycle = VALUES(billing_cycle),
		one_invoice_per_candidate = VALUES(one_invoice_per_candidate),
		full_name_on_invoice = VALUES(full_name_on_invoice),
		created_at = VALUES(created_at)"; 
		
		// $query = str_replace("''","'0'",$query);
		echo $query."<br><br>";
		$result = mysqli_query($link,$query );
		echo "<P>";
		sleep(3);
		$response = $client->request('GET', 'https://api.manatal.com/open/v3/contacts/'.($contacts['results'][$x]['id']).'/', [
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
		],
		]);

		$response->getBody();
		$responseStr = $response->getBody();
	
		// Decode JSON data
		$jsonData = json_decode($responseStr, true);

		// now place an email address that can be reached in contact email templates
		
		// Replace "hiddenemail" with a variable
		$email = $contacts['results'][$x]['email'];
		// if (isset($jsonData['custom_fields']['hiddenemail'])) {
			$jsonData['custom_fields']['hiddenemail'] = $email;
		// }
		// $commaSeparatedString = json_encode($jsonData['custom_fields']);
		// echo $commaSeparatedString = preg_replace('/[^a-zA-Z0-9_\-#",;:@&()<> ]/', '',  $commaSeparatedString);

		// Extract "custom_fields"
		$customFields = $jsonData['custom_fields'];

		// Initialize an array to store key-value pairs
		$keyValuePairs = [];

		// Loop through "custom_fields" and create key-value pairs
		foreach ($customFields as $key => $value) {
			// Skip fields with a value of false
			if ($value === false) {
				continue;
		}

    // Convert boolean values to lowercase strings
    $formattedValue = is_bool($value) ? strtolower(var_export($value, true)) : $value;
    $keyValuePairs[] = "\"$key\": \"$formattedValue\"";
	}


	// Create a comma-separated string
	$commaSeparatedString = implode(",\n", $keyValuePairs);

	// Display the result
	
	$commaSeparatedString = str_replace('"true"','true',$commaSeparatedString);
	$commaSeparatedString = str_replace('"false"','false',$commaSeparatedString);
	
	echo "Comma Separated = ".$commaSeparatedString."<br>";
	
		// exit();
		
		$client = new \GuzzleHttp\Client();

		$response = $client->request('PATCH', 'https://api.manatal.com/open/v3/contacts/'.$contacts['results'][$x]['id'].'/', [
		'body' => '{"custom_fields":{'.$commaSeparatedString.'},"full_name":"'.$contacts['results'][$x]['full_name'].'"}',
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
		'content-type' => 'application/json',
		],
		]);	
		// echo $response->getBody();	
		
	}

$datetime_2 = date("Y-m-d H:i:s"); 
 
 
$from_time = strtotime($datetime_1); 
$to_time = strtotime($datetime_2); 
echo "<P>";
echo $diff_minutes = round(abs($from_time - $to_time) / 60,2). " minutes";
Echo "<br>Done";
?>


<?php
// sleep so we are not thottled by the Manatal API
sleep(10);
// now run match updates too

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
	
	// position_name
	for($x=0; $x<count($jobs['results']); $x++) {
		//  echo $jobs['results'][$x]['custom_fields']['portalusers'];
		if(isset($jobs['results'][$x]['custom_fields']['portalusers'])){
			$portalusers_array = implode(', ',(array)$jobs['results'][$x]['custom_fields']['portalusers']);
		} else {
			$portalusers_array = "";
		}
		if(isset($jobs['results'][$x]['custom_fields']['ponumber'])){
			$po_number = $jobs['results'][$x]['custom_fields']['ponumber'];
		} else {
			$po_number = "";
		}
		if(isset($jobs['results'][$x]['custom_fields']['apinvoiceemailcommadelimited'])){
			$ap_email = $jobs['results'][$x]['custom_fields']['apinvoiceemailcommadelimited'];
		} else {
			$ap_email = "";
		}
		if(isset($jobs['results'][$x]['custom_fields']['poamount'])){
			$po_amount = $jobs['results'][$x]['custom_fields']['poamount'];
		} else {
			$po_amount = "";
		}
		if(isset($jobs['results'][$x]['custom_fields']['poenddate'])){
			$po_end_date = $jobs['results'][$x]['custom_fields']['poenddate'];
		} else {
			$po_end_date = "0000-00-00";
		}
		$position_name = $jobs['results'][$x]['position_name'];
		
		$query = "UPDATE ic_matches set 
			job_name = '". $position_name ."',
			portal_users = '". $portalusers_array ."', 
			po_amount = '". $po_amount ."', 
			po_end_date = '". $po_end_date ."', 
			po_number = '". $po_number ."', 
			ap_email = '". $ap_email ."' 
			WHERE job = '" . $jobs['results'][$x]['id'] . "'";
		echo $query."<br><br>";
		$result = mysqli_query($link,$query );
		
		if(isset($jobs['results'][$x]['custom_fields']['openorclosed']) && $jobs['results'][$x]['custom_fields']['openorclosed'] == "Closed"){
			// $openorclosed = 1;
			$query = "UPDATE ic_matches set closed = 1 WHERE job = '" . $jobs['results'][$x]['id'] . "'";
			echo $query."<br><br>";
			$result = mysqli_query($link,$query );
		} 
	}

$datetime_2 = date("Y-m-d H:i:s"); 
 
 
$from_time = strtotime($datetime_1); 
$to_time = strtotime($datetime_2); 
echo "<P>";
echo $diff_minutes = round(abs($from_time - $to_time) / 60,2). " minutes";
Echo "<br>Done";
?>