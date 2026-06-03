<?php
// Database connection details
$servername = "localhost";
$username = "re0nm8";
$password = "50h8r6WNvB!ozVY2";
$dbname = "ck3b2t";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$organization = isset($_REQUEST['company']) ? $_REQUEST['company'] : '';

$full_name = $display_name = $email = $company_name = $icreativesportalaccess = $accountspayable = $address1 = $address2 = $postalcode = $city = $state = $country = $phone_number = $ap_template = $full_name_on_invoice = $one_invoice_per_candidate = $terms = $billing_cycle = $created_at = $encrypted_password = $deactivated = '';

// Check if record exists
if (!empty($organization)) {
    $sql = "SELECT * FROM ic_company WHERE organization = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $organization);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Record exists, fetch data
        $row = $result->fetch_assoc();
        $full_name = $row['full_name'];
        $display_name = $row['display_name'];
        $email = $row['email'];
        $company_name = $row['company_name'];
        $icreativesportalaccess = $row['icreativesportalaccess'];
        $accountspayable = $row['accountspayable'];
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
        $created_at = $row['created_at'];
        $encrypted_password = $row['encrypted_password'];
        $deactivated = $row['deactivated'];
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $display_name = $_POST['display_name'];
    $email = $_POST['email'];
    $company_name = $_POST['company_name'];
    $icreativesportalaccess = isset($_POST['icreativesportalaccess']) ? 1 : 0;
    $accountspayable = isset($_POST['accountspayable']) ? 1 : 0;
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $postalcode = $_POST['postalcode'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $phone_number = $_POST['phone_number'];
    $ap_template = $_POST['ap_template'];
    $full_name_on_invoice = isset($_POST['full_name_on_invoice']) ? 1 : 0;
    $one_invoice_per_candidate = isset($_POST['one_invoice_per_candidate']) ? 1 : 0;
    $terms = $_POST['terms'];
    $billing_cycle = $_POST['billing_cycle'];
    $created_at = $_POST['created_at'];
    $encrypted_password = $_POST['encrypted_password'];
    $deactivated = isset($_POST['deactivated']) ? 1 : 0;

    if (!empty($organization)) {
        // Insert or update record using ON DUPLICATE KEY UPDATE
        $sql = "INSERT INTO ic_company (organization, full_name, display_name, email, company_name, icreativesportalaccess, accountspayable, address1, address2, postalcode, city, state, country, phone_number, ap_template, full_name_on_invoice, one_invoice_per_candidate, terms, billing_cycle, created_at, encrypted_password, deactivated) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                full_name = VALUES(full_name), 
                display_name = VALUES(display_name), 
                email = VALUES(email), 
                company_name = VALUES(company_name), 
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
                created_at = VALUES(created_at), 
                encrypted_password = VALUES(encrypted_password), 
                deactivated = VALUES(deactivated)";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssiiissssssisssisssi", $organization, $full_name, $display_name, $email, $company_name, $icreativesportalaccess, $accountspayable, $address1, $address2, $postalcode, $city, $state, $country, $phone_number, $ap_template, $full_name_on_invoice, $one_invoice_per_candidate, $terms, $billing_cycle, $created_at, $encrypted_password, $deactivated);

        if ($stmt->execute()) {
            echo "<script>
                alert('Record saved successfully');
                setTimeout(function() { window.close(); }, 5000);
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
    <title>Company Form</title>
</head>
<body>
    <h2>Company Form</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table>
            <tr>
                <td><label for="full_name">Full Name:</label></td>
                <td><input type="text" id="full_name" name="full_name" value="<?php echo $full_name; ?>" required></td>
            </tr>
            <tr>
                <td><label for="display_name">Display Name:</label></td>
                <td><input type="text" id="display_name" name="display_name" value="<?php echo $display_name; ?>" required></td>
            </tr>
            <tr>
                <td><label for="email">Email:</label></td>
                <td><input type="email" id="email" name="email" value="<?php echo $email; ?>" required></td>
            </tr>
            <tr>
                <td><label for="company_name">Company Name:</label></td>
                <td><input type="text" id="company_name" name="company_name" value="<?php echo $company_name; ?>" required></td>
            </tr>
            <tr>
                <td><label for="icreativesportalaccess">ICreatives Portal Access:</label></td>
                <td><input type="checkbox" id="icreativesportalaccess" name="icreativesportalaccess" value="1" <?php echo ($icreativesportalaccess ? 'checked' : ''); ?>></td>
            </tr>
            <tr>
                <td><label for="accountspayable">Accounts Payable:</label></td>
                <td><input type="checkbox" id="accountspayable" name="accountspayable" value="1" <?php echo ($accountspayable ? 'checked' : ''); ?>></td>
            </tr>
            <tr>
                <td><label for="address1">Address 1:</label></td>
                <td><input type="text" id="address1" name="address1" value="<?php echo $address1; ?>"></td>
            </tr>
            <tr>
                <td><label for="address2">Address 2:</label></td>
                <td><input type="text" id="address2" name="address2" value="<?php echo $address2; ?>"></td>
            </tr>
            <tr>
                <td><label for="postalcode">Postal Code:</label></td>
                <td><input type="text" id="postalcode" name="postalcode" value="<?php echo $postalcode; ?>"></td>
            </tr>
            <tr>
                <td><label for="city">City:</label></td>
                <td><input type="text" id="city" name="city" value="<?php echo $city; ?>"></td>
            </tr>
            <tr>
                <td><label for="state">State:</label></td>
                <td><input type="text" id="state" name="state" value="<?php echo $state; ?>"></td>
            </tr>
            <tr>
                <td><label for="country">Country:</label></td>
                <td><input type="text" id="country" name="country" value="<?php echo $country; ?>" required></td>
            </tr>
            <tr>
                <td><label for="phone_number">Phone Number:</label></td>
                <td><input type="text" id="phone_number" name="phone_number" value="<?php echo $phone_number; ?>" required></td>
            </tr>
            <tr>
                <td><label for="ap_template">AP Template:</label></td>
                <td><input type="text" id="ap_template" name="ap_template" value="<?php echo $ap_template; ?>"></td>
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
                <td><input type="text" id="terms" name="terms" value="<?php echo $terms; ?>"></td>
            </tr>
            <tr>
                <td><label for="billing_cycle">Billing Cycle:</label></td>
                <td><input type="text" id="billing_cycle" name="billing_cycle" value="<?php echo $billing_cycle; ?>"></td>
            </tr>
            <tr>
                <td><label for="created_at">Created At:</label></td>
                <td><input type="date" id="created_at" name="created_at" value="<?php echo $created_at; ?>" required></td>
            </tr>
            <tr>
                <td><label for="encrypted_password">Encrypted Password:</label></td>
                <td><input type="password" id="encrypted_password" name="encrypted_password" value="<?php echo $encrypted_password; ?>"></td>
            </tr>
            <tr>
                <td><label for="deactivated">Deactivated:</label></td>
                <td><input type="checkbox" id="deactivated" name="deactivated" value="1" <?php echo ($deactivated ? 'checked' : ''); ?>></td>
            </tr>
        </table>
        
        <input type="hidden" name="organization" value="<?php echo $organization; ?>">
        
        <input type="submit" value="Save">
        <button type="button" onclick="window.close();">Cancel</button>
    </form>
</body>
</html>

