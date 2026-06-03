<a href="/icreatives-2/apply" role="button" aria-disabled="true" class="btn btn-lg bg-primary-color text-white">
          Apply now
        </a><?php include 'manatal_header.php'; ?>
<?php	
echo "<h1>MANATAL CREATE INVOICES</h1>";
// Connect to the MySQL database

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

if (!$link) {
    die('Connection failed: ' . mysqli_connect_error());
}
// find last invoice number
	
function Contact_Info($link, $organization) {
    $f_query = "select full_name, terms, email, phone_number, one_invoice_per_candidate,address1,address2,city,state,postalcode,country,created_at from ic_contacts where organization = '" . $organization . "' AND accountspayable IS TRUE";
    $SQL = mysqli_query($link, $f_query);
    if (!$SQL) {
        die('Query failed: ' . mysqli_error($link));
    }
    $row3 = mysqli_fetch_array($SQL);

    $infoArray = array(
        'full_name' => $row3['full_name'],
        'terms' => $row3['terms'],
		'address1' => $row3['address1'],
		'address2' => $row3['address2'],
        'email' => $row3['email'],
		'city' => $row3['city'],
		'state' => $row3['state'],
		'postalcode' => $row3['postalcode'],
		'country' => $row3['country'],
		'phone_number' => $row3['phone_number'],
		'created_at' => $row3['created_at'],
		'terms' => $row3['terms'],
        'one_invoice_per_candidate' => $row3['one_invoice_per_candidate']
    );

    return $infoArray;
}

$rowSQL = mysqli_query( $link,"SELECT MAX( invoice_number ) AS max FROM `ic_timesheets`where void = 0 or void='' or void = NULL;" );
$row = mysqli_fetch_array( $rowSQL );
$nextinvoice = $row['max']+1;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$nextinvoice = $_REQUEST['nextinvoice'];
}
// echo "Next Invoice = ". strval($nextinvoice). "<br>";
?>

<form method="post">
Week Ending:  <input type="date" id="week_ending" name="week_ending" > 
Company:  <input type="text" id="company_name" name="company_name" > 
Billing Cycle:  
<select name="billing_cycle" id="billing_cycle">
	<option value="All" selected>All</option>
	<option value="Weekly">Weekly</option>
	<option value="Monthly">Monthly</option>
</select>

<input type="submit" value="Submit">
<P>

<?php

$strSQL = "SELECT  'TIMEACT' as '!TIMEACT' ";
$strSQL = $strSQL . ", wt.first_name as FIRST ";
$strSQL = $strSQL . ", wt.last_name as LAST ";
$strSQL = $strSQL . ", wt.Employee_ID as EMPID ";
$strSQL = $strSQL . ", wt.billrate as BILLRATE ";
$strSQL = $strSQL . ", wt.payrate as PAYRATE ";
$strSQL = $strSQL . ", wt.Hours as HOURS ";
$strSQL = $strSQL . ", oj.organization as JOB ";
$strSQL = $strSQL . ", wt.company_name as COMPANY ";
$strSQL = $strSQL . ", oj.po_number as PO ";
$strSQL = $strSQL . ", wt.invoice_type as TYPE ";
$strSQL = $strSQL . ", wt.title as ITEM ";
$strSQL = $strSQL . ", wt.AssignmentNumber as PROJ ";
$strSQL = $strSQL . ", wt.AssignmentNumber as xORDER ";
// $strSQL = $strSQL . ", oj.Primary_Contact_ID as ORDERTAKENID ";
// $strSQL = $strSQL . ", oj.Second_Contact_ID as REPORTID ";
// $strSQL = $strSQL . ", oj.Primary_Contact_First as PFIRST "; 
// $strSQL = $strSQL . ", oj.Primary_Contact_Last as PLAST "; 
$strSQL = $strSQL . ", wt.Primary_Contact_Email as email ";
// $strSQL = $strSQL . ", oj.Second_Contact_First as SFIRST ";
// $strSQL = $strSQL . ", oj.Second_Contact_Last as SLAST ";
$strSQL = $strSQL . ", wt.Second_Contact_Email as semail  ";
$strSQL = $strSQL . ", oj.ap_email as APEMAIL  ";
$strSQL = $strSQL . ", wt.paid_amount as PAIDAMOUNT  ";
$strSQL = $strSQL . ", wt.Unique_id as UNUMB  ";
$strSQL = $strSQL . ", '40' as DURATION ";
$strSQL = $strSQL . ", '1' as BILLINGSTATUS ";
$strSQL = $strSQL . ", 'CONTR' as PITEM ";
$strSQL = $strSQL . ", '0' as BITEM ";
$strSQL = $strSQL . ", 'Yes' as Contract "; 

for ($i = 1; $i <= 7; $i++) {

 	$strSQL = $strSQL . ", wt.TimeInHr" . $i . " as INHR" . $i . " ";
	$strSQL = $strSQL . ", wt.TimeOutHr" . $i . " as OUTHR" . $i . " ";
	$strSQL = $strSQL . ", wt.Break" . $i . " as BREAK" . $i . " ";
}
$strSQL = $strSQL . ", wt.Continuing as CONT " ;
$strSQL = $strSQL . ", wt.ApproveDate as APPROVE " ;
$strSQL = $strSQL . ", wt.SentDate as SENT " ;
$strSQL = $strSQL . ", wt.DeclineDate as DECLINE " ;
$strSQL = $strSQL . ", wt.Weekending as WKEND " ;
$strSQL = $strSQL . ", wt.invoice_number as INVNUM " ;
$strSQL = $strSQL . ", wt.billing_cycle as CYCLE " ;
$strSQL = $strSQL . "from ic_timesheets wt ";

$strSQL = $strSQL . "LEFT JOIN ic_matches oj ON (oj.candidate = wt.employee_id AND oj.job = wt.AssignmentNumber) ";
// $strSQL = $strSQL . "LEFT JOIN ic_contacts c ON c.organiztion = oj.organization ";
// PUT BACK !! $strSQL = $strSQL . "WHERE wt.void = FALSE AND (wt.invoice_number < 1 OR wt.invoice_number IS NULL) AND wt.ApproveDate <> '0000-00-00 00:00:00' AND wt.ExportDate <> '0000-00-00 00:00:00'" ;

// AND wt.ExportDate <> '0000-00-00 00:00:00'";
$strSQL = $strSQL . " WHERE billrate > 0 AND NOT VOID AND wt.ExportDate <> '0000-00-00 00:00:00' AND (wt.invoice_number IS NULL OR wt.invoice_number = 0) ";

if (!empty($_REQUEST['company_name'])) {
	$strSQL = $strSQL . " AND wt.company_name like '%". $_REQUEST['company_name'] . "%' ";
}
if (!empty($_REQUEST['week_ending'])) {
	$strSQL = $strSQL . " AND WKEND = '". $_REQUEST['week_endng'] . "' ";
}
if ($_REQUEST['billing_cycle'] == "Monthly") {
	$strSQL = $strSQL . " AND wt.billing_cycle = 'Monthly' ";
}
if ($_REQUEST['billing_cycle'] == "Weekly") {
	$strSQL = $strSQL . " AND (wt.billing_cycle = 'Weekly' or wt.billing_cycle IS NULL) ";
}



$strSQL2 = $strSQL . " ORDER BY COMPANY ASC, APEMAIL ASC, EMPID ASC, WKEND ASC";
// echo $strSQL2;
// Query the database for the list of recipients
$result = mysqli_query($link,$strSQL2);	

/*
Function find_job_contacts($job) {
		require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
	$client = new \GuzzleHttp\Client();

	$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/'.$job.'/', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);

	$response->getBody();

	$responseStr = $response->getBody();
	$job = json_decode($responseStr, true);
	$Primary_Contact_Email = $job['custom_fields']['timeapproveremail'];
	$Second_Contact_Email =  $job['custom_fields']['timeapproveremail_b'];
	return ($Primary_Contact_Email);
}
*/

$invnum = $nextinvoice-1;

// Start the HTML form
echo '<p><form method="post">';
echo 'Start Invoice #:  <input type="number" id="nextinvoice" name="nextinvoice" value = '. $nextinvoice. ' required >';
echo '  <input type="submit" value="Submit">';
echo '<br>';
// Output the table headers
echo '<table>';
echo "<thead><tr><th><input type='checkbox' id='check_all'></th><th><P>Check All</p></th></tr></thead>";
echo '<tr align="center">
<th> </th>
<th>Talent</th>
<th>Position</th>
<th>Client</th>
<th>Hours</th>
<th>Bill Rate</th>
<th>Inv Amt</th>
<th>Pay Rate</th>
<th>Pay Amt</th>
<th>Wk Ending</th>
<th>Hours</th>
<th>Approve Date</th>
<th>Job ID</th>
<th>Emp ID</th>
<th>Cycle</th>
</tr>';

// Loop through the results and output each row
$invnum = $nextinvoice-1;
while ($row = mysqli_fetch_array($result)) {
	$approved_date = strtotime( $row['APPROVE']);
	$week_ending = strtotime( $row['WKEND']);
    echo '<tr align="center">';
    echo '<td>';
	if (!is_null($row['JOB'])){
	echo '<input type="checkbox" name="recipients[]" value="' . $row['UNUMB'] . '">';
	}
	echo '</td>';
    echo '<td ALIGN="LEFT" >' . $row['FIRST'] . ' ' .$row['LAST'] . '</td>';
	echo '<td ALIGN="LEFT">' . $row['ITEM'] .  '</td>';
	echo '<td ALIGN="LEFT">';
		if (!is_null($row['JOB'])) {
			echo "<a target = '_blank' href = 'https://app.manatal.com/clients/" . $row['JOB']."'>" . $row['COMPANY']. "</a>";
		} else {
			echo '<font color="red"><b>RE-OPEN JOB</b></font>';
		}
		echo "</td>";
	echo "<td>" . $row['HOURS'] . "</td>";
	echo "<td>$" . $row['BILLRATE'] . "/hr</td>";
	echo "<td>$" . ROUND($row['BILLRATE'] * $row['HOURS'],2) . "</td>";
	echo "<td>$" . $row['PAYRATE'] . "/hr</td>";
	echo "<td>$" . ROUND($row['PAYRATE'] * $row['HOURS'],2) . "</td>";
	echo "<td>" . date("m/d/Y",$week_ending) . '</td>';
	echo "<td>".  $row["HOURS"]."</td>";
    echo "<td>" . date("m/d/Y",$approved_date) . '</td>';
	echo "<td><a target = '_blank' href = 'https://app.manatal.com/jobs/" . $row['PROJ']."'>" . $row['PROJ']. "</a></td>";
	echo "<td><a target = '_blank' href = 'https://app.manatal.com/candidates/" . $row['EMPID']."'>" . $row['EMPID']. "</a></td>";
	echo "<td>" . $row['CYCLE'] . "</td>";
    echo '</tr>';
	$invnum = $invnum + 1;
}

// Close the table and add the submit button
echo '</table><p>';
// echo '<input type="hidden" name = "billing_cycle" value = "'.$_REQUEST['billing_cycle']."'";
echo '<input type="submit" value="Download and Mark">';
echo '</form>';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	// *** Start Customer Upload File Section ***
	
	$recipients = $_POST['recipients'];
	
	 // Define the CSV file name and path
	$customerfile = 'selected_customers.csv';
	$customerpath = '' . $customerfile;

	// Open the CSV file for writing
	$file = fopen($customerpath, 'w');
  
	// Open file for appending
	$file = fopen($customerfile, "a");
	
	// make header
	 $text = '"Name","Company","Customer Type","Email","Phone","Mobile","Fax","Website","Street","City","State","Zip","Country","Opening Balance","Date","Resale Number"'. PHP_EOL;

	 fwrite($file, $text);

    // Loop through the recipients and build the file
	$customerfile = "customer.txt";
	$org_test = "test";
	$ap_test = "test";
	// echo "count = ".count($recipients);
	if(is_array($recipients)) {
	for($x=0; $x<count($recipients); $x++) {
 		$query2 = $strSQL . " AND wt.Unique_id= '". $recipients[$x]."'";
		// echo $query2;
		$result2 = mysqli_query($link,$query2);
		if (!$result2) {
			die('Query failed: ' . mysqli_error($link));
		}

		$row3 = mysqli_fetch_array($result2);
		$organization = $row3['JOB'];
		If ($org_test !== $row3['JOB'] && $ap_test !== $row3['APEMAIL']) { 
		//full_name,terms'],address1,address2,email,city,state,postalcode,country,one_invoice_per_candidate;
			$contact_array = Contact_Info($link,$organization);
			// list($full_name,$terms,$email,$oneper) = $contact_array;;
			// echo "XXX".$contact_array['created_at']."XXX";
			$org_test = $row2['JOB'];
			$ap_test = $row2['APEMAIL'];
			// write formula for duedate here
			// $duedate = date('Y-m-d', strtotime($Date . ' + ' . $row2['TERMS'] . ' days'));			
			$text = '"'.$contact_array['full_name'].'","'.
			$row3['COMPANY'].'"," ","'.
			$contact_array['email'].'","'.
			$contact_array['phone_number'].'","'.
			$contact_array['phone_number'].'","0"," ",'.
			$contact_array['address1']." ".$contact_array['address2'].'","'.
			$contact_array['city'].'","'.
			$contact_array['state'].'","'.
			$contact_array['postalcode'].'","'.
			$contact_array['country'].'"," ","'.
			$contact_array['created_at'].'"," "'. PHP_EOL;
				
			fwrite($file, $text);
			}
			$org_test = $row3['JOB'];
			$ap_test = $row3['APEMAIL'];
		}
		    // Mark the row as downloaded
		$update_sql = "UPDATE ic_timesheets SET ExportDate = NOW(), invoice_date = NOW()  WHERE  Unique_ID = '". $recipients[$X]."'";
		mysqli_query($link,$update_sql);
	fclose($file);
	// Redirect the user to the CSV file download link
	$customer_download_link = 'https://' . $_SERVER['HTTP_HOST'] . '/mngr/' . $customerpath;
	echo '<BR><a href="'.$customer_download_link.'">Download Customers CSV File </a>';
	
	// *** Start Invoice Section ***
	$nextinvoice = $_REQUEST['nextinvoice'] - 1;
    // Get the selected recipients
    $recipients = $_POST['recipients'];
	$WKEND = $_POST['WKEND'];
	
	 // Define the CSV file name and path
	$invoicefile = 'selected_invoices.csv';
	$invoicepath = '' . $invoicefile;

	// Open the CSV file for writing
	$file = fopen($invoicepath, 'w');
  
	// Open file for appending
	$file = fopen($invoicefile, "a");
	
	// make header
	 $text = '"*InvoiceNo","*Customer","Email","*InvoiceDate","*DueDate","Terms","Location","Memo","Item","ItemDescription","ItemQuantity","ItemRate","*ItemAmount","Taxable","TaxRate","Service Date"'. PHP_EOL;

	 fwrite($file, $text);

	// NOTE:  You must turn on "Custom transaction numbers" in Accounts and Settings or your 
	// invoice numbers will be replaced by standard QuickBooks invoice numbers.

    // Loop through the recipients and build the file

	$invoicefile = "invoice.txt";
	$invnum = $nextinvoice;
	$org_test = "test";
	$ap_test = "test";
	// echo "Count = ".count($recipients);

	for($z=0; $z<count($recipients); $z++) {
		$contact_array = Contact_Info($link,$organization);
 		$query2 = $strSQL . " AND wt.Unique_id= '". $recipients[$z]."'";
// 		AND wt.Assignment_ID = '". $PROJ[$z]."' AND wt.Weekending = '" .$WKEND[$z]. "'";

		// echo $query2;

		$result2 = mysqli_query($link,$query2);
		$row2 = mysqli_fetch_array($result2);
		$organization = $row2['JOB'];
		$contact_array = Contact_Info($link,$organization);
		If ($org_test !== $row2['JOB'] ) { 
			$invnum = $invnum + 1;
		}
		$org_test = $row2['JOB'];
		$ap_test = $row2['APEMAIL'];
			// write formula for duedate here
			$due_date =  date('m/d/Y', strtotime(' + '.$contact_array['terms'].' days'));
			$text = '"'.$invnum.'","'.
			$row2['COMPANY'].'","'.
			$contact_array['email'].'","'.
			date('m/d/Y').'","'.
			$due_date.'","Net '.
			$contact_array['terms'] .'","","'.
			$row2['PO'].'","","'.
			$row2['ITEM'].'","'.
			$row2['HOURS'].'","'.
			$row2['BILLRATE'].'","'.
			$row2['BILLRATE']*$row2['HOURS'].'","N","0%","'.
			date('m/d/Y',strtotime($row2['WKEND'])).'"'. PHP_EOL;
			
			if ($row2['HOURS'] > 0) {
				fwrite($file, $text);
			} 
		
		    // Mark the row as downloaded
		$update_sql2 = "UPDATE ic_timesheets SET invoice_date = NOW(), terms = '" . $contact_array['terms'] . "', ExportDate = NOW(), invoice_number = '" . $invnum . "' WHERE  Unique_ID = '". $recipients[$z]."'";
		mysqli_query($link,$update_sql2);
		// echo $update_sql2;
		}
		fclose($file);
		$invoice_download_link = 'https://' . $_SERVER['HTTP_HOST'] . '/mngr/' . $invoicepath;
		echo '<BR><a href="'.$invoice_download_link.'">Download Invoices CSV File </a>';
	
    }


// Delete the CSV file from server
// unlink($file);
	

// Close the database connection
// $link->close();
}
?>
<script>
// Add a "Check All" checkbox to select all checkboxes at once
var checkAll = document.getElementById('check_all');
checkAll.addEventListener('click', function() {
    var checkboxes = document.getElementsByName('recipients[]');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = checkAll.checked;
    }
});
</script>

