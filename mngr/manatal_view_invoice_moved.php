
<?php
$ciphertext = str_replace(" ","+",$_REQUEST['invnum']);
$key = "4b14783a4b5848cd43b9fc7e4cf0fb6f"; // Previously used in encryption 
$c = base64_decode($ciphertext); 
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
$iv = substr($c, 0, $ivlen); 
$hmac = substr($c, $ivlen, $sha2len=32); 
$ciphertext_raw = substr($c, $ivlen+$sha2len); 
$invnum = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 

// Connect to the MySQL database

// echo "server doc root= ".$_SERVER['DOCUMENT_ROOT'];
// require_once __DIR__.'/dompdf/autoload.inc.php';
// use Dompdf\Dompdf;

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
	
function Contact_Info($link, $organization, $job) {
   $f_query = "
	select full_name, terms, email, one_invoice_per_candidate,address1,address2,city,state,postalcode,country,created_at from ic_company 
	where organization = '" . $organization . "'";
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
		'accountspayable' => $row0['accountspayable']
    );

    return $infoArray;
}

$rowSQL = mysqli_query( $link,"SELECT MAX( invoice_number ) AS max FROM `ic_timesheets`;" );
$row0 = mysqli_fetch_array( $rowSQL );
$nextinvoice = $row0['max']+1;

// echo "Next Invoice = ". strval($nextinvoice). "<br>";
?>

<?php

$strSQL = "SELECT 'TIMEACT' as '!TIMEACT' ";
$strSQL = $strSQL . ", wt.first_name as FIRST ";
$strSQL = $strSQL . ", wt.last_name as LAST ";
$strSQL = $strSQL . ", wt.Employee_ID as EMPID ";
$strSQL = $strSQL . ", wt.billrate as BILLRATE ";
$strSQL = $strSQL . ", wt.payrate as PAYRATE ";
$strSQL = $strSQL . ", wt.Hours as HOURS ";
$strSQL = $strSQL . ", oj.organization as JOB ";
$strSQL = $strSQL . ", oj.company_name as COMPANY ";
$strSQL = $strSQL . ", oj.po_number as PO ";
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
$strSQL = $strSQL . ", wt.paid_amount as PAIDAMOUNT  ";
$strSQL = $strSQL . ", wt.Signature as ESIG ";
$strSQL = $strSQL . ", wt.CustomerIpAddr as IP ";
$strSQL = $strSQL . "from ic_timesheets wt ";
$strSQL = $strSQL . "LEFT JOIN ic_matches oj ON (oj.candidate = wt.employee_id AND oj.job = wt.AssignmentNumber) ";
$strSQL = $strSQL . "WHERE wt.invoice_number = ".$invnum." ";

$strSQL2 = $strSQL . " AND NOT void GROUP BY wt.invoice_number ORDER BY INVNUM DESC,wt.first_name ASC LIMIT 50 ";

// Query the database for the list of recipients
$result = mysqli_query($link,$strSQL2);	

$invnum = $nextinvoice-1;


    // Loop through the invoice details and build the invoice

	$inv_test = 0;
	// echo "count = ".count($recipients);


 		$query2 = $strSQL;
		// echo $query2;
		$result2 = mysqli_query($link,$query2);
		if (!$result2) {
			die('Query failed: ' . mysqli_error($link));
		}

		$row3 = mysqli_fetch_array($result2);
		$invoice_number = $row3['INVNUM'];
		If ($inv_test !== $row3['INVNUM']) { 
		
			$inv_test = $row3['INVNUM'];

			include( 'manatal_invoice_inc.php');
			$invoice=str_replace('url("https://www.icreatives.com/webtime/email/images/assets/background.gif")',"url('/webtime/email/images/assets/background.gif')",$invoice);
// 			// Start Making Timesheets
			$sScreen = "";
			$result6 = mysqli_query($link,$query2);
			while ($row6 = mysqli_fetch_array($result6)) {	
				include( 'manatal_timesheet_inc.php');
				$invoice = $invoice . $sScreen;
			}
			echo $invoice;
		}
//Close the database connection
$link->close();
?>


