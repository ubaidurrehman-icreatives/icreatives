 <!-- Bootstrap CSS, font-awesome custom CSS -->
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_red.css">
  <link rel="stylesheet" href="/portal/styles.css">
  
    <link rel="stylesheet" type="text/css" href="/webtime/css/css.css" />
   <link rel="stylesheet" type="text/css" href="/webtime/css/style.css" />

    <link href="/webtime/css/style.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="/webtime/css/js.js"></script>
    <script type="text/javascript" src="/webtime/css/jquery.js"></script>
    <script type="text/javascript" src="/webtime/css/custom-form-elements.js"></script>

 <style>
  body {

  width: 100%; /* Or any desired width */
  margin-left: auto;
  margin-right: auto;
  height: 100%;            /* ensures it fills the iframe height */
  }


.btn-section a, .btn-section input[type="submit"] {
  display: inline-block;
  padding: 10px  25px;
  color: #ffffff;
  text-transform: uppercase;
  background: #b22625;
  font-weight: 600;
  border: 1px solid transparent;
}
.btn-section a:hover, .btn-section input[type="submit"]:hover {
  color: #b22625;
  background: #ffffff;
  border: 1px solid #b22625;
}

.btn-submit input[type="submit"] {
  display: inline-block;
  padding: 13px 25px 27px 25px;
  color: #ffffff;
  font-family: arial;
  text-transform: uppercase;
  background: #b22625;
  font-weight: 600;
  border: 1px solid transparent;
}
.btn-submit input[type="submit"]:hover {
  color: #b22625;
  background: #ffffff;
  border: 1px solid #b22625;
}

.defaulttable {
  display: table;
}
.defaulttable thead {
  display: table-header-group;
}
.defaulttable tbody {
  display: table-row-group;
}
.defaulttable tfoot {
  display: table-footer-group;
}
.defaulttable tbody>tr:hover,
.defaulttable tbody>tr {
  display: table-row;
}
.defaulttable tbody>tr:hover>td,
.defaulttable tbody>tr>td {
  display: table-cell;
}
.defaulttable,
.defaulttable tbody,
.defaulttable tbody>tr:hover,
.defaulttable tbody>tr,
.defaulttable tbody>tr:hover>td,
.defaulttable tbody>tr>td,
.defaulttable tbody>tr:hover>th,
.defaulttable tbody>tr>th,
.defaulttable thead>tr:hover>td,
.defaulttable thead>tr>td,
.defaulttable thead>tr:hover>th,
.defaulttable thead>tr>th,
.defaulttable tfoot>tr:hover>td,
.defaulttable tfoot>tr>td,
.defaulttable tfoot>tr:hover>th,
.defaulttable tfoot>tr>th {
  background: transparent;
  border: 0px solid #000;
  border-spacing: 5px;
  border-collapse: separate;
  empty-cells: show;
  padding: 0px;
  margin: 0px;
  outline: 0px;
  font-size: 100%;
  vertical-align: middle;`
  text-align: right;
  font-family: arial;
  table-layout: auto;
  
}
</style>


<?php
// see if we have invoiced yet, if not generate a timesheet:


// echo  "XXX".$_REQUEST["REPORTTOCC"];
/* obsolete 
invSQL = "select count(*) as invcount from ar_invoice_detail where divisionKey = '" & request.form("DIVISION") &"'"
$resMySel = odbc_exec($conn,$invSQL);
$row = odbc_fetch_array($resMySel)) 

$InvCount = $row["invcount"] // number of invoices
*/
?>
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
<script type="text/javascript">

function submitform2()
{
	
  		document.forms["webtime2"].submit();
	           // var t=setTimeout("alert('please wait...')",3000)
}


function submitform3()
{
	
  		document.forms["webtime3"].submit();
		// var t=setTimeout("alert('please wait...')",3000)
	
}
</script>

<?php

Function CleanHrs() {

$sCount = 0;
for ($i = 1; $i <= 7; $i++) {
// echo "XXX". "TimeInHr" . $i. "=" .$_POST["TimeInHr" . $i]."<br>";
       if (!Is_Numeric($_POST["TimeInHr" . $i]) ||  !Is_Numeric($_POST["TimeOutHr" . $i]))  {
         $sCount++;
       }
 }
 	// response.write(sCount)
 	if ($sCount > 0) { 
 		$CleanHrs = False;
 	} else {
 		$CleanHrs = True;
 	}
Return $CleanHrs;
}

Function TotalHrs($i) {

    $sInHours = ($_POST["TimeInHr" . $i]);
    if ($_POST["TimeInAmPm" . $i] == "PM" && $sInHours < 12) { 
	$sInHours = $sInHours + 12;
    	// 12am
    }
    if ($_POST["TimeInAmPm" . $i] == "AM" && $sInHours == 12) {$sInHours = 0;}
    	
    $sInMinutes = ($_POST["TimeInMin" . $i]/60);
    $sInAmPm = $_POST["TimeInAmPm" . $i];
    
    $sOutHours = ($_POST["TimeOutHr" . $i]);
	 if ($_POST["TimeOutAmPm" . $i] == "PM" && $_POST["TimeOutHr" . $i] > 0 && $_POST["TimeOutHr" . $i] < 12) { $sOutHours = $sOutHours + 12;}

	 if ($_POST["TimeOutAmPm" . $i] == "AM" && $_POST["TimeInAmPm" . $i] == "AM" && $_POST["TimeInHr" . $i] == 12 && $_POST["TimeOutHr" . $i] > 0 && $_POST["TimeOutHr" . $i] < 12) { $sInHours = 0;}


    $sOutMinutes = ($_POST["TimeOutMin" . $i]/60);
    $sOutAmPm = $_POST["TimeOutAmPm" . $i];

    $sBreakHr = $_POST["BreakHr" . $i];

    $sBreakMin = ($_POST["BreakMin" . $i]/60);

// Calculate Total

$TotalHrs = ( ($sOutMinutes + $sOutHours) - ($sInMinutes + $sInHours) ) - ($sBreakHr + $sBreakMin);

return $TotalHrs;
}

function GrandTotal() {
    $lsGT = 0; // consistent casing
    for ($i = 1; $i <= 7; $i++) {
        $lsGT += TotalHrs($i);
    }
    return $lsGT;
}


IF (CleanHrs()) {

$Contract = $_POST["Contract"] ?? '';
// $InvMethod = $_POST["INVMETHOD"] ?? ''; 

$sTalentFirst =  SubStr( $_REQUEST["EMP"],StrPos($_REQUEST["EMP"],"_") +1 );
	list($sTalentFirst, $sTalentLast) = explode(" ",$_REQUEST["EMP"]);
$sTalentName = Trim($_REQUEST["EMP"]);

$MyNewRandomNum = (Trim(date("Y") . date("m") . date("d")) . date("h") . date("m") . date("s")) . intval(rand()) ;


// echo "XXX".$MyNewRandomNum."XXX";

// write the email while we have the chance

		include 'manatal_eapprovetxt.php';

?>		

<BR /> 
<table class="defaulttable" border="0" style="width:500px;" cellspacing="1" cellpadding="0">
<tr>

<td width="10%" valign="bottom" style="text-align:right; font-size:120px; color:#B22625;"><b>(</b></td>
<td><h4 style="color:#a9a9a9;">
<center>
<table class="defaulttable" border="0" cellspacing="1" cellpadding="0">
 <tr>
    <td width="1%">&nbsp;</td>
    <td width="34%">Contractor:</td>
    <td width="7%">&nbsp;</td>
    <td width="50%" style="text-align:left;"><?php echo ltrim($_POST["EMP"]); ?></td>
    <td width="8%">&nbsp;</td>
  </tr>
  <tr>
    <td width="1%">&nbsp;</td>
    <td width="34%">Customer:</td>
    <td width="14%">&nbsp;</td>
    <td width="50%" style="text-align:left;"><?php echo trim(str_replace( "_"," ",$_POST["JOB"])); ?></td>
    <td width="8%">&nbsp;</td>
  </tr>
  <tr>
    <td width="1%">&nbsp;</td>
    <td width="34%">PO Number:</td>
    <td width="14%">&nbsp;</td>
    <td width="50%" style="text-align:left;"><?php echo trim($_POST["PO"]); ?></td>
    <td width="8%">&nbsp;</td>
  </tr>
  <tr>
    <td width="1%">&nbsp;</td>
    <td width="34%">Week Ending:</td>
    <td width="14%">&nbsp;</td>
    <td width="50%" style="text-align:left;"><?php echo date("m/d/Y",strtotime($_POST["WKEND"])); ?></td>
    <td width="8%">&nbsp;</td>
  </tr>
</table></h4>
</center>
</td>
<td width="50" valign="bottom" style="text-align:right; font-size:120px; color:#B22625;"><b>)</b></td>
</tr>
</table>
 <BR>

<h4  style="color:#545454;"> 
<table class="defaulttable" style="font-family:arial;" width="500" id="table_02">
  <tr>
    <td width="130" height="20">&nbsp;</td>
    <td width="140" valign="bottom" ><h4 style="font-size:12px; font-family:arial;">Start Time<h4></td>
    <td width="140" valign="bottom"><h4 style="font-size:12px; font-family:arial;">Finish Time</h4></td>
    <td width="80" valign="bottom"><h4 style="text-align:center; font-size:12px; font-family:arial;">Lunch &amp; Break Time</h4></td>
    <td width="40"><h4 style="text-align:right; font-size:12px; font-family:arial;"> Daily_Total</h4></td>
  </tr>
  <tr>

  <tr>
    <td width="130"></td>
    <td width="140"></td>
    <td width="140"></td>
    <td width="80"></td>
    <td width="40"></td>
  </tr>
  <tr>


<?php
for ($i = 1; $i <= 7; $i++) {
?>

    <td width="130" height="20" align="left">&nbsp;<?php echo jddayofweek($i-2,1); ?>:</th>

    <td width="140" align="center"><?php echo $_POST["TimeInHr" . $i] ;?>:<?php	echo(($_POST["TimeInMin" . $i]==0)?"00":$_POST["TimeInMin" . $i]);?>
       &nbsp;<?php echo $_POST["TimeInAmPm" . $i]; ?></td>

    <td width="140" align="center"><?php echo $_POST["TimeOutHr" . $i]; ?>:<?php echo(($_POST["TimeOutMin". $i]==0)?"00":$_POST["TimeOutMin". $i]); ?>
       &nbsp;<?php echo $_POST["TimeOutAmPm" . $i] ?></td>
    <td width="80" align="center"><?php echo $_POST["BreakHr" . $i]; ?>:<?php echo(($_POST["BreakMin"  . $i]==0)?"00":$_POST["BreakMin"  . $i]); ?>
    </td>
    <td width="40" style="text-align:right"><?php echo StrVal(TotalHrs($i)) ?> </td>
  </tr>
<?php } ?>
  <tr>
    <th width="130" height="20" align="left" scope="row">&nbsp;</th>
    <td width="140" align="center">&nbsp;</td>
    <td width="140" align="center">&nbsp;</td>
    <td width="80" align="center">Week Total</td>

    <td width="10%" style="text-align:right" align="right"><strong><?php echo StrVal(GrandTotal()) ?></td>
  </tr>

</table>
</h4>
	
&nbsp;		

            <table class="defaulttable" width="400">
              <tr>
                <td align="left" width="150">This assignment is:</td>
                <td align="left" width="90" style="color:#B22625;"><p>
                <?php if ($_POST["Continuing"] == "1") { ?> 
                      Continuing
                <?php } Else { ?>
                		 over
                <?php } ?></p>
                </td>
                <td align="left" width="110"><td>
              </tr>
            </table>

<br /> <br />
<table style="font-family:arial;" class="defaulttable" border="0" width="100%">
  <tr>
    <td width="0%"></td>
    <td width="30%">
		<div class="btn-section" style=" width:200px;">
		<a href = "javascript:history.back()">EDIT TIME SHEET
		</a>
		</div>
 
<!-- javascript:history.back()-->
	</td>
	<td width="0%"> </td>
   	<td width="30%">    
		<form id='webtime3' name='WebTime3' Method='Post' action='manatal_write.php?snextstep=Send' onsubmit='window.parent.scroll(0,0);'>   
		<?php include "global2.php" ?>
		<?php // echo("<input type='hidden' name='EMP' value='" . $rowz["candidate_name"] . "'>"); ?>	
		<?php echo("<input type='hidden' name='sScreen' value='" . $sScreen . "'>");	?>	
		<?php echo("<input type='hidden' name='MyNewRandomNum' value='" . $MyNewRandomNum . "'>");	?>	
		<?php echo("<input type='hidden' name='AltEmpEmail' value='" . ($_POST["ALTEMPEMAIL"] ?? '') . "'>");	?>
		<?php echo("<input type='hidden' name='Contract' value='" . ($_POST["Contract"] ?? ''). "'>");	?>
		<?php // echo("<input type='hidden' name='InvMethod' value='" . $InvMethod . "'>");	?>		
		<?php echo ("<input type='hidden' name='PROJ' value='" . $_REQUEST['PROJ'] . "'>");	?>			
		<?php echo ("<input type='hidden' name='Contractor_ID' value='" . $_REQUEST['Contractor_ID'] . "'>");	?>	
		<?php echo ("<input type='hidden' name='billingcycle' value='" . $_REQUEST['billingcycle']. "'>"); ?>	
		<input type="hidden" name="REPORTTO" value="<?php echo $_REQUEST['REPORTTO']; ?>">
		<input type="hidden" name="REPORTTOCC" value="<?php echo $_REQUEST['REPORTTOCC']; ?>">
		<?php echo ("<input type='hidden' name='JOB' value='" . $_REQUEST['JOB']. "'>"); ?>
		<?php echo ("<input type='hidden' name='JOBID' value='" . $_REQUEST['JOBID']. "'>"); ?>
		<?php echo ("<input type='hidden' name='AcctEmail' value='" . $_REQUEST['AcctEmail']. "'>"); ?>
		<?php echo ("<input type='hidden' name='EMAIL' value='" . $_REQUEST['EMAIL']. "'>"); ?>
		<?php echo ("<input type='hidden' name='PO' value='" . $_REQUEST['PO']. "'>"); ?>

		<?php $sNextStep = ($_POST["WebTime"] ?? '') ?> 				
		
		<div class="btn-submit" style="padding:0 0 0 0px;"><input type="submit" OnClick="javascript:submitform();" value="Approve & Send to client" /> </div>
		</Form>
	</td>
	<td width="0%"> </td>
	<td width="30%">
		<form id = 'webtime2' name='WebTime2' Method='Post' action='manatal_write.php?snextstep=Save' onsubmit='window.parent.scroll(0,0);'> 
		<?php include "global2.php" ?>              
		<?php echo("<input type='hidden' name='sScreen' value='" . $sScreen . "'>");	?>
		<?php echo("<input type='hidden' name='MyNewRandomNum' value='" . $MyNewRandomNum . "'>");	?>	
				<?php include "global2.php" ?>         
		<?php echo("<input type='hidden' name='sScreen' value='" . $sScreen . "'>");	?>	
		<?php echo("<input type='hidden' name='MyNewRandomNum' value='" . $MyNewRandomNum . "'>");	?>	
		<?php echo("<input type='hidden' name='AltEmpEmail' value='" . ($_POST["ALTEMPEMAIL"] ?? ''). "'>");	?>
		<?php echo("<input type='hidden' name='Contract' value='" . ($_POST["Contract"] ?? '') . "'>");	?>
		<?php //  echo("<input type='hidden' name='InvMethod' value='" . ($InvMethod ?? ''). "'>");	?>		
		<?php echo ("<input type='hidden' name='PROJ' value='" . $_REQUEST['PROJ'] . "'>");	?>			
		<?php echo ("<input type='hidden' name='Contractor_ID' value='" . $_REQUEST['Contractor_ID'] . "'>");	?>		
		<?php echo ("<input type='hidden' name='REPORTTO' value='" . $_REQUEST['REPORTTO'] . "'>"); ?>
		<?php echo ("<input type='hidden' name='REPORTTOCC' value='" . $_REQUEST['REPORTTOCC']. "'>"); ?>
		<?php echo ("<input type='hidden' name='JOB' value='" . $_REQUEST['JOB']. "'>"); ?>
		<?php echo ("<input type='hidden' name='INVOICECC' value='" . ($invoice_cc_mail ?? '') . "'>"); ?>
		<?php echo ("<input type='hidden' name='EMAIL' value='" . $_REQUEST['EMAIL']. "'>"); ?>
		<div class="btn-submit"  style="padding:0 0 0 40px;" ><input type="submit" OnClick="javascript:submitform();" value="save"  /></div>
		</Form>    
    	</td>
        <td width="30%"'
		<form id = 'webtime2' name='WebTime2' Method='Post' action='manatal_write.php?snextstep=Save' onsubmit='window.parent.scroll(0,0);'> 
		<?php include "global2.php" ?>               
		<?php echo("<input type='hidden' name='sScreen' value='" . $sScreen . "'>");	?>
		<?php echo("<input type='hidden' name='MyNewRandomNum' value='" . $MyNewRandomNum . "'>");	?>	
		</Form>  

	</td>

     



  
    <td width="3%"></td>
  </tr>
</table>


</Form>





<P>&nbsp;
		
</p>


<?php

 } Else { ?>

Invalid Data

<?php }

 ?>
