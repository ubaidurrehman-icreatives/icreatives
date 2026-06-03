<style>
  input[type='radio']:after {
        width: 15px;
        height: 15px;
        border-radius: 15px;
        top: -1px;
        left: -1px;
        position: relative;
        background-color: #ffffff;
        content: '';
        display: inline-block;
        visibility: visible;
        border: 1px solid #101010;
    }

    input[type='radio']:checked:after {
        width: 15px;
        height: 15px;
        border-radius: 15px;
        top: -1px;
        left: -1px;
        /*position: relative; */
        background-color: #b22625;
        content: '';
        display: inline-block;
        visibility: visible;
        border: 1px solid #101010;
    }
	</style>




<script language="JavaScript">
function validateFields2()
{
		
		// is the continuing radio button checked		
		  	var radioSelected = false;
  			for (x = 0;  x < document.forms[0].Assignment.length;  x++)
  			{
		    if (document.forms[0].Assignment[$x].checked)
		    
       		 radioSelected = true;
  			}
			if (!radioSelected)
		  	{
			   	alert("Please select one of the \"Assignment\" options.");

		    return (false);
		  	}
	window.parent.scroll(0,0);
		return true;
}		
		 	 
</script>


<script type="text/javascript"> 
function validate() { 
// Checking if at least one period button is selected. Or not. 

{
		
		// is the continuing radio button checked		
		  	var radioSelected = false;
  			// for (x = 0;  x < document.forms[0].Assignment.length;  x++)
  			{
		    if (document.forms[0].Assignment[0].checked || document.forms[0].Assignment[1].checked || document.forms[0].Assignment[2].checked )
		    
       		 radioSelected = true;
  			}
			if (!radioSelected)
		  	{
			   	alert("Please select one of the \"Assignment\" options.");

		    return (false);
		  	}
	
		return true;
}		
		 	 
</script>





<script type="text/javascript">

function submitform()
{
	
	if (validateFields2()==true)	
	{	
  		document.forms["webtime"].submit();
	}
	
}
</script>
<?php 
require_once __DIR__ . '/../db/db.php';
$link = db();   

Function Weekend($i) {

$date = mktime(date('G'), date('i'), date('s'), date('n'), date('j') + $i, date('Y'));
// $Weekend = date("Y-m-d", strtotime('next friday', $date));
$Weekend = date('m/d/Y', strtotime("next friday", $date));

return $Weekend;
 }

$Contractor_ID = $_SESSION['resource_id'];
/*
$query = "SELECT  * from  ic_matches
          WHERE closed = 0 AND candidate = '".$Contractor_ID. "'";
	*/	  
$query = "SELECT * FROM ic_matches
          WHERE (closed = 0 OR (closed = 1 AND closed_date >= DATE_SUB(NOW(), INTERVAL 14 DAY)))
         AND (is_active = 1 OR (is_active = 0 AND deactive_date >= DATE_SUB(NOW(), INTERVAL 14 DAY) AND deactive_date <> '0000-00-00')) AND candidate = '".$Contractor_ID."'";

			  
	$query;	  
	$SQLr = mysqli_query($link,$query );	
	// while ($row = mysqli_fetch_array($result)) {


IF (mysqli_fetch_array($SQLr)){
		echo ("<p><b>Select an assignment to record your hours on a daily basis,<br /> and to generate a timesheet for approval.<br />&nbsp;<br /></b>") ;
		echo ("<form id='webtime' name='WebTime' Method='Post' action='/webtime/manatal_detail_1.php' onsubmit='return validateFields2(); parent.scrollTo(0, 0); '>") ;
		$StrLogOff = false ;  
} Else {	
		// echo ("<div style='padding-top:100px;'> The customer is not set up for electronic timesheets.<br /><br />please <a href='https://www.icreatives.com/timesheet.pdf' target='_blank'><font color = '#b22625'>click here</font></a> to download our manual fax form </div>");		
		echo ("<div style='padding-top:100px;'> The customer is not set up for electronic timesheets.<br /><br /><font color = '#b22625'>Please call our office at 1-888-icreate / 954-529-6291 </div>");		

        $StrLogOff = true;
		echo ("<form id='webtime' name='WebTime' Method='Post' action='default.php'>")  ;             
} 

?>         


<input type="hidden" name="Contractor_ID" value="<?php echo $Contractor_ID; ?> >">       

<?php   
$tsStat =   "";
// 	for ($x = 0;  $x <= $iRecordCount-1; $x++) {
//	$resMySel = odbc_exec($conn,$strSQL);
$SQLr = mysqli_query($link,$query );
while($row = mysqli_fetch_array($SQLr)){
	$candidate_email = $row['candidate_email'];
	$company_name = $row['company_name'];
	$organization = $row['organization'];
	
	echo ("<table style='font-size:15px;' class='defaulttable' border='0' width='650'align='top'>");
	echo  ("<TR><td width='271' colspan='4'>");
	echo  ("Assignment Number: ")	;
	echo  $row['job']; 
	echo  ("</TD></TR>");
	
	echo  ("<TR><td width='271' colspan='4'>");
	echo  $row['company_name'] . ": " . $row['job_name'];
	echo  ("</TD></TR>");
	echo  ("<TR><td width='271' colspan='4'></TD></TR>");
	$i = 0;
	
	ECHO "
	</table>
	<BR/>
	<table class='defaulttable' border='0' width='650' valign='top'>";

	for ($i = 0; $i <= 2; $i++) {

		$strSQL2 = "SELECT wt.Employee_ID as EID  , wt.AssignmentNumber as AN, wt.WeekEnding as WE, wt.SentDate as SD, wt.ApproveDate as AD, wt.declineDate as DD FROM ic_timesheets wt   ";
		$strSQL2 .= " WHERE NOT wt.void AND wt.Employee_ID = '"  .  $Contractor_ID  .  "' " ;
		$strSQL2 .= "  AND wt.AssignmentNumber = '"  .  $row['job']  . "' ";
		$strSQL2 .= " AND wt.WeekEnding = '" . date("Y-m-d", strtotime(WeekEnd(($i-2)*7))). "'  ";

	 	// echo $strSQL2;
		 // date("Y-m-d", strtotime(WeekEnd(($i-2)*7)));

		$tsStat = " <td width='130' valign='top' align = 'left'>&nbsp;&nbsp;Week Ending </td> <td width='100'  align = 'left'>"  .  WeekEnd(($i-2)*7)  .  "</td>";	

		echo ("<tr>");

		// echo "EID = " .$row2['EID']. " SD = " . $row2['SD'] . " DD = " . $row2['DD'] . " AD= " . $row2['AD'] . "<br>";
		$resMySel2 = mysqli_query($link,$strSQL2 ); 
		$row2 = mysqli_fetch_array($resMySel2);
		
		// echo "XXX" . $row2["SD"];	
		 if ( mysqli_num_rows($resMySel2) > 0 ) {

            if ( $row2["SD"] !== "0000-00-00 00:00:00" &&  $row2["AD"] == "0000-00-00 00:00:00" && $row2["DD"] == "0000-00-00 00:00:00" ) {
      			$tsStat .= "<td width='180' 'height='20' align = 'left'><P> Approval Pending </P></TD>";
				echo "<td width='15'  height=20''></TD>" . $tsStat . "" ;
       		   } elseif  ( $row2['DD'] !== "0000-00-00 00:00:00" && $row2['AD']== "0000-00-00 00:00:00" ) {
      			$tsStat .=  "<td width='180' style='color:#b22625' height=20'' align = 'left'> <P>Editing</p></font> </TD>";
      			echo ("<td width='15'  height='20'><input id`='". $row['job']."' name='Assignment'  type='radio' value='" . $row['job']  . "|" . WeekEnd(($i-2)*7) . "' required> </td>". $tsStat . "") ; 
      			
     		   } elseif   ($row2["SD"] == "0000-00-00 00:00:00"  || ($row2['DD'] !== "0000-00-00 00:00:00"   && $row2['AD'] == "0000-00-00 00:00:00")  )  {
      			$tsStat .=  "<td width='180' style='color:#b22625' height='20'align = 'left'>  Editing <TD>";
			echo ("<td width='15'  height='20'><input id='". $row['job'] ."' name='Assignment'  type='radio' value='" . $row['job']  . "|" . WeekEnd(($i-2)*7) . "' required> </td>". $tsStat . "") ; 

	           } elseif  ( $row2['SD'] !== "0000-00-00 00:00:00"  && $row2['AD'] !== "0000-00-00 00:00:00"  ) {
      			$tsStat .=  "<td width='180' height='20' align = 'left'> Approved </td>";
			echo ("<td width='15' height=20''></td>" . $tsStat . "");
		   } 
      	       	   } else {
     		        $tsStat .= "<td width='180'  align = 'right'></TD>";
			echo ("<td width='15' height='20'><input id='". $row['job'] ."' name='Assignment' type='radio' value='" . $row['job']  . "|" . WeekEnd(($i-2)*7) . "' required> </td>" . $tsStat . "");   		
   		   }	 	
		   echo ("</TR>");
		echo '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>';
	}

	echo ("</TABLE><br /><br />");

	// echo "x = " . $x . "<br>";
	// echo "i = " . $i . "<br>";
}


IF ($StrLogOff) {
?>


<!--       <input type="Submit" value="Log Off" name="Log Off"> -->
	<!-- this is how the buttons should be 
        <div style="float:left; padding: 65px 150px 0 0px;">
		
		
		
           <div class="blankbtn" style="text-align:right; "><a href="/portal-talent-sign-in/?p=logout" target="_top" class="blankbtntxt">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               Log-off</a>
           </div>
        </div>

        <div style="float:left; padding: 65px 150px 0 0px;">
           <div class="blankbtn" style="text-align:right; "><a href="/talent-login-submit" target="_top" class="blankbtntxt">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               Back to profile</a>
           </div>
        </div>
-->

<?php } else { ?>
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>

	<div style="display:none;"  class="styled">
	<input name="Assignment" type="radio" class="styled" value="fake">
	</div>
	<input type='hidden' name = 'Email' value = '<?php echo $candidate_email; ?>'> 
	<input type='hidden' name = 'JOB' value = '<?php echo $company_name; ?>'> 	
	<input type='hidden' name = 'JOBID' value = '<?php echo $organization; ?>'> 
	
	<div class="btn-submit" style="padding:0 0 0 75px;"><input style="width:200px;" type="submit" OnClick="javascript:submitform();" value="Next" /> </div>

<?php } ?>
</Form>

