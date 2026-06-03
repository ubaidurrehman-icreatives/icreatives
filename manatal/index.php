<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';

use GuzzleHttp\Exception\RequestException;

$link = db();

$requestBody = file_get_contents('php://input');
$bodyContact  = $requestBody ? json_decode($requestBody, true) : null;

$contact_id = $_REQUEST['id'] ?? null;

$client = new \GuzzleHttp\Client([
    'timeout'         => 30,
    'connect_timeout' => 10,
]);

// --------------------------------------------------
// 1) Decide where $contact comes from
// --------------------------------------------------
if (empty($contact_id) && $bodyContact) {
    // "Test" / webhook mode: we trust the body
    $contact    = $bodyContact;
    $contact_id = (string) $contact['id'];

    // First time: store manatalid in custom fields
    $contact['custom_fields']['manatalid'] = (string) $contact['id'];

} else {
    // "Live" mode: id is passed as ?id=...
    try {
        $response = $client->request('GET', 'https://api.manatal.com/open/v3/contacts/'.$contact_id.'/', [
            'headers' => [
                'Authorization' => 'Token 92e3967b096dc33e0f09df8c0a927ec0437d8942',
                'accept'        => 'application/json',
            ],
        ]);

        $responseStr = (string) $response->getBody();
        $contact     = json_decode($responseStr, true);
        $contact_id  = (string) $contact['id'];

    } catch (RequestException | \Exception $e) {
        echo "<script>alert('The server is extremely busy, please retry');</script>";
        $apiError = true;
        exit();
    }
}

// --------------------------------------------------
// 2) Normalize custom_fields (works for both modes)
// --------------------------------------------------
$normalizedCustomFields = [];

if (!empty($contact['custom_fields']) && is_array($contact['custom_fields'])) {
    foreach ($contact['custom_fields'] as $key => $value) {
        if (is_int($key) && is_array($value)) {
            // Handle numeric entries like [ ['field' => 'value'], ... ]
            foreach ($value as $fieldName => $fieldValue) {
                $normalizedCustomFields[$fieldName] = $fieldValue;
            }
        } elseif (!is_int($key)) {
            // Normal associative fields
            $normalizedCustomFields[$key] = $value;
        }
    }
}
// after your normalization loop:
$contact['custom_fields'] = $normalizedCustomFields;

// --------------------------------------------------
// 3) Enforce your fields (same types in test + live)
// --------------------------------------------------

// Hidden email: use the contact email if present
$normalizedCustomFields['hiddenemail'] = (string) ($contact['email'] ?? '');

// Manatal id: only strictly needed the first time, but safe:
$normalizedCustomFields['manatalid'] = (string) $contact['id'];

// Date: proper YYYY-MM-DD string
// $normalizedCustomFields['holidayemailsent'] = date('Y-m-d'); // or your logic

// Boolean: REAL boolean, not "true"/"1"
$normalizedCustomFields['icreativesportalaccess'] = true; // or false as needed

// --------------------------------------------------
// 4) Build final payload and PATCH
// --------------------------------------------------
$payload = [
    'custom_fields' => $normalizedCustomFields,
];

$body = json_encode($payload);

// Debug so you can see what happens in both modes:
echo "\n\n<!-- REQUEST BODY:\n" . $body . "\n-->";

try {
    $response = $client->request('PATCH', 'https://api.manatal.com/open/v3/contacts/'.$contact_id.'/', [
        'body'    => $body,
        'headers' => [
            'Authorization' => 'Token 92e3967b096dc33e0f09df8c0a927ec0437d8942',
            'accept'        => 'application/json',
            'content-type'  => 'application/json',
        ],
    ]);

    $response->getBody(); // consume if needed

} catch (RequestException | \Exception $e) {
    echo "<script>alert('The server is extremely busy, please retry');</script>";
    $apiError = true;
    exit();
}

// Add or Update Contact
function Company_Name($company_id) {
	$client = new \GuzzleHttp\Client([
    'timeout'         => 30,  // wait up to 10 seconds per attempt
    'connect_timeout' => 10
	]);

	try {

	$response = $client->request('GET', 'https://api.manatal.com/open/v3/organizations/'.$company_id.'/', [
		'headers' => [
		'Authorization' => 'Token 92e3967b096dc33e0f09df8c0a927ec0437d8942',
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



// add to contacts

		if(isset($contact['custom_fields']['icreativesportalaccess']) && $contact['custom_fields']['icreativesportalaccess']){
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
			// $query2 = "DELETE from ic_contacts WHERE id = '".$contact['id']."'";
			// $result2 = mysqli_query($link,$query2 );
		}
