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
echo $bytes = openssl_random_pseudo_bytes(16); 
echo" XXX ";
echo $key = bin2hex($bytes);
exit();
*/

function calculateInterest($invoiceDate, $invoiceAmount, $termsInDays) {
    $interestRate = 0.18; // 18% interest rate

    // Calculate today's date
    $todayDate = date('Y-m-d');

    // Calculate the number of days from the invoice date to today
    $invoiceTimestamp = strtotime($invoiceDate);
    $todayTimestamp = strtotime($todayDate);
    $daysOverdue = max(0, floor(($todayTimestamp - $invoiceTimestamp) / (60 * 60 * 24)));

    // Calculate interest based on the due date
    if ($daysOverdue <= $termsInDays) {
        // Interest calculated based on the terms
        // $totalAmount = ($invoiceAmount + ($invoiceAmount * $interestRate * $termsInDays / 365));
		$totalAmount = ($invoiceAmount + ($invoiceAmount * $interestRate * 30/ 365));
    } else {
        // Interest calculated based on the difference between today and invoice date
        $totalAmount = ($invoiceAmount + ($invoiceAmount * $interestRate * $daysOverdue / 365));
    }
    return $totalAmount;
}


/*
$bytes = openssl_random_pseudo_bytes(16); 
$key = bin2hex($bytes);


function encrypt_string($plaintext) {
$key = "4b14783a4b5848cd43b9fc7e4cf0fb6f";
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
$iv = openssl_random_pseudo_bytes($ivlen); 
$ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true); 
// Encrypted string 
return = base64_encode($iv.$hmac.$ciphertext_raw);
}





Decrypt String using PHP:
Transform ciphertext back to original plaintext with key using openssl_decrypt() function in PHP.

$key = 'YOUR_SALT_KEY'; // Previously used in encryption 
$c = base64_decode($ciphertext); 
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC"); 
$iv = substr($c, 0, $ivlen); 
$hmac = substr($c, $ivlen, $sha2len=32); 
$ciphertext_raw = substr($c, $ivlen+$sha2len); 
$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); 
$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true); 
 
if(hash_equals($hmac, $calcmac)){ //PHP 5.6+ Timing attack safe string comparison 
  echo 'Original String: '.$original_plaintext; 
}else{ 
  echo 'Decryption failed!'; 
}


function Contact_Info($link, $organization) {
    $f_query = "select full_name, terms, email, phone_number, one_invoice_per_candidate,address1,address2,city,state,postalcode,country,created_at from ic_company where organization = '" . $organization . "'";
    $SQL = mysqli_query($link, $f_query);
    if (!$SQL) {
        die('Query failed: ' . mysqli_error($link));
    }
    $row3 = mysqli_fetch_array($SQL);

    $infoArray = array(
        'full_name' => $row4['full_name'],
        'terms' => $row4['terms'],
		'address1' => $row4['address1'],
		'address2' => $row4['address2'],
        'email' => $row4['email'],
		'city' => $row4['city'],
		'state' => $row4['state'],
		'postalcode' => $row4['postalcode'],
		'country' => $row4['country'],
		'phone_number' => $row4['phone_number'],
		'created_at' => $row4['created_at'],
		'terms' => $row4['terms'],
        'one_invoice_per_candidate' => $row4['one_invoice_per_candidate']
    );

    return $infoArray;
}

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

if (!$link) {
    die('Connection failed: ' . mysqli_connect_error());
}

// sBillingProfileKey = request.QueryString("StateNum")
$strSQL = "SELECT ";
$strSQL = $strSQL . " wt.first_name as FIRST ";
$strSQL = $strSQL . ", wt.last_name as LAST ";
$strSQL = $strSQL . ", wt.Employee_ID as EMPID ";
$strSQL = $strSQL . ", wt.billrate as BILLRATE ";
$strSQL = $strSQL . ", wt.payrate as PAYRATE ";
$strSQL = $strSQL . ", wt.Hours as HOURS ";
$strSQL = $strSQL . ", oj.organization as JOB ";
$strSQL = $strSQL . ", oj.company_name as COMPANY ";
$strSQL = $strSQL . ", oj.po_number as PO ";
$strSQL = $strSQL . ", REPLACE(wt.title,' ','_') as ITEM ";
$strSQL = $strSQL . ", wt.Assignment_ID as PROJ ";
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

$strSQL = $strSQL . ", wt.ApproveDate as APPROVE " ;
$strSQL = $strSQL . ", wt.SentDate as SENT " ;
$strSQL = $strSQL . ", wt.DeclineDate as DECLINE " ;
$strSQL = $strSQL . ", wt.Weekending as WKEND " ;
$strSQL = $strSQL . ", wt.invoice_number as INVNUM " ;
$strSQL = $strSQL . ", wt.terms as TERMS ";
$strSQL = $strSQL . ", wt.invoice_date as INVDATE ";
$strSQL = $strSQL . ", wt.paid_amount as PAIDAMOUNT  ";
$strSQL = $strSQL . ", wt.Signature as ESIG ";$strSQL = $strSQL . ", COALESCE (SUM(wt.billrate * wt.Hours),0)  as AMOUNT " ;
$strSQL = $strSQL . "from ic_timesheets wt ";
$strSQL = $strSQL . "LEFT JOIN ic_matches oj ON (oj.candidate = wt.employee_id AND oj.job = wt.Assignment_ID) ";
$strSQL = $strSQL . " WHERE wt.invoice_number > 0 AND oj.organization = '". $_REQUEST['JOB'] . "' ";
if ($_REQUEST['paid_invoices'] !== "1"  ) {
	$strSQL = $strSQL . " AND wt.paid_amount < 1";
} 

$strSQL2 = $strSQL . " AND NOT void GROUP BY oj.organization ORDER BY wt.invoice_number ";
*/


$result = mysqli_query($link,$strSQL);	
$PageNo = 0;
while ($row4 = mysqli_fetch_array($result)) {
	$PageNo = $PageNo + 1;
	$sBPID = $row4['APCCEMAIL'];
	$ap_info = Contact_Info($link, $row4["JOB"]); 
	$statement = '
	<div style = "
	clear:both;
	height: 1000px;
	width: 750px; 
    margin-left: auto; 
    margin-right: auto; 
    border-radius: 20px 20px 20px 20px;
    padding: 40px; 
    font-family:Arial, Helvetica, sans-serif; 
    font-size:12px; 
    background-color: #FFFFFF; 
    border: 2px solid #A50F14; 
    background-image:url('. "'". 'http://www.icreatives.com/webtime/email/images/assets/background.gif' . "'". ');
    background-position:center; 
    background-repeat:no-repeat; 
    vertical-align: top;">


	<div style="float:left; padding: 20px 0 0 10px;">
		<div>
			<a border="0" href="http://www.icreatives.com">
			<img width="110" border="0" height="123" style="margin:15px 0px" alt="i creatives logo" src="https://www.icreatives.com/webtime/email/images/assets/logo.gif">
			</a>
		</div>
	</div>
	<div style=" float:left; text-align:right; width:600px;">
		<div style="float:right; padding:20px 0 0 0;">
			<h1 style="color:#a4100c; margin:0; padding:2px; font-size:50px;">Statement</h1>
		</div>
		<div style="clear:right; float:right; padding:0px 2px 0 0; width:230px;">
			<span style="color:#000000; margin:0; font-size:20px">
			<b>page no: </b><?php echo $PageNo; ?></span>
		</div>
		<div style="clear:right; float:right; padding:0px 2px 0 0; width:230px;">
			<span style="color:#000000; margin:0; font-size:20px">
			<b>date: </b>'.date("m/d/Y").'</span>
		</div>
		<div style="clear:right; float:right; padding:0px 2px 0 0; width:300px;"></div>
		<div style="clear:right; float:right; padding:0px 2px 0 0;  width:330px;"></div>
		<div style="clear:right; float:right; padding:0px 2px 0 0;  width:230px;">
			<span style="color:#000000; margin:0; font-size:20px"><b>vendor no: </b>'. $row4["JOB"]. '</span>
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
		<div style="float:left; font-size:14px; padding: 0px 0 0 10px; width:200px;">
			Accounts Payable</br />'.
			$row4["COMPANY"].'<br />'.
			$ap_info["address1"].'<br />';
			if($ap_info['address2'] <> "") { 
				$statement = $statement . $ap_info['address2'] ."<br />";}
			$statement = $statement . $ap_info["city"] . ', ' . $ap_info["state"]  . ' ' . $ap_info["postalcode"];
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
		$sBPID = $_REQUEST['JOB'];
		$Total = 0;
		// echo "XXX".$query2;
		$result5 = mysqli_query($link,$query2);
		while ($row5 = mysqli_fetch_array($result5)){
			$pCount = $pCount + 1;
			if ($pCount > 20) {exit;}
			
			if($_REQUEST['JOB'] <> $sBPID) {
				exit;
			} else {
				$sBPID = $_REQUEST['JOB'] ;
			}
			
			$daysLate = max(0, (new DateTime())->diff(new DateTime($row5['INVDATE']))->days - $row5['TERMS']);
			// echo "DDD". $daysLate ."DDD";
			$url = 'https://www.icreatives.com/mngr/manatal_view_invoice.php?invnum='.encrypt_string($row5['INVNUM']);
			// echo "<td><a href='javascript:void(0);' onclick='openPopup(".$url.");'>". $row['INVNUM'] . "</a></td>";
			// $url = "http://www.icreatives.com/webtime/StatementInvoice.php?Varib=".$row5['INVNUM'];
			$statement = $statement . '

			<div style="clear:both; padding:5px 0 0 0;  font-size:14px;">
				<div style="float:left; width:130px; text-align:center;">'.
				"<a href='javascript:void(0);' onclick='openPopup(". '"'. $url. '"'. ");'>". $row5['INVNUM']. '</a>
				</div>
				<div style="float:left; width:115px; text-align:center;">'.$row5['INVDATE']. '</div>
				<div style="float:left; width:115px; text-align:center;">&nbsp;&nbsp;'. $daysLate. ' days</div>
				<div style="float:left; width:115px; text-align:right;">'. number_format($row5['AMOUNT'],2, '.', ','). '</div>
				<div style="float:left; width:115px; text-align:right;">'. number_format($row5['PAIDAMOUNT'],2, '.', ','). '</div>';
							
					$LateAmount = number_format(calculateInterest($row5['INVDATE'], $row5['AMOUNT'], $row5['TERMS']),2, '.', ',');
				 $statement = $statement .'
				<div style="float:left; width:115px; text-align:right;">'.$LateAmount.'</div>
			</div>';

			

			$Total = $Total + $LateAmount;

		}
		// $statement = $statement .'</div> </div>  <BR style="page-break-after: always">
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
					<div style="float:left; width:80px; text-align:right; padding-right:10px;">Total</div>
					<div style="float:left; width:170px; text-align:right;">'. number_format($Total, 2, ".", ","). '</div>
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
				<div style="float:left; width:170px; text-align:right;">' . number_format($Total, 2, '.', ',') . '</div>
			</div>
		</div>

		<div style="float:left; width:300px; text-align:center;">
			<b>Terms are '. $row4['TERMS'] . ' days</b>
		</div>
	</div>  
</div>';

}

?>