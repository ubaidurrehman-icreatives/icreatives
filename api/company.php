<?php
// Database connection details

require_once __DIR__ . '/../db/db.php';
$conn = db();   


$organization = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $organization = isset($_POST['organization']) ? $_POST['organization'] : $organization;
}

$full_name = $display_name = $drug = $background = $drug_back_period = $email = $company_name = $vendor_number = $icreativesportalaccess = $accountspayable = $address1 = $address2 = $postalcode = $city = $state = $country = $phone_number = $ap_template = $full_name_on_invoice = $one_invoice_per_candidate = $terms = $billing_cycle = $created_at = $encrypted_password = $deactivated = '';

// Check if record exists
if (!empty($organization)) {
    $sql = "SELECT * FROM ic_company WHERE organization = $organization";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Record exists, fetch data
        $row = $result->fetch_assoc();
        $full_name = $row['full_name'];
        $display_name = $row['full_name'];
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
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $display_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $company_name = $conn->real_escape_string($_POST['company_name']);
    $vendor_number = $_POST['vendor_number'];
    // $icreativesportalaccess = isset($_POST['icreativesportalaccess']) ? 1 : 0;
    $icreativesportalaccess = 1;
    // $accountspayable = isset($_POST['accountspayable']) ? 1 : 0;
    $accountspayable = 1;
    $address1 = $conn->real_escape_string($_POST['address1']);
    $address2 = $conn->real_escape_string($_POST['address2']);
    $postalcode = $conn->real_escape_string($_POST['postalcode']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $country = $conn->real_escape_string($_POST['country']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $ap_template = $conn->real_escape_string($_POST['ap_template']);
    $full_name_on_invoice = isset($_POST['full_name_on_invoice']) ? 1 : 0;
    $one_invoice_per_candidate = isset($_POST['one_invoice_per_candidate']) ? 1 : 0;
    $terms = $_POST['terms'] ?? 10;
    $billing_cycle = $_POST['billing_cycle'];
	$po_required      = isset($_POST['po_required']) ? 1 : 0;
	$waive_late_fee   = isset($_POST['waive_late_fee']) ? 1 : 0;
	$waive_interest   = isset($_POST['waive_interest']) ? 1 : 0; // <-- correct name/key
	$background       = isset($_POST['background']) ? 1 : 0;
	$drug             = isset($_POST['drug']) ? 1 : 0;
    $background = isset($_POST['background']) ? 1 : 0;
    $drug_back_period= $_POST['drug_back_period'] ?? 0;
    // $created_at = $_POST['created_at']; 
    $encrypted_password = $conn->real_escape_string($_POST['encrypted_password']);
    $deactivated = $conn->real_escape_string(isset($_POST['deactivated']) ? 1 : 0);

    if (!empty($organization)) {
        // Insert or update record using ON DUPLICATE KEY UPDATE
        $sql = "INSERT INTO ic_company (organization, full_name, display_name, email, company_name,vendor_number, icreativesportalaccess, accountspayable, address1, address2, postalcode, city, state, country, phone_number, ap_template, full_name_on_invoice, one_invoice_per_candidate, terms, billing_cycle, po_required, waive_late_fee, waive_interest, drug, background, drug_back_period, created_at, encrypted_password, deactivated) 
                VALUES ($organization, '$full_name', '$display_name', '$email', '$company_name', '$vendor_number', $icreativesportalaccess, $accountspayable, '$address1', '$address2', '$postalcode', '$city', '$state', '$country', '$phone_number', '$ap_template', $full_name_on_invoice, $one_invoice_per_candidate, '$terms', '$billing_cycle', '$po_required','$waive_late_fee','$waive_interest',  '$drug', '$background', '$drug_back_period','$created_at', '$encrypted_password', $deactivated)
                ON DUPLICATE KEY UPDATE 
                full_name = VALUES(full_name), 
                display_name = VALUES(full_name), 
                email = VALUES(email), 
                company_name = VALUES(company_name), 
                vendor_number = VALUES(vendor_number), 
                icreativesportalaccess = VALUES(icreativesportalaccess), 
                accountspayable = VALUES(accountspayable), 
                address1 = VALUES(address1), 
                address2 = VALUES(address2), 
                postalcode = VALUES(postalcode), 
                city = VALUES(city), 
                state = VALUES(state), 
                country = VALUES(country), 
                phone_number = VALUES(phone_number), 
                ap_template = VALUES(ap_template), 
                full_name_on_invoice = VALUES(full_name_on_invoice), 
                one_invoice_per_candidate = VALUES(one_invoice_per_candidate), 
                terms = VALUES(terms), 
                billing_cycle = VALUES(billing_cycle), 
				po_required = VALUES(po_required), 
				waive_late_fee = VALUES(waive_late_fee), 
				waive_interest = VALUES(waive_interest), 
				drug = VALUES(drug), 
				background = VALUES(background), 
				drug_back_period = VALUES(drug_back_period), 
                created_at = VALUES(created_at), 
                encrypted_password = VALUES(encrypted_password), 
                deactivated = VALUES(deactivated)";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                window.onload = function() {
                    showCustomAlert('Billing Info Saved');
                };
            </script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
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
                    <input type="hidden" name="organization" value="<?php echo htmlspecialchars($organization); ?>">
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
