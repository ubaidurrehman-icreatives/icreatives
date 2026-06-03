<?php

$link = mysqli_connect('icreatives.com', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());
require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";

	use GuzzleHttp\Exception\ClientException;
	use GuzzleHttp\Exception\RequestException;

// Read the raw POST data
$requestBody = file_get_contents('php://input');

// fix JSON string
// Decode the JSON string into an associative array
$data = json_decode($requestBody, true);

// Check if the 'custom_fields' section needs to be fixed
$needsFixing = is_array($data['custom_fields']) && count($data['custom_fields']) > 0 && is_array($data['custom_fields'][0]);

// If 'custom_fields' needs fixing, merge the fields into a single associative array
// if ($needsFixing) {
	// Create a new associative array for custom fields
	$customFields = array();
	foreach ($data['custom_fields'] as $field) {
		// Merge each field into the custom fields array
		$customFields += $field;
	}
	// Replace the 'custom_fields' array with the new associative array
	$data['custom_fields'] = $customFields;
 // }
$contact = $data;
// end fix JSON string



// $contact = json_decode($requestBody, true);


// echo $requestBody;
// Log the request contact data
$logFile = 'request.log';
$logMessage = date('Y-m-d H:i:s') . " - " . $requestBody. PHP_EOL;
// $logMessage = date('Y-m-d H:i:s') . " - " . json_encode($contact) . PHP_EOL;

file_put_contents($logFile, $logMessage, FILE_APPEND);

// Set response headers before sending any output
header('Content-Type: application/json');

// Echo the received data as response
// echo json_encode($contact);

// Add or Update Contact
function Company_Name($company_id) {
	$client = new \GuzzleHttp\Client([
    'timeout'         => 30,  // wait up to 10 seconds per attempt
    'connect_timeout' => 10
	]);

	try {

	$response = $client->request('GET', 'https://api.manatal.com/open/v3/organizations/'.$company_id.'/', [
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
		],
	]);

	$response->getBody();
	$responseStr = $response->getBody();
	$organization = json_decode($responseStr, true);
	
	} catch (ConnectException | RequestException | \Exception $e) {
	 echo "<script>alert('The server is extremely busy, please retry');</script>";
    $apiError = true;
	exit();
	}

	
	
	
	$company_name = $organization['name'];
	sleep(3);
	
    return $company_name;
}

		if(isset($contact['custom_fields']['icreativesportalaccess']) && $contact['custom_fields']['icreativesportalaccess'] == "Yes" ){
			$portal = 1;
		} else {
			$portal = 0;
		}
		if(isset($contact['custom_fields']['deactivated']) && $contact['custom_fields']['deactivated'] == "Yes" ){
			$deactivated = 1;
		} else {
			$deactivated = 0;
		}
		
		if(isset($contact['custom_fields']['invoicepercandidate']) && $contact['custom_fields']['invoicepercandidate'] == "Yes"){
			$one_invoice_per_candidate= 1;
		} else {
			$one_invoice_per_candidate= 0;
		}
		
		// echo "ap = " .$contact['custom_fields']['accountspayable']."\n";
		// echo $contact['custom_fields']['accountspayable']"<br>";
		// exit();
		if(isset($contact['custom_fields']['accountspayable']) && $contact['custom_fields']['accountspayable']){
			$accountspayable = 1;
		} else {
			$accountspayable = 0;
		}
		
		if(isset($contact['custom_fields']['deactivated']) && $contact['custom_fields']['deactivated'] == "Yes"){
			$deactivate = 1;
		} else {
			$deactivated = 0;
		}

		echo "AP = ".$accountspayable ."/n";
		if(isset($contact['custom_fields']['displayfullnameoninvoice']) && $contact['custom_fields']['displayfullnameoninvoice'] == "Yes"){
			$displayfullnameoninvoice = 1;
		} else {
			$displayfullnameoninvoice = 0;
		}
		$org_id = $contact['organization'];
		$org_name = Company_Name($org_id);
		
		if (isset($contact['custom_fields']['streetaddress'])) {
			$streetaddress = addslashes($contact['custom_fields']['streetaddress']);
		}else{
			$streetaddress = "";
		}
		if (isset($contact['custom_fields']['streetaddress_b'])) {
			$streetaddress_b = addslashes($contact['custom_fields']['streetaddress_b']);
		}else{
			$streetaddress_b = "";
		}
		if (isset($contact['custom_fields']['postalcode'])) {
			$postalcode = str_replace('Zip: ','',$contact['custom_fields']['postalcode']);
		}else{
			$postalcode = "";
		}
		if (isset($contact['custom_fields']['country'])) {
			$country = $contact['custom_fields']['country'];
		}else{
			$country = "";
		}
		if (isset($contact['custom_fields']['ap_template'])) {
			$ap_template = $contact['custom_fields']['ap_template'];
		}else{
			$ap_template = "";
		}
		if (isset($contact['custom_fields']['terms'])) {
			$terms = $contact['custom_fields']['terms'];
		}else{
			$terms = "";
		}
		if (isset($contact['custom_fields']['billingcycle'])) {
			$billingcycle = $contact['custom_fields']['billingcycle'];
		}else{
			$billingcycle = "";
		}
		if (isset($contact['custom_fields']['city'])) {
			$city = $contact['custom_fields']['city'];
		}else{
			$city = "";
		}
		if (isset($contact['custom_fields']['state'])) {
			$state = $contact['custom_fields']['state'];
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
		deactivated,
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
		VALUES ('" . addslashes($contact['id']) . "', '".
		addslashes($contact['full_name'])."','".
		addslashes($contact['display_name'])."','".
		addslashes($contact['email'])."','".
		addslashes($contact['organization'])."','".
		addslashes($org_name)."',".
		$portal. ", ".
		$accountspayable . ", ".
		$deactivated . ", '".
		$streetaddress."', '".
		$streetaddress_b."', '".
		$city."', '".
		$state."', '".
		$postalcode."', '".
		$country."', '".
		$contact['phone_number']."', '".
		$ap_template."', '".
		$terms."', '".
		$billingcycle."', ".
		$one_invoice_per_candidate. ", ".
		$displayfullnameoninvoice . ", '".
		$contact['created_at']. "' 
		) 
		 ON DUPLICATE KEY UPDATE 
		full_name= VALUES(full_name),
		display_name=VALUES(display_name),
		email=VALUES(email), 
		organization= VALUES(organization),
		company_name= VALUES(company_name),			
		icreativesportalaccess= VALUES(icreativesportalaccess), 
		accountspayable = VALUES(accountspayable),
		deactivated = VALUES(deactivated),
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
		
		// Now delete deactivated ic_contact
		if($deactivated == 1) {
			$query2 = "DELETE from ic_contacts WHERE id = '".$contact['id']."'";
			$result2 = mysqli_query($link,$query2 );
		}
	
		// now place an email address that can be reached in contact email templates
		
		// Replace "hiddenemail" with a variable
		$email = $contact['email'];
		if (!isset($contact['custom_fields']['hiddenemail']) || $contact['email'] !== $contact['custom_fields']['hiddenemail']) {
		
		
			$contact['custom_fields']['hiddenemail'] = $email;
			
		// $commaSeparatedString = json_encode($jsonData['custom_fields']);
		// echo $commaSeparatedString = preg_replace('/[^a-zA-Z0-9_\-#",;:@&()<> ]/', '',  $commaSeparatedString);

		// Extract "custom_fields"
		$customFields = $contact['custom_fields'];

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
	
	$commaSeparatedString = str_replace('"Yes"','true',$commaSeparatedString);
	$commaSeparatedString = str_replace('"No"','false',$commaSeparatedString);
	
	echo "Comma Separated = ".$commaSeparatedString."<br>";
	
		// exit();
		
	$client = new \GuzzleHttp\Client([
	$client = new \GuzzleHttp\Client([
    'timeout'         => 30,  // wait up to 10 seconds per attempt
    'connect_timeout' => 10
	]);



	try {

		$response = $client->request('PATCH', 'https://api.manatal.com/open/v3/contacts/'.$contact['id'].'/', [
		'body' => '{"custom_fields":{'.$commaSeparatedString.'},"full_name":"'.$contact['full_name'].'"}',
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
		'content-type' => 'application/json',
		],
		]);	
		$response->getBody();	
	} catch (ConnectException | RequestException | \Exception $e) {
	 echo "<script>alert('The server is extremely busy, please retry');</script>";
    $apiError = true;
	exit();
}
		$result->free();
mysqli_close($mysqli);
$mysqli->close();		
	}
?>