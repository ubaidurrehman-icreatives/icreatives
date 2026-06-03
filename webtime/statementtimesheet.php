
<?php
include "db5.php";

// InvNum = "IVC0000000" & request.QueryString("InvNum")

// IVC000000036211-2021.25-2012-10-20

 	$rsGT = 0;
	$i = 1;
	$Unique_ID = $_REQUEST["Unique_ID"];	
	$lsAccept = $_REQUEST["Accept"];	
		
// Now include email layout in StatementTimesheet.txt


include "StatementTimesheet.txt";


Function HrComp($lsInHr,$lsOutHr) {
If ($lsInHr >= 13) {
   $lsInHr = floor($lsInHr) -12;
} ElseIf ($lsInHr == 0 && $lsOutHr <> 0) {
	$lsInHR = 12;
} Else {
   $lsInHr = floor($lsInHr);
}
return $lsInHr;
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

Function MinComp($lsHr) {
$lsMin = ($lsHr - floor($lsHr)) * 60;

If ($lsMin == 0) {
	$MinComp = "00";
} ElseIf ($lsMin < 10 && $lsMin > 0) {
	$MinComp = "0" . Round($lsMin,0);
} Else {
	$MinComp = Round($lsMin,0);
}
return $MinComp;
}
	$strSQL = "SELECT 'TIMEACT' as '!TIMEACT' ";
	$strSQL = $strSQL . ", REPLACE(REPLACE(cast(em.Last_Name as varchar(20))+ ', ' +cast(em.First_Name as varchar(20))  +CASE WHEN Middle_Name is null THEN '' ELSE ' '+cast(em.Middle_Name as varchar(20)) END,' ','_'),',','') as EMP ";
	$strSQL = $strSQL . ", REPLACE(cm.Customer_Name,' ','_') as JOB ";
	$strSQL = $strSQL . ", REPLACE(em.PostalCode,' ','_') as ZIP ";
	$strSQL = $strSQL . ", '-' as NOTE, oa.Assignment_ID as PROJ ";
	$strSQL = $strSQL . ", oa.Order_ID as xORDER ";
	$strSQL = $strSQL . ", om.TakenContactKey as ORDERTAKENID ";
	$strSQL = $strSQL . ", om.StartContactKey as REPORTID ";
	$strSQL = $strSQL . ", om.SupervisorContactKey as SUPERVISORID ";
	$strSQL = $strSQL . ", om.InvoiceField03Data as ATTACHTIME ";
	$strSQL = $strSQL . ", '40' as DURATION ";
	$strSQL = $strSQL . ", '1' as BILLINGSTATUS ";
	$strSQL = $strSQL . ", 'CONTR' as PITEM ";
	$strSQL = $strSQL . ", '0' as BITEM ";
	$strSQL = $strSQL . ", id.OAPurchaseOrder as PO ";
	$strSQL = $strSQL . ", om.BillingProfileKey as BILLINGPROFILE ";
	$strSQL = $strSQL . ", bp.InvoiceField01Break as SHOWLAST " ;

 	for ($i = 1; $i <= 7; $i++) {
 		$strSQL = $strSQL . ", wt.TimeInHr" . $i . " as INHR" . $i . " ";
		$strSQL = $strSQL . ", wt.TimeOutHr" . $i . " as OUTHR" . $i . " ";
		$strSQL = $strSQL . ", wt.Break" . $i . " as BREAK" . $i . " ";
 	}
	$strSQL = $strSQL . ", wt.Hours as Hours ";
	$strSQL = $strSQL . ", wt.WeekEnding as WeekEnding ";
	$strSQL = $strSQL . ", wt.CustomerIpAddr as IP ";
	$strSQL = $strSQL . ", wt.Signature as Signature "	;
	$strSQL = $strSQL . ", wt.Continuing as CONT " ;
	$strSQL = $strSQL . ", wt.SuperEmail as SUPER " ;
	$strSQL = $strSQL . ", wt.EmpEmail as AltEmpEmail " ;
	$strSQL = $strSQL . ", wt.AcctEmail as AcctEmail " ;
	$strSQL = $strSQL . ", wt.unique_id as unique_id "  ;
	$strSQL = $strSQL . ", wt.approvedate as APPROVEDATE " ;
	$strSQL = $strSQL . "from AR_INVOICE_DETAIL as id ";
	$strSQL = $strSQL . "join ic_webtime wt on wt.assignment_id = id.orderKey and wt.weekending = id.weekending and id.employeekey = wt.employee_id ";
	$strSQL = $strSQL . "join orderassignment oa on oa.assignment_id = id.assignmentkey ";
	$strSQL = $strSQL . "join AR_INVOICE inv on inv.documentkey = id.documentkey ";

	$strSQL = $strSQL . "JOIN ordermaster om on oa.Order_ID = om.Order_ID ";
	$strSQL = $strSQL . "JOIN CustomerBillingProfile bp on om.billingprofilekey = bp.billingprofilekey ";
	$strSQL = $strSQL . "JOIN employeemaster em on em.employee_ID = oa.Employee_ID ";
	$strSQL = $strSQL . "JOIN CustomerMaster cm on cm.Customer_ID = om.Customer_ID ";
	$strSQL = $strSQL . " WHERE inv.documentkey = '" . $sInvNum . "' ";
	$strSQL = $strSQL . " and wt.signature is not null ";
	$strSQL = $strSQL . " and id.VoidDate IS NULL ";
	$strSQL = $strSQL . " and wt.approvedate is not null ORDER BY id.weekending";

// echo $strSQL;


	$resMySel = odbc_exec($conn,$strSQL);
	$row = odbc_fetch_array($resMySel);

IF (!Empty($row["PROJ"])) {
	$IP =  $row["IP"];
	$xSignature = $row["Signature"];
	$count = 0;
	$sAttach = 0;

	$resMySel = odbc_exec($conn,$strSQL);
	while($row = odbc_fetch_array($resMySel)){ 

		If ($row["ATTACHTIME"] == "N") {
			$sAttach = 1;
		}

		IF ($sAttach == 0) {
 	  		$count = $count + 1;
			$WkEnd = $row["WeekEnding"];
			$HOURS = $row["Hours"];
			$AssignNo = $row["PROJ"];
	
			$xScreen = sScreen($row);


			if ($count > 1) { // add a page break after first page
   				$xScreen ="<BR style=|page-break-before: always|>" .  $xScreen;
			}
	
			$xScreen = Str_Replace("IPIP",$IP,$xScreen);

			$xScreen = Str_Replace("AAAA",$xSignature,$xScreen);

			$xScreen = Str_Replace("|","'",$xScreen);


 			if ($row["SHOWLAST"] > 0) { 
				$sTalentName = $row["EMP"];
 			} else {
				$pieces = explode( "_", $row["EMP"]);
				$sTalentName = $pieces[0];
			}

			 $xScreen = Str_Replace("XXXX",$sTalentName,$xScreen);

			$xScreen = Str_Replace("YYYY","<b style='color:#a4100c'>Customer: </b>" . Str_Replace("_"," ",$row["JOB"]) . "<BR>" . "\r\n", $xScreen);

			   if ($row["PO"] <> "" ) {
	      	 		$xScreen = Str_Replace("ZZZZ","PO Number: " . $row["PO"], $xScreen);
			} else {
	      	 		$xScreen = Str_Replace("ZZZZ","",$xScreen);
			}

			echo "</div>";
			ECHO $xScreen;

		} 
	}
} Else {

 ECHO( "<BR style='page-break-before: always'><H1>  </H1>") ;

}
?>		

