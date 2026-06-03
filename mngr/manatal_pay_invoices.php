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


echo "<h1>MANATAL PAY INVOICES</h1>";
// Include your database connection code here
require_once __DIR__ . '/../db/db.php';
$conn = db(); 
	
?>
<form method="post">
Company #:  <input type="text" id="company" name="company" required >
<input type="submit" value="Filter Invoices">
</form><p>
<?php

// Handle form submission for paying multiple rows
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form has been submitted
    if (isset($_POST["pay"])) {
        // Loop through the submitted data to pay rows
        foreach ($_POST["pay"] as $invoice_number => $new_values) {
			if (isset($new_values['pay']) && $new_values['pay'] == 1) {
            // Escape and sanitize the values to prevent SQL injection
            // $Unique_id = mysqli_real_escape_string($conn, $new_values["Unique_id"]);
			// $Unique_id = mysqli_real_escape_string($conn, $Unique_id);
			$invoice_number = mysqli_real_escape_string($conn, $invoice_number);
 
       		// $invoice_number = mysqli_real_escape_string($conn, $new_values["invoice_number"]);
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
			$inv_amount = mysqli_real_escape_string($conn, $new_values["inv_amount"]);
			$invoice_export = mysqli_real_escape_string($conn, $new_values["invoice_export"]);
			$paid_amount = mysqli_real_escape_string($conn, $new_values["paid_amount"]);
			$void = mysqli_real_escape_string($conn, $new_values["void"]);		

            // Construct and execute the SQL query to ` the row
            // $sql = "UPDATE invoices SET invoice_number='$new_invoice_number', other_data='$new_other_data' WHERE Unique_id='$Unique_id'";
			
            $sql = "UPDATE ic_timesheets SET 
			paid_date ='$paid_date', 
			paid_amount = paid_amount + $paid_amount 
			WHERE invoice_number = '$invoice_number'";
			// echo $sql;
			// exit();
			If ($paidate < '2000-01-01' && $paid_amount < 1 && $invoice_number <> '') {
				echo "<script type='text/javascript'>alert('Invoice ".$invoice_number." missing date or amount');</script>";
				exit();
			} else {
				 $result = $conn->query($sql);
			}

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

// Retrieve data from the database for paying
$sql = "SELECT 
	company_name, 
	invoice_number, invoice_date, 
	paid_amount, 
	paid_date,
	terms, 
	SUM(CAST(billrate AS DECIMAL(18,4)) * CAST(Hours AS DECIMAL(18,4))) AS inv_amount, 
	COALESCE(MAX(paid_amount), 0) AS paid_amt, 	
	Unique_id 
	FROM ic_timesheets ";
	if(!empty($_REQUEST['company'])) {
		$sql = $sql . "WHERE invoice_number > 0 AND (void IS FALSE OR void ='' OR void IS NULL) AND company_name like '%".$_REQUEST['company']."%' ";
	} else {
		$sql = $sql . "WHERE WHERE invoice_number > 0 AND void IS FALSE ";
	}
$sql = $sql . " GROUP by invoice_number HAVING inv_amount > paid_amt+1"; 

$result = $conn->query($sql);

// Check if there are rows in the result
if ($result->num_rows > 0) {
    echo "<form method='post' action=''>";
    echo "<table>";
    echo "	<tr><th>Invoice</th>
			<th>Company</th>
			<th>Terms</th>
			<th>Amount</th>
			<th>Date</th>
			<th>Paid Date</th>
			<th>Paid Amount</th>
			<th>Pay Amount</th>
			<th>Pay</th></tr>";

    while ($row = $result->fetch_assoc()) {
        // $Unique_id = $row["Unique_id"];
        $invoice_number = $row["invoice_number"];
		$inv_amount = $row["inv_amount"];
		$terms = $row["terms"];
		$BillingProfile = $row["BillingProfile"];
		$invoice_date = date("Y-m-d",strtotime($row["invoice_date"]));

		$paid_date = date("Y-m-d",strtotime($row["paid_date"]));
		$paid_amount = $row["paid_amt"];
		$amount_due = $row["inv_amount"] - $row["paid_amt"];

        // Create input fields for paying
        echo "<tr>"; 
        echo '<td><a href="javascript:void(0);" onclick="openPopup(' . "'https://www.icreatives.com/api/customer/view_invoice.php?invnum=".encrypt_string($invoice_number)."');" . '">' . $invoice_number . "</a>";	
		echo "<td>".$row['company_name']."</td>";
		echo "<td align='right'>".$row['terms']."</td>";
		echo "<td align ='right'>". round($row['inv_amount'],2)."</td>";
		echo "<td>".$row['invoice_date']."</td>";
		echo "<td><input type='date' name='pay[$invoice_number][paid_date]' value='".date("Y-m-d")."'></td>";
		echo "<td align='right'>". round($paid_amount,2)."</td>";
		echo "<td align='right'><input type='number'  step='0.01' style='width:100px;'  name='pay[$invoice_number][paid_amount]' value='". round($amount_due,2)."'></td>";
        echo "<td><input type='checkbox' name='pay[$invoice_number][pay]' value='1'></td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<input type='submit' value='Save Changes'>";
    echo "</form>";
} else {
	If ($paid_date < '2000-01-01' && $paid_date !== '0000-00-00' && $paid_amount > 0 && $invoice_number <> '') {
		echo "No invoices checked.";
	}
}

// Close the database connection
$conn->close();
?>
