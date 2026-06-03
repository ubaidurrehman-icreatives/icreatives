<?php include 'manatal_header.php'; ?>
<script>
        function openPopup(url) {
            // Specify the size and position of the window
            var width = 800;
            var height = 850;
            var left = (screen.width - width) / 2;
            
            // Adjust the top value to move the popup higher on the page
            var top = (screen.height - height) / 2; // Adjust this value as needed

            // Open the pop-up window with the provided URL
            var popup = window.open(url, 'PopupWindow', 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top);

            // Focus the pop-up window (optional)
            popup.focus();
        }
    </script>
	

<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
?>
<?php
function encrypt_string($plaintext) {

$key = "4b14783a4b5848cd43b9fc7e4cf0fb6f";

// $key = 'YOUR_SALT_KEY'; // Previously generated safely, ie: openssl_random_pseudo_bytes 
 //$plaintext = "String to be encrypted"; 
 
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
$iv = openssl_random_pseudo_bytes($ivlen); 
$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true); 
 
// Encrypted string 
return base64_encode($iv.$hmac.$ciphertext_raw);
}


// echo $ciphertext = encrypt_string("12345");
$key = "4b14783a4b5848cd43b9fc7e4cf0fb6f"; // Previously used in encryption 
$c = base64_decode($ciphertext); 
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
$iv = substr($c, 0, $ivlen); 
$hmac = substr($c, $ivlen, $sha2len=32); 
$ciphertext_raw = substr($c, $ivlen+$sha2len); 
echo $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 


echo "<h1>MANATAL EDIT INVOICE</h1>";
// Include your database connection code here
require_once __DIR__ . '/../db/db.php';
$conn = db(); 
	
?>
<form method="post">
Invoice #:  <input type="number" id="startinvoice" name="startinvoice" value = 0 required >
<!-- End Invoice #:  <input type="number" id="endinvoice" name="endinvoice" value = 0 required > -->
<input type="submit" value="Filter Invoices">
</form><p>
<?php

// Handle form submission for editing multiple rows
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form has been submitted
    if (isset($_POST["edit"])) {
        // Loop through the submitted data to edit rows
        foreach ($_POST["edit"] as $Unique_id => $new_values) {
			if (isset($new_values['edit']) && $new_values['edit'] == 1) {
            // Escape and sanitize the values to prevent SQL injection
            // $Unique_id = mysqli_real_escape_string($conn, $new_values["Unique_id"]);
			$Unique_id = mysqli_real_escape_string($conn, $Unique_id);
            // $new_invoice_number = mysqli_real_escape_string($conn, $new_values["invoice_number"]);
			$invoice_number = mysqli_real_escape_string($conn, $new_values["invoice_number"]);
            		$title = mysqli_real_escape_string($conn, $new_values["title"]);
			$billrate = mysqli_real_escape_string($conn, $new_values["billrate"]);
			$payrate = mysqli_real_escape_string($conn, $new_values["payrate"]);
			$BillingProfile = mysqli_real_escape_string($conn, $new_values["BillingProfile"]);
			$Continuing = mysqli_real_escape_string($conn, $new_values["Continuing"]);
			$Hours = mysqli_real_escape_string($conn, $new_values["Hours"]);
			$invoice_date = mysqli_real_escape_string($conn, $new_values["invoice_date"]);
			$ExportDate = mysqli_real_escape_string($conn, $new_values["ExportDate"]);
			$terms = mysqli_real_escape_string($conn, $new_values["terms"]);
			$paid_date = mysqli_real_escape_string($conn, $new_values["paid_date"]);
			$invoice_amount = mysqli_real_escape_string($conn, $new_values["invoice_amount"]);
			$invoice_export = mysqli_real_escape_string($conn, $new_values["invoice_export"]);
			$paid_amount = mysqli_real_escape_string($conn, $new_values["paid_amount"]) ?? 0.00;
			$void = mysqli_real_escape_string($conn, $new_values["void"]);		
			$Primary_Contact_Email = mysqli_real_escape_string($conn, $new_values["Primary_Contact_Email"]);		
			$Second_Contact_Email = mysqli_real_escape_string($conn, $new_values["Second_Contact_Email"]);		

            // Construct and execute the SQL query to ` the row
            // $sql = "UPDATE invoices SET invoice_number='$new_invoice_number', other_data='$new_other_data' WHERE Unique_id='$Unique_id'";
if ($paid_date = " "){$paid_date = "0000-00-00";}
	
	if (!isset($paid_amount) || empty($paid_amount))  {$paid_amount = 0;}
            $sql = "UPDATE ic_timesheets SET 
			title ='$title', 
			billrate =$billrate, 
			payrate = $payrate, 
			BillingProfile ='$BillingProfile', 
			Primary_Contact_Email ='$Primary_Contact_Email', 
			Second_Contact_Email ='$Second_Contact_Email', 
			Hours =$Hours, 
			invoice_date ='$invoice_date', 
			terms =$terms, 
			paid_date ='$paid_date', 
			paid_amount =$paid_amount, 
			void =$void 
			WHERE Unique_id='$Unique_id'";

            $result = $conn->query($sql);

            // Check if the query was successful
            if ($result) {
                echo "<font color = 'red'>Row with Title: $title updated successfully.</font><br>";
            } else {
                echo "Error updating row with with Title: $title " . $conn->error . "<br>";
            }
			}
        }
    }
}

// Retrieve data from the database for editing
$sql = "SELECT * FROM ic_timesheets ";
$sql = $sql . "WHERE invoice_number > 0 AND invoice_number= ".$_REQUEST['startinvoice']." ";
$sql = $sql . " order by invoice_number ASC LIMIT 50";

$result = $conn->query($sql);

// Check if there are rows in the result
if ($result->num_rows > 0) {
    echo "<form method='post' action=''>";
    echo "<table>";
    echo "	<tr><th>Invoice No</th>
			<th>Wk Wnd</th>
			<th>Name</th>
			<th>Company</th>
			<th>Title</th>
			<th>Bill</th>
			<th>Pay</th>
			<th>Hours</th>
			<th>Terms</th>
			<th>Profile</th>
			<th>Contact 1</th>
			<th>Contact 2</th>
			<th>InvDate</th>		
			<th>AmtPaid</th>
			<th>Paid</th>
			<th>Void</th>
			<th>Edit</th></tr>";

    while ($row = $result->fetch_assoc()) {
        $Unique_id = $row["Unique_id"];
        $invoice_number = $row["invoice_number"];
        $title = $row["title"];
		$billrate = $row["billrate"];
		$payrate = $row["payrate"];
		$Hours = $row["Hours"];
		$invoice_amount = $row["invoice_amount"];
		$terms = $row["terms"];
		$BillingProfile = $row["BillingProfile"];
		$invoice_date = date("Y-m-d",strtotime($row["invoice_date"]));
		$Primary_Contact_Email = $row["Primary_Contact_Email"];
		$Second_Contact_Email = $row["Second_Contact_Email"];

		$paid_amount = $row["paid_amount"] ?? 0.00;
		$paid_date = date("Y-m-d",strtotime($row["paid_date"]));
		$paid_amount = $row["paid_amount"];
		$void = $row["void"];
		
		// echo $url = "https://app.tempback.com/mngr/manatal_view_invoice.php?invnum=".encrypt_string($invoice_number);
// <a href="javascript:void(0);" onclick="openPopup('https://example.com');">Open Pop-up Window</a>

        // Create input fields for editing
        echo "<tr>"; 
        echo '<td><a href="javascript:void(0);" onclick="openPopup(' . "'https://WWW.icreatives.com/api/customer/view_invoice.php?invnum=".encrypt_string($invoice_number)."');" . '">' . $invoice_number . "</a>";	
		
		// ". $ur");">$invoice_number</td>';
		echo "<td>". date("Y-m-d",strtotime($row["WeekEnding"]))."</td>";
		echo "<td>".$row['company_name']."</td>";
		echo "<td>".$row['first_name']." ".$row['last_name']."</td>";
        echo "<td><input type='text' name='edit[$Unique_id][title]' value='$title'></td>";
		echo "<td><input type='number'  step='0.01' style='width:50px;'  name='edit[$Unique_id][billrate]' value='$billrate'></td>";
		echo "<td><input type='number'  step='0.01' style='width:50px;'  name='edit[$Unique_id][payrate]' value='$payrate'></td>";
		echo "<td><input type='number' step='0.01' style='width:50px;' name='edit[$Unique_id][Hours]' value='$Hours'></td>";
		echo "<td><input type='number' style='width:50px;'  name='edit[$Unique_id][terms]' value='$terms'></td>";
		echo "<td><input type='text' style='width:50px;'  name='edit[$Unique_id][BillingProfile]' value='$BillingProfile'></td>";
		echo "<td><input type='email' style='width:150px;'  name='edit[$Unique_id][Primary_Contact_Email]' value='$Primary_Contact_Email'></td>";
		echo "<td><input type='email' style='width:150px;'  name='edit[$Unique_id][Second_Contact_Email]' value='$Second_Contact_Email'></td>";
		echo "<td><input type='date' name='edit[$Unique_id][invoice_date]' value='$invoice_date'></td>";

		echo "<td><input type='number' step='0.01' style='width:100px;' name='edit[$Unique_id][paid_amount]' value='$paid_amount'></td>";
		echo "<td><input type='date' name='edit[$Unique_id][paid_date]' value='$paid_date'></td>";
		echo "<td><select name='edit[$Unique_id][void]' id='void'>"; ?>
    			<option value='0' <?php if (isset($void) && $void == 0) { echo "selected"; } ?>> </option>
    			<option value='1' <?php if (isset($void) && $void == 1) { echo "selected"; } ?>>Void</option>
		<?php echo "</select></td>";


		// echo "<td><input type='text' size='1' name='edit[$Unique_id][void]' value='$void'></td>";
        echo "<td><input type='checkbox' name='edit[$Unique_id][edit]' value='1'></td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<input type='submit' value='Save Changes'>";
    echo "</form>";
} else {
    echo "No invoices found.";
}

// Close the database connection
$conn->close();
?>
