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

<?php include 'manatal_header.php'; ?>
<?php
echo "<h1>OLD INVOICES</h1>";

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

function calculateInterest($invoiceDate, $invoiceAmount, $termsInDays) {
    $interestRate = 0.18; // 18% interest rate

    // Calculate today's date
    $todayDate = date('Y-m-d');

    // Calculate the number of days from the invoice date to today
    $invoiceTimestamp = strtotime($invoiceDate);
    $todayTimestamp = strtotime($todayDate);

    // Calculate days overdue based on the due date
    $daysOverdue = max(0, floor(($todayTimestamp - $invoiceTimestamp - $termsInDays * 24 * 3600) / (24 * 3600)));

    // Calculate interest
    $interest = $invoiceAmount * $interestRate * $daysOverdue / 365;

    // Calculate the total amount including interest
    $totalAmount = $invoiceAmount + $interest;
	if ($interest == 0) { $totalAmount = 1.05 * $totalAmount; }

    return [
        'totalAmount' => $totalAmount,
        'daysOverdue' => $daysOverdue,
        'interest' => $interest
    ];
}


// Connect to the MySQL database

// echo "server doc root= ".$_SERVER['DOCUMENT_ROOT'];
require_once __DIR__.'/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/PHPMailer.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/Exception.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/SMTP.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/class-phpmailer.php");

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

if (!$link) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Timesheet Functions
Function HrComp($lsInHr,$lsOutHr){
If ($lsInHr >= 13) {
   $lsInHr = intval($lsInHr -12);
} ElseIf ($lsInHr == 0 AND $lsOutHr <> 0) {
	$lsInHR = 12;
} Else {
   $lsInHr = intval($lsInHr);
}
return $lsInHr;
}

Function TotalHrs($i) {

$TotalHrs = $row2['OUTHR'. $i] - $row2['INHR' . $i] - $row2['BREAK' . $i];

return $TotalHrs;
}
Function GrandTotal() {
	$lsGt = 0;
	for ($i = 1; $i <= 7; $i++) {
		$lsGT = $lsGT + TotalHrs($i);
	}
	$GrandTotal = $lsGT;

	return $GrandTotal;
}


Function AmPmCompIn($lsHr) { // need two because out should default to PM for new record
	If ($lsHr >= 12) {
        $AmPmCompIn = "PM";
	} Else {
        $AmPmCompIn = "AM";
	}
	return $AmPmCompIn;
}

Function AmPmCompOut($lsHr) {
	If ($lsHr >= 12 || $lsHr == 0) {
        $AmPmCompOut = "PM";
	} Else {
        $AmPmCompOut = "AM";
	}
	return $AmPmCompOut;
}

function truncate($value, $precision) {
    $multiplier = pow(10, $precision);
    $value = (int)($value * $multiplier);
    return $value / $multiplier;
}

Function MinComp($lsHr) {

	$lsMin = ($lsHr - floor($lsHr)) * 60;

	Switch ($lsMin) {

	Case 0:
		$MinComp = "00";
		break;        
	Case 15:
		$MinComp = "15";
		break; 
	Case 30:
		$MinComp = "30";
		break; 
	Case 45:
		$MinComp = "45";
		break; 
	default:
		$MinComp = "00";
		break; 
	}
	return $MinComp;
}


// find last invoice number
/*
function Contact_Info($link, $organization, $job) {
   $f_query = "
	select * from ic_contacts 
	where organization = '" . $organization . "' AND accountspayable IS TRUE";
	// echo $f_query;
    $SQL = mysqli_query($link, $f_query);
    if (!$SQL) {
        die('Query failed: ' . mysqli_error($link));
    }
    $row0 = mysqli_fetch_array($SQL);
	
	
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
	$invoice_override = $job['custom_fields']['apinvoiceemailcommadelimited'];
	
	if (strpos($invoice_override,"@")) {
		$email = $invoice_override;
	} else {
		$email = $row0['email'];
	}

    $infoArray = array(
        'full_name' => $row0['full_name'],
        'terms' => $row0['terms'],
		'address1' => $row0['address1'],
		'address2' => $row0['address2'],
        'email' => $email,
		'city' => $row0['city'],
		'state' => $row0['state'],
		'postalcode' => $row0['postalcode'],	
		'country' => $row0['country'],
		'created_at' => $row0['created_at'],
        'one_invoice_per_candidate' => $row0['one_invoice_per_candidate'],
		'full_name_on_invoice' => $row0['full_name_on_invoice'],
		'accountspayable' => $row0['accountspayable']
    );

    return $infoArray;
}
*/
/*
$rowSQL = mysqli_query( $link,"SELECT MAX( invoice_number ) AS max FROM `ic_timesheets`;" );
$row5 = mysqli_fetch_array( $rowSQL );
$nextinvoice = $row5['max']+1;
*/
// echo "Next Invoice = ". strval($nextinvoice). "<br>";
?>
 
<?php 

$strSQL = "SELECT 'TIMEACT' as '!TIMEACT' ";
$strSQL = $strSQL . ", wt.first_name as FIRST ";
$strSQL = $strSQL . ", wt.last_name as LAST ";
$strSQL = $strSQL . ", wt.Employee_ID as EMPID ";
$strSQL = $strSQL . ", wt.paid_amount as PAID ";
$strSQL = $strSQL . ", wt.billrate as BILLRATE ";
$strSQL = $strSQL . ", wt.payrate as PAYRATE ";
$strSQL = $strSQL . ", wt.Hours as HOURS ";
$strSQL = $strSQL . ", wt.company_id as JOB ";
$strSQL = $strSQL . ", wt.company_name as COMPANY ";
$strSQL = $strSQL . ", wt.po_number as PO ";
$strSQL = $strSQL . ", wt.title as ITEM ";
$strSQL = $strSQL . ", wt.AssignmentNumber as PROJ ";
$strSQL = $strSQL . ", wt.AssignmentNumber as xORDER ";
$strSQL = $strSQL . ", wt.AcctEmail as APCCEMAIL ";
$strSQL = $strSQL . ", wt.Primary_Contact_Email as PEMAIL ";
// $strSQL = $strSQL . ", oj.Second_Contact_First as SFIRST ";
// $strSQL = $strSQL . ", oj.Second_Contact_Last as SLAST ";
$strSQL = $strSQL . ", wt.Second_Contact_Email as SEMAIL  ";
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
$strSQL = $strSQL . ", wt.invoice_date as INVDATE ";
$strSQL = $strSQL . ", wt.invoice_type as TYPE ";
$strSQL = $strSQL . ", wt.Signature as ESIG ";
$strSQL = $strSQL . ", wt.CustomerIpAddr as IP ";
$strSQL = $strSQL . ", COALESCE (SUM(wt.billrate * wt.Hours),0)  as AMOUNT " ;
$strSQL = $strSQL . "from ic_timesheets wt WHERE (NOT wt.void or wt.void is null or wt.void = '')";
// $strSQL = $strSQL . "LEFT JOIN ic_matches oj ON (oj.candidate = wt.employee_id AND oj.job = wt.AssignmentNumber) ";
if($_REQUEST['startinvoice'] > 0) {
	$strSQL = $strSQL . " AND wt.invoice_number >= ".$_REQUEST['startinvoice']."  AND wt.invoice_number IS NOT NULL ";
}else{
	$strSQL = $strSQL . " AND wt.invoice_number > 0 AND wt.invoice_number IS NOT NULL ";
}
if($_REQUEST['show_paid'] !== "show_paid") {
	// $strSQL = $strSQL . " AND (wt.paid_amount < 1 OR wt.paid_amount IS NULL)";
}
if($_REQUEST['endinvoice'] > 0) {
	$strSQL = $strSQL . "AND wt.invoice_number <= ".$_REQUEST['endinvoice']. " ";
}
if(!empty($_REQUEST['company_name'])) {
	$strSQL = $strSQL . " AND wt.company_name  like '%".$_REQUEST['company_name']. "%' ";
}
$strSQL2 = $strSQL . " GROUP BY wt.invoice_number ORDER BY INVNUM DESC, wt.first_name ASC LIMIT 30 ";

// Query the database for the list of recipients
$result = mysqli_query($link,$strSQL2);	

$invnum = $nextinvoice-1;

// Start the HTML form
?>
<form method="post">

Start Invoice #:  <input type="number" id="startinvoice" name="startinvoice" value = <?php echo $_REQUEST['startinvoice'] ?> >
End Invoice #:  <input type="number" id="endinvoice" name="endinvoice" value = <?php echo $_REQUEST['endinvoice'] ?> > 
Company Name:  <input type="text" id="company_name" name="company_name" value = <?php echo $_REQUEST['company_name'] ?> > 

Email Override: <input type="email" id="email_redirect" name="email_redirect" value = <?php echo $_REQUEST['email_redirect'] ?>>  
<?php 
if ($_REQUEST['show_paid'] == "show_paid") { 
	echo 'Show Paid: <input type="checkbox" id="show_paid" name="show_paid" value = "show_paid checked "><p>';
} else {
	echo 'Show Paid: <input type="checkbox" id="show_paid" name="show_paid" value = "show_paid"><p>';
}

?>
	
<input type="submit" value="Filter Invoices">
</form>

<form method="post">
<?php 

echo '<br>';
// Output the table headers
echo '<table>';
echo "<thead><tr><th><input type='checkbox' id='check_all'></th><th><P>Check All</p></th></tr></thead>";
echo '<tr align="center">
<th> </th>
<th>A/P Name</th>
<th>A/P Email</th>
<th>Client</th>
<th>Inv Amt</th>
<th>Paid Amt</th>
<th>Invoice Date</th>
<th>Job ID</th>
<th>Inv No</th>
<th>PO Number</th>
</tr>';

// Loop through the results and output each row
$invnum = $nextinvoice-1;
while ($row = mysqli_fetch_array($result)) {
	
	$approved_date = strtotime( $row['APPROVE']);
	$week_ending = strtotime( $row['WKEND']);
	// $ap_info = Contact_Info($link, $row['JOB'],$row['PROJ']);
	

    echo '<tr align="center">';
    echo '<td>';
	if (!is_null($row['JOB'])){
		echo '<input type="checkbox" name="recipients[]" value="' . $row['INVNUM'] . '">';
	}
	echo '</td>';
	// $ap_info = Contact_Info($link, $row['JOB'],$row['PROJ']);
	echo '<td ALIGN="LEFT">' . $row['FIRST']. ' ' . $row['LAST'] . '</td>';
	// echo '<td ALIGN="LEFT">' . $ap_info['email'] . '</td>';
	echo '<td ALIGN="LEFT">';


	if(empty($_REQUEST['email_redirect'])) {
		if (!is_null($row['PEMAIL'])) {
			echo $row['PEMAIL'] ;
		} else {
			echo '<font color="red"><b>A/P MISSING</b></font>';
		}
	} else {
		echo '<font color="red"><b>'. $_REQUEST['email_redirect'].'</b></font>';
	}
	echo "</td>";
	echo '<td ALIGN="LEFT">';
		if (!is_null($row['JOB'])) {
			echo $row['COMPANY'] ;
		} else {
			echo '<font color="red"><b>RE-OPEN JOB</b></font>';
		}
		echo "</td>";
	echo "<td align='right'>" . money_format(" %(n", $row['AMOUNT']) . "</td>";
	echo "<td align='right' >" . money_format(" %(n", $row['PAID']) . "</td>";
	echo "<td>" . date("m/d/Y",strtotime($row['INVDATE']) ) . '</td>';
	echo "<td>".$row['PROJ']."</td>";
	$url = '"manatal_view_old_invoices.php?invnum='.encrypt_string($row['INVNUM']).'"';
	echo "<td><a href='javascript:void(0);' onclick='openPopup(".$url.");'>". $row['INVNUM'] . "</a></td>";
	echo "<td>" . $row['PO'] . "</td>";
    echo '</tr>';
	$invnum = $invnum + 1;

}

// Close the table and add the submit button
echo '</table><p>';
echo '<input type="hidden" name="email_redirect" value="'.$_REQUEST["email_redirect"]. '">';
echo '<input type="submit" value="Email Checked Invoices">';
echo '</form>';


	
// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' and !is_null($_POST['recipients'])) {	
	
	// *** Start sending invoices ***
	$recipients = $_POST['recipients'];	

    // Loop through the invoice details and build the invoice

	$inv_test = $row0['INVNUM'];

	// echo "count = ".count($recipients);

	$strSQL_N = str_replace(", COALESCE (SUM(wt.billrate * wt.Hours),0)  as AMOUNT ","",$strSQL);

	for($x=0; $x<count($recipients); $x++) {

 		$query2 = $strSQL_N . " AND wt.invoice_number = '". $recipients[$x]."'";
		$query2 = $query2 . " ORDER BY WKEND ASC, FIRST ASC";
		// $query2;
		$result2 = mysqli_query($link,$query2);
		if (!$result2) {
			die('Query failed: ' . mysqli_error($link));
		}

		$row3 = mysqli_fetch_array($result2);
		
		$invoice_number = $row3['INVNUM'];

			// echo $inv_test = $row3['INVNUM'];

			include( 'manatal_invoice_inc.php');
			$invoice=str_replace('url("https://www.icreatives.com/webtime/email/images/assets/background.gif")',"url('/webtime/email/images/assets/background.gif')",$invoice);
// 			// Start Making Timesheets
			$sScreen = "";
			$result6 = mysqli_query($link,$query2);
			if($row6['TYPE'] !== "e" && $row6['TYPE'] !== "f") {
				while ($row6 = mysqli_fetch_array($result6)) {		
					include( 'manatal_timesheet_inc.php');
					$invoice = $invoice . $sScreen;
				}
			}
			
			// echo $invoice;

			// Start Sending Invoice as a PDF
			$pdf = new Dompdf();

			if ($invoice) {
				$pdf->loadHtml($invoice);	     
				$pdf->setPaper('letter', 'portrait');
				$pdf->set_option('isHtml5ParserEnabled', true);
				$pdf->set_option('isRemoteEnabled', true);
				$pdf->render();

				// Create a PDF string
				$pdfContent = $pdf->output();

				// Create a new PHPMailer instance
				$mailer = new PHPMailer();
        
				// Configure SMTP or other mail settings
				$mail = new PHPMailer();
				$mail->IsSMTP(); // telling the class to use SMTP
				// $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
				// 1 = errors and messages
				// 2 = messages only
				$mail->SMTPAuth   = true;                  // enable SMTP authentication
				$mail->Host       = "smtp.1and1.com"; // sets the SMTP server
				$mail->Username   = "exchange@icreatives.co"; // SMTP account username
				$mail->Password   = "Call1888icreate!";        // SMTP account password
				$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
				$mail->Port       = 587;  // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above		
				$mail->isHTML(true);                             // Set email format to HTML
				$mail->CharSet = "UTF-8";


				// Set sender and recipient addresses
				$mail->setFrom('andreaa@icreatives.com', 'icreatives');
				
				// check if we want to send the invoices to redirect address
				// echo "XXXX". $_REQUEST['email_redirect'] . "XXX";
				
				if (empty($_REQUEST['email_redirect'])) {
					
						$mail->addAddress($row['Primary_Contact_Email'], 'Accounts Payable');
						
				} else {
					$email = $_REQUEST['email_redirect'];
					$mail->addAddress($email, 'Accounts Payable');
				}
				
				$mail->addBcc("andreaa@icreatives.com");				
				$mail->addBcc("invoice@blindemail.com");
				$mail->addBcc("stevenc@icreatives.com");
				// Set email subject and body
				$mail->Subject = 'icreatives invoice ' .$row3['INVNUM']. ' ' ;
				// $mail->Body = 'Please find the attached multi page PDF Invoice.';
				$mail->Body = 'Dear '. $row3["COMPANY"].',
				<p>Attached please find a multi-page Acrobat formatted invoice.
				<p>If there is anything else you may need, or have any questions about this invoice, please do not hesitate to contact us.
				<p>Thank you so much for your business.
				<p>Sincerely,'.'
				<p><p><span style="FONT-FAMILY:arial,sans-serif;FONT-SIZE:10pt">the icreatives team<br>
				</span><span style="FONT-FAMILY:times new roman,serif";FONT-SIZE:12pt"><br></span><b><span style="font-family: arial , sans-serif ;color:maroon; font-size:10pt">i 
				creatives</span></b><span style="FONT-FAMILY:arial,sans-serif;FONT-SIZE:10pt"> | <span style="COLOR:gray">staffing</span></span><span style="FONT-FAMILY:times new roman,serif; FONT-SIZE:12pt"> 
				<br></span><span style="FONT-FAMILY:arial,sans-serif; COLOR:gray;FONT-SIZE:10pt">"the art 
				of selection"<br></span><span style="font-family:Symbol;color:maroon;font-size:10pt">·</span><span style="font-family:times new roman,serif;color:maroon;font-size:10pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:Symbol;color:maroon;font-size:10pt"> </span><span style="font-family:times New Roman,serif;color:navy; font-size:10pt">&nbsp;</span><span style="font-family:Symbol;color:navy;font-size:10pt"> ·</span><span style="font-family:Times New Roman,serif;color:navy; font-size:10pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:Symbol;color:navy;font-size:10pt"> </span><span style="font-family:Symbol;color:green;font-size:10pt">·</span><span style="font-family:Times New Roman,serif;color:green;font-size:10pt">&nbsp;&nbsp;</span><span style="font-family:Symbol;color:green;font-size:10pt"> </span><span style="font-family:Times New Roman,serif;color:green;font-size:10pt">&nbsp;&nbsp;</span><span style="font-family:Symbol;color:green;font-size:10pt"> </span><span style="font-family:Times New Roman,serif;color:green;font-size:10pt">&nbsp;</span><span style="font-family:Symbol;color:green;font-size:10pt"> </span><span style="font-family:Symbol;color:olive;font-size:10pt">·</span><span style="font-family:Times New Roman,serif;color:olive;font-size:10pt">&nbsp;&nbsp;</span><span style="font-family:Symbol;color:olive;font-size:10pt"> </span><span style="font-family:Times New Roman,serif;color:olive;font-size:10pt">&nbsp;</span><span style="font-family:Symbol;color:olive;font-size:10pt"> </span><span style="font-family:Times New Roman,serif;color:olive;font-size:10pt">&nbsp;&nbsp;</span><span style="font-family:Symbol;color:olive;font-size:10pt"> </span><span style="font-family:Symbol;font-size:10pt">·</span><span style="font-family:Symbol"><br></span><span style="FONT-FAMILY:Times New Roman,serif; FONT-SIZE:12pt"> </span><span style="FONT-FAMILY:arial,sans-serif; COLOR:#999999;FONT-SIZE:10pt">+1.i.creatives</span><span style="FONT-FAMILY:arial,sans-serif; COLOR:#999999;FONT-SIZE:7.5pt"> / 
				+1.855.427.3284</span><span style="FONT-FAMILY:times new roman,serif; FONT-SIZE:12pt"> </span><span style="FONT-FAMILY:times new roman,serif; FONT-SIZE:7.5pt"><br></span><span style="FONT-FAMILY:arial,sans-serif;COLOR:maroon;FONT-SIZE:7.5pt"> <font color="#999999">
				</font></span><span style="FONT-FAMILY:arial,sans-serif;COLOR:#999999;FONT-SIZE:10pt"><a href="http://www.icreatives.com" target="_blank"><font color="#999999">http://www.icreatives.com</font></a><br>
				creative + i.t. professionals</span><b><span style="FONT-FAMILY:arial,sans-serif; COLOR:maroon;FONT-SIZE:7.5pt"><br></span></b></p>
				';				

				$mail->AltBody = 'Please find the attached PDF Invoice.';

				// Add the PDF as an attachment
				$mail->addStringAttachment($pdfContent, 'web_page.pdf', 'base64', 'application/pdf');

				// Send the email
				// echo 'Email to: '.$row['Primary_Contact_Email']."<br>";
				
				
				if ($mail->send()) {
					if (empty($_REQUEST['email_redirect'])) {
						echo 'Email sent successfully to: '.$ap_info['email']."<br>";
					} else {
						echo 'Email sent successfully to: '.$_REQUEST['email_redirect']."<br>";
					}
				} else {
					echo 'Email could not be sent.';
				}
				
				

				
				
			} else {
				$error = 'Please provide a URL.';
			}

		$inv_test = $row3['INVNUM'];	
	}		
}

// Close the database connection
// $link->close();
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

