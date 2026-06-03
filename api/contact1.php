<?php
use GuzzleHttp\Exception\ClientException;
// Create connection
require_once __DIR__ . '/../db/db.php';
$conn = db();  
require_once  dirname(__DIR__) . '/vendor/autoload.php';


$contactId = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contactId = isset($_POST['contactId']) ? $_POST['contactId'] : $contactId;
}

// $full_name = $display_name = $email = $company_name = $vendor_number = $icreativesportalaccess = $accountspayable = $address1 = $address2 = $postalcode = $city = $state = $country = $phone_number = $ap_template = $full_name_on_invoice = $one_invoice_per_candidate = $terms = $billing_cycle = $created_at = $encrypted_password = $deactivated = '';

// Check if record exists
if (!empty($contactId)) {
    $sql = "SELECT * FROM ic_contacts WHERE id = $contactId";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Record exists, fetch data
        $row = $result->fetch_assoc();
        $icreativesportalaccess = $row['icreativesportalaccess'];
        $manatal_id = $row['id'];
        $email = $row['email'];
        $company_name = $row['company_name'];
        $vendor_number = $row['vendor_number'];
        $icreativesportalaccess = 1;
        $accountspayable = 1;
        $address1 = $row['address1'];
        $address2 = $row['address2'];
        $postalcode = $row['postalcode'];
        $city = $row['city'];
        $state = $row['state'];
        $country = $row['country'];
        $phone_number = $row['phone_number'];
        $ap_template = $row['ap_template'];
        $full_name_on_invoice = $row['full_name_on_invoice'];
        $one_invoice_per_candidate = $row['one_invoice_per_candidate'];
        $terms = $row['terms'];
        $billing_cycle = $row['billing_cycle'];
        $po_required = $row['po_required'];
		$waive_late_fee = $row['waive_late_fee'];
        $waive_interest = $row['waive_interest'];
        $drug= $row['drug'];
        $background = $row['background'];
        $drug_back_period = $row['drug_back_period'];
        $created_at = $row['created_at'];
        $encrypted_password = $row['encrypted_password'];
        $deactivated = $row['deactivated'];

    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$icreativesportalaccess = isset($_POST['icreativesportalaccess']) ? 1 : 0;
    // $icreativesportalaccess = 1;
    // $deactivated = $conn->real_escape_string(isset($_POST['deactivated']) ? 1 : 0);

    // if (!empty($contactId)) {
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
    'timeout'         => 30,  // wait up to 10 seconds per attempt
    'connect_timeout' => 10
	]);



	try {

		$response = $client->request('PATCH', 'https://api.manatal.com/open/v3/contacts/'.$contact['id'].'/', [
		'body' => '{"custom_fields":{'.$commaSeparatedString.'},"full_name":"'.$contact['full_name'].'"}',
		'headers' => [
		'Authorization' => 'Token 92e3967b096dc33e0f09df8c0a927ec0437d8942',
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
mysqli_close($link);		
	
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Company Billing Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
			font-size:14px;
        }
        .custom-button {
            background-color: #1976D2;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            border-radius: 0;
        }

        .custom-alert {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #bbdefb;
            color: black;
            padding: 20px;
            border: 1px solid #1976D2;
            border-radius: 0;
            z-index: 1000;
            display: none;
        }

        table {
            width: 90%;
        }

        table tr td:first-child {
            width: 40%;
        }

        table tr td:last-child {
            width: 60%;
        }

        input[type="number"], input[type="text"], input[type="email"], input[type="tel"], input[type="password"], input[type="checkbox"], input[type="date"] {
            width: 100%;
            box-sizing: border-box;
        }
    </style>
    <script>
        function showCustomAlert(message) {
            var alertBox = document.createElement('div');
            alertBox.className = 'custom-alert';
            alertBox.innerText = message;

            document.body.appendChild(alertBox);
            alertBox.style.display = 'block';

            setTimeout(function() {
                alertBox.style.display = 'none';
                document.body.removeChild(alertBox);
                window.close(); // Close the popup window
            }, 500);
        }

        function closeWindow() {
            window.close();
        }
    </script>
</head>
<body>
    <h2>Billing Info: <?php echo htmlspecialchars($_REQUEST['company_name']); ?></h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table>
            <tr>
                <td><label for="full_name">Full Name:</label></td>
                <td><input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required></td>
            </tr>
            <tr>
                <td><label for="email">Email:</label></td>
                <td><input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required></td>
            </tr>
            <tr>
                <td><label for="company_name">Company Name:</label></td>
                <td><input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($company_name); ?>" required></td>
            </tr>
             <tr>
                <td><label for="vendor_number">Vendor Number:</label></td>
                <td><input type="text" id="vendor_number" name="vendor_number" value="<?php echo htmlspecialchars($vendor_number); ?>" </td>
            </tr>
           <tr>
                <td><label for="address1">Address 1:</label></td>
                <td><input type="text" id="address1" name="address1" value="<?php echo htmlspecialchars($address1); ?>" required></td>
            </tr>
            <tr>
                <td><label for="address2">Address 2:</label></td>
                <td><input type="text" id="address2" name="address2" value="<?php echo htmlspecialchars($address2); ?>"></td>
            </tr>
            <tr>
                <td><label for="postalcode">Postal Code:</label></td>
                <td><input type="text" id="postalcode" name="postalcode" value="<?php echo htmlspecialchars($postalcode); ?>"></td>
            </tr>
            <tr>
                <td><label for="city">City:</label></td>
                <td><input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>"></td>
            </tr>
            <tr>
                <td><label for="state">State:</label></td>
                <td><input type="text" id="state" name="state" value="<?php echo htmlspecialchars($state); ?>"></td>
            </tr>
            <tr>
                <td><label for="country">Country:</label></td>
                <td><input type="text" id="country" name="country" value="<?php echo htmlspecialchars($country); ?>"></td>
            </tr>
            <tr>
                <td><label for="phone_number">Phone Number:</label></td>
                <td><input type="tel" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>"></td>
            </tr>
            <tr>
                <td><label for="ap_template">AP Template:</label></td>
                <td><input type="text" id="ap_template" name="ap_template" value="<?php echo htmlspecialchars($ap_template); ?>"></td>
            </tr>
            <tr>
                <td><label for="full_name_on_invoice">Full Name on Invoice:</label></td>
                <td><input type="checkbox" id="full_name_on_invoice" name="full_name_on_invoice" value="1" <?php echo ($full_name_on_invoice ? 'checked' : ''); ?>></td>
            </tr>
            <tr>
                <td><label for="one_invoice_per_candidate">One Invoice per Candidate:</label></td>
                <td><input type="checkbox" id="one_invoice_per_candidate" name="one_invoice_per_candidate" value="1" <?php echo ($one_invoice_per_candidate ? 'checked' : ''); ?>></td>
            </tr>
            <tr>
                <td><label for="terms">Terms:</label></td>
                <td><input type="number" min="0" step="1" id="terms" name="terms" value="<?php echo htmlspecialchars($terms); ?>" required></td>
            </tr>
            <tr>
                <td><label for="billing_cycle">Billing Cycle:</label></td>
                <td>
                    <select id="billing_cycle" name="billing_cycle">
                        <option value="Weekly" <?php echo ($billing_cycle == 'Weekly' ? 'selected' : ''); ?>>Weekly</option>
                        <option value="Monthly" <?php echo ($billing_cycle == 'Monthly' ? 'selected' : ''); ?>>Monthly</option>
                    </select>
                </td>
            </tr>
			<tr>
                <td><label for="po_required">PO Required:</label></td>
                <td align = "left"><input type="checkbox" id="po_required" name="po_required" value="1" <?php echo ($po_required ? 'checked' : ''); ?>></td>
            </tr>
			<tr>
                <td><label for="waive_late_fee">Waive Late Fee:</label></td>
                <td align = "left"><input type="checkbox" id="waive_late_fee" name="waive_late_fee" value="1" <?php echo ($waive_late_fee ? 'checked' : ''); ?>></td>
            </tr>
 			<tr>
                <td><label for="waive_interest">Waive Interest:</label></td>
                <td align = "left"><input type="checkbox" id="waive_interest" name="waive_interest" value="1" <?php echo ($waive_interest ? 'checked' : ''); ?>></td>
            </tr>
 
            <tr>
                <td><label for="deactivated">Background Check Required:</label></td>
                <td style="text-align: left; padding-left: 0;"><input type="checkbox" id="background" name="background" value="1" <?php echo ($background ? 'checked' : ''); ?>></td>
            </tr>

            <tr>
                <td><label for="deactivated">Drug Test Required:</label></td>
                <td style="text-align: left; padding-left: 0;"><input type="checkbox" id="drug" name="drug" value="1" <?php echo ($drug ? 'checked' : ''); ?>></td>
            </tr>
           <tr>
                <td><label for="deactivated">Drug & Background Expiry Period (months):</label></td>
                <td style="text-align: left; padding-left: 0;"><input type="text" id="drug_back_period" name="drug_back_period" value="<?php echo $drug_back_period ?? '0'; ?>"></td>
            </tr>

            <tr>
                <td><label for="deactivated">Deactivated:</label></td>
                <td style="text-align: left; padding-left: 0;"><input type="checkbox" id="deactivated" name="deactivated" value="1" <?php echo ($deactivated ? 'checked' : ''); ?>></td>
            </tr>
            <tr>
                <td>
                    <input type="hidden" name="contactId" value="<?php echo htmlspecialchars($contactId); ?>">
                    <input type="submit" class="custom-button" value="Save">
                </td>
                <td>
                    <button type="button" class="custom-button" onclick="closeWindow();">Cancel</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
