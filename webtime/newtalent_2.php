<script language="JavaScript">
function validatePasswords() 
{
//	var username = document.forms[0].login.value;
	var pass1 = document.forms[0].password1.value;
	var pass2 = document.forms[0].password2.value;

	
	// Validate the first password field
	
//	if (username == '' || username.length < 6) 
//	{
//		alert("\nThe User Name is less than 6 characters.\n\nPlease re-enter your user name.");
//		document.forms[0].login.select();
//		document.forms[0].login.focus();
//
//		return false;
//	}	

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
$sRegCode = SubStr($_REQUEST['sregcode'],3,8);

// echo "regcode = " . $sRegCode ."<br>";

$strSQL = "SELECT Last_Name, 
	First_Name, 
	Employee_ID as Contractor_ID, 
	WebAccount, 
	WebRegistrationCode, 
	InternetPassword AS PW, 
	ModifyUser, 
	Status  
	from EmployeeMaster WHERE Status = 1 AND Employee_ID = '" . $sRegCode . "'" .
	"AND InternetPassword IS NULL";

// echo $strSQL;
include("db5.php");
$resMySel = odbc_exec($conn,$strSQL);
$row = odbc_fetch_array($resMySel);

$resMySel = odbc_exec($conn,$strSQL);

IF (odbc_fetch_array($resMySel)){
	// $row = odbc_fetch_array($resMySel);
 	If ( strlen($row["PW"]) < 3 OR Is_Null($row["PW"]) ) {
		echo '<div style="font-size:20px;">Hello ' . $row["First_Name"]. " " . $row["Last_Name"] ;?> 
		</div>
		<!--      <div style="clear:left; font-size:15px; padding:15px 0 15px 0;">Please use this page to create a 6 to 10 digit password using a combination of Upper & Lower characters with at least one number and optional special characters (!@#$%^&*). Your user id is your registered email address. <br />This will enable you to access your profile information, manage your online portfolio and upload your latest resume. </div>
		-->
		<div style="clear:left; font-size:20px; line-height:30px; padding:15px 0 15px 0;">Please use this page to create a 6 to 10 digit password using a combination of Upper & Lower characters with at least one number and optional special characters (!@#$%^&*). Your user id is your registered email address.</div>
			<form action="newtalent.php?sRegCode=Done"  method="post" onsubmit="return validatePasswords()">

			<input type='hidden' name='Contractor_ID' value='<?php echo $row["Contractor_ID"] ?>' />
		
		<div style="clear:left;padding-top:20px;">	
			<div style="float:left; width:100px;padding:0 10px 0 0; font-size:20px;">Password: </div>
			<div style="float:left; padding:0 10px 0 0; font-size:20px;">
				<input title="Please match the requested format." type="password" required pattern="(?=.*[A-Z][a-z].*\d)(^[a-zA-Z0-9!@#$%^&*]{6,10}$)" name="password1" size="30" onchange="
				  this.setCustomValidity(this.validity.patternMismatch ? this.title : '');">
			</div>
		</div>
		<div style="clear:left; ">
			<div style="float:left; width:100px; padding:20px 10px 0 0; font-size:20px;">Retype Password: </div>
			<div style="float:left; padding:20px 10px 0 0; font-size:15px;">
			  <input title="Please enter the same Password as above" type="password"  size="30" required pattern="(?=.*[A-Z][a-z].*\d)(^[a-zA-Z0-9!@#$%^&*]{6,10}$)" name="password2" onchange="
			  this.setCustomValidity(this.validity.patternMismatch ? this.title : '');">
			</div>
		</div>
		<div class="btn-submit" style="padding:25px 0 0 0px; clear:left; padding-top:25px;clear:left; float:left;">
<!--		<div style="padding-top:25px;clear:left; float:left; font-size:20px;" class="vg-submit"> -->
			<input type="submit" style="height:30px;" onsubmit="return validatePasswords() value="Login" />
		</div>
		</Form>
	
	<?php  

	} Else { ?>
			<script language="javascript" type="text/javascript">
         	 // redirect parent (manager.asp)
		window.parent.location="/talent-profile/";  
                </script>
	<?php 
	}
} Else {
	$xstrSQL = $xstrSQL . "UPDATE EmployeeMaster SET InternetPassword = '" . $_REQUEST["password1"] . "' ";
	$xstrSQL = $xstrSQL . "WHERE Status = 1 AND Employee_ID = '" . $_REQUEST["Contractor_ID"] . "'";
	$resMySel = odbc_exec($conn,$xstrSQL); 
	// echo $xstrSQL ; ?>
			<script language="javascript" type="text/javascript">
         	 // redirect parent (manager.asp)
		window.parent.location="/talent-profile/";  
                </script>
	
<?php }?>



