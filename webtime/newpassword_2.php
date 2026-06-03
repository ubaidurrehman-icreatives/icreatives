<script language="JavaScript">
function validatePasswords() 
{
//	var username = document.forms[0].login.value;
	var pass1 = document.forms[0].password1.value;
	var pass2 = document.forms[0].password2.value;

	if (pass1.indexOf(" ") > 0) 
	{
		alert("\nNo spaces allowed.\n\nPlease re-enter first password.");
		document.forms[0].password1.select();
		document.forms[0].password1.focus();

		return false;
	}
	
	
	if (pass1 == '' || pass1.length < 6) 
	{
		alert("\nThe FIRST PASSWORD field is either empty or less than 6 characters.\n\nPlease re-enter first password.");
		document.forms[0].password1.select();
		document.forms[0].password1.focus();

		return false;
	}
	
	if(pass2 == '' || pass2.length < 6)
	{
		alert("\nThe SECOND PASSWORD field is either empty or is less than 6 characters.\n\nPlease re-enter the second password.");
		document.forms[0].password2.select();
		document.forms[0].password2.focus();
		
		return false;
	}

	if (pass2 != pass1)
	{
		alert("Your passwords do not match, please verify you entered the same password in each field.\n\n");
		document.forms[0].password1.select();
		document.forms[0].password1.focus();
		return false;
	}	
	
	if (pass1.length > 10)
	{
		alert("Your password must be greater than 6 and less than 10.\n\n");
		document.forms[0].password1.select();
		document.forms[0].password1.focus();
		return false;
	}

          // redirect parent (talent profile)
          // window.parent.location="/talent-profile/";   

	return true;
	
	
}
</script>


<?php

include "db5.php";

$sRegCode = SubStr($_REQUEST["sregcode"],3,8);
$status = $_REQUEST["status"];

	$strSQL = "SELECT Last_Name, First_Name, Employee_ID as Contractor_id, WebAccount, WebRegistrationCode, InternetPassword AS PW, ModifyUser, Status  from EmployeeMaster WHERE Status = 1 AND " ;
	$strSQL = $strSQL	. "employee_id = '" . $sRegCode . "'";

	$resMySel = odbc_exec($conn,$strSQL);
	$row = odbc_fetch_array($resMySel);
	// echo $sRegCode . "XXX";
	// echo $row["Contractor_id"] . "XXX";
	// exit();
	
If (!empty($row["Contractor_id"] ) && $status !== "done") { ?>
 		
	<Font size = 3>
		&nbsp;Hello <?php echo $row["First_Name"] . " " . $row["Last_Name"] ?>; 

		</Font>
<table border="0" width="65%">
  <tr>
    <td width="100%">&nbsp;
      <p>Please use this page to create a 6 to 10 digit password, You password must contain one lowercase, one uppercase, one number and one of the following special characters (!@#$%^&*).  Your user id is your registered email address. 
	This will enable you to access your profile information, manage your online portfolio and upload your latest resume. <br /><br />
	Most importantly, you will be able to 
	record your hours on a daily basis and send your timecards for approval on-line.&nbsp;</td>
  </tr>
</table>
	<!-- <form action="NewTalentAcct.asp?rsRegCode=Done"  method="post" onsubmit="return validatePasswords()"> -->
	<form action="newpassword.php?sregcode=64E<?php echo $row["Contractor_id"];?>2011-6573&status=done"  method="post" onsubmit="return validatePasswords()">
				<input type='hidden' name='Contractor_ID' value='<?php echo $row["Contractor_ID"]; ?>' />
 
	
<p>&nbsp;</p>
<table border="0" width="60%">
  <tr>
    <td width="4%"></td>
    <td width="13%"></td>
    <td width="5%"></td>
    <td width="85%"></td>
    <td width="51%"></td>
  </tr>
  <!--
  <tr>
    <td width="4%"></td>
    <td width="13%" align="right">User ID:</td>
    <td width="5%"></td>
    <td width="85%"><input size="20" title="Enter your username" type="text" required pattern="\w+" name="login">
	</td>
    <td width="51%"></td>
  </tr>
  -->
  <tr>
    <td width="4%"></td>
    <td width="13%" align="right"></td>
    <td width="5%"></td>
    <td width="85%"></td>
    <td width="51%"></td>
  </tr>
  <tr>
    <td width="4%"></td>
    <td width="13%" align="right">Password:</td>
    <td width="5%"></td>
    <td width="85%">
<!--<input title="Please match the requested format." type="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]) (?=.*[!@#$%^&*]).{6,10}" name="password1" size="30" onchange=" -->
<input title="Please match the requested format." type="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,10}" name="password1" size="30" onchange="



				  this.setCustomValidity(this.validity.patternMismatch ? this.title : '');">

    </td>
    <td width="51%"></td>
  </tr>
  <tr>
    <td width="4%"></td>
    <td width="13%" align="right"></td>
    <td width="5%"></td>
    <td width="85%">&nbsp;</td>
    <td width="51%"></td>
  </tr>
  <tr>
    <td width="4%"></td>
    <td width="13%" align="right">Retype Password</td>
    <td width="5%"></td>
    <td width="85%"><!--
				  <input title="Please enter the same Password as above" type="password"  size="30" required pattern="(?=.*[A-Z][a-z].*\d)(^[a-zA-Z0-9!@#$%^&*]{6,10}$)" name="password2" onchange="
			  this.setCustomValidity(this.validity.patternMismatch ? this.title : '');">-->
				  <input title="Please enter the same Password as above" type="password"  size="30" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{6,10}" name="password2" onchange="
			  this.setCustomValidity(this.validity.patternMismatch ? this.title : '');">
			  


    </td>
    <td width="51%"></td>
  </tr>
  <tr>
    <td width="4%"></td>
    <td width="13%" align="right"></td>
    <td width="5%"></td>
    <td width="85%"></td>
    <td width="51%"></td>
  </tr>
  <tr>
    <td width="4%"></td>
    <td width="13%" align="right"></td>
    <td width="5%"></td>
    <td width="85%">
	<button type="submit" class="blankbtn" onsubmit="return validatePasswords()"> Change Password </button>
	
	<!-- 		<div class="btn-submit" style="padding:25px 0 0 0px; clear:left; padding-top:25px;clear:left; float:left;">
			<input type="submit" style="height:30px;" onsubmit="return validatePasswords() value="Change Password" />
		</div>

	 -->
	
    </td>
    <td width="51%"></td>
  </tr>
</table>
</Form>

<?php 

} Else { 
	if ($status == "done") {
	$xstrSQL =  "UPDATE EmployeeMaster SET InternetPassword = '" . $_REQUEST["password1"] . "' ";
	$xstrSQL = $xstrSQL . "WHERE Status = 1 AND Employee_ID = '" . $sRegCode . "'";
	$xresMySel = odbc_exec($conn,$xstrSQL);
		echo 	$xstrSQL;
	}

?>
		<script language="javascript" type="text/javascript">
         	 // redirect parent (manager.asp)
	 window.parent.location="/talent-profile/";  
                </script>
	<?php
	// https://www.icreatives.com/index.php?pagename=new-talent&sregcode=64E00005XC82011-6573
	// response.redirect("default.asp")
}?>
</body>
</html>
