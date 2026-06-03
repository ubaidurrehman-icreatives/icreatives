<script type="text/javascript">

function submitform()
{
	if 	(SignTest()==true)	
	
	{	
  		document.forms["webtime"].submit();
	}
	
}
</script>

<script type="text/javascript">

function submitform6()
{
	
	if (validateFields()==true)	
	{	
  		document.forms["webtime"].submit();
	}
	
}
</script>

<script type="text/javascript">
function submitform2()
{


	
  		document.forms["webtime2"].submit();
	            alert("please wait...");
}


function submitform3()
{
	
  		document.forms["webtime3"].submit();
		alert("please wait...");
	
}
</script>



<script language="JavaScript">
function SignTest() 
{
	var sSignature = document.forms[0].Signature.value;

	// Validate signature field
	
	if (sSignature == '' || sSignature.length < 2) 
	{
		alert("\nThe SIGNATURE field is either empty or less than 2 characters.\n\nPlease re-enter your name.");
		document.forms[0].Signature.select();
		document.forms[0].Signature.focus();

		return false;
	}
	onsubmit="window.parent.scroll(0,0)";	
	return true;
	
}
</script>

<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
<style>
.mobile-spacer{
  clear:left;
  height:50px;
}

/* Mobile devices */
@media (max-width: 768px){
  .mobile-spacer{
    height:70px;
  }
}
</style>
<?php
session_start();
$varib_arr = explode("-",$_GET["varib"]);
list($varib,$order_id) = $varib_arr ;
	$lsAccept = substr($varib,0,1);	
	$Unique_ID = substr($varib,1);	
	
// Now include email layout in ASN_Approve.txt


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

require_once __DIR__ . '/../db/db.php';
$link = db();   

if ($link->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} else {
    // echo "Connected successfully!";
}

// when ready to implement strSQL = "SELECT AssignmentNumber, approvedate, declinedate, weekending, employee_id, Hours from ic_webtime Where unique_ID = '" & Unique_ID & "' AND Assignment_ID = '" . $OrderNo . "' AND Batch_ID IS NULL";
$strSQL = "SELECT Unique_ID, AssignmentNumber, approvedate, declinedate, weekending, employee_id, Hours from ic_timesheets Where unique_ID = '" . $Unique_ID ."'; ";

$resMySel = mysqli_query($link,$strSQL);
$countz = mysqli_num_rows($resMySel);
$row = mysqli_fetch_array($resMySel);

// echo "XXX".$row["weekending"];
// 
IF ( $countz > 0 && $row["approvedate"] =="0000-00-00 00:00:00" && $row["declinedate"] =="0000-00-00 00:00:00"  && !is_Null($row["Unique_ID"])) {
	// &&  is_Null($row["declinedate"]) && !is_Null($row["AssignmentNumber"]) ) { // if allready approved, let them know
	If (!is_Null($row["AssignmentNumber"])) {
   		$AssignNo = $row["AssignmentNumber"];
   
   		$WKEND = $row["weekending"];
   		$Contractor_ID = $row["employee_id"];
		$HOURS = Round($row["Hours"],2);

$strSQL = "SELECT 'TIMEACT' as '!TIMEACT' ";
$strSQL = $strSQL . ", wt.first_name as FIRST ";
$strSQL = $strSQL . ", wt.last_name as LAST ";
// $strSQL = $strSQL . ", oj.candidate_name as FULLNAME ";
$strSQL = $strSQL . ", oj.bill_rate as BILLRATE ";
$strSQL = $strSQL . ", oj.pay_rate as PAYRATE ";
// $strSQL = $strSQL . ", REPLACE((oj.last_name+ ' ' +oj.first_name),' ','_') as EMP ";
// $strSQL = $strSQL . ", convert(varchar(12),(SELECT DATEADD(day, DATEDIFF(day,0 , GETDATE()) - (DATEDIFF(day,  6,  GETDATE()) % 7), 0)),101) as DATE ";
$strSQL = $strSQL . ", oj.company_name as JOB ";
// $strSQL = $strSQL . ", REPLACE(em.PostalCode,' ','_') as ZIP ";
$strSQL = $strSQL . ", oj.job_name as ITEM ";
$strSQL = $strSQL . ", '-' as NOTE, job as PROJ ";
$strSQL = $strSQL . ", oj.job as xORDER ";
// $strSQL = $strSQL . ", oj.Primary_Contact_ID as ORDERTAKENID ";
// $strSQL = $strSQL . ", oj.Second_Contact_ID as REPORTID ";
// $strSQL = $strSQL . ", oj.Second_Contact_ID as SUPERVISORID ";
// $strSQL = $strSQL . ", oj.Branch_ID as BRANCH ";
// $strSQL = $strSQL . ", om.Division_ID as DIVISION ";
// $strSQL = $strSQL . ", om.UserDefined2 as ALTEMPMAIL ";
$strSQL = $strSQL . ", '40' as DURATION ";
$strSQL = $strSQL . ", '1' as BILLINGSTATUS ";
$strSQL = $strSQL . ", 'CONTR' as PITEM ";
// $strSQL = $strSQL . ", ctm.First_Name + ' ' + ctm.Last_Name as SUPERVISOR ";
$strSQL = $strSQL . ", '0' as BITEM ";
$strSQL = $strSQL . ", oj.po_number as PO ";
// $strSQL = $strSQL . ", om.BillingProfileKey as BILLINGPROFILE ";
// $strSQL = $strSQL . ", ctm.EMAIL_Address_1 as INVMETHOD "; // First Userdefined field in the additional tab in contact master
// $strSQL = $strSQL . ", bp.UserDefined3 as ALTETIME ";
// $strSQL = $strSQL . ", bp.UserDefined2 as ALTETIME2 ";
// $strSQL = $strSQL . ", bp.UserDefined1 as AcctEmail ";
$strSQL = $strSQL . ", 'Yes' as Contract ";

for ($i = 1; $i <= 7; $i++) {

 	$strSQL = $strSQL . ", wt.TimeInHr" . $i . " as INHR" . $i . " ";
	$strSQL = $strSQL . ", wt.TimeOutHr" . $i . " as OUTHR" . $i . " ";
	$strSQL = $strSQL . ", wt.Break" . $i . " as BREAK" . $i . " ";
}
$strSQL = $strSQL . ", wt.Continuing as CONT " ;
$strSQL = $strSQL . ", wt.Primary_Contact_Email " ;
$strSQL = $strSQL . ", wt.Second_Contact_Email " ;
$strSQL = $strSQL . ", wt.ApproveDate as APPROVE " ;
$strSQL = $strSQL . ", wt.SentDate as SENT " ;
$strSQL = $strSQL . ", wt.DeclineDate as DECLINE " ;
$strSQL = $strSQL . "from ic_timesheets wt ";
$strSQL = $strSQL . "JOIN ic_matches oj on oj.candidate = wt.Employee_ID AND oj.job=wt.AssignmentNumber";
$strSQL = $strSQL . " WHERE wt.Unique_ID = '" . $Unique_ID ."' ";
		
// echo 	$strSQL ;


$resMySel = mysqli_query($link,$strSQL);
// $countz = mysqli_num_rows($resultz);
$row = mysqli_fetch_array($resMySel);

		include dirname(__DIR__) . "/webtime/manatal_approve_t.txt";
		include dirname(__DIR__) . "/webtime/manatal_approve_c.txt";


	?>
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>

 <div class="mobile-spacer"></div>
		<div style="float: left; width:100%; height:1050px; background-color:#e9e9e0;">
			<div style="float:left; width:100%; height:850px; padding-left:10px;">
				<div style="float:left; width:142px;"></div>
				<div style="float:right; width:100%; margin-right:10px;">
					<div style="float:left; width:100%;">
						<div style="float:left; padding-top:20px;">
							<div style="float:left;">
							<?php IF ($lsAccept== "1" ) {
										// echo "XXX" . $strSQL ;
									echo "<H1 style = 'font-family: Arial, Helvetica, sans-serif;'>Approve Time Sheet</H1>";
								} ELSE {
									echo "<H1 style = 'font-family: Arial, Helvetica, sans-serif;'>Decline Time Sheet</H1>";
							} ?>
							</div>
						</div>
					</div>

					<?php IF ( ($row["POREQUIRED"] ?? '') <> "Y" ) {  ?>
						<script language="JavaScript">
						function SignTest() 
						{
						var sSignature = document.forms[0].Signature.value;
						// Validate signature field
						if (sSignature == '' || sSignature.length < 2) 
						{
						alert("\nThe PO Number field is either empty or less than 2 characters.\n\nPlease enter your PO Number.");
						document.forms[0].Signature.select();
						document.forms[0].Signature.focus();

						return false;
						}
						return true;
	
						}
						</script>

					<?php } ELSE { ?>

						<script language="JavaScript">
						function SignTest() 
						{
						var sSignature = document.forms[0].Signature.value;
						// Validate signature field
						if (sSignature == '' || sSignature.length < 2) 
						{
						alert("\nThe SIGNATURE field is either empty or less than 2 characters.\n\nPlease re-enter your name.");
						document.forms[0].Signature.select();
						document.forms[0].Signature.focus();

						return false;
						}
						var sPoNumber = document.forms[0].PoNumber.value;
						// Validate Po Field field
						if (sPoNumber == '' || sPoNumber.length < 2) 
						{
						alert("\nThe Po Number field is either empty or has less than 2 characters.\n\nThis PO number will show on our invoice.");
						document.forms[0].PoNumber.select();
						document.forms[0].PoNumber.focus();
						return false;
						}		
						return true;
						}
						</script>
					<?php  }
					If ( !empty($row["PROJ"]) ) {     // do it again without the WebTime Table Info

						// Figure out who to email timesheet to:
						$strReportTo = ($row["ORDERTAKENID"] ?? '');
						IF ( empty($row["REPORTID"]) ?? '' ) {
							$strReportTo = ($row["REPORTID"] ?? '') ;
						}
						IF ( empty( ($row["SUPERVISORID"] ?? '') ) ) {
							$strReportTo = ($row["SUPERVISORID"] ?? '');
						}	
						echo "<div style='Clear:left;'>" ;
						echo "Assignment: " .$row["ITEM"]. "" ;
						echo "</div>" ;
						$lsOrder = $row["xORDER"] ;
						?>
						<?php IF ($lsAccept=="1") { ?>
							<form id = "webtime" name="WebTime" Method="Post" action="/webtime/manatal_write.php?snextstep=Approve" onsubmit="return SignTest()">  
						<?php } ELSE { ?>
							<form id = "webtime" name="WebTime" Method="Post" action="/webtime/manatal_write.php?snextstep=Decline">  		
						<?php } 
						echo ("<input type='hidden' name='Unique_ID' value='" . $Unique_ID . "'>");	
						echo ("<input type='hidden' name='billrate' value='" . $row["BILLRATE"]. "'>");	
						echo ("<input type='hidden' name='payrate' value='" . $row["PAYRATE"] . "'>");	
						$rsGT = 0;
						?>

						<table>
						<tr>
							<td width="20" align="right" valign="bottom"><span style="font-size:120px; text-align:right;">{</span></td>
							<td width="400"> 
								<table width="450" class="defaulttable" border="0" style="color:#B22625; border-spacing: 0; margin-left: auto; margin-right: auto; margin-top: 0; margin-bottom: 0"cellspacing="1" cellpadding="0">
								<tr>
									<td width="3%"></td>
									<td width="45%" align="right">Contractor: </td>
									<td width="4%"> </td>
									<td width="45%"> <?php echo ($row["FIRST"]); ?></td>
									<td width="3%"></td>
								</tr>
								<tr>
									<td width="3%"></td>
									<td width="45%">Customer: </td>
									<td width="4%"> </td>
									<td width="45%"> <?php echo ($row["JOB"]); ?></td>
									<td width="3%"></td>
								</tr>
								
								<tr>
									<td width="3%"></td>
									<td width="45%">PO Number: </td>
									<td width="4%"> </td>
									<td width="45%"> <?php echo $row["PO"]; ?></td>									
									<td width="3%"></td>
								</tr>
								
								<tr>
									<td width="3%"></td>
									<td width="45%">Week Ending: </td>
									<td width="4%"> </td>
									<td width="45%"> <?php echo date('m/d/Y',StrToTime($WKEND)) ?></td>
									<td width="3%"></td>
								</tr>
								</table>
							</td>
					<td width="60"align="left"  valign="bottom"><span style="font-weight:100; font-size:120px;  text-align:left">&nbsp</span></td>
						</tr>
						</table>
						<BR>
						<!-- <H5> -->
						<table class="defaulttable" border="0" cellpadding="0" height="265" width="95%" style="font-size:15px;font-family: Arial, Helvetica, sans-serif; border-spacing: 0; margin-left: auto; margin-right: auto; margin-top: 0; margin-bottom: 0">
						<tr>
							<TD vAlign=top align=right width=2 height="1"></TD>
							<TD vAlign=top align=right width=25 height="1"></TD>
							<td width="97" align="center" height="1" valign="bottom">Start Time</td>
							<TD width="91"></TD>
							<td width="99" align="center" height="1" valign="bottom">Finish Time</td>
							<TD width="82"></TD>
							<td width="120" align="center" height="1" valign="bottom">Deduct Lunch &amp;&nbsp;Break Time</td>
							<td width="42" align="center" height="1" valign="bottom">Daily Total</td>
							<td width="1" height="1"></td>
						</tr>
						<tr>
						<?php for ($i = 1; $i <= 7; $i++) { ?>
							<TD vAlign=top align=right width=2 height="1"></TD>
							<TD vAlign=middle align=left height="21"><?php echo jddayofweek($i-2,1) ?>:</FONT></TD>
							<td width='97' height='1' style="text-align:right;"><?php echo  HrComp($row["INHR" . $i],$row["OUTHR" . $i]); ?>
								:<?php	echo MinComp($row["INHR" . $i]); ?>
							</td>
							<td width="91" height="1">
								<?php	echo AmPmCompIn($row["INHR" . $i]); ?>
							</td>    	
							<td width="99" height="1" style="text-align:right;"><?php echo HrComp($row["OUTHR" . $i],00); ?>:
								<?php echo MinComp($row["OUTHR" . $i]); ?>
								</select>
							</td>
							<td width="82" height="1">
								<?php echo AmPmCompOut($row["OUTHR" . $i]); ?>
							</td>    	
							<td width="94" height="1" align = "center">
								<?php echo HrComp($row["BREAK" . $i],00); ?>:
								<?php echo MinComp($row["BREAK" . $i]);  ?>
								</select>
							</td>
							<td width="42" height="1" style="text-align:right;">
								<?php echo number_format(Round( ($row["OUTHR" . $i] - $row["INHR" . $i] ) - $row["BREAK" . $i],2 ), 2, '.', ',');
								$rsGT = $rsGT + ($row["OUTHR" . $i] - $row["INHR" . $i]) -$row["BREAK" . $i]; ?>
							</td>
							<td width="1" height="1"></td>
							</tr>
						<?php } ?>
						<tr>
							<TD vAlign=top align=right width=2 height="1"></TD>
							<TD vAlign=top align=right width=25 height="1"></TD>
							<td width="97" align="center" height="1" valign="bottom"></td>
							<TD width="91"></TD>
							<td width="99" align="center" height="1" valign="bottom"></td>
							<TD width="82"></TD>
							<td width="98" align="center" height="1" valign="bottom">Total</td>
							<td width="42" style="text-align:right;"><?php echo round($rsGT,2) ; ?></td>
		
							<td width="1" height="1"></td>
						</tr>
						</table>
						<?php 
						
							echo "<input type='hidden' name='EMP' value='" . $row["FIRST"] . "'>" ;
							// echo "<input type='hidden' name='DATE' value='" . $row["DATE"] . "'>" ;
							echo "<input type='hidden' name='JOB' value='" . $row["JOB"] . "'>" ;
							echo "<input type='hidden' name='ITEM' value='" . $row["ITEM"] . "'>" ;
							echo "<input type='hidden' name='NOTE' value='" . $row["NOTE"] . "'>" ;
							echo "<input type='hidden' name='PROJ' value='" . $row["PROJ"] . "'>" ;
							echo "<input type='hidden' name='xORDER' value='" . $row["xORDER"] . "'>";
							echo "<input type='hidden' name='PO' value='" . $row["PO"] . "'>";
							echo "<input type='hidden' name='DURATION' value='" . $row["DURATION"] . "'>" ;
							echo "<input type='hidden' name='BILLINGSTATUS' value='" . $row["BILLINGSTATUS"] . "'>" ;
							echo "<input type='hidden' name='PITEM' value='" . $row["PITEM"] . "'>" ;
							echo "<input type='hidden' name='BITEM' value='" . $row["BITEM"] . "'>" ;
							echo "<input type='hidden' name='Contractor_ID' value='" . $Contractor_ID . "'>" ;
							// echo "<input type='hidden' name='BILLINGPROFILE' value='" . $row["BILLINGPROFILE"] . "'>" ;
							echo "<input type='hidden' name='WKEND' value='" . $WKEND . "'>";
							// echo "<input type='hidden' name='REPORTTO' value='" . $StrReportTo . "'>";
							echo "<input type='hidden' name='sScreen' value='" . $sScreen . "'>";
							echo "<input type='hidden' name='eScreen' value='" . $eScreen . "'>";		
							echo "<input type='hidden' name='Unique_ID' value='" . $Unique_ID . "'>";
							?>
							<input type="hidden" name="SuperEmail"  value="<?php echo $row["Primary_Contact_Email"]; ?>">
							<input type='hidden' name='AltEmpEmail' value="<?php echo $row["Second_Contact_Email"]; ?>">;
							<?php 
							echo "<input type='hidden' name='AcctEmail' value='" . ($row["AcctEmail"] ?? '') . "'>";
							echo "<input type='hidden' name='Continuing' value='" . $row["CONT"] . "'>";
						?>
						&nbsp;		
						<table class="defaulttable" width="300" style="font-size:15px;font-family: Arial, Helvetica, sans-serif; border-spacing: 0; margin-left: auto; margin-right: auto; margin-top: 0; margin-bottom: 0">
						<tr>
							<td align="right" width="150">This assignment is:</td>
								<?php IF ($row["CONT"] == "1") { ?>
									<td align="left" width="120">Continuing&nbsp;</font></td>
								<?php } ELSE { ?>
									<td align="left" width="110">Over</font></td>
								<?php } ?>
						</tr>
						</table>
						<div style="clear:both; width:500px; padding-left:6px; font-size:15px;font-family: Arial, Helvetica, sans-serif;">
							<br />
							<?php if ($lsAccept == "1") { ?>
								Please sign by typing your name in the text box below: <BR><br>
								<input type='text' name='Signature' size='25' required >
								<BR /><BR />
								Please let us know if there is anything else we can help with, or any comments you may have for us:<br>&nbsp;<br>
								<textarea name='comments' rows = "5" cols = "65"></textarea>
							<br />
						</div>
						<div  style="clear:left;float:left;padding:15px 30px 0 6px;" >
						<!-- <form onclick = "javascript:submitform();"> -->
							<div class="btn-submit"><input type="submit" value="I APPROVE" /></div>
							</form>
						</div>

						<div  style="float:left; padding:15px 0px 0 0;" >
							<form action="/webtime/manatal_approve_1.php&varib=0<?php echo $Unique_ID; ?>-<?php echo $OrderNo; ?>" method="post" target="_top">
							<div class="btn-submit"><input type="submit" value="I DECLINE" /></div>
							</form>
						</div>
					<?php } Else { ?>
						<H1 style = "font-family: Arial, Helvetica, sans-serif;" > TIMESHEET WILL BE DECLINED </H1>
						Thank you for bringing this problem to our attention; please write a brief explanation and an <b>i creatives</b> representative will contract you shortly.<br>&nbsp;<br></h2>
						<Textarea name='comments' rows = "5" cols = "65" required></textarea>
						<p />
						<div  style="clear:left;float:left;padding-top:15px;" >
							<!-- <form onclick = "javascript:submitform_decline();"> -->
								<div class="btn-submit"><input type="submit" value="I DECLINE" /></div>
						</form>
					<?php } ?>
				<?php }
			}
} ELSE { ?>
			<div class="container" style="float: left; width:100%; height:850px; padding-left: 0px;">
			<div class="mobile-spacer"></div>
				<Center>
				<?php if ( isset($row["declinedate"]) && $row["declinedate"] == "0000-00-00 00:00:00" && !is_Null($row["AssignmentNumber"]) ) { ?>
					<H1><br>Timesheet already processed </H1>
				<?php } else if ( $countz > 0 && $row["declinedate"] !== "0000-00-00 00:00:00" ) { ?>
					<H1><br>This timesheet was already declined </H1>
				<?php } else { ?>
					<H1><br>Timesheet was removed </H1>
				<?php } ?>
				please call us at 954.468.5550 if we can be of further assistance
			</div>		
<?php } ?>
	<br /><br />
</Form>
&nbsp;		
</p>
