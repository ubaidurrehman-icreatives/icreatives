<?php
$inv_total = 0;
$invoice = '<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>
<body>';

$page_no = 0;
$vendor_number = $row3["vendor_number"] ?? '';
$terms = $row3["terms"] ?? 10;

$result2 = mysqli_query($link,$query2);

// Fetch all rows into an array
$rows = array();
while ($row = $result2->fetch_assoc()) {
    $rows[] = $row;
}

$totalItems = count($rows);
$itemsPerPage = 12;
$totalPages = ceil($totalItems / $itemsPerPage);
$pageNumber = 1;
$itemCount = 0;

// while ($row5 = mysqli_fetch_array($result2)) { 
while ($pageNumber <= $totalPages) {
	$terms = $row3["terms"] ?? 10;

	$item_no = 0;
	$page_no = $page_no + 1;
	$invoice = $invoice .'
  <table align="center" style="
    width: 600px; 
	height:875px;
    border-radius: 20px 20px 20px 20px;
    padding: 25px; 
    font-family:Arial, Helvetica, sans-serif; 
    font-size:12px; 
    background-color: #FFFFFF; 
    border: 2px solid #A50F14; 
    background-image:url("https://port.icreatives.com/webtime/email/images/assets/background.gif"); 
    background-position:center; 
    background-repeat:no-repeat; 
    vertical-align: top;">
    <tbody>
   <tr>
   <td width="100%" align="left" valign="top" height="150">
		<table style="vertical-align: top; width: 100%; float:left; padding: 20px 0 0 10px; border:0">
			<tr>
				<td valign="top" style=" width: 110px; height:123px; vertical-align: top;">
					<a border="0" href="https://www.icreatives.com">
					<img width="110" border="0" height="123" style="margin:15px 0px" alt="i creatives logo" src="https://port.icreatives.com/webtime/email/images/assets/logo.gif">
					</a>
				</td>
				<td> </td>
				<td style=" float:right; text-align:right; width:525px;padding:20px 0 0 0;">
				<h1 style="color:#a4100c; margin:0; padding:2px; font-size:50px;">Invoice</h1>
				<span style="color:#000000; margin:0; font-size:15px">
				<b>inv no: </b>'.$row3["INVNUM"].'<br>
				<b>page no: </b>'. $page_no .'<br>
				<b>date: </b>'.date("m/d/Y",strtotime($row3["INVDATE"] )).'<br>';
				if (!empty($row3["PO"])) {
					$invoice=$invoice. '<b>po no: </b>'.$row3["PO"].'<br>';
				}
				// special Walmart Stuff, no interest and show vendor no
					$full_name_on_invoice = $row3['full_name_on_invoice'];
					$walmart_test =  substr_count(strtoupper($row3["COMPANY"]), "WALMART");  
					$sams_test =  substr_count(strtoupper($row3["COMPANY"]), "SAMS");  	
					if (!empty($row3['vendor_number'])) {
						$invoice=$invoice. '<b>vendor no: </b>'.$row3['vendor_number'].'<br>';
					} else {
					$invoice=$invoice. '<b>customer no: </b>'.$row3["JOB"].'<br>'; 
					} 
					// $ap_info = Contact_Info($link, $row3["JOB"],$row3["PROJ"]); 
					
					
				$invoice=$invoice. '
				</span>
				</td>
			</tr>
		</table>
    </td>
	</tr>
	<tr width="100%" align="left" valign="top">
		   <td width="100%" height="125" align="left" valign="top">
		   <div style="float:left;width:50%;">
			<table style="width:100%; align:left; vertical-align:top;">
				<tr>	
					<td style="width:100%; align:left; vertical-align:top; float:left;  width:65px;font-size:12px;">
						Remit To:
					</td>
					<td style="float:left; font-size:12px; padding: 0px 0 0 10px; width:200px;">
						i creatives <br>  
						Operations Center <br> 		
						PO Box 551450<br>
						Fort Lauderdale, FL 33355
					</td>
				</tr>
				<tr>
					<td style="width:100%; align:left; vertical-align:top; float:left;  width:65px; padding-top:10px;font-size:12px;">
						Bill To:
					</td>
					<td style="width:100%; align:left; vertical-align:top; float:left;  width:200px; padding:10px 0px 0px 10px; font-size:12px;">
						'.$row3["APNAME"].'<br>
						'.$row3["COMPANY"].'<br>
						'.$row3["address1"].'<br>';
						
						if( !empty($row3["address2"])) {$invoice=$invoice. $row3["address2"].'<br>';} 
						$invoice=$invoice.$row3["city"].", ".$row3["state"]. ' '. $row3["postalcode"].'<br>
						
					</td>
				</tr>
			</table>
			</div>
			<div style="float:right; padding-top:25px;">
						
						<div style="float: right; background-color: white; border: 2px solid #A50F14; 
						border-radius: 20px 20px 20px 20px; margin: 0; width: 200px; font-family: Arial, 
						Helvetica, sans-serif; color: #a4100c; padding: 4px 2px 4px 2px; font-size: 12px; 
						text-align: center;">Bank Remittance Information:<br />JP Morgan Chase<br />
						ACH Routing #: 267084131<br />Wire Routing #: 021000021<br />Account #: 299818531
						</div>
			</div>	
		</td>
	</tr>
	<tr>
		 <td valign="top" >
		 <div style="height:10px;"> </div>
			<table style="font-size:12px;">
				<tr>
					<td style="width:80px;text-align:center;">Name</td>
					<td style="width:175px;text-align:center;">Description</td>
					<td style="width:100px;text-align:center;">Wk Ending</td>
					<td style="width:88px;text-align:center;">Type</td>
					<td style="width:50px;text-align:center;">Qty</td>
					<td style="width:70px;text-align:center;">Rate</td>
					<td style="width:70px;text-align:center;">Total</td>
				</tr>

				<!-- Start Repeating -->';

				// $result2 = mysqli_query($link,$query2);
				if (isset($row5['INVNUM'])) {$inv_num = $row5['INVNUM'];}
				

				// while ($row5 = mysqli_fetch_array($result2)) { 
				   // Print 25 items for this page
				    
				for ($i = 0; $i < $itemsPerPage && $itemCount < $totalItems; $i++) {
					$row5 = $rows[$itemCount];
					// $item_no = $item_no + 1;
					$unique_array[] = $row5["UNUMB"];
					$invoice=$invoice.'
					<tr>
						<td style="width:100px; text-align:left;">';
						// echo $full_name_in_invoice = $ap_info["full_name_on_invoice"];
						
						if ($walmart_test || $sams_test) {
							$full_name_on_invoice = 1;
						}
						if($full_name_on_invoice == 1) {$name = $row5["FIRST"]. " ". $row5["LAST"];} else {$name = $row5["FIRST"];}
				
						if ($row5["TYPE"]== 't') {$worktype="Contractor";
						}else if ($row5["TYPE"]== 'f') {$worktype="Placement";
						}else{$worktype="";}

						$invoice=$invoice . $name.' </td>
						<td style="width:185px; text-align:left;">';
						if ($row5["TYPE"] == 'f' || $row5["TYPE"] == 'e') {
							$invoice=$invoice . $row5["ITEM"]. '</td.>';
						} else {
							$invoice=$invoice .substr($row5["ITEM"],0,29).'</td>';
						}
						$invoice=$invoice . '
						<td style="width:100x; padding:right:5px; text-align:center;">'.date("m-d-Y",strtotime($row5["WKEND"])).'</td>  
						<td style="width:40px; text-align:center;">'. $worktype . '</td>       
						<td style="width:50px; text-align:center;">'.$row5["HOURS"].'</td>
						<td style="width:70px; text-align:right;">'.number_format($row5["BILLRATE"],2).'</td>
						<td style="width:70px; text-align:right;">'.number_format($row5["HOURS"] * $row5["BILLRATE"],2).'&nbsp;</td>
					</tr>';
					$inv_total = $inv_total + round($row5["HOURS"] * $row5["BILLRATE"],2); 
					$itemCount++;
				}
				$pageNumber++;
				$invoice=$invoice.'</table>';
				
				if ( $pageNumber > $totalPages) {
					$invoice=$invoice.'
				
				<!-- End Repeating -->
				<table style="font-size:12px; width:100%">
				<tr>
					<td style="align:left; width:50%; padding:20px 0 10px 0;"><b>&nbsp;&nbsp;&nbsp;Payment Due</b>
						
					<div style="background-color: White;
						border: 2px solid #A50F14;
						border-radius: 20px 20px 20px 20px;
						margin:0;
						height: 25px;
						width: 300px; 
						font-family:Arial, Helvetica, sans-serif; 
						color:#a4100c; 
						padding:4px 0 0 0; 
						font-size:16px; 
						text-align:center;">

						<b>'.date("m-d-Y", strtotime($row3["INVDATE"]. " + ". $terms ."days")).'</b>

					</div>
					<b>&nbsp;&nbsp;&nbsp;Terms are Net '.$terms.' days</b>
					</td>
					<td style="align:right; width:50%;">
						<table width="100%" border="0" cellpadding="0">
							<tr style="text-align:right;">
								<td style="text-align:right;width:100px;font-size:12px;"> </td>
								<td style="text-align:right;width:50px;font-size:12px;"><hr></td>
							</tr>
							<tr style="text-align:right;">
								<td style="text-align:right;width:100px;font-size:12px;">Subtotal:</td>
								<td style="text-align:right;width:50px;font-size:12px;">$ '.number_format($inv_total,2).'</td>
							</tr>
							<tr style="text-align:right;">
								<td style="text-align:right;width:100px;font-size:12px;">Tax:</td>
								<td style="text-align:right;width:50px;font-size:12px;">0.00</td>
							</tr>
							<tr style="text-align:right;">
								<td style="text-align:right;width:100px;font-size:12px;"> </td>
								<td style="text-align:right;width:50px;font-size:12px;"><hr></td>
							</tr>
							<tr style="text-align:right;">
								<td style="text-align:right;width:100px;font-size:12px;">Total:</td>
								<td style="text-align:right;width:50px;font-size:12px;">$ '.number_format($inv_total,2).'</td>
							</tr>';
							$interest_calc = calculateInterest($row3["INVDATE"], $inv_total-($row3["PAIDAMOUNT"] ?? 0), ($terms ?? 10) );
								$totalDue = $interest_calc['totalAmount'];
								$daysOverdue = $interest_calc['daysOverdue'];
								$interest = $interest_calc['interest'];
								// if ($totalDue ==$inv_total) { $totalDue = 1.18 * $totalDue; }
								
							// echo "UUUU".$interest."XXX".$totalDue."XXX".$daysOverdue ;
							
							if($inv_total-( ($row3['PAIDAMOUNT'] ?? 0) > 0) && $daysOverdue < 5) {
								$invoice=$invoice.'
	
							<tr style="text-align:right;">
								<td style="padding-top:20px;text-align:right;width:100px;font-size:12px;">';
								if (($row3['waive_late_fee'] ?? 0) == 0 &&  ($row3['PAIDAMOUNT'] ?? 0) == 0) {
									$invoice=$invoice.'
							
								After ' .$row3["terms"]. ' days:</td>
								<td style="padding-top:20px;text-align:right;width:50px;font-size:12px;">';
								$invoice=$invoice.'$ '.number_format($totalDue, 2);
								}
			
								/*
									$date1_ts = strtotime($row3["INVDATE"]);
									$date2_ts = time();
									$diff = ($date2_ts - $date1_ts)/86400;
									$late_amount = money_format(" %(n", $inv_total + (($diff * 18/365 * $inv_total )/100));
									$invoice=$invoice.money_format(" %(n", $late_amount).'
								*/
								
								
								// if ($walmart_test == 0 && $sams_test == 0 ) {
									
								// }
							}
							if ($inv_total - ($row3['PAIDAMOUNT'] ?? 0) > 0 ) {		
							

								
							if (($row3['PAIDAMOUNT'] ?? 0) > 0) {
							$invoice=$invoice.'<tr style="text-align:right;">
								<td style="padding-top:5px;text-align:right;width:100px;font-size:12px;">Amount Paid:</td>
								<td style="padding-top:5px;text-align:right;width:50px;font-size:12px;">';
								$invoice=$invoice."$ ".number_format($row3['PAIDAMOUNT'], 2);
								/*
									$date1_ts = strtotime($row3["INVDATE"]);
									$date2_ts = time();
									$diff = ($date2_ts - $date1_ts)/86400;
									$late_amount = money_format(" %(n", $inv_total + (($diff * 18/365 * $inv_total )/100));
									$invoice=$invoice.money_format(" %(n", $late_amount).'
								*/
											
								$invoice=$invoice.'</td>
							</tr>';
							}
							// if ($inv_total - $row3['PAIDAMOUNT'] > 0 && $row3['PAIDAMOUNT'] > 0 ) {
							if ($inv_total - ($row3['PAIDAMOUNT'] ?? 0) > 0 && $daysOverdue > 5 && $row3['waive_interest'] == 0) {
								$invoice=$invoice.'<tr style="text-align:right;">
								<td style="padding-top:5px;text-align:right;width:100px;font-size:12px;">Late Fee</td>
								<td style="padding-top:5px;text-align:right;width:50px;font-size:12px;">';							
								// $invoice=$invoice.'$ '. number_format($row3['PAIDAMOUNT']-$inv_total,2) .'</td>
								$invoice=$invoice.'$ '. number_format($interest,2) .'</td>
							</tr>';
								
							
							$invoice=$invoice.'
							<tr style="text-align:right;">
								<td style="padding-top:5px;text-align:right;width:100px;font-size:12px;">Amount Due:</td>
								<td style="padding-top:5px;text-align:right;width:50px;font-size:12px;">';							
								if($inv_total-$row3['PAIDAMOUNT'] <= 0) {
									$totalDue = 0;
								} else {
									$totalDue = $inv_total-$row3['PAIDAMOUNT']+$interest;
								}
								$invoice=$invoice.'$ '.number_format($totalDue, 2).'
								</td>
							</tr>';
							}}
			
								$invoice=$invoice.'											
							
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			
		</td>
	</tr>
	</tbody>
</table>
	  
	  
	  <br style="page-break-after: always"></div>';
				}else{
		$invoice = $invoice.'<br style="page-break-after: always">';		
				}
$invoice = $invoice.'</table></body></html>';
				}
//<!-- <div style="Height:' . strval(150- 3.5*(30-$item_no)). 'px;"> -->
