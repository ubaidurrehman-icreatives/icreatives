<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
    <script>
       function openPopup(url) {
    // Specify the size and position of the window
    var width = 800;
    var height = 850;
    var left = (screen.width - width) / 2;
    var top = (screen.height - height) / 2; 

    // Open the pop-up window with the provided URL
    var popup = window.open(url, '_blank', `width=${width},height=${height},left=${left},top=${top}`);

    // Check if the popup was blocked
    if (popup) {
        popup.focus(); // Focus the pop-up window
    } else {
        alert('Pop-up blocked! Please allow pop-ups for this site.');
    }
}

    </script>
<body>
<?php 

// Include your database connection code here
if (!isset($link)) {
	require_once __DIR__ . '/../db/db.php';
	$link = db(); 
}
if (!function_exists('Statement_Contact_Info')) {
function Statement_Contact_Info($link, $organization) {

   $f_query = "
	select * from ic_company 
	where organization = '" . $organization . "' ";
    $SQL = mysqli_query($link, $f_query);
    if (!$SQL) {
        die('Query failed: ' . mysqli_error($link));
    }
    $row0 = mysqli_fetch_array($SQL);
/*
    $infoArray = array(
        'full_name' => $row0['full_name'],
        'terms' => $row0['terms'],
		'address1' => $row0['address1'],
		'address2' => $row0['address2'],
        'email' => $row0['email'],
		'city' => $row0['city'],
		'state' => $row0['state'],
		'postalcode' => $row0['postalcode'],
		'country' => $row0['country'],
		'created_at' => $row0['created_at'],
        'one_invoice_per_candidate' => $row0['one_invoice_per_candidate']
    );
	*/
	    // return $infoArray;
		return $row0;
}
}

$org = $_REQUEST['org'] ?? '';

if(empty($org)) { 
$org = $row3['company_id']; 
}
$ap_info = Statement_Contact_Info($link, $org);

$address1 = $ap_info['address1'];
$address2 = $ap_info['address2'];
$city = $ap_info['city'];
$state = $ap_info['state'];
$postalcode = $ap_info['postalcode'];
$terms = $ap_info["terms"];
$vendor_number = $ap_info['vendor_number'];
// echo "XXX". $ap_info['organization'];
// if(empty($terms)){$terms = 10; }



function encrypt_string($plaintext) {
$key = "4b14783a4b5848cd43b9fc7e4cf0fb6f";
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
$iv = openssl_random_pseudo_bytes($ivlen); 
$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true); 
// Encrypted string 
return  base64_encode($iv.$hmac.$ciphertext_raw);
}


							$interest_calc = calculateInterest(($row3["INVDATE"] ?? '0000-00-00'), ($inv_total ?? 0), $terms);
							$totalDue = $interest_calc['totalAmount'];
							$daysOverdue = $interest_calc['daysOverdue'];
							$interest = $interest_calc['interest'];

function calculateInterest($invoiceDate, $invoiceAmount, $termsInDays) {
    $interestRate = 0.18; // 18% interest rate

    // Calculate today's date
    $todayDate = date('Y-m-d');

    // Calculate the number of days from the invoice date to today
    $invoiceTimestamp = strtotime(($invoiceDate ?? '0000-00-00'));
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


// COALESCE(SUM(wt.billrate * wt.Hours), 0) AS AMOUNT
/*
$strSQL2 = "
SELECT
  oj.organization      AS JOB,
  oj.company_name      AS COMPANY,
  oj.po_number         AS PO,
  wt.invoice_number    AS INVNUM,
  MAX(wt.terms)        AS TERMS,      
  MAX(wt.invoice_date) AS INVDATE, 
  COALESCE(MAX(wt.paid_amount), 0) AS PAIDAMOUNT,  
  ROUND(SUM(wt.billrate * wt.Hours), 2) AS AMOUNT
FROM ic_timesheets wt
LEFT JOIN ic_matches oj
  ON oj.candidate = wt.employee_id
 AND oj.job       = wt.AssignmentNumber
WHERE

    oj.organization = '".  $org."' ";
	
	$strSQL2 = $strSQL2 . "  
	AND (wt.void = 0 OR wt.void IS NULL OR wt.void = '')
	AND wt.invoice_number > 0
	GROUP BY
	wt.invoice_number ";

	if (!isset($_REQUEST['paid_invoices']) || $_REQUEST['paid_invoices'] == 0) {
	
		// $strSQL2 .= " HAVING ROUND(SUM(wt.billrate * wt.Hours), 2) <> COALESCE(MAX(wt.paid_amount), 0) ";
		$strSQL2 .= " HAVING AMOUNT > PAIDAMOUNT +1 ";

}

    // HAVING AMOUNT > 0
	$strSQL2 .= " ORDER BY wt.invoice_number;";
	
*/

$orgEsc = mysqli_real_escape_string($link, $org);

$strSQL2 = "
SELECT
  inv.JOB,
  inv.COMPANY,
  inv.PO,
  inv.INVNUM,
  inv.TERMS,
  inv.INVDATE,
  ROUND(inv.PAIDAMOUNT, 2) AS PAIDAMOUNT,
  ROUND(inv.AMOUNT, 2)     AS AMOUNT,
  ROUND(inv.UNPAID, 2)     AS UNPAID
FROM (
  SELECT
    wt.company_id                         AS JOB,
    MAX(wt.company_name)                  AS COMPANY,   -- or ANY_VALUE(...)
    MAX(wt.po_number)                     AS PO,        -- or ANY_VALUE(...)
    wt.invoice_number                     AS INVNUM,
    MAX(wt.terms)                         AS TERMS,
    MAX(wt.invoice_date)                  AS INVDATE,
    SUM(wt.billrate * wt.Hours)           AS AMOUNT,
    COALESCE(SUM(wt.paid_amount), 0)      AS PAIDAMOUNT,
    (SUM(wt.billrate * wt.Hours) - COALESCE(SUM(wt.paid_amount),0)) AS UNPAID
  FROM ic_timesheets wt
  WHERE wt.company_id = '{$orgEsc}'
    AND (wt.void = 0 OR wt.void IS NULL OR wt.void = '')
    AND wt.invoice_number > 0
  GROUP BY wt.invoice_number, wt.company_id
) AS inv
";

if (!isset($_REQUEST['paid_invoices']) || $_REQUEST['paid_invoices'] == 0) {
  // tolerance of > 1; use >= 0.01 if you want a true “still owes” filter
  $strSQL2 .= "WHERE inv.UNPAID > 1 ";
}

$strSQL2 .= "ORDER BY inv.INVNUM ASC";

 // echo $strSQL2;

$result = mysqli_query($link,$strSQL2);	
$PageNo = 0;

// while ($row4 = mysqli_fetch_array($result)) {
	$row4 = mysqli_fetch_array($result);

	$PageNo = $PageNo + 1;
	$sBPID = ($row4['APCCEMAIL'] ?? '');
	$statement = '
	<div style = "
	clear:both;
	height: 1000px;
	width: 700px; 
    margin-left: auto; 
    margin-right: auto; 
    border-radius: 20px 20px 20px 20px;
    padding: 40px; 
    font-family:Arial, Helvetica, sans-serif; 
    font-size:12px; 
    background-color: #FFFFFF; 
    border: 2px solid #A50F14; 
    background-image:url('. "'". 'http://port.icreatives.com/webtime/email/images/assets/background.gif' . "'". ');
    background-position:center; 
    background-repeat:no-repeat; 
    vertical-align: top;">


	<div style="float:left; padding: 20px 0 0 10px;">
		<div>
			<a border="0" href="http://port.icreatives.com">
			<img width="110" border="0" height="123" style="margin:15px 0px" alt="i creatives logo" src="https://port.icreatives.com/webtime/email/images/assets/logo.gif">
			</a>
		</div>
	</div>
	<div style=" float:right; text-align:right; width:500px;">
		<div style="float:right; padding:20px 0 0 0;">
			<h1 style="color:#a4100c; margin:0; padding:2px; font-size:50px;">Statement</h1>
		</div>
		<div style="clear:right; float:right; padding:0px 2px 0 0; width:230px;">
			<span style="color:#000000; margin:0; font-size:20px">
			<b>page no: </b>'.$PageNo.'</span>
		</div>
		<div style="clear:right; float:right; padding:0px 2px 0 0; width:230px;">
			<span style="color:#000000; margin:0; font-size:20px">
			<b>date: </b>'.date("m/d/Y").'</span>
		</div>
		<div style="clear:right; float:right; padding:0px 2px 0 0; width:300px;"></div>
		<div style="clear:right; float:right; padding:0px 2px 0 0;  width:330px;"></div>
		<div style="clear:right; float:right; padding:0px 2px 0 0;  width:230px;">
		<span style="color:#000000; margin:0; font-size:20px">';
		
					// special Walmart Stuff, no interest and show vendor no	
					// $walmart_test =  substr_count(strtoupper($row4["COMPANY"]), "WALMART");  
					// $sams_test =  substr_count(strtoupper($row4["COMPANY"]), "SAMS");  
					
					
					// echo $row4["COMPANY"]."xLL".$walmart_test."xLL";

					
					if (!empty($ap_info['vendor_number'])) {$statement = $statement . '<b>vendor no:</b> '.$ap_info['vendor_number'].'<br>';} else {
					$statement = $statement . '<b>customer no:</b>'.$row4["JOB"].'<br>'; } 
		
		
		
			$statement = $statement .'</span>
		</div>
	</div>
	<div style="clear:left; width:720px;float :left; padding: 10px 0 0 0px; font-size:14px;">
		<div style="float:left; width:65px;">Remit To:</div>
		<div style="float:left; font-size:14px; padding: 0px 0 10px 10px; width:165px;">
			i creatives <br /> 
			operations center <br /> 		
			po box 551450<br />
			fort lauderdale, fl 33355
		</div>
	</div>
	<div style="clear:left; float:left; padding: 10px 0 0 0px; font-size:14px;  width:720px;">
		<div style="float:left;  width:65px; padding: 0 0 0 0; font-size:14px;">Bill To:</div>	
		<div style="float:left; font-size:14px; padding: 0px 0 0 10px; width:300px;">
			Accounts Payable<br />'.
			$ap_info["company_name"].'<br />'.
			$ap_info["full_name"].'<br />'.
			$ap_info['address1'].'<br />';
			if(isset($ap_info['address2']) && !empty($ap_info['address2'])) { 
				$statement = $statement . $address2 ."<br />";
			}
			$statement = $statement . $ap_info['city'] . ', ' . $ap_info['state'] . ' ' . $ap_info['postalcode'];
			$statement = $statement . '
		</div> 

		<div style="clear:both; float:left: width:720px;">
			<div style="clear:both; float:left; padding:50px 0 5px 0;  font-size:16px; text-align:center;">
				<div style="float:left; width:130px;">Invoice Number</div>
				<div style="float:left; width:115px;">Invoice Date</div>
				<div style="float:left; width:115px;">Past Due</div>
				<div style="float:left; width:115px;">Amount</div>
				<div style="float:left; width:115px;">Amt Paid</div>
				<div style="float:left; width:115px;">Amt Due</div>
			</div>
		</div>';

		$pCount = 1;
		$sBPID = $org;
		$Total = 0;
		// echo $strSQL2;
		$result5 = mysqli_query($link,$strSQL2);
		while ($row5 = mysqli_fetch_array($result5)){
			if ($row5['AMOUNT'] > $row5['PAIDAMOUNT']) {
			$pCount = $pCount + 1;
			if ($pCount > 30) {exit;}
			/*
			if($_REQUEST['JOB'] <> $sBPID) {
				exit;
			} else {
				$sBPID = $_REQUEST['JOB'] ;
			}
			*/
			$daysLate = max(0, (new DateTime())->diff(new DateTime($row5['INVDATE']))->days - $terms);
			// echo "DDD". $daysLate ."DDD";
			$url = 'https://port.icreatives.com/api/customer/view_invoice.php?invnum='.encrypt_string($row5['INVNUM']);
							
			$interest_calc = calculateInterest($row5['INVDATE'], $row5['AMOUNT']-$row5['PAIDAMOUNT'], $terms);
			$LateAmount = $interest_calc['totalAmount'];
			$daysLate = $interest_calc['daysOverdue'];
			
			// let's not embed java in pdf "<a href='javascript:void(0);' onclick='openPopup(". '"'. $url. '"'. ");'>". $row5['INVNUM'].
			$statement = $statement . '

			<div style="clear:both; padding:5px 0 0 0;  font-size:14px;">
				<div style="float:left; width:130px; text-align:center;">';
				// do not include javascript in emailed version
				if(!empty($_REQUEST['org'])) {
					$statement = $statement ."<a href='javascript:void(0);' onclick='openPopup(". '"'. $url. '"'. ");'>". $row5['INVNUM'];
				} else {				
				$statement = $statement . '<a target="_blank" href=  "'. $url. '">'. $row5["INVNUM"]; 
				}
				$statement = $statement . '</a>
				</div>
				<div style="float:left; width:115px; text-align:center;">'.$row5['INVDATE']. '</div>
				<div style="float:left; width:115px; text-align:center;">&nbsp;&nbsp;'. $daysLate. ' days</div>
				<div style="float:left; width:115px; text-align:right;">'. number_format($row5['AMOUNT'],2, '.', ','). '</div>
				<div style="float:left; width:115px; text-align:right;">'. number_format($row5['PAIDAMOUNT'],2, '.', ','). '</div>';
					
				if ($ap_info['waive_interest'] > 0 ) {
						$Total = $Total + $row5['AMOUNT'];
					$statement .= '
					<div style="float:left; width:115px; text-align:right;">'
					. number_format($row5['AMOUNT'] - $row5['PAIDAMOUNT'], 2, '.', ',')
					. '</div></div>';
				} else {
					$Total = $Total + $LateAmount;
					$statement .= '
					<div style="float:left; width:115px; text-align:right;">'
					
					. number_format($LateAmount, 2, '.', ',')

					. '</div></div>';
				}
// 		. number_format(($row5['billrate'] < 0) ? ($row5['AMOUNT'] - $row5['PAIDAMOUNT']) : max(0, $row5['AMOUNT'] - $row5['PAIDAMOUNT']),2,'.',',')
		}
		}
					
		$statement = $statement .'
		<div style="float:left; width:655px; padding:55px 0 10px 425px; height:00px;">
			<div style="float:left; height:3px;"> &nbsp;</div>
			<div style="background-color: White;
				border: 2px solid #A50F14;
				border-radius: 20px 20px 20px 20px;
				margin:0;
				height: 110px;
				width: 300px; 
				font-family:Arial, Helvetica, sans-serif; 
				color:#a4100c; 
				padding:4px 0 0 0; 
				font-size:16px; 
				text-align:center;">
				<div style="clear:both; padding:5px 0 0 20px;  color:black; font-size:14px;">
					<div style="float:left; width:80px; text-align:right; padding-right:10px;">Sub-Total</div>
					<div style="float:left; width:170px; text-align:right;">'. number_format($Total - ($row5['PAIDAMOUNT'] ?? 0), 2). '</div>
				</div>
				<div style="clear:both; padding:5px 0 0 20px;  color:black; font-size:14px;">
				<div style="float:left; width:80px; text-align:right;padding-right:10px;">Sales Tax</div>
				<div style="float:left; width:170px; text-align:right;">0.00</div>
			</div>
			<div style="clear:both; padding:0px 0 0 20px;  color:black; font-size:14px;">
				<div style="float:left; width:80px; text-align:right;">&nbsp;</div>
				<div style="float:left; width:170px; text-align:right;"><hr></div>
			</div>
			<div style="clear:both; padding:0px 0 0 20px;  color:black; font-size:14px;">
				<div style="float:left; width:80px; text-align:right; padding-right:10px;">Total</div>
				<div style="float:left; width:170px; text-align:right;">' . number_format($Total - ($row5['PAIDAMOUNT'] ?? 0 ), 2, '.', ',') . '</div>
			</div>
		</div>

		<div style="float:left; width:300px; text-align:center;">
			<b>Terms are '. $terms . ' days</b>
		</div>
	</div>  
</div>';
	// $statement = $statement .'</div> </div>  <BR style="page-break-after: always">
$statement = $statement .'<BR style="page-break-after: always">';

// echo "XXX".$_REQUEST['org']."XXX";
if(!empty($_REQUEST['org'])) { 
echo $statement;
}
echo "</body></html>	  ";
// }

?>