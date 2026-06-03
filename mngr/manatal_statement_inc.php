<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<?php 

/*
echo $bytes = openssl_random_pseudo_bytes(16); 
echo" XXX ";
echo $key = bin2hex($bytes);
exit();
*/


function calculateInterest1($invoiceDate, $invoiceAmount, $termsInDays) {
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
	$ap_info = Contact_Info1($link, $row4["JOB"]); 
	$statement = '
	<div style = "
	clear:both;
	height: 800px;
	width: 750px; 
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
	<div style="clear:left; width:600px;float :left; padding: 10px 0 0 0px; font-size:14px;">
		<div style="float:left; width:65px;">Remit To:</div>
		<div style="float:left; font-size:14px; padding: 0px 0 10px 10px; width:165px;">
			i creatives <br /> 
			operations center <br /> 		
			po box 551450<br />
			fort lauderdale, fl 33355
		</div>
	</div>
	<div style="clear:left; float:left; padding: 10px 0 0 0px; font-size:14px;  width:600px;">
		<div style="float:left;  width:65px; padding: 0 0 0 0; font-size:14px;">Bill To:</div>	
		<div style="float:left; font-size:14px; padding: 0px 0 0 10px; width:200px;">
			Accounts Payable<br>'.
			$row4["COMPANY"].'<br>'.
			$ap_info["address1"].'<br>';
			if($ap_info['address2'] <> "") { 
				$statement = $statement . $ap_info['address2'] ."<br>";}
			$statement = $statement . $ap_info["city"] . ', ' . $ap_info["state"]  . ' ' . $ap_info["postalcode"];
			$statement = $statement . '
		</div>

		<div style="clear:both; float:left: width:600px;">
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
			// echo $query2;
			$pCount = $pCount + 1;
			if ($pCount > 50) {exit;}
			
			if($_REQUEST['JOB'] <> $sBPID) {
				exit;
			} else {
				$sBPID = $_REQUEST['JOB'] ;
			}
			$terms = $ap_info['terms'];
			$daysLate = max(0, (new DateTime())->diff(new DateTime($row5['INVDATE']))->days - $terms);
			// echo "DDD". $daysLate ."DDD";
			$url = 'https://port.icreatives.com/view_invoice.php?invnum='.encrypt_string($row5['INVNUM']);
			$statement = $statement . '

			<div style="clear:both; padding:5px 0 0 0;  font-size:14px;">
				<div style="float:left; width:130px; text-align:center;">'.
				'<a href="'.$url. '">'. $row5['INVNUM']. '</a>
				</div>
				<div style="float:left; width:115px; text-align:center;">'.$row5['INVDATE']. '</div>
				<div style="float:left; width:115px; text-align:center;">&nbsp;&nbsp;'. $daysLate. ' days</div>
				<div style="float:left; width:115px; text-align:right;">'. number_format($row5['AMOUNT'],2, '.', ','). '</div>
				<div style="float:left; width:115px; text-align:right;">'. number_format($row5['PAIDAMOUNT'],2, '.', ','). '</div>';
					// $terms  = row5['TERMS'];
					
					if(empty($terms) || or is_null($terms)) {$terms = 10;}
					
					// special Walmart Stuff, no interest and show vendor no
					// $walmart_test =  substr_count(strtoupper($row4["COMPANY"]), "WALMART");  
					// $sams_test =  substr_count(strtoupper($row4["COMPANY"]), "SAMS");  
					
	// echo "XXX".$row5["company_name"];
	// exit();
					$LateAmount = number_format(calculateInterest1($row5['INVDATE'], $row5['AMOUNT'], $terms),2, '.', ',');

					if ($ap_info['waive_interest'] > 0  ) {$LateAmount = $row5['AMOUNT']} 

				 $statement = $statement .'
				<div style="float:left; width:115px; text-align:right;">'.$LateAmount.'</div>
			</div>';

			

			$Total = $Total + $LateAmount;

		}
		// 		<div style="float:left; width:655px; padding:55px 0 10px 425px; height:00px;">
		// $statement = $statement .'</div> </div>  <BR style="page-break-after: always">
		$statement = $statement .'
		<div style="float:left; width:300px; padding:20px 0 10px 0; margin-left:auto;">
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
				<div style="clear:both; padding:15px 0 0 20px;  color:black; font-size:14px;">
					<div style="float:left; width:80px; text-align:right; padding-right:10px;">Total</div>
					<div style="float:left; width:170px; text-align:right;">'. number_format($Total, 2, ".", ","). '</div>
				</div>
				<div style="clear:both; padding:5px 0 0 20px;  color:black; font-size:14px;">
				<div style="float:left; width:80px; text-align:right;padding-right:10px;">Sales Tax</div>
				<div style="float:left; width:170px; text-align:right;">0.00</div>
			
				<div style="clear:both; padding:0px 0 0 20px;  color:black; font-size:14px;"></div>
				<div style="float:left; width:80px; text-align:right;">&nbsp;</div>
				<div style="float:left; width:170px; text-align:right;"><hr></div>
			</div>
			<div style="clear:both; padding:0px 0 0 20px;  color:black; font-size:14px;">
				<div style="float:left; width:80px; text-align:right; padding-right:10px;">Total</div>
				<div style="float:left; width:170px; text-align:right;">' . number_format($Total, 2, '.', ',') . '</div>
			</div>
		</div>

		<div style="float:left; width:300px; text-align:center;">
			<b>Terms are '. ap_info['terms'] . ' days</b>
		</div>
	</div>  
</div>';

}

?>