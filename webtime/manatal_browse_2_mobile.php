<script>
function validateFields2() {
  // Grab the form
  var form = document.getElementById('webtime');
  if (!form) return false;

  // Get the Assignment radios (works for 1 or many)
  var radios = form.elements['Assignment'];
  var selected = false;

  if (radios) {
    if (radios.length === undefined) {
      // Only one radio rendered
      selected = !!radios.checked;
    } else {
      for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) { selected = true; break; }
      }
    }
  }

  if (!selected) {
    alert('Please select one of the "Assignment" options.');
    return false;
  }

  // Try to scroll the parent if same-origin; otherwise just scroll this frame
  try {
    if (window.parent && window.parent !== window) {
      var ref = document.referrer;
      var sameOrigin = false;
      if (ref) {
        try { sameOrigin = (new URL(ref)).origin === location.origin; } catch (e) {}
      }
      if (sameOrigin) {
        window.parent.scroll(0, 0);
      } else {
        window.scroll(0, 0);
      }
    } else {
      window.scroll(0, 0);
    }
  } catch (e) {
    // Cross-origin or other issue — ignore and continue
    window.scroll(0, 0);
  }

  return true;
}
</script>



<style>
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
.outerbox
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
body{ 
	font-family: Arial, Helvetica, sans-serif; 
} 
</style>



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

Function Weekend($i) {

$date = mktime(date('G'), date('i'), date('s'), date('n'), date('j') + $i, date('Y'));
$Weekend = date("Y-m-d", strtotime('next friday', $date));
return $Weekend;
 }
 
$query = "SELECT * FROM ic_matches
          WHERE (closed = 0 OR (closed = 1 AND closed_date >= DATE_SUB(NOW(), INTERVAL 14 DAY)))
         AND (is_active = 1 OR (is_active = 0 AND deactive_date >= DATE_SUB(NOW(), INTERVAL 14 DAY) AND deactive_date <> '0000-00-00')) AND candidate = '".$Contractor_ID."'";
			  
// echo $query;	

	$result = mysqli_query($link,$query );	
	$rowcount=mysqli_num_rows($result);
//  $row = mysqli_fetch_array($result)

            IF ($rowcount > 0 ) {
              echo ("<p style='text-align: left;padding-top:30px;'>Select an assigment to record your hours on a daily basis and to generate a timesheet for approval.") ;
             //  echo ("<form id='webtime' name='webtime' Method='Post' action='manatal_detail_1_mobile.php' onsubmit='return validateFields2()'>");
              echo ("<form id='webtime' name='webtime' Method='Post' action='/webtime/manatal_detail_1_mobile.php' onsubmit='return validateFields2()'>");
              $StrLogOff = "False"   ;
            } Else {
				ECHO "NO";
				EXIT();
              echo ("<div style='padding-top:100px;'> The customer is not set up for electronic timesheets.<br /><br />please <a href='/timesheet.pdf' target='_blank'><font color = '#b22625'>click here</font></a> to download our manual fax form </div>");
            	$StrLogOff = "True";
			// echo  ("<form id='webtime' name='webtime' Method='Post' action='manatal_browse_mobile.php'>")   ;            
              echo  ("<form id='webtime' name='webtime' Method='Post' action='manatal_talent_portal_signin.php'>")   ;            
            }         
?>         


<input type="hidden" name="Contractor_ID" value="<?php echo $Contractor_ID ?>">       



<?php         

$result = mysqli_query($link,$query );
while($row = mysqli_fetch_array($result) ){
	
	$PROJ = $row["job"];
	$ITEM = $row["job_name"];
	
	$JOB = $row["company_name"];
	// myArray(2,i) = Contractor_ID
	$xORDER = $row["job"];
	$TITLE = $row["job_name"];		
	$EMP = $row["candidate_name"];
	$PROJ = $row["job"];
	$candidate_email = $row['candidate_email'];
	$company_name = $row['company_name'];
	$organization = $row['organization'];
	echo "<div style='padding-top:20px'>";
	echo "Assignment Number: ";	
	echo "<b>";
	echo $xORDER;
	echo "</b><br/>";
	echo $JOB . ":<b>  " . $TITLE . "</b>" ;
	echo "<p> </p></div>";
	?>
	<!-- <div class="formText"> -->
	<?php
//	echo $strSQL;
//  echo WeekEnd((1-2)*7)."XXX";
	for ($i = 0; $i <= 2; $i++) {
		$strSQL2 = "SELECT wt.Employee_ID as EID  , wt.AssignmentNumber as AN, wt.weekending as WE, wt.SentDate as SD, wt.ApproveDate as AD, declineDate as DD FROM ic_timesheets wt ";
		$strSQL2 = $strSQL2 . " WHERE wt.void = FALSE AND wt.Employee_ID = '" . $Contractor_ID . "' ";
		$strSQL2 = $strSQL2 . "  AND wt.AssignmentNumber = '" . $PROJ ."' ";
		$strSQL2 = $strSQL2 . "  AND wt.WeekEnding = '" . WeekEnd(($i-2)*7) . "'";
		// echo $strSQL2 ;
		$resMySel2 = mysqli_query($link,$strSQL2 );
		$row2 = mysqli_fetch_array($resMySel2);
		
		$tsStat = "Week Ending &nbsp;&nbsp;&nbsp;&nbsp;" . WeekEnd(($i-2)*7) ;	?>
	
		<?php	
		// echo 		$strSQL2;
		
		IF (isset($row2["EID"]) && !Is_Null($row2["EID"])) {
		// echo "XXX";
		// echo 		$row2["EID"];
                IF ($row2["SD"] !== "0000-00-00 00:00:00" &&  $row2["AD"] == "0000-00-00 00:00:00" && $row2["DD"] == "0000-00-00 00:00:00") { 
      			$tsStat = $tsStat . "<span style='color:#b22625 ;'>&nbsp;&nbsp;Pending Approval</span>";
				echo "<div class='outerbox'><p style='margin-top: 24px; margin-right: 50px;margin-left:25px;'>&nbsp;&nbsp;". $tsStat . "</p></div>" ; 				

       		   } else if ( $row2["DD"] !== "0000-00-00 00:00:00" && $row2["AD"] == "0000-00-00 00:00:00" )  {
      			$tsStat = $tsStat .  "<span style='color:#b22625 ;'>&nbsp;&nbsp;&nbsp;Editing</span>";
      			echo "<div class='outerbox'><p style='margin-top: 24px; margin-right: 50px;margin-left:25px;'><input id='" . $PROJ . "' name='Assignment'  type='radio' class='styled' value='" . $PROJ  . "|" . WeekEnd(($i-2)*7) . "'>&nbsp;&nbsp;" . $tsStat . "</p></div>";  
      			
     		   } else if ( $row2["SD"] == "0000-00-00 00:00:00" || ($row2["DD"] !== "0000-00-00 00:00:00" && $row2["AD"] == "0000-00-00 00:00:00" ) )  {
      			$tsStat = $tsStat .  "<span style='color:#b22625 ;'>&nbsp;&nbsp;  Editing</span>         ";

   				echo "<div class='outerbox'><p style='margin-top: 24px; margin-right: 50px;margin-left:25px;'><input id='". $PROJ ."' name='Assignment'  type='radio' class='styled' value='" . $PROJ  . "|" . WeekEnd(($i-2)*7) . "'> &nbsp;&nbsp;" . $tsStat . "</p></div>" ;  

	           } else if ($row2["SD"] !== "0000-00-00 00:00:00" && $row2["AD"] !== "0000-00-00 00:00:00" ) {
      			$tsStat = $tsStat .  " Approved ";
				echo "<div class='outerbox'><p style='margin-top: 24px; margin-right: 50px;margin-left:25px;'>" . $tsStat . "</p></div>";
      		   }
      	} ELSE {

			echo "<div class='outerbox'><p style='font-family: Arial; margin-top: 24px; margin-right: 50px;margin-left:25px;'><input id='". $PROJ ."' name='Assignment' type='radio'  class='styled' value='" . $PROJ  . "|" . WeekEnd(($i-2)*7) . "'> &nbsp;&nbsp;" . $tsStat . "</p></div>";  		
   		}	 	
	}
	echo "<p>";
	echo "<div style='float:left; height:30px; padding-top:80px;'> </div>";
} 
   
 IF (isset($strLogOff) && $strLogOff == "True") {
?>

	<!-- this is how the buttons should be -->
        <div style="float:right; ">
           <div class="blankbtn" style="text-align:right; "><a href="/portal-talent-profile/?p=logout" target="_top" class="blankbtntxt">
		Log-off</a>
           </div>
        </div>

        <div style="float:right; ">
           <div class="blankbtn" style="text-align:right; "><a href="/portal-talent-profile/?p=portfolio" target="_top" class="blankbtntxt">
               Back to profile</a>
           </div>
        </div>


  <?php } else { ?>

<div style="display:none;"  class="styled">
<input name="Assignment" type="radio" class="styled" value="fake">
</div>
 <div>
<button type="submit" id="Next" name="Next" class="Button" style="border:0;">
  Next
</button>
  </div>
	       <!-- this is how the buttons should be -->
 <input type="hidden" name="PROJ" value="<?php echo $PROJ?>">  
  <input type="hidden" name="JOB" value="<?php echo $JOB?>">  
   <input type="hidden" name="xORDER" value="<?php echo $xORDER?>">  
    <input type="hidden" name="TITLE" value="<?php echo $TITLE?>"> 
	   <input type="hidden" name="EMP" value="<?php echo $EMP?>"> 
	   <input type="hidden" name="ITEM" value="<?php echo $ITEM?>"> 
	   	<input type='hidden' name = 'Email' value = '<?php echo $candidate_email; ?>'> 


  </Form>
<?php echo "</ul>"; }

?>		

