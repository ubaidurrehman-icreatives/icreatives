<?php
session_start(); 

// include "global2.php";

$Contract = $_REQUEST["Contract"] ?? '';
$InvMethod = $_REQUEST["INVMETHOD"] ?? ''; 


// see if we have invoiced yet, if not generate a timesheet:

// $invSQL = "select count(*) as invcount from ar_invoice_detail where divisionKey = '" . $_REQUEST["DIVISION"] ."'";
// echo $invSQL;
// exit();

// $resMySel = odbc_exec($conn,$invSQL);
// $row = odbc_fetch_array($resMySel);

// $invcount = $row["invcount"];
?>
<script>
(function () {
  // Await ACK from parent after asking it to scroll
  function scrollParentThen(fn) {
    return new Promise(function (resolve) {
      function onAck(e) {
        if (e && e.data && e.data.type === 'SCROLL_TOP_ACK') {
          window.removeEventListener('message', onAck);
          // give the parent one paint before we navigate
          requestAnimationFrame(function(){ requestAnimationFrame(resolve); });
        }
      }
      window.addEventListener('message', onAck);
      // Ask parent to scroll
      try { window.parent.postMessage({ type: 'SCROLL_TOP_REQ' }, '*'); } catch(_) {}
      // Safety timeout: proceed after 150ms if no ACK (prevents deadlocks)
      setTimeout(function () {
        window.removeEventListener('message', onAck);
        resolve();
      }, 150);
    }).then(function () {
      if (typeof fn === 'function') fn();
    });
  }

  // Intercept all form submits and re-submit after parent scrolls
  document.addEventListener('DOMContentLoaded', function () {
    Array.prototype.forEach.call(document.forms, function (form) {
      form.addEventListener('submit', function (ev) {
        ev.preventDefault(); // stop immediate navigation
        scrollParentThen(function () {
          form.submit();      // native submit after parent is at top & painted
        });
      }, { once: true });
    });
  });
})();
</script>

<script type="text/javascript">
function submitform1() {
  try { (window.parent || window.top).scrollTo(0, 0); } catch (e) {}
  setTimeout(function () {
    document.forms.webtime1.submit(); // native submit after paint
  }, 0);
}

function submitform2() {
  try { (window.parent || window.top).scrollTo(0, 0); } catch (e) {}
  setTimeout(function () {
    document.forms.webtime2.submit();
  }, 0);
}

function submitform3() {
  try { (window.parent || window.top).scrollTo(0, 0); } catch (e) {}
  setTimeout(function () {
    document.forms.webtime3.submit();
  }, 0);
}
</script>


<?php

Function SaveVal() {
	echo("<input type='hidden' name='PoNumber' value='" . $_REQUEST["PoNumber"]  . "'>") ;
	echo("<input type='hidden' name='PO' value='" . $_REQUEST["PO"]  . "'>") ;
	echo("<input type='hidden' name='EMP' value='" . $_REQUEST["EMP"]. "'>") ;
	echo("<input type='hidden' name='TITLE' value='" . $_REQUEST["TITLE"]. "'>") ;
	echo("<input type='hidden' name='DATE' value='" . $_REQUEST["DATE"]. "'>") ;
	echo("<input type='hidden' name='JOB' value='" . $_REQUEST["JOB"]. "'>") ;
	echo("<input type='hidden' name='ITEM' value='" . $_REQUEST["TITLE"]. "'>") ;
	echo("<input type='hidden' name='NOTE' value='" . $_REQUEST["NOTE"]. "'>") ;
	echo("<input type='hidden' name='PROJ' value='" . $_REQUEST["PROJ"]. "'>") ;
	echo("<input type='hidden' name='xORDER' value='" . $_REQUEST["xORDER"] . "'>") ;
	echo("<input type='hidden' name='DURATION' value='" . $_REQUEST["DURATION"]. "'>") ;
	echo("<input type='hidden' name='BILLINGSTATUS' value='" . $_REQUEST["BILLINGSTATUS"]. "'>") ;
	echo("<input type='hidden' name='PITEM' value='" . $_REQUEST["PITEM"]. "'>") ;
	echo("<input type='hidden' name='BITEM' value='" . $_REQUEST["BITEM"]. "'>") ;
	echo("<input type='hidden' name='Contractor_ID' value='" . $_REQUEST["Contractor_ID"] . "'>") ;
	echo("<input type='hidden' name='BILLINGPROFILE' value='" . $_REQUEST["BILLINGPROFILE"]. "'>") ;
	echo("<input type='hidden' name='WKEND' value='" . $_REQUEST["WKEND"] . "'>") ;
	echo("<input type='hidden' name='billingcycle' value='" . $_REQUEST["billingcycle"] . "'>") ;
	// changed below to accomidate apostrophies in email addresses
	?>
	<input type="hidden" name="REPORTTO" value="<?php echo $_REQUEST['REPORTTO']; ?>">
	<input type="hidden" name="REPORTTOCC" value="<?php echo $_REQUEST['REPORTTOCC']; ?>">
	<input type="hidden" name="EMAIL" value="<?php echo $_REQUEST['EMAIL']; ?>">
	<?php
	echo("<input type='hidden' name='BRANCH' value='" . $_REQUEST["BRANCH"] . "'>") ;
	echo("<input type='hidden' name='INVMETHOD' value='" . $_REQUEST["INVMETHOD"] . "'>") ;
	// echo("<input type='hidden' name='ALTETIME' value='" . $_REQUEST["ALTETIME"] . "'>") ;
	// echo("<input type='hidden' name='ALTETIME2' value='" . $_REQUEST["ALTETIME2"] . "'>") ;
	echo("<input type='hidden' name='ALTETIME' value='" . $_REQUEST["ALTETIME"] . "'>") ;
	echo("<input type='hidden' name='ALTEMPMAIL' value='" . $_REQUEST["ALTEMPMAIL"] . "'>") ;
	echo("<input type='hidden' name='AcctEmail' value='" . $_REQUEST["AcctEmail"]  . "'>") ;
	echo("<input type='hidden' name='DIVISION' value='" . $_REQUEST["DIVISION"]  . "'>") ;
	echo("<input type='hidden' name='CONTRACT' value='" . $_REQUEST["CONTRACT"]  . "'>") ;
	echo("<input type='hidden' name='Continuing' value='" . $_REQUEST["Continuing"]  . "'>") ;
	echo("<input type='hidden' name='Next' value='" . $_REQUEST["Next"]  . "'>") ;

	for ($i = 1; $i <= 7; $i++) { 
		
    // Extract "HH:MM" from POST (e.g. "08:15", "17:45", "00:30")
    $inStr    = trim($_POST["TimeInHr{$i}"]  ?? '');
    $outStr   = trim($_POST["TimeOutHr{$i}"] ?? '');
    $breakStr = trim($_POST["BreakHr{$i}"]   ?? '');

    // ----------------------------------
    // Split each into hours + minutes
    // ----------------------------------
    list($InHr, $InMin)       = array_pad(explode(":", $inStr),     2, 0);
    list($OutHr, $OutMin)     = array_pad(explode(":", $outStr),    2, 0);
    list($BreakHr, $BreakMin) = array_pad(explode(":", $breakStr),  2, 0);

    $InHr     = (int)$InHr;
    $InMin    = (int)$InMin;
    $OutHr    = (int)$OutHr;
    $OutMin   = (int)$OutMin;
    $BreakHr  = (int)$BreakHr;
    $BreakMin = (int)$BreakMin;

    // ----------------------------------
    // Output exactly what NEXT program expects:
    // ----------------------------------
    echo "<input type='hidden' name='TimeInHr{$i}' value='{$InHr}'>" . PHP_EOL;
    echo "<input type='hidden' name='TimeInMin{$i}' value='{$InMin}'>" . PHP_EOL;

    echo "<input type='hidden' name='TimeOutHr{$i}' value='{$OutHr}'>" . PHP_EOL;
    echo "<input type='hidden' name='TimeOutMin{$i}' value='{$OutMin}'>" . PHP_EOL;

    echo "<input type='hidden' name='BreakHr{$i}' value='{$BreakHr}'>" . PHP_EOL;
    echo "<input type='hidden' name='BreakMin{$i}' value='{$BreakMin}'>" . PHP_EOL;
}

	
	 // echo "XXX" . ($_POST["TimeInAmPm" . $i]) . "XXX";
	
	// $TotalHrs = round( (floatval($sOutMinutes) + floatval($sOutHours) ) - ( floatval($sInMinutes)  +  floatval($sInHours) ) - ( floatval($sBreakHours) + floatval($sBreakMinutes)),2 );
}

function TotalHrs($i) {
    $TotalHrs = 0;

    // Safely get posted values
    $inRaw  = $_POST["TimeInHr"  . $i] ?? '';
    $outRaw = $_POST["TimeOutHr" . $i] ?? '';
    $brkRaw = $_POST["BreakHr"   . $i] ?? '';

    // Split into hours/minutes safely
    $InHrMin   = explode(":", $inRaw);
    $OutHrMin  = explode(":", $outRaw);
    $BreakHrMin = explode(":", $brkRaw);

    // Provide defaults if minutes are missing
    $sInHours      = isset($InHrMin[0])   ? (float)$InHrMin[0]   : 0;
    $sInMinutes    = isset($InHrMin[1])   ? (float)$InHrMin[1]/60   : 0;
    $sOutHours     = isset($OutHrMin[0])  ? (float)$OutHrMin[0]  : 0;
    $sOutMinutes   = isset($OutHrMin[1])  ? (float)$OutHrMin[1]/60  : 0;
    $sBreakHours   = isset($BreakHrMin[0])? (float)$BreakHrMin[0] : 0;
    $sBreakMinutes = isset($BreakHrMin[1])? (float)$BreakHrMin[1]/60 : 0;

    // Adjust for AM/PM safely
    if (($_POST["TimeInAmPm" . $i] ?? '') === "PM")  { $sInHours  += 12; }
    if (($_POST["TimeOutAmPm" . $i] ?? '') === "PM") { $sOutHours += 12; }

    // Calculate total hours
    $TotalHrs = round(
        (($sOutHours + $sOutMinutes) -
         ($sInHours + $sInMinutes) -
         ($sBreakHours + $sBreakMinutes)), 2
    );

    return $TotalHrs;
}




Function GrandTotal() {
	$lsGT = 0;
for ($i = 1; $i <= 7; $i++) {
	$lsGT = $lsGT + TotalHrs($i);
}
$GrandTotal = $lsGT;

return $GrandTotal;
}



$Contract = $_POST["Contract"] ?? '';
$InvMethod = $_POST["INVMETHOD"]; 

// $sTalentName = RIGHT(request.form("EMP"), LEN(request.form("EMP")) - InStr(request.form("EMP"),"_"))

list($sTalentFirst,$sTalentLast) = explode(" ",$_REQUEST["EMP"]);

// $sTalentFirst =  SubStr( $_REQUEST["EMP"],StrPos($_REQUEST["EMP"],"_") +1 );

$sTalentName = Trim($_REQUEST["EMP"]);

$MyNewRandomNum = (Trim(date("Y") . date("m") . date("d")) . date("h") . date("m") . date("s")) . intval(rand()) ;

	// test to see if UserDefined Invoice methos id markerd as "Plain" or as "Email" Plain is for plain text

		include 'manatal_eapprovetxt_mobile.php';
		// include 'manatal_eapprovetxt.php';

?>		


<link href="/webtime/css/mobile/styles.css" rel="stylesheet" type="text/css" />
  <style>
  .largetxt1{
  font-size:18px;
  }
  .footer-block
  {
  width:100%;
  height:25px;
  display:block;
  margin-bottom:20px;
  }
  .footer-block-left
  {
  float:left;
  width:100%;
  }
  .footer-block-right
  {
  float:right;
   width:30%;
  }
  .footer-img
  {
width:95%;
padding-left:4px;
padding-top:4px;
}
  </style>
        
<style>
.Container{
background-image:url('/webtime/css/mobile/gray_img.jpg');
background-size:100% 100%;
width:100%;
height:78px;
background-repeat: no-repeat;

  
}
 .Button{
padding-top:3px;
WIDTH:99%;
height:40px; 
padding-left: 11px;
margin-top: 12px;
background-color: #b22625;
/*border-radius: 5px;*/
color: white;
border:none;
}
.outerboxT
{
position: relative;
float: left;
width: 100%;
height: 71px;
background-color: #e5e5e5;
  border-top-left-radius: 20px;
  border-top-right-radius: 20px;
  border-bottom-right-radius: 20px;
  margin-top: 7px;
}
</style>		
<style>
.BlockClass{
height:70px;width:305px; 
background-color:RED;
  border-top-left-radius: 30px;
  border-top-right-radius: 20px;
  border-bottom-right-radius: 20px;
  padding-top: 16px
			}
</style>
<style>
/* Desktop (default) */
.mobile-spacer{
  clear: left;
  height: 50px;
}

/* Tablet */
@media (max-width: 1024px){
  .mobile-spacer{
    height: 120px;
  }
}

/* Mobile */
@media (max-width: 768px){
  .mobile-spacer{
    height: 300px;
  }
}
</style>

<div class="mobile-spacer"></div>

<h1 style="font-family:lato;font-size: 40px;text-align:left"> Timesheet</h1>
  <p>Assignment Number:<b> <?php echo($_REQUEST["xORDER"]); ?></b><br/>
  Contractor:<b> <?php echo($_REQUEST["EMP"]); ?></b><br/>
  Customer:<b> <?php echo Str_Replace("_"," ",$_REQUEST["JOB"]); ?></b><br/>
  Position:<b> <?php echo Str_Replace("_"," ",$_REQUEST["TITLE"]); ?></b><br/>
  <?php $TITLE = $_REQUEST["TITLE"];?>
  PO Number: <?php echo ($_REQUEST["PO"]) ?><br/>Week Ending: <?php	echo($_REQUEST["WKEND"]); ?><p>

<?php for ($i = 1; $i <= 7; $i++) { ?>
	<div class="outerboxT">
		<div style="width:60%;padding-top:10px; font-size:18px; color:#b22625;">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo jddayofweek($i-2,1); ?></div>&nbsp;&nbsp;&nbsp;&nbsp;
			<div style="width:20%;float:right;padding:0px 15px 0 0;"><?php echo(TotalHrs($i)); ?>Hrs</div>
			<div style="clear:left; font-size:10px; padding:5px 0 0 15px;">From <?php echo ($_POST["TimeInHr" . $i] ?? '00') ;?>:<?php	echo(( ($_POST["TimeInMin" . $i] ?? '00')==0)?"00":($_POST["TimeInMin" . $i] ?? '00'));?> To <?php echo ($_POST["TimeOutHr" . $i] ?? '00'); ?>:<?php echo(($_POST["TimeOutMin". $i] ?? '00')==0)?"00":($_POST["TimeOutMin". $i] ?? '00'); ?> Lunch <?php	echo ($_REQUEST["BreakHr" . $i] ?? '00') ?>Hrs</p>  
			</div>
			<br/>
<?php } ?>

 <br/>
 <div class="footer-block">
 <div class="footer-block-left"><h1>  <span style="color:#b22625;font-family:lato;"> Total Week Hours </span> &nbsp;&nbsp;<?php echo(GrandTotal()) ?> hrs</h1> 
 <!--<div class="footer-block-right" style="width:auto;float:none"></div> -->
 
<div class="clear"></div>

<p>This assignment is:<?php if ($_REQUEST["Continuing"] == "1") { ?> 
                      Continuing
                <?php } else {?>
                		 over
                <?php } ?></p>
				</div></div>
<div class="clear"></div>

<div>
<form id="webtime1" name="webtime1" method="post" action="manatal_write.php?snextstep=Send">
  <!-- hidden inputs -->
  <button type="submit" class="Button" style="border:0;">Approve/Send to Client</button>
  <?php echo("<input type='hidden' name='sScreen' value='" . $sScreen . "'>");	?>	
  <?php echo("<input type='hidden' name='MyNewRandomNum' value='" . $MyNewRandomNum . "'>");	?>	

  <?php include "global2.php";
	SaveVal();  ?>
</form>


<form id="webtime2" name="webtime2" method="post" action="manatal_write.php?snextstep=Save">
  <!-- hidden inputs -->
  <button type="submit" class="Button">Save for Later</button>
  <?php include "global2.php"; 
  SaveVal(); ?>
  <?php echo("<input type='hidden' name='sScreen' value='" . $sScreen . "'>");	?>
  <?php echo("<input type='hidden' name='MyNewRandomNum' value='" . $MyNewRandomNum . "'>");	?>	


</form>

 <div class="clear"></div>
   <div >
<form name="GoBack" Method="Post" action=javascript:history.go(-1)>
                   
					 <input type="button" id="Edit" name="Edit" value="                                  Edit                                       " onclick="javascript:history.go(-1);"  class="Button" >
	</Form>
  </div>   



</Form>





<P>&nbsp;
		
</p>

