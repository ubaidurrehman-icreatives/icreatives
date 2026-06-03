<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php
include "db5.php";

foreach ($_GET as $param_name => $param_val) {
	// echo "XXX".$param_name ."XXX". $param_val."XXX";
    $post_name= $param_name; 
    $post_value = $param_val;
}

Function decode($sIn) {
	$InvString = "0";
	for( $x = 1; $x <= 8; $x++) {$InvString = $InvString . substr($sIn, ($x*9)-1,1) ; }
	Return $InvString;
}


// $sInvDate = "2012-10-10";
// $sCustomerID = "000041QY";
// $sInvNum = "IVC000000036211";
$billing_profile_key = SubStr($post_value,72,8);

$sInvNum = "IVC000" . decode($post_value);

/*
// echo "invnum = " . $sInvNum . "<BR>";
// echo "decode invoice = " . decode($post_value) . "<br>";
// echo "post name = " .     $post_name  . "<br>";
// echo "post value = " . $post_value . "<br>";
// echo "profile key = " . SubStr($post_value,72,8) . "<br>";
*/

$ShowLast = $_REQUEST["ShowLast"];

// Create an ADO recordset object


	$invSQL = "SELECT 'TIMEACT' as '!TIMEACT' ";
	$invSQL = $invSQL . ", cast(id.EMNameFirst as varchar(20)) as EMP ";
	$invSQL = $invSQL . ", cast(id.EMNameLast as varchar(20)) as EMPLAST ";
	$invSQL = $invSQL . ", REPLACE(cm.Customer_Name,' ','_') as CUSTOMER ";
	$invSQL = $invSQL . ", REPLACE(id.DVName,' ','_') as SERVTO";
	$invSQL = $invSQL . ", REPLACE(em.PostalCode,' ','_') as ZIP ";
	$invSQL = $invSQL . ", OM.Position_Title as TITLE ";
	$invSQL = $invSQL . ", '-' as NOTE, oa.Assignment_ID as PROJ ";
	$invSQL = $invSQL . ", oa.Order_ID as xORDER ";
	$invSQL = $invSQL . ", om.TakenContactKey as ORDERTAKENID ";
	$invSQL = $invSQL . ", om.StartContactKey as REPORTID ";
	$invSQL = $invSQL . ", om.SupervisorContactKey as SUPERVISORID ";
	$invSQL = $invSQL . ", om.UserDefined2 as HIDENAME ";
	$invSQL = $invSQL . ", '40' as DURATION ";
	$invSQL = $invSQL . ", '1' as BILLINGSTATUS ";
	$invSQL = $invSQL . ", 'CONTR' as PITEM ";
	$invSQL = $invSQL . ", '0' as BITEM ";
	$invSQL = $invSQL . ", id.OAPurchaseOrder as PO ";
	$invSQL = $invSQL . ", inv.DueDate as DUEDATE ";
	$invSQL = $invSQL . ", om.BillingProfileKey as BILLINGPROFILE ";
	$invSQL = $invSQL . ", inv.documentkey as INVNUM "  ;
	$invSQL = $invSQL . ", inv.documentDate as INVDATE " ; 
	$invSQL = $invSQL . ", inv.BPContactName as BPCONTACT "  ;
	$invSQL = $invSQL . ", inv.BPProfileDescription as BPCOMPANY "  ;
	$invSQL = $invSQL . ", inv.BPAddress1 as BPADDR1 "  ;
	$invSQL = $invSQL . ", inv.BPAddress2 as BPADDR2 "  ;
	$invSQL = $invSQL . ", inv.BPCity as BPCITY "    ;
	$invSQL = $invSQL . ", inv.BPCity as BPCITY "    ;
	$invSQL = $invSQL . ", inv.BPStateCode as BPSTATE "    ;
	$invSQL = $invSQL . ", inv.BPPostalCode as BPZIP "   ;
	$invSQL = $invSQL . ", inv.CMCustomerKey as CUSTOMERID "   ;
	$invSQL = $invSQL . ", CONVERT(VARCHAR, inv.Amount,1) as TOTAL "   ;
	$invSQL = $invSQL . ", CONVERT(VARCHAR, id.TaxAmount,1) as TAX "   ;
	$invSQL = $invSQL . ", inv.CreditTermsDescription as TERMS "   ;
//	$invSQL = $invSQL . ", bp.ProfileDescription  as BILLTONAME " ;
	$invSQL = $invSQL . ", bp.PurchaseOrder  as BUDGETCODE "   ;
	$invSQL = $invSQL . ", bp.InvoiceField01Data as VENDORNO "  ; 
	$invSQL = $invSQL . ", bp.InvoiceField01Break as SHOWLAST " ;
//	$invSQL = $invSQL . ", bp.StatementName as BILLTONAME " ;
	$invSQL = $invSQL . ", inv.BPTemplateCode as FORMAT ";
	$invSQL = $invSQL . ", ctm.First_Name as STFIRST "   ;
	$invSQL = $invSQL . ", ctm.Last_Name as STLAST "   ;
	$invSQL = $invSQL . ", ctm.Address1 as STADDR1 "   ;
	$invSQL = $invSQL . ", ctm.Address2 as STADDR2 "    ; 
	$invSQL = $invSQL . ", ctm.City as STCITY "     ;
	$invSQL = $invSQL . ", ctm.StateCode as STSTATE "     ;
	$invSQL = $invSQL . ", ctm.PostalCode as STZIP "     ;
	$invSQL = $invSQL . ", oc.InvoiceIsApproved as FULLTIME  "    ;
	$invSQL = $invSQL . ", om.InvoiceField01Data as LinkInit ";
	$invSQL = $invSQL . ", om.InvoiceField02Data as LinkCommit "   ;

 
	$invSQL = $invSQL . ", om.TakenContactKey as SERVICETO "  ;

	$invSQL = $invSQL . ", id.WeekEnding as WeekEnding ";
	$invSQL = $invSQL . ", CONVERT(VARCHAR, id.units,1) as QTY ";
	$invSQL = $invSQL . ", CONVERT(VARCHAR, id.UnitRate,1) as RATE ";
	$invSQL = $invSQL . ", CONVERT(VARCHAR, id.ARAmount,1) as COST ";
	$invSQL = $invSQL . ", id.EarningsCode as TYPE ";
  
	$invSQL = $invSQL . "from AR_INVOICE_DETAIL as id ";
	$invSQL = $invSQL . "LEFT JOIN orderassignment oa on oa.assignment_id = id.assignmentkey ";
	$invSQL = $invSQL . "JOIN AR_INVOICE inv on inv.documentkey = id.documentkey ";
	$invSQL = $invSQL . "LEFT JOIN ordermaster om on oa.Order_ID = om.Order_ID ";
	$invSQL = $invSQL . "LEFT JOIN ContactMaster ctm on ctm.Contact_ID = om.TakenContactKey " ;
	$invSQL = $invSQL . "LEFT JOIN employeemaster em on em.employee_ID = oa.Employee_ID ";
	$invSQL = $invSQL . "LEFT JOIN CustomerMaster cm on cm.Customer_ID = om.Customer_ID ";
	$invSQL = $invSQL . "LEFT JOIN CustomerBillingProfile bp on bp.BillingProfileKey = om.BillingProfileKey ";
	$invSQL = $invSQL . "LEFT JOIN OrderCareer oc on oc.order_ID = id.OrderKey ";

	$invSQL = $invSQL . " WHERE id.documentkey = '" . $sInvNum. "'  AND bp.BillingProfileKey = '". $billing_profile_key . "' AND id.VoidDate IS NULL ";

	if ($ShowLast  > 0) { 
		$invSQL = $invSQL . "ORDER BY EMPLAST ASC, EMP ASC, WeekEnding ASC" ;
	} else {
		$invSQL = $invSQL . "ORDER BY EMP ASC, EMPLAST ASC, WeekEnding ASC" ;
	}

echo $invSQL;
$resMySel = odbc_exec($conn,$invSQL);
$row = odbc_fetch_array($resMySel);

	$Total = $row["TOTAL"];
	$InvDate = $row["INVDATE"];
	$Terms = $row["TERMS"];
	$DueDate = $row["DueDate"];

// response.write( rsInvoice("EMP"))
// response.write( invSQL)

$count = 0 ;
$PageNo = 0 ;

IF ( !empty($row["xORDER"]) ) {
	$sInvNum = $row["INVNUM"];

	while($row = odbc_fetch_array($resMySel)){
		IF ( $row["INVNUM"] <> $sInvNum  ) {
			break;
		} ELSE {
			$sInvNum = $row["INVNUM"];
		}

		$PageNo = $PageNo++;
	}
}
		?>


		<div style = "
		height: 1000px;
		width: 800px; 

		margin-left: auto; 
		margin-right: auto; 
		border-radius: 20px 20px 20px 20px;
		padding: 40px; 
		font-family:Arial, Helvetica, sans-serif; 
		font-size:12px; 
		background-color: #FFFFFF; 
		border: 2px solid #A50F14; 
		background-image:url('https://www.icreatives.com/webtime/email/images/assets/background.gif');
		background-position:center; 
		background-repeat:no-repeat; 
		vertical-align: top;">


		<div style="float:left; padding: 20px 0 0 10px;">
			<div>
				<a border="0" href="https://www.icreatives.com" 
				img width="110" border="0" height="123" style="margin:15px 0px" alt="i creatives logo" src="https://www.icreatives.com/webtime/email/images/assets/logo.gif">
				</a>
			</div>
		</div>
		<div style=" float:left; text-align:right; width:600px;">
			<div style="float:right; padding:20px 0 0 0;">
				<h1 style="color:#a4100c; margin:0; padding:2px; font-size:50px;">Invoice</h1>
			</div>
			<div style="clear:right; float:right; padding:0px 2px 0 0; width:230px;">
				<span style="color:#000000; margin:0; font-size:20px">
	   			<b>inv no:</b>
	   			<?php echo( substr( $row["INVNUM"],5 ) ); ?></span>
      			</div>
      			<div style="clear:right; float:right; padding:0px 2px 0 0; width:230px;">
				<span style="color:#000000; margin:0; font-size:20px">
				<b>page no:</b>
				<?php echo(  $PageNo ); ?></span>
			</div>

			<div style="clear:right; float:right; padding:0px 2px 0 0; width:230px;">
				<span style="color:#000000; margin:0; font-size:20px">
				<b>date:</b>
				<?php echo( $row["INVDATE"] ); ?></span>
      			</div>
			<div style="clear:right; float:right; padding:0px 2px 0 0; width:300px;">
				<span style="color:#000000; margin:0; font-size:20px">
				<b>customer no:</b>
				<?php echo( $row["CUSTOMERID"]); ?></span>
			</div>
			<div style="clear:right; float:right; padding:0px 2px 0 0; width:300px;">
				<?php if ($row["PO"] <> "") {?>
					<span style="color:#000000; margin:0; font-size:20px">
					<b>po no:</b>
		 			<?php echo( $row["IPO"]); ?></span>
				<?php } ?>
      			</div>
      			<div style="clear:right; float:right; padding:0px 2px 0 0;  width:230px;">
				<?php if ($row["VENDORNO"] <> "" ) {?>
	    				<span style="color:#000000; margin:0; font-size:20px">
					vendor no:
		 			<?php echo( $row["IVENDORNO"]); ?></span>
				<?php } ?>
      			</div>
			<!--- New for walmart 07/2015 --->
      			<div style="clear:right; float:right; padding:0px 2px 0 0;  width:230px;">
				<?php if ($row["LinkInit"] <> "") { ?>
	    				<span style="color:#000000; margin:0; font-size:20px">
					<?php IF ($row["FORMAT"] == "WALMART" ) {?>
						<?php IF ($row["LinkInit"] <> "" ) { ?>
							lynk initiative ID:
		 					<?php echo( $row["LinkInit"]) ; ?></span>
						<?php } ?>
					<?php } ?>
				<?php } ?>
      			</div>
      			<div style="clear:right; float:right; padding:0px 2px 0 0;  width:230px;">
				<?php if( $row["LinkCommit"] <> ""  ) { ?>
	    				<span style="color:#000000; margin:0; font-size:20px">
					<?php IF ($row["FORMAT"] == "WALMART" ) { ?>
		   				lynk CID:
		   				<?php echo( $row["LinkCommit"]); ?></span>
					<?php } ?>
				<?php } ?>
      			</div>
		<!--- End New for walmart 07/2015 --->

			</div>
			<div style="clear:left; width:720px;float :left; padding: 10px 0 0 0px; font-size:14px;">
				<div style="float:left; width:65px;">Remit To:</div>
				<div style="float:left; font-size:14px; padding: 0px 0 10px 10px; width:165px;">
					i creatives <br /> 
					operations center <br /> 		
					po box 350127<br />
					fort lauderdale, fl 33335
				</div>
			</div>
			<div style="clear:left; float:left; padding: 10px 0 0 0px; font-size:14px;  width:720px;">
				<div style="float:left;  width:65px; padding: 0 0 0 0; font-size:14px;">Bill To:</div>	
				<div style="float:left; font-size:14px; padding: 0px 0 0 10px; width:200px;">
				<?php echo( $row["BPCONTACT"]) ;?><br />
				//<?php IF ($row["CUSTOMER"] <> "" ) { ?>
				// response.write(rsInvoice("BPCONTACT"))
				//	response.write(rsInvoice("CUSTOMER"))
				//	} Else {
				//	response.write(rsInvoice("SERVTO"))
		  		// End If %>
				<?php echo( $row["BPCOMPANY"]); ?>
				<br />
				<?php echo( $row["BPADDR1"]) ;?><br />
				<?php IF ($row["BPADDR2"] <> "") { ?>
					<?php echo( $row["BPADDR2"]); ?><br />
				<?php } ?>
				<?php echo( $row["BBPCITY"] . ", " . $row["BPSTATE"] . " " . $row["BPZIP"] ) ;?>
	 		</div>


			<div style="float:right">
				<div style="float:left; padding: 0 0 0 0px; font-size:14px; width:100px;">Service To:</div>
				<div style="float:left; font-size:14px; padding: 0px 0 0 10px; width:200px;">
					<?php echo( $row["STFIRST"] . " " . $row["STLAST"] ) ; ?><br />
					<?php echo( $row["SERVTO"] ) ; ?><br />
					<?php echo( $row["STADDR1"]) ; ?><br />
					<?php IF ($row["STADDR2"] <> "" ) { ?>
						<?php echo( $row["STADDR2"]) ; ?><br />
					<?php } ?>
					<?php echo( $row["STCITY"]) . ", " . $row["STSTATE"]  . " " . $row["STZIP"]; ?>
				</div>
			</div>     
		</div>
		<div style="clear:both; float:left: width:720px;">
			<div style="clear:both; float:left; padding:50px 0 5px 0;  font-size:16px; text-align:center;">
				<div style="float:left; width:130px;">
					<?php If (StrToUpper($row["HIDENAME"]) == "N" ) {
						$sHideName = 1;
					} else {
						$sHideName = 0;
					} ?>
					<?php If ($sHideName == 0 || $row["TYPE"] == "EXPEN") { ?>
						Name
					<?php } else { ?>
						&nbsp; &nbsp;
					<?php } ?>
				</div>
				<div style="float:left; width:200px;">Description</div>
					<?php IF ( ($row["FORMAT"] == "WALMART" && $row["TYPE"] == "RETAIN") || ($row["FORMAT"] == "WALMART" && $row["INVNUM"] < "IVC000000042221" && $row["TYPE"] <> "EXPEN" ) ) { ?>
						<div style="float:left; width:95px;">Month</div>
					<?php } else { ?>
						<div style="float:left; width:95px;">Wk Ending</div>	
					<?php } ?>
					<div style="float:left; width:60px;">
						<?php If ($sHideName == 0 || $row["TYPE"] == "EXPEN") { ?>
							Type
						<?php } else { ?>
						&nbsp;
						<?php } ?>
					</div>
					<div style="float:left; width:75px;">Qty</div>
					<div style="float:left; width:75px;">Rate</div>
					<div style="float:left; width:87px;">Total</div>
				</div>
				<?php
				$pCount = 1;
				while($row = odbc_fetch_array($resMySel) && $pCount < 20 ){
					$pCount++ ;
					IF ($row["INVNUM"] <> $sInvNum ) {
						break;
					} ELSE {
						$sInvNum = $row["INVNUM"];
					}
					?>

      					<div style="clear:both; padding:5px 0 0 0;  font-size:14px;">
						<div style="float:left; width:130px;">
							<?php If ($sHideName == 0 || $row["TYPE"] == "EXPEN") {
								echo $row["EMP"];
								if ($row["SHOWLAST"] > 0 ) {
									echo "&nbsp;" . $row["EMPLAST"];
								}
	    						}
							echo "&nbsp;"; ?>
						</div>
					
					<?php IF ($row["FULLTIME"] == 1) { ?>
						<div style="float:left; width:200px;">Fulltime Placement</div>
					<?php } ELSE { ?>
						<div style="float:left; width:200px;"><?php echo $row["TITLE"] ?></div>
					<?php } ?>
					<?php IF ( ($row["FORMAT"] == "WALMART" && $row["TYPE"] == "RETAIN") || ($row["FORMAT"] == "WALMART" && $row["INVNUM"] < "IVC000000042221" && $row["TYPE"] <> "EXPEN" ) ) { ?>
						<div style="float:left; width:88px; text-align:right;"><?php echo(date("F", strtotime($row["weekending"]))) ?></div>
					<?php } ELSE { ?>
						<div style="float:left; width:88px; text-align:right;"><?php echo(date("m/d/Y", strtotime($row["weekending"]))) ?></div>
					<?php } ?>

    					<?php If ($sHideName == 0 || $row["TYPE"] == "EXPEN" ) { ?>
         					<?php IF ( StrToLower($row["TYPE"]) == "contra" ) { ?>
	        			 		<div style="float:left; width:60px; text-align:center;">&nbsp;&nbsp;contractor</div>
         					?php }ELSEIF (StrToLower($row["TYPE"]) == "othnon" ) { ?>
	         					<div style="float:left; width:60px; text-align:center;">&nbsp;</div>
         					<?php } ELSE { ?>
         						<div style="float:left; width:60px; text-align:center;"><?php echo(StrToUpper($row["TYPE"])); ?></div>
         					<?php } ?>
					<?php } ELSE { ?>
						<div style="float:left; width:60px; text-align:center;">&nbsp;</div>
					<?php } ?>
         				<div style="float:left; width:75px; text-align:right;"><?php echo $row["QTY"]; ?></div>
         				<div style="float:left; width:75px; text-align:right;"><?php echo $row["RATE"]; ?></div>
         				<div style="float:left; width:87px; text-align:right;"><?php echo $row["COST"]; ?></div>
      				</div>
			</div> 		
		</div>  
		<BR style='page-break-after: always'>";

		<?php } ?>

   		<div style="clear:both; padding:5px 0 0 0;  font-size:14px;">
      			<div style="float:left; width:130px;">&nbsp;</div>
      			<div style="float:left; width:200px;">&nbsp;</div>
      			<div style="float:left; width:100px;">&nbsp;</div>
      			<div style="float:left; width:60px; text-align:center;">&nbsp;</div>
      			<div style="float:left; width:75px; text-align:right;">&nbsp;</div>
      			<div style="float:left; width:75px; text-align:right;">&nbsp;</div>
      			<div style="float:left; width:75px; text-align:right;"><hr /></div>
   		</div>

   		<div style="float:left; width:455px; padding:55px 0 10px 0; height:00px;"><b>&nbsp;&nbsp;&nbsp;Payment Due</b>
      			<div style="float:left; height:3px;"> &nbsp;</div>
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

				<b><?php echo $DUEDATE; ?></b>

      			</div>
      			<b>&nbsp;&nbsp;&nbsp;Terms are <?php echo $TERMS ?></b>
  		 </div>
   
		<div style="float:left;  width:280px">
			<div style="clear:both; padding:5px 0 0 0;  font-size:14px;">
				<div style="float:left; width:170px; text-align:right; padding-right:10px;">Subtotal</div>
				<div style="float:left; width:80px; text-align:right;"><?php echo $TOTAL ?></div>
			</div>

			<div style="clear:both; padding:5px 0 0 0;  font-size:14px;">	
				<div style="float:left; width:170px; text-align:right;  padding-right:10px;">Sales Tax</div>
				<div style="float:left; width:80px; text-align:right;">0.00</div>
			</div>

			<div style="clear:both; padding:5px 0 0 0;  font-size:14px;">
				<div style="float:left; width:185px; text-align:right;">&nbsp;</div>
				<div style="float:left; width:75px; text-align:right;"><hr></div>
			</div>
			<div style="clear:both; padding:5px 0 0 0;  font-size:14px;">
				<div style="float:left; width:170px; text-align:right; padding-right:10px;">Total</div>
				<div style="float:left; width:80px; text-align:right;">$ <?php echo number_format($TOTAL,2) ?></div>
			</div>

			<div style="clear:both; padding:5px 0 0 0;  font-size:14px;">
				<div style="float:left; width:170px; text-align:right; padding-right:10px;">Late Amount</div>

			<?php

			$DateDiff = Date_Diff(date_create(DATE("Y-m-d")) ,  date_create($InvDate));
			// echo "date diff = ". $DateDiff;
			if ( $DateDiff < 15 ) { 
				$LateAmount = number_format($TOTAL * 1.015, 2) ;
			} else {
  				$LateAmount = number_format($TOTAL + ( $DateDiff * 18/365 * $TOTAL ) /100,2);
			}
			?>
			<div style="float:left; width:80px; text-align:right;"><?php echo $LateAmount; ?></div>
         	</div>
      	</div>

	<BR style="page-break-after: always">
<?php

} Else {

echo( "<BR style='page-break-before: always'><H1> this page is blank </H1>") ;

}
include "statementtimesheet.php";


?>

