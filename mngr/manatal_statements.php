<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<script>
        function openPopup(url) {
            // Specify the size and position of the window
            var width = 900;
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

echo "<h1>MANATAL STATEMENTS</h1>";

// echo "server doc root= ".$_SERVER['DOCUMENT_ROOT'];
require_once __DIR__.'/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once dirname(__DIR__) . '/PHPMailer/PHPMailer.php';
require_once dirname(__DIR__) . '/PHPMailer/Exception.php';
require_once dirname(__DIR__) . '/PHPMailer/SMTP.php';


require_once  dirname(__DIR__) . '/vendor/autoload.php';
// Include your database connection code here
require_once __DIR__ . '/../db/db.php';
$link = db(); 


/*
function encrypt_string($plaintext) {
$key = "4b14783a4b5848cd43b9fc7e4cf0fb6f";
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
$iv = openssl_random_pseudo_bytes($ivlen); 
$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true); 
// Encrypted string 
return  base64_encode($iv.$hmac.$ciphertext_raw);
}
*/
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
	
function Statement_Contact_Info2($link, $organization) {

   $f_query = "
	select full_name, terms, email, one_invoice_per_candidate,address1,address2,city,state,postalcode,country,created_at from ic_company 
	where deactivated <> 1 AND organization = '" . $organization . "' ";
    $SQL = mysqli_query($link, $f_query);
    if (!$SQL) {
        die('Query failed: ' . mysqli_error($link));
    }
    $row0 = mysqli_fetch_array($SQL);

    $infoArray = array(
    'full_name'               => $row0['full_name']               ?? '',
    'terms'                   => $row0['terms']                   ?? '',
    'address1'                 => $row0['address1']                ?? '',
    'address2'                 => $row0['address2']                ?? '',
    'email'                   => $row0['email']                   ?? '',
    'city'                    => $row0['city']                    ?? '',
    'state'                   => $row0['state']                   ?? '',
    'postalcode'              => $row0['postalcode']              ?? '',
    'country'                 => $row0['country']                 ?? '',
    'created_at'              => $row0['created_at']              ?? '',
    'one_invoice_per_candidate' => $row0['one_invoice_per_candidate'] ?? ''
	);


    return $infoArray;
}

function Contact_Info($link, $organization) {
    $f_query = "select full_name, terms, email, phone_number, one_invoice_per_candidate,address1,address2,city,state,postalcode,country,created_at from ic_company where deactivated <> 1 AND organization = '" . $organization . "'";
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
/*
function encrypt_string($plaintext) {
$key = "4b14783a4b5848cd43b9fc7e4cf0fb6f";
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
$iv = openssl_random_pseudo_bytes($ivlen); 
$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true); 
// Encrypted string 
return  base64_encode($iv.$hmac.$ciphertext_raw);
}
*
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

    return [
        'totalAmount' => $totalAmount,
        'daysOverdue' => $daysOverdue,
        'interest' => $interest
    ];
}


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
*/
?>
<form method="post">
	Company Name: <input type="text" id="company_filter" name="company_filter" value = "<?php echo ($_REQUEST['company_filter'] ?? ''); ?>" >
	<label for="paid_invoices">Include Paid: </label>  
	<select name="paid_invoices" id="paid_invoices">
		<?php 
		if($_REQUEST['paid_invoices'] == 1) {
			echo '<option value="0">Unpaid</option>
			<option value="1" selected>All</option>	';
		} else {
		echo '<option value="0" selected>Unpaid</option>
		<option value="1">All</option>	';}
		?>
	</select>
	Alternet Email: <input type="email" id="email_redirect" name="email_redirect" value = "<?php echo ($_REQUEST['email_redirect'] ?? ''); ?>">
	<input type="submit" value="Submit">
</form>

<form method="post">
<?php 

$rowSQL = mysqli_query( $link,"SELECT MAX( invoice_number ) AS max FROM `ic_timesheets`;" );
$row0 = mysqli_fetch_array( $rowSQL );
$nextinvoice = $row0['max']+1;


/*

  $strSQL2 = "SELECT
  inv.company_name,
  inv.company_id,
  inv.paid_amount

FROM (
  SELECT
    ts.company_name,
    ts.company_id,
    ts.invoice_number,
	ts.paid_amount,

    SUM(CAST(ts.billrate AS DECIMAL(18,4)) * CAST(ts.Hours AS DECIMAL(18,4))) AS AMOUNT
    
  FROM ic_timesheets ts
  WHERE ts.invoice_number IS NOT NULL
    AND ts.invoice_number > 0
    AND (ts.void IS NULL OR ts.void = 0 OR ts.void = '') ";
	
	if (!empty($_REQUEST['company_filter']) ) {
		$strSQL2 .= "AND ts.company_name LIKE '%". $_REQUEST['company_filter']  ."%' ";
	}	
	
  $strSQL2 .= "GROUP BY ts.company_name, ts.company_id, ts.invoice_number
) AS inv
GROUP BY inv.company_name, inv.company_id
HAVING inv.paid_amount < 1        -- keep only companies with unpaid balance
ORDER BY inv.company_name ASC;";
*/
// echo $strSQL2;

// echo "Next Invoice = ". strval($nextinvoice). "<br>";



/*
if ($_REQUEST['paid_invoices'] !== 1 ) {
  $strSQL .= "AND inv.paid_amt < inv.total_amt ";
}
*/
/*
  $strSQL = "SELECT
  inv.company_name,
  inv.company_id,
  ROUND(
    SUM(
      CASE
        -- compare without rounding, allow tiny epsilon for float noise
        WHEN inv.total_amt > COALESCE(inv.paid_amt, 0) + 0.005
          THEN inv.total_amt - COALESCE(inv.paid_amt, 0)
        ELSE 0
      END
    ), 2
  ) AS outstanding_balance
FROM (
  SELECT
    ts.company_name,
    ts.company_id,
    ts.invoice_number,
    -- use raw SUM here; round only at the end
    SUM(CAST(ts.billrate AS DECIMAL(18,4)) * CAST(ts.Hours AS DECIMAL(18,4))) AS total_amt,
    COALESCE(MAX(ts.paid_amount), 0) AS paid_amt
  FROM ic_timesheets ts
  WHERE ts.invoice_number IS NOT NULL
    AND ts.invoice_number > 0
    AND (ts.void IS NULL OR ts.void = 0 OR ts.void = '')
  GROUP BY ts.company_name, ts.company_id, ts.invoice_number
) AS inv ";

	if (!empty($_REQUEST['company_filter']) ) {
		$strSQL .= "WHERE inv.company_name LIKE '%". $_REQUEST['company_filter']  ."%' ";
	}		

  $strSQL2 = $strSQL . "GROUP BY inv.company_name, inv.company_id;";

*/

/*
$strSQL = "
SELECT c.*, ts.company_name, ts.company_id, sum(ts.billrate * ts.Hours) AS AMOUNT FROM ic_timesheets ts 
  SUM(CAST(ts.billrate AS DECIMAL(18,4)) * CAST(ts.Hours AS DECIMAL(18,4))) AS AMOUNT,
  COALESCE(MAX(ts.paid_amount), 0) AS PAIDAMOUNT   -- not summed; one value per invoice
LEFT JOIN ic_company c ON  ts.company_id = c.organization
LEFT JOIN ic_matches m ON (m.candidate = ts.employee_id AND m.job = ts.AssignmentNumber) 
WHERE 
(ts.void = 0 or NOT void or ts.void = '') AND
    ts.invoice_number > 0  AND PAIDAMOUNT < AMOUNT";

if (!empty($_REQUEST['company_filter']) ) {
	$strSQL = $strSQL ."AND ts.company_name LIKE '%". $_REQUEST['company_filter']  ."%' ";
}
$strSQL2 = $strSQL . " GROUP BY ts.company_name";
*/

/*
$strSQL2 = "
SELECT
  ts.company_name,
  ts.company_id,
  ts.invoice_number,
  SUM(CAST(ts.billrate AS DECIMAL(18,4)) * CAST(ts.Hours AS DECIMAL(18,4))) AS AMOUNT,
  COALESCE(MAX(ts.paid_amount), 0) AS PAIDAMOUNT   -- not summed; one value per invoice
FROM ic_timesheets ts
LEFT JOIN ic_company c ON ts.company_id = c.organization
LEFT JOIN ic_matches m ON (m.candidate = ts.employee_id AND m.job = ts.AssignmentNumber)
WHERE
  (ts.void = 0 OR ts.void IS NULL OR ts.void = '')
  AND ts.invoice_number > 0
GROUP BY
  ts.company_name, ts.company_id
HAVING
  AMOUNT > PAIDAMOUNT;

";
*/
/*
$strSQL = "
SELECT 
    ts.company_name, 
    ts.company_id,  
	TRUNCATE(SUM(CAST(ts.billrate AS DECIMAL(18,4)) * CAST(ts.Hours AS DECIMAL(18,4))), 2)  AS AMOUNT, COALESCE(MAX(ts.paid_amount), 0) AS PAIDAMOUNT
	FROM ic_timesheets ts 

WHERE 
    (ts.void = 0 OR ts.void IS NULL OR ts.void = '') 
    AND ts.invoice_number > 0 ";
if (!empty($_REQUEST['company_filter']) ) {
	$strSQL = $strSQL ."AND ts.company_name LIKE '%". $_REQUEST['company_filter']  ."%' ";
}
$strSQL2 = $strSQL . " 
GROUP BY 
    ts.company_id
HAVING 
    AMOUNT > PAIDAMOUNT
	ORDER BY ts.company_name ASC;

";
*/
/*
	$strSQL2 = "SELECT
  c.company_name,
  i.company_id,
  TRUNCATE(SUM(i.total_amt), 2) AS BILLAMOUNT,
  TRUNCATE(SUM(i.paid_amt), 2) AS PAIDAMOUNT,
  TRUNCATE(SUM(i.total_amt) - SUM(i.paid_amt), 2) AS AMOUNT
FROM (
  SELECT
    ts.company_id,
    ts.invoice_number,
    SUM(CAST(ts.billrate AS DECIMAL(18,4)) * CAST(ts.Hours AS DECIMAL(18,4))) AS total_amt,
    CAST(COALESCE(MAX(ts.paid_amount), 0)AS DECIMAL(18,4)) AS paid_amt
  FROM ic_timesheets ts
  WHERE (ts.void = 0 OR ts.void IS NULL OR ts.void = '')
    AND ts.invoice_number > 0
  GROUP BY ts.company_id, ts.invoice_number
) i
LEFT JOIN ic_company c ON c.organization = i.company_id
GROUP BY i.company_id
HAVING AMOUNT > 0
ORDER BY c.company_name ASC;

";
*/


//

$strSQL = "
SELECT 
    i.company_name, 
    i.company_id, 

    -- Sum of principal still due per invoice (never negative)
    ROUND(SUM(GREATEST(i.total_amt - i.paid_amt, 0)), 2) AS AMOUNT, 

    -- Sum of principal actually paid (capped at total_amt; interest ignored)
    ROUND(SUM(LEAST(i.paid_amt, i.total_amt)), 2) AS PAIDAMOUNT 

FROM ( 
    SELECT 
        ts.company_id, 
        ts.company_name, 
        ts.invoice_number, 

        -- Principal amount for this invoice: billrate * Hours, safe for '' values
        SUM(
            COALESCE(NULLIF(ts.billrate, ''), 0) 
            * COALESCE(NULLIF(ts.Hours, ''), 0)
        ) AS total_amt, 

        -- Raw total paid for this invoice (may include interest / overpayment)
        COALESCE(MAX(NULLIF(ts.paid_amount, '')), 0) AS paid_amt

    FROM ic_timesheets ts 
    WHERE (ts.void = 0 OR ts.void IS NULL OR ts.void = '') 
      AND ts.invoice_number > 0 
";

if (!empty($_REQUEST['company_filter'])) {
    $strSQL .= "AND ts.company_name LIKE '%" . $_REQUEST['company_filter'] . "%' ";
}

$strSQL2 = $strSQL . "
    GROUP BY ts.company_id, ts.company_name, ts.invoice_number 
) i 
GROUP BY i.company_id, i.company_name 
";

if (empty($_REQUEST['paid_invoices']) || (int)$_REQUEST['paid_invoices'] == 0) {
    // Treat anything <= 0.01 as fully paid; only show if more than a penny is due
    $strSQL2 .= "
    HAVING SUM(GREATEST(i.total_amt - i.paid_amt, 0)) > 0.10
    ";
}

$strSQL2 .= "ORDER BY i.company_name ASC;";

// echo $strSQL2;
//		GROUP BY ts.company_id, ts.company_name, ts.invoice_number ) 



// echo "XXX". $_REQUEST['paid_invoices'] ;





 // echo $strSQL2;


// Query the database for the list of recipients
$result = mysqli_query($link,$strSQL2);	

$invnum = $nextinvoice-1;

// Start the HTML form

echo '<br>';
// Output the table headers
echo '<table>';
echo "<thead><tr><th><input type='checkbox' id='check_all'></th><th align='left'><P>Check All</p></th></tr></thead>";
echo '<tr align="center">
<th> </th>
<th width="400">A/P Name</th>
<th width="250">A/P Email</th>
<th>Client</th>
<th width="100">Amt Due</th>
<th> </th>
</tr>';

// Loop through the results and output each row
$grand_total = 0;
$invnum = $nextinvoice-1;
while ($row = mysqli_fetch_array($result)) {
	$grand_total = $grand_total + $row['AMOUNT'] ;
	$ap_info = Statement_Contact_Info2($link, $row['company_id']);
    echo '<tr align="center">';
    echo '<td>';
	if (!is_null($row['company_id']) && !is_null($ap_info['email'])){
		echo '<input type="checkbox" name="recipients[]" value="' . $row['company_id'] . '">';
	}
	echo '</td>';

	echo '<td ALIGN="LEFT">' . $row['company_name'] . '</td>';
	// echo '<td ALIGN="LEFT">' . $ap_info['email'] . '</td>';
	echo '<td ALIGN="LEFT">';
		if (!is_null($ap_info['email'])) {
			echo $ap_info['email'] ;
		} else {
			echo '<font color="red"><b>A/P MISSING</b></font>';
		}
	echo "</td>";	
	echo "<td align='right'> $" . number_format($row['AMOUNT'],2) . "</td>";
	
	$url = 'https://port.icreatives.com/mngr/manatal_view_statement.php?org='.$row['company_id'].'&paid='.($_REQUEST['paid_invoices'] ?? '');
	
	?>
	<td>
	<input type="button" onclick="openPopup('<?php echo $url; ?>');" value="Preview" />
	</td>
	<?php
	
    echo '</tr>';
	$invnum = $invnum + 1;
}

echo '<tr align="center">
<th> </th>
<th width="400"></th>
<th align="right" width="250">Total</th>
<th align="right"> $' . number_format($grand_total,2) .'</th>
<th width="100"></th>
<th> </th>
</tr>';

// Close the table and add the submit button

echo '</table><p>';
echo '<input type="hidden" name="company_filter" value="' . ($_REQUEST["company_filter"] ?? '') . '">';
echo '<input type="hidden" name="paid_invoices" value="' . ($_REQUEST["paid_invoices"] ?? '') . '">';
echo '<input type="hidden" name="email_redirect" value="' . ($_REQUEST["email_redirect"] ?? ''). '">';

echo '<input type="submit" value="Send Statements">';

echo '</form>';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' and !empty(($_POST['recipients'] ?? ''))) {	
	// *** Start sending invoices ***
	$recipients = $_POST['recipients'];	

    // Loop through the invoice details and build the invoice

	$inv_test = 0;
	// echo "count = ".count($recipients);
 
	// $strSQL_N = str_replace(", COALESCE (SUM(wt.billrate * wt.Hours),0)  as AMOUNT ","",$strSQL);

	for($x=0; $x<count($recipients); $x++) {

 		$query2 = $strSQL . " AND company_id= '". $recipients[$x]."'
		GROUP BY ts.company_id, ts.invoice_number ) 
		i GROUP BY i.company_id ";


		// $query2 = $query2 . " GROUP BY company_id ORDER BY company_name ASC LIMIT 500 ";
		// echo "XXXXXXXXXXXXXXXXXX",$query2;

		$result2 = mysqli_query($link,$query2);
		if (!$result2) {
			die('Query failed: ' . mysqli_error($link));
		}
		// old code from invoice not statement, should delete below
		$row3 = mysqli_fetch_array($result2);
		
// $ap_info = Statement_Contact_Info($link, $row3['company_id']);

			
		// $invoice_number = $row3['INVNUM'];
		// If ($inv_test !== $row3['INVNUM']) { 
		
		//	$inv_test = $row3['INVNUM'];

			// include( 'manatal_statement_inc.php');
			include( 'manatal_view_statement.php');
			$statement=str_replace('url("https://port.icreatives.com/webtime/email/images/assets/background.gif")',"url('/webtime/email/images/assets/background.gif')",$statement);
// 			// Start Making Statements

			// echo $statement;
			// exit();
			// Start Sending statement as a PDF
			$pdf = new Dompdf();
			
			if ($statement) {
				$pdf->loadHtml($statement);	     
				$pdf->setPaper('letter', 'portrait');
				$pdf->set_option('isHtml5ParserEnabled', true);
				$pdf->set_option('isRemoteEnabled', true);
				$pdf->render();

				// Create a PDF string
				$pdfContent = $pdf->output();

				// Create a new PHPMailer instance
				$mailer = new PHPMailer();
        
				// Configure SMTP or other mail settings
				
	$mail             = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = "smtp.1and1.com"; // SMTP server
	// $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
	// 1 = errors and messages
	// 2 = messages only
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Host       = "smtp.1and1.com"; // sets the SMTP server
	$mail->Username   = "exchange@icreatives.com"; // SMTP account username
	$mail->Password   = "Call1888icreate!";        // SMTP account password
	$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
	$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
	$mail->CharSet = "UTF-8";
	$mail->isHTML(true);      
	// DKIM Setup
				$mail->DKIM_domain = 'icreatives.com';
				$mail->DKIM_selector = 'performa';
				$mail->DKIM_private = $_SERVER['DOCUMENT_ROOT'] . '/dkim_keys/ionos-icreatives-dkim-private-key.key'; // Replace with actual path
				$mail->DKIM_passphrase = ''; // If your key has a passphrase, enter it here
				$mail->DKIM_identity = 'exchange@icreatives.com'; // Typically same as From

				// Set sender and recipient addresses
				$mail->setFrom('exchange@icreatives.com', 'icreatives');
				$mail->addReplyTo('andreaa@icreatives.com', 'icreatives');
				// $mail->addAddress($ap_info['email'], 'accounts payable');
				if (empty($_REQUEST['email_redirect'])) {
						
					$ap_info = Statement_Contact_Info2($link, $row3['company_id']);

					$emailArray = explode(',', $ap_info['email']);
					foreach ($emailArray as $email) {
						$email = trim($email); // Remove any extra spaces

						// Add the email address with a default name
						$mail->addAddress($email, 'Accounts Payable');
					}	
				} else {
					$email = $_REQUEST['email_redirect'];
					$mail->addAddress($email, 'Accounts Payable');
				}
				if(!empty($row3['APCCEMAIL'])) {
					$mail->AddCC($row3['APCCEMAIL'], 'Invoice Copy');
				}
				
				$mail->addBcc("andreaa@icreatives.com");
				$mail->addBcc("invoice@blindemail.com");
				$mail->addBcc("stevenc@icreatives.com");
				// Set email subject and body
				$mail->Subject = '+++ icreatives statement +++' ;
				$statement = 'Dear Accounts Payable,<p>

				I hope this email finds you well. 
				I am writing to inform you that a statement for all outstanding invoices has been 
				generated and is now available for your review.<p>
				You may click on any invoice number to access a copy of the invoice and associated timesheets:<p>
				<p>If there is anything else you may need, or have any questions about this statement, 
				please do not hesitate to contact us.
				<p>Thank you so much for business.
				<p>Sincerely,
				<p>i creatives staffing<br><br>
				+1.954.468.5550<br>
				accounting_mail@icreatives.com'
				.$statement;
				/*
				// bring this back when we can convert the email html from divs to tables
				$mail->Body = 'Dear '. $row3["COMPANY"].',
				<p>Attached please find a multi-page Acrobat formatted Statement.
				<p>You may click on the invoice numbers to see the invoice along with the accompanying timesheets.
				<p>If there is anything else you may need, or have any questions about this invoice, please do not hesitate to contact us.
				<p>Thank you so much for business.
				<p>Sincerely,
				<p>i creatives staffing<br><br>
				+1.954.468.5550
				invoices@icreatives.com
			';

				$mail->AltBody = 'Please find the attached PDF Statement';
				*/
				$mail->Body = $statement;
				// Add the PDF as an attachment
				// $mail->addStringAttachment($pdfContent, 'icreatives_statement.pdf', 'base64', 'application/pdf');

				// Send the email
		
				if ($mail->send()) {
					echo 'Email sent successfully to: '.$email."<br>";
				} else {
					echo 'Email could not be sent.';
				}
		
			} else {
				$error = 'Please provide a URL.';
			}
		// }
		$inv_test = $row3['INVNUM'] ?? 0 ;	
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

