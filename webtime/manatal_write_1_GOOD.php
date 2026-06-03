<?php
$sScreen = Str_Replace("|","'",$_REQUEST["sScreen"]);
$eScreen = Str_Replace("|","'",$_REQUEST["eScreen"]);
$InvCount = $_REQUEST["invcount"];
$Contract = $_REQUEST["CONTRACT"];
$InvMethod = $_REQUEST["INVMETHOD"];
$sNextStep = $_REQUEST["snextstep"];
$StrWeekEnd = date('m/d/Y',StrToTime($_REQUEST["WKEND"]));
$REPORTTO = $_REQUEST["REPORTTO"];
$REPORTTOCC = $_REQUEST["REPORTTOCC"];
$Contractor_ID = $_REQUEST['Contractor_ID'];



$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());
// accomodate an apostrophy (')
	$Primary_Contact_Email = mysqli_real_escape_string($link,$_REQUEST['REPORTTO']);
	$Second_Contact_Email =  mysqli_real_escape_string($link,$_REQUEST['REPORTTOCC']);


$Unique_ID = $_REQUEST["MyNewRandomNum"];
	if ( empty($Unique_ID) ) {
	//	$Unique_ID = $_REQUEST["Unique_ID"];
	}
$PoNumber = str_replace(" ","-",$_REQUEST["PO"]);
$PoNumber = filter_var($PoNumber, FILTER_SANITIZE_STRING);

Function CleanTheString($theString) {
  //msgbox thestring 
      $strAlphaNumeric = " abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; // Used to check for numeric characters. 
	for ($i = 0; $i <= StrLen($theString)-1; $i++) {
		$strChar = SubStr($theString,$i,1) ;
		If ( StrPos($strAlphaNumeric,$strChar)) { 
              		$CleanedString = $CleanedString . $strChar ;
         	} 
	}
      // msgbox cleanedstring 
      $CleanTheString = str_replace(" ", "_", $CleanedString);
  return $CleanTheString;
} 

Function SendMyMail($subject, $message, $to1, $to2, $cc, $bcc, $efrom, $html) {

	require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/PHPMailer.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/Exception.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/SMTP.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/class-phpmailer.php");

	$mail             = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = "smtp.1and1.com"; // SMTP server
	// $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
	// 1 = errors and messages
	// 2 = messages only
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	// $mail->Host       = "smtpout.secureserver.net"; // sets the SMTP server
	$mail->Host       = "smtp.ionos.com"; // sets the SMTP server"; // sets the SMTP server
	$mail->Username   = "exchange@icreatives.comm"; // SMTP account username
	$mail->Password   = "Call1888icreate!";        // SMTP account password
	$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
	$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
// DKIM Setup
				$mail->DKIM_domain = 'icreatives.com';
				$mail->DKIM_selector = 'performa';
				$mail->DKIM_private = $_SERVER['DOCUMENT_ROOT'] . '/dkim_keys/ionos-icreatives-co-dkim-private-key.key'; // Replace with actual path
				$mail->DKIM_passphrase = ''; // If your key has a passphrase, enter it here
				$mail->DKIM_identity = 'exchange@icreatives.comm'; // Typically same as From
	$mail->setFrom($efrom, 'icreatives');
	$mail->addBcc("stevenc@icreatives.com");	

	$addresses = explode(',', $to1);
	foreach ($addresses as $address) {
    		$mail->AddAddress($address);
	}

	if (!empty($to2)) {
		$addresses = explode(',', $to2);
		foreach ($addresses as $address) {
    			$mail->AddAddress($address);
		}
	}
	if (!empty($cc)) {
		$addresses = explode(',', $cc);
		foreach ($addresses as $address) {
    			$mail->AddCC($address);
		}
	}

	if (!empty($bcc)) {
		$addresses = explode(',', $bcc);
		foreach ($addresses as $address) {
    			$mail->AddBCC($address);
		}
	}
	
	// $mail->From =  $efrom;
	$mail->Subject = $subject;
	if ( StrToUpper($html) == "P") {
		$mail->IsHTML(false); 
		} else {
		$mail->IsHTML(true); 
	}
	$mail->Body = "\r\n" . "\r\n" . $message. "\r\n" . "\r\n" ;

	if(!$mail->Send()) {
 		echo "Mailer Error: " . $mail->ErrorInfo;
	}
	
	Return True;
}

Function WhoToSend() {
global $sNextStep;
global $eScreen;
global $sScreen;
global $strEmpMAIL;
global $strCliMail;
global $PoNumber;
global $sFirst;
global $InvCount;
global $Contract;
global $ContactEmail;
global $sSubject;
global $StrWeekEnd;

$REPORTTO = $_REQUEST["REPORTTO"];
$REPORTTOCC = $_REQUEST["REPORTTOCC"];
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

$strSQLx = "SELECT Unique_ID, first_name, EmpEmail, Second_Contact_Email, Primary_Contact_Email, Employee_ID , AssignmentNumber, weekending, SentDate, Continuing, DeclineDate, ApproveDate FROM ic_timesheets wt ";
$strSQLx = $strSQLx . " WHERE NOT wt.void AND wt.Employee_ID = '" . $_REQUEST["Contractor_ID"] . "' ";
$strSQLx = $strSQLx . "  AND wt.AssignmentNumber = '" . $_REQUEST["PROJ"] ."' ";
$strSQLx = $strSQLx . "  AND wt.WeekEnding = '" . $_REQUEST["WKEND"] . "'";

$strSQLx;

$resMySel =  mysqli_query($link,$strSQLx);
$row = mysqli_fetch_array($resMySel) ;
$_REQUEST["EMP"];

$sTalentName = $row['first_name'];
	 // $sTalentName = SubStr( $_REQUEST["EMP"],StrPos($_REQUEST["EMP"],"_")-1 );
	 // list($sTalentName, $sTalentLast) = Explode(" ",$_REQUEST["EMP"]);
	// list($sTalentName, $TalentLast) = explode(" ",$_REQUEST["EMP"]);
	$pattern = "/[\/'\"{}\[\]90&;]/";  // Define the pattern to match the characters
	$sSubject =  "++ Timesheet for " . $sTalentName . " Week Ending " . $StrWeekEnd . " ++";
	
	if ($sNextStep == "Approve") {

		// send email to talent that timesheet was appproved
			$xScreen = $eScreen;
			$xScreen = Str_Replace("RRRRR",$row['Unique_ID'],$xScreen);
			$xScreen = Str_Replace("XXXX",$row['first_name']." ".$row['last_name'],$xScreen);
			$xScreen = Str_Replace("_"," ", $xScreen)  ; 
			
			$To1 = $row['EmpEmail'];				   
		// $To2 = "stevenc@icreatives.com";
		$sSubject =  "++ Timesheet APPROVED for " . $sTalentName . " Week Ending " . $StrWeekEnd . " ++";
		$eFrom = 'form_mail@icreatives.com';
		// objCDO.ReplyTo = "form_mail@icreatives.com" 10/10/19 SC this really messed it up why?
		$Bcc = "Time_Sheet_Approved@BlindEmail.com";	      
		$Message = "\r\n" . "\r\n". $xScreen;

		 SendMyMail($sSubject, $Message, $To1, $To2, "", $Bcc, $eFrom, "Y");
			
		// send another email customer
		// x$x = preg_replace("/-[^-]*$/\'", "", $item);
		$xScreen = $sScreen;
		$xScreen = Str_Replace("XXXX", preg_replace($pattern, " ", $sTalentName),$xScreen);
		$xScreen = Str_Replace("RRRRR",$row['Unique_ID'],$xScreen);
		$xScreen = Str_Replace("YYYY","<b style='color:#a4100c'>Customer: </b>" . Str_replace("_"," ",$_REQUEST["JOB"],) . "<BR>" . "\r\n",$xScreen);

		if (!empty($PoNumber)) {
	      	 		$xScreen = Str_Replace("ZZZZ","PO Number: " . preg_replace($pattern, "",$PoNumber) ,$xScreen,);
		} else {
	      	 		$xScreen = Str_Replace("ZZZZ","",$xScreen);
		} 
		//XXX
		$xScreen = Str_Replace("IPIP",$_SERVER['REMOTE_ADDR'],$xScreen);
		$xScreen = Str_Replace("AAAA",preg_replace($pattern, "",$_REQUEST["Signature"]),$xScreen);
		$xScreen = Str_Replace("QQQQ",$_REQUEST["ITEM"],$xScreen);
		$Message = "\r\n" . "\r\n". $xScreen;
		$To1 = $row["Primary_Contact_Email"];	
		$CC =  $row["Second_Contact_Email"];	
		$BCC =  "Time_Sheet_Approved@BlindEmail.com";
		$sSubject =  "++ Timesheet APPROVED for " . $sTalentName . " Week Ending " . $StrWeekEnd . " ++";
		$eFrom =  "form_mail@icreatives.com";
		$Message =  $xScreen;

		SendMyMail($sSubject, $Message, $To1, "", $CC, $BCC, $eFrom, "Y");

		// send a copy to accounting and blindemail archive if there is a comment
		
		$pattern = "/[\/'\"{}\[\]90&;]/";  // Define the pattern to match the characters
		
		if (!empty(trim($_REQUEST["comments"])) ) {
			
				$xScreen = $sScreen;
				$xScreen = "\r\n" . "\r\n" . "<B>TIMESHEET APPROVED COMMENT</B><P>" . "\r\n" . "\r\n" . $sScreen;
      				$xScreen = Str_Replace("_"," ",Str_Replace("XXXX",$sTalentName,$xScreen,));
			   	$xScreen = Str_Replace("ZZZZ",$PoNumber,$xScreen);
				$xScreen = Str_Replace("RRRRR",$row['Unique_ID'],$xScreen);
				$xScreen = Str_Replace("QQQQ",$row['ITEM'],$xScreen);
		     		$xScreen = $xScreen. "\r\n" . " ip: " . $_SERVER['REMOTE_ADDR'] . "-" . date('h-i-s');
				$To1 = "Contact_Form@icreatives.com";
				$To2 = "Time_Sheet_Approved@BlindEmail.com";
				$sSubject =  "++ Timesheet COMMENTS for " . $sTalentName . " Week Ending " . $StrWeekEnd . " ++";
				$eFrom =  "Time_Sheet_Approved@icreatives.com";
				$Message = "\r\n" . "\r\n" . $_REQUEST["comments"]. "\r\n" . "\r\n"  . $xScreen . "\r\n" . "\r\n" ;
				SendMyMail($sSubject, $Message, $To1, $To2,"","", $eFrom, "Y");
		}
	}
	if ($sNextStep == "Decline") {
		// $xScreen = $sScreen;
		$sSubject =  "++ Timesheet DECLINED for " . $sTalentName . " Week Ending " . $StrWeekEnd . " ++";
		$xScreen = "\r\n" . "\r\n" . "TIMESHEET DECLINED" . "\r\n" . "\r\n";
		$xScreen = "\r\n" . "\r\n" . "Reason: ". $_REQUEST["comments"] . "\r\n" . "\r\n";
		$xScreen = $xScreen . "Contractor: " . $sTalentName . " "  . $strEmpMAIL . " " . "\r\n" . "\r\n" ;
		$xScreen = $xScreen . "From: " . $_REQUEST["SuperEmail"] . " " . $strEmpMAIL . " " . "\r\n" . "\r\n" ;
		$xScreen = $xScreen . "Reason: " . preg_replace($pattern, " ",$_REQUEST["comments"]);
		
		// fix it here!!!
		
		$xScreen = "\r\n" . "\r\n" . "<B>TIMESHEET DECLINED</B><P>" . "\r\n" . "\r\n" ;
		$xScreen = $xScreen . "Contractor: " . $sTalentName . " "  . $strEmpMAIL . " " . "\r\n" . "\r\n" ;
		$xScreen = $xScreen . "<br>From: " . $_REQUEST["SuperEmail"] . " " . $strEmpMAIL . " " . "\r\n" . "\r\n" ;
		$xScreen = $xScreen . "\r\n" . "\r\n" . "<br>Reason: ". $_REQUEST["comments"] . "\r\n" . "\r\n". $sScreen;
		$xScreen = Str_Replace("_"," ",Str_Replace("XXXX",$sTalentName,$xScreen,));
		$xScreen = Str_Replace("YYYY","<b style='color:#a4100c'>Customer: </b>" . Str_replace("_"," ",$_REQUEST["JOB"],) . "<BR>" . "\r\n",$xScreen);
		$xScreen = Str_Replace("APPROVED","DECLINED",$xScreen,);
		$xScreen = Str_Replace("ZZZZ",$PoNumber,$xScreen);
		$xScreen = Str_Replace("IPIP",$_SERVER['REMOTE_ADDR'],$xScreen);
		$xScreen = Str_Replace("AAAA",$_REQUEST["Signature"],$xScreen);
		$xScreen = Str_Replace("RRRRR",$row['Unique_ID'],$xScreen);
		
		$xScreen = $xScreen. "\r\n" . " ip: " . $_SERVER['REMOTE_ADDR'] . "-" . date('h-i-s');

		$To1 = "Contact_Form@icreatives.com";
		$To2 = "Time_Sheet_DECLINED@blindemail.com";	
			
		$eFrom =  "Form_Mail@icreatives.com";

		SendMyMail($sSubject, $xScreen, $To1, $To2, "", "", $eFrom, "Y");
		
	}
 	// echo $strCliMail. $sNextStep. "XXX HEY ARE YOU THERE? XXX";
	if ($sNextStep == "Send") {
		// Send  Talent's timesheet to customer
		
		
		// echo $REPORTTO. " " . $REPORTTOCC;
		
		
		$To1 = $strCliMail;
			IF ( !empty($REPORTTO)) {
				$To1 = $REPORTTO;			
			} 
			IF ( !empty($REPORTTOCC)) {
				$To2 = $REPORTTOCC ;				
			}
			$BCC = "Time_Sheet_Approval@BlindEmail.com,steven@cohen.email";
			$sSubject =  "++ Timesheet for " . $sTalentName . " Week Ending " . $StrWeekEnd . " ++";

		$eFrom = "form_mail@icreatives.com";

		// plaintext = P otherwise HTML
		if ( StrToUpper(SubStr($InvMethod,1))  == "P") { 
				$sScreen = "\r\n" . "\r\n" . $sScreen;
				$HTML = "P";
		} Else {
				$HTML = "";
		}
		$sScreen = Str_Replace("RRRRR",$row['Unique_ID'],$sScreen);
		
		SendMyMail($sSubject, $sScreen, $To1, $To2, "", $BCC, $eFrom, $HTML);

		// if first assignment or is not continuing, send recruiters a notification, and report-to a survey.
		if ( $_REQUEST["Continuing"] == 0 || $sFirst == "Yes" ) {
			$xBody = "An integral part of our Program for Employee Relations and Customer Service (PERCS) is the Assignment Merit Evaluation. This evaluation is used to monitor the performance of our talent on assignment for you. In addition, this evaluation is used to consider talent for compensation increases, training and targeting areas for improvement. Please take a moment to fill out this evaluation. Your candor and honesty will provide us with the continuing guidance that we need to increase the level and quality of service that our company offers. Many thanks for your time and help.";
			$xBody = $xBody . "<P> <P>Please click on this link to evaluate " . $sTalentName . " <A href='https://www.icreatives.com/percs/?varib=" . $_REQUEST["MyNewRandomNum"] . "'>https://www.icreatives.com/percs/?varib=" . $_REQUEST["MyNewRandomNum"] . "<BR>";

			$To1 = $strCliMail;
			IF ( !empty($REPORTTO)) {
				$To1 = $REPORTTO;			
			} 
			IF ( !empty($REPORTTOCC)) {
				$To2 = $REPORTTOCC ;				
			}

			IF ( $_REQUEST["Continuing"] == 0) { // Warn us that assignment is over.
					$BCC = "Talent_Evaluation@BlindEmail.com" ;
					$CC = "contact_form@icreatives.com" ;
			}
			$sSubject =  "++ Talent Evaluation for " . $sTalentName . " ++"	;		
			$eFrom = "form_mail@icreatives.com";
			if ( StrToUpper(SubStr($InvMethod ,1)) == "P" ) { 
					$HTML = "P";
			} else {
				 	$HTML = "";
			}
			
			SendMyMail($sSubject, $xBody, $To1, $To2, $CC, $BCC, $eFrom, $HTML);	
		}		
	}
	Return True;	
}
// date_add($date,date_interval_create_from_date_string("30 days"));
/*
Function Weekend($i) {

	$tsDay = date("Y-m-d", strtotime("+".$i." days"));
	$tsDayOfWeek = date('N', strtotime($tsDay));

	IF ($tsDayOfWeek < 0 ) {
		$Weekend = date("Y-m-d", strtotime($tsDay. "+".(6- $tsDayOfWeek) +7 ." days" ));
	} ELSE {
		$Weekend = date("Y-m-d", strtotime($tsDay. "+".(6- $tsDayOfWeek)." days"));
	}

	return $Weekend;
}
*/

function GetPayDate($entryDate) {
    $entryDay = date('N', strtotime($entryDate)); // 1 = Monday, ... 7 = Sunday

    // Determine base Friday (week-ending)
    if ($entryDay <= 1) {
        // Friday (previous week), Saturday, Sunday, or Monday
        $baseFriday = strtotime("last friday", strtotime($entryDate) + 86400);
    } else {
        // Tuesday or later → next Friday
        $baseFriday = strtotime("next friday", strtotime($entryDate));
    }

    // Add 2 weeks to that base Friday
    $payDate = strtotime("+2 weeks", $baseFriday);

    return date('Y-m-d', $payDate);
}


Function AmPm2InHrs($sDay) {
	If ( $_REQUEST["TimeInAmPm" . $sDay] == "PM" && $_REQUEST["TimeInHr" . $sDay] > 0 && $_REQUEST["TimeInHr" . $sDay] < 12 ) {
		$AmPm2InHrs = Round(12 + $_REQUEST["TimeInHr" . $sDay] + ($_REQUEST["TimeInMin" . $sDay])/60,2) ;		
	} ElseIf ($_REQUEST["TimeInAmPm" . $sDay] == "AM" && $_REQUEST["TimeInHr" . $sDay] > 0 && $_REQUEST["TimeInHr" . $sDay] == 12 ) {
		$AmPm2InHrs = Round( ($_REQUEST["TimeInHr" . $sDay]-12) + ($_REQUEST["TimeInMin" . $sDay]/60),2) ;		
	} Else {
		$AmPm2InHrs = Round($_REQUEST["TimeInHr" . $sDay] + ($_REQUEST["TimeInMin" . $sDay]/60),2) ;
	}
	return 	$AmPm2InHrs;
}	

Function AmPm2OutHrs($sDay) {
	If ( $_REQUEST["TimeOutAmPm" . $sDay] == "PM" && $_REQUEST["TimeOutHr" . $sDay] <> 0 && $_REQUEST["TimeOutHr" . $sDay] < 12 ) {
		$AmPm2OutHrs = Round(12 + $_REQUEST["TimeOutHr" . $sDay] + ($_REQUEST["TimeOutMin" . $sDay]/60),2) ;		
	} Else {
		$AmPm2OutHrs = Round($_REQUEST["TimeOutHr" . $sDay] + ($_REQUEST["TimeOutMin" . $sDay]/60),2) ;
	}
	return 	$AmPm2OutHrs;
}	

Function BreakHrs($sDay) {
		if ( IS_NULL($_REQUEST["BreakHr" . $sDay]) ) {
			$BreakHrs = Round($_REQUEST["BreakMin" . $sDay]/60,2) ;	
		} else {
			$BreakHrs = Round($_REQUEST["BreakHr" . $sDay] + ($_REQUEST["BreakMin" . $sDay]/60),2) ;	
		}
		return $BreakHrs;
}	

Function TotalHrs($i)  {
	$Day_Total = (AmPm2OutHrs($i) - AmPm2InHrs($i) - BreakHrs($i));
	return $Day_Total ;
	}

Function xTotalHrs($i)  {

	$sInHours = ($_REQUEST["TimeInHr" . $i]) ;
	if ( $_REQUEST["TimeInAmPm" . $i] == "PM" && $sInHours < 12 ) {
		$sInHours = $sInHours + 12;   // 12am
	}
	if ( $_REQUEST["TimeInAmPm" . $i] == "AM" && $sInHours == 12 ) {
		$sInHours = 0;
	}

	$sInMinutes = ($_REQUEST["TimeInMin" . $i]/60);
	$sInAmPm =$_REQUEST["TimeInAmPm" . $i] ;
	$sOutHours = ($_REQUEST["TimeOutHr" . $i]) ;

	if ( $_REQUEST["TimeOutAmPm" . $i] == "PM" && $_REQUEST["TimeOutHr" . $i] > 0 && $_REQUEST["TimeOutHr" . $i] < 12 ) {
		$sOutHours = $sOutHours + 12;
	}
	if ( $_REQUEST["TimeinAmPm" . $i] == "PM" && $_REQUEST["TimeintHr" . $i] > 0 && $_REQUEST["TimeinHr" . $i] < 12 ) {
		$sInHours = $sInHours + 12;
	}

	$sOutMinutes = Round($_REQUEST["TimeOutMin" . $i]/60,2);
	$sOutAmPm = $_REQUEST["TimeOutAmPm" . $i];
	$sBreakHr = $_REQUEST["BreakHr" . $i];
	$sBreakMin = Round($_REQUEST["BreakMin" . $i]/60,2);

	// Calculate Total

	$TotalHrs = ( ($sOutMinutes + $sOutHours) - ($sInMinutes + $sInHours) ) - ($sBreakHr + $sBreakMin);
	Return $TotalHrs;
}

Function GrandTotal() {
	$lsGt = 0;
	for ($i = 1; $i <= 7; $i++) {
		$lsGT = $lsGT + TotalHrs($i);
	}
	$GrandTotal = round($lsGT,2);

	$GrandTotal = number_format($GrandTotal, 2, '.', '');

	return $GrandTotal;
}

?>

<?php

// -- 	Build our SQL Statement to see if first timesheet of an assignment to send a PERC assessment
$strSQLz = "SELECT AssignmentNumber FROM ic_timesheets WHERE void = 0 AND AssignmentNumber = '" . $_REQUEST["PROJ"] . "' and Employee_ID = '". $Contractor_ID ."' AND SentDate IS NOT NULL ";

// echo $strSQLz;

$resMySel =  mysqli_query($link,$strSQLz);
$row = mysqli_fetch_array($resMySel) ;

if ( $row  ) {
		$sFirst = "No";
} else {
		// fix this later xxx $sFirst = "Yes";

		$sFirst = "No";
}
// echo $sFirst;
$EmployeeIpAddr = $_SERVER['REMOTE_ADDR'];	
$CustomerIpAddr = $_SERVER['REMOTE_ADDR'];	
 
	// -- Build our SQL Statement to see if it has been saved

$strSQLx = "SELECT Unique_ID, Employee_ID , AssignmentNumber, weekending, SentDate, Continuing, ApproveDate, DeclineDate FROM ic_timesheets wt ";
$strSQLx = $strSQLx . " WHERE wt.void = 0 AND wt.Employee_ID = '" . $_REQUEST["Contractor_ID"] . "' ";
$strSQLx = $strSQLx . "  AND wt.AssignmentNumber = '" . $_REQUEST["PROJ"] ."' ";
$strSQLx = $strSQLx . "  AND wt.WeekEnding = '" . $_REQUEST["WKEND"] . "'";

// echo $strSQLx;

$resMySel =  mysqli_query($link,$strSQLx);
$row = mysqli_fetch_array($resMySel) ;


// if ( !IS_NULL($row["SentDate"]) && IS_NULL($row["DeclineDate"]) && ($sNextStep == "Send" || $sNextStep == "Save") ) {

if ( $row["SentDate"] !== "0000-00-00 00:00:00" && $row["DeclineDate"] == "0000-00-00 00:00:00" && ($sNextStep == "Send" || $sNextStep == "Save") ) {
	$sSkip = "Yes";
} else {
	$sSkip = "No";
}

If ( $sSkip == "No" ) {   // was it saved before?
	If ( IS_NULL($row["AssignmentNumber"]) ) {  
		$sExists = "No";
	} else {
		// $Unique_ID = $row["Unique_ID"];
	}

	// ContactEmail ' Order Taken Contact Key or Order Taken ID
	// ContactEmailCC ' Supervisor or Start Contact Key ID


	$ALTETIME = $_REQUEST["ALTETIME"];
	$AcctEmail = $_REQUEST["AcctEmail"];

	// First see there is a 

// Primary_Contact_Email as P_EMAIL, Primary_Contact_First as P_FIRST, Primary_Contact_Last as P_LAST, 
// 	Second_Contact_Email as S_EMAIL, Second_Contact_First as S_FIRST, Second_Contact_Last as S_LAST
// xxx
	
	$strSQL = "SELECT organization as JOBID, company_name as JOB, candidate as Contractor_ID, company_name as JOB, candidate_name, bill_rate as BILLRATE, Pay_rate as PAYRATE, candidate_email as R_EMAIL 
	FROM  ic_matches ";
	$strSQL = $strSQL . "WHERE candidate = '" . $_REQUEST["Contractor_ID"] . "' AND job = '". $_REQUEST["PROJ"]. "' ";
	$resMySel = mysqli_query($link,$strSQL);
	$row = mysqli_fetch_array($resMySel);
	
	// echo $strSQL;

	$ContactEmail = $row['P_EMAIL'];
	// echo "ContactEmail = " .$ContactEmail . "<br>";
	$ContactName = $row["P_FIRST"] . " " . $row["P_LAST"];
	$strEmpMAIL = $row['R_EMAIL'];
	 		$ContactEmailCC = $row["S_EMAIL"];
	 		$ContactNameCC = $row["S_FIRST"] . " " . $row["S_LAST"];
	 		$ContactEmail = $ContactEmail . ", " . $ContactEmailCC;
			list($first_name,$last_name) = explode(" ",$row['candidate_name']);

	if ( $sExists == "No" ) {	
		$strSQL = "INSERT INTO ic_timesheets " ;
   		$strSQL = $strSQL . " ( 
		company_name,
		company_id,
		billrate, 
		payrate, 
		Employee_ID, 
		Email, 
		first_name, 
		Last_name, 
		title,
		Primary_Contact_Email, 
		Second_Contact_Email, 
		BillingProfile, 
		billing_cycle, 
		Hours, 
		AssignmentNumber, 
		EmployeeIpAddr, 
		WeekEnding, 
		Continuing, 
		Unique_ID, 
		Branch_ID,
		SuperEmail,
		Assignment_ID, 
		Reminders, 
		EmpEmail, 
		AcctEmail,
		invoice_type" ;
		
 
  		if ($sNextStep == "Send" ){ // if TALENT clicked approve and send in Asn_Send.asp
			$strSQL = $strSQL . " , SentDate" ;  
		}
		if ($sNextStep == "Approve") { // if CUSTOMER clicked approve and send in Asn_Send.asp
			$strSQL = $strSQL . " , ApproveDate" ;  
		}
		if ($sNextStep == "Decline") { // if CUSTOMER clicked decline and send in Asn_Send.asp
			$strSQL = $strSQL . " , DeclineDate" ;  
		}

     		
		for ($i = 1; $i <= 7; $i++) {
        		$strSQL = $strSQL . ", TimeInHr" . $i . ", " ;       
        		$strSQL = $strSQL . "TimeOutHr" . $i . ", " ;
			$strSQL = $strSQL . "Break" . $i . " " ;
		}

		$strSQL = $strSQL . ") VALUES  ('".
		addslashes($row["JOB"]). "', '". 
		$row['JOBID']. "', '".
		$row["BILLRATE"]."', '".
		$row["PAYRATE"] ."', '" . 
		$_REQUEST["Contractor_ID"] . "', '". 
		$_REQUEST['EMAIL'] . "', '"  . 
		addslashes($first_name). "', '"  .
		addslashes($last_name) . "', '"  .
		$_REQUEST['ITEM']. "', '$Primary_Contact_Email', '$Second_Contact_Email', '"  . 
		$_REQUEST["BILLINGPROFILE"] . "', '"  . 
		$_REQUEST["billingcycle"] . "', " . 
		GrandTotal() . ", '" . 
		$_REQUEST["PROJ"] . "', '" . 
		$EmployeeIpAddr . "', '" . 
		$_REQUEST["WKEND"] . "', " ;
		$strSQL = $strSQL . 
		$_REQUEST["Continuing"] . ", '" . 
		$Unique_ID  . "', '" . 
		$_REQUEST["BRANCH"] . "', '" . 
		$strCliMail . "', '" . 
		$_REQUEST["xORDER"] . "', 
		0, '" . 
		$strEmpMAIL . "', '" . 
		$AcctEmail . "','t'" ;

   		if ( $sNextStep == "Send" ) {
	   		// $strSQL = $strSQL . ", '" . date('Y-m-d') . "' "   ;
			$strSQL = $strSQL . ", Now()"   ;
		}

   		for ($i = 1; $i <= 7; $i++) {
        		if ( empty( $_REQUEST["TimeInHr" . $i]) ) { 
        			$strSQL = $strSQL . ", 00, " ;
        		} Else {
        			$strSQL = $strSQL . ", " . AmPm2InHrs($i) . ", " ;
        		}
        		if ( empty($_REQUEST["TimeOutHr" . $i]) ) { 
        			$strSQL = $strSQL . " 00, " ;
        		} Else {
        			$strSQL = $strSQL . AmPm2OutHrs($i) . ", " ;
        		}
			         $strSQL = $strSQL . BreakHrs($i) . " ";
    		}
		$strSQL = $strSQL . ") " ;

		$resMySel = mysqli_query($link,$strSQL);

	/* not in tracker
		If ( $_REQUEST["Continuing"] == 1 ) {
			$strSQL = $strSQL . "UPDATE OrderAssignment SET End_Estimate_Date = '" . date('Y-m-d', strtotime($_REQUEST["WKEND"].'+7 days')) . "' " ;
			$strSQL = $strSQL . "WHERE AssignmentNumber = " .  $_REQUEST["PROJ"] .  " AND End_Estimate_Date <= '" . $_REQUEST["WKEND"] . "' " ;
			$strSQL = $strSQL . "UPDATE OrderMaster SET End_Estimate_Date = '" . date("Y-m-d", strtotime($_REQUEST["WKEND"]."+7 days")) . "' " ;
			$strSQL = $strSQL . "WHERE Order_ID = '" .  $_REQUEST["xORDER"] . "'  AND End_Estimate_Date <= '" . $_REQUEST["WKEND"] . "' " ;
		}
	
		$strSQL = $strSQL . "UPDATE OrderAssignment SET PurchaseOrder = '" . $PoNumber . "' " ;
		$strSQL = $strSQL . "WHERE AssignmentNumber = " .  $_REQUEST["PROJ"] . " " ;
		// echo $strSQL;
		$resMySel = odbc_exec($conn,$strSQL);
	*/

 
	} ELSE {

		$sExists = "Yes"; // so we are updating

		$strSQL = "UPDATE ic_timesheets Set" ; 

		if ( $sNextStep <> "Approve" && $sNextStep <> "Decline") { // for talent submit or save
			// $strSQL = $strSQL . " BillingProfile = '" . $_REQUEST["BILLINGPROFILE"] . "', ";
			$strSQL = $strSQL . " Unique_ID = '" . $Unique_ID . "', "  ;
			$strSQL = $strSQL . " billrate = " . $row["BILLRATE"] . ", ";
			$strSQL = $strSQL . " payrate = " . $row["PAYRATE"] . ", ";
			// $strSQL = $strSQL . " Branch_ID = '" . $_REQUEST["BRANCH"] . "', "  ;
			// $strSQL = $strSQL . " AcctEmail = '" . $_REQUEST["AcctEmail"] . "', "  ;
			
			if ( $sNextStep == "Send" ) {  // if clicked approve and send in Asn_Send.asp
	   			// $strSQL = $strSQL . " SentDate = '" . DATE('Y-m-d') . "', " ;  
				$strSQL = $strSQL . " SentDate = NOW()," ;  
				$strSQL = $strSQL . " DeclineDate = NULL, "   ;	 // to stop editing after declined and resaved  
  	 		}
	
			$strSQL = $strSQL . " Hours = " . GrandTotal() . ", ";
	//     		$strSQL = $strSQL . " AssignmentNumber = '" . $_REQUEST["PROJ"] . "', ";
			$strSQL = $strSQL . " EmployeeIpAddr = '" . $EmployeeIpAddr . "', ";
			$strSQL = $strSQL . " Continuing = " . $_REQUEST["Continuing"] . " ";
	// 		$strSQL = $strSQL . " WeekEnding  = '" $StrWeekEnd . "' ";
			for ($i = 1; $i <= 7; $i++) {
				$strSQL = $strSQL . ", TimeInHr" . $i . " = "  . AmPm2InHrs($i);
			        $strSQL = $strSQL . ", TimeOutHr" . $i . " = "  . AmPm2OutHrs($i);
    			    	$strSQL = $strSQL . ", Break" . $i . " = "  . BreakHrs($i);
 			}
	   		$strSQL = $strSQL . " WHERE Employee_ID = '" . $_REQUEST["Contractor_ID"] . "' ";
			$strSQL = $strSQL . "  AND AssignmentNumber = '" . $_REQUEST["PROJ"] . "' ";
			$strSQL = $strSQL . "  AND WeekEnding = '" . $_REQUEST["WKEND"] . "' ";
			// echo $strSQL;
			
			// $strSQL = $strSQL . "AND  Unique_ID = '" .  $Unique_ID . "' ";
			/* not now in tracker
			If ( $_REQUEST["Continuing"] == 1 ) {
				$strSQL = $strSQL . "UPDATE OrderAssignment SET End_Estimate_Date = '" . date("Y-m-d", strtotime($_REQUEST["WKEND"]."+7 days")) . "' ";
				$strSQL = $strSQL . "WHERE AssignmentNumber = '" .  $_REQUEST["PROJ"] .  "' AND End_Estimate_Date <= '" . $_REQUEST["WKEND"] . "' ";
				$strSQL = $strSQL . "UPDATE OrderMaster SET End_Estimate_Date = '" . date("Y-m-d", strtotime($_REQUEST["WKEND"]."+7 days")) . "' ";
				$strSQL = $strSQL . "WHERE Order_ID = '" .  $_REQUEST["xORDER"] . "' ";
				
				// End_Estimate_Date <= '" . $_REQUEST["WKEND"] . "' ";
			}
			*/
			/*
			$strSQL = $strSQL . "UPDATE OrderAssignment SET PurchaseOrder = '" . $PoNumber . "' " ;
			$strSQL = $strSQL . "WHERE AssignmentNumber = '" .  $_REQUEST["PROJ"] . "' ";
			*/

		} ELSE {
			if ($sNextStep == "Approve" ) {
   		   		$strSQL = $strSQL . " ApproveDate = NOW(), "   ;
  	 			$strSQL = $strSQL . " Signature = '" . CleanTheString($_REQUEST["Signature"]) . "', ";
				$strSQL = $strSQL . " billrate = " . $row["BILLRATE"] . ", ";
				$strSQL = $strSQL . " payrate = " . $row["PAYRATE"] . ", ";

  	 		} else {
		   		$strSQL = $strSQL . " DeclineDate = NOW() , "   ;   	   
 	 		}
   			$strSQL = $strSQL . " CustomerIpAddr = '" . $CustomerIpAddr . "' ";
			$strSQL = $strSQL . " WHERE Employee_ID = '" . $_REQUEST["Contractor_ID"] . "' ";
			$strSQL = $strSQL . "  AND AssignmentNumber = '" . $_REQUEST["PROJ"] . "' ";
			$strSQL = $strSQL . "  AND WeekEnding = '" . $_REQUEST["WKEND"] . "' ";
			/* not yet for tracker
			$strSQL = $strSQL . "UPDATE OrderAssignment SET PurchaseOrder = '" . $PoNumber . "' ";
			$strSQL = $strSQL . "WHERE AssignmentNumber = " .  $_REQUEST["PROJ"] . " ";
			$strSQL = $strSQL . " AND PurchaseOrder <> '" . $PoNumber . "' ";
			*/
		}
			// echo $strSQL;			
			$resMySel = mysqli_query($link,$strSQL);

   	}

	?>
	<div style="clear:left; padding:20px 0 0 0;">
      	<div style="float:left; padding: 20px 0 20px 10px;  font-family: Arial, Helvetica, sans-serif;">
	<?php
	WhoToSend() ;
	// echo "random # " . $Unique_ID;
	IF ( $sNextStep == "Send" ) { 
	// date("m/d/Y", strtotime(Weekend(14))
	$entryDate = date("Y-m-d");
	echo '
		<p style=" font-family: Arial, Helvetica, sans-serif;" >Thank you for letting us represent you:</p><br /><br />

		<Center>
		If your timesheet is approved before noon Monday, your check for this week&apos;s hours <br /><br />will arrive by ' . GetPayDate($entryDate) . '.
		</Center>';

		// if ( $invcount < 1 && StrToUpper(SubStr($Contract,1)) <> "Y") { // No LOnger needed 
		if ( $invcount < 1 && StrToUpper(SubStr($Contract,1)) == "_") { // No LOnger needed
			echo '
			<br /><br />

			Please click on the button below to generate and print a timesheet to be signed and dated by your supervisor. Confirm your supervisor&apos;s name is printed clearly.
			<br />

				<form method="post" action="generatetimesheet.php" name="gentime" target = "_top"> ' ;
				$gScreen = Str_Replace("'","|",$_REQUEST["sScreen"]);
				

				echo ("<input type='hidden' name='gScreen' value='" . $gScreen . "'>");	
				echo '<br /><br /><br /><br />
					<input type = "image" src="/webtime/images/generatetimesheet.jpg" style="height:auto; background-color:#FFF; border:none; padding-left:0px; padding-top:12px;" value="submit" name = "gentime" target="_top">

				</form> ';

		}
	}
	IF ( $sNextStep == "Decline" ) {
			echo '
			<HTML>
			<BODY>
			<div class="container" style="float: right; width:100%; padding-left:20px; padding-bottom:500px text-align:center;  font-family: Arial, Helvetica, sans-serif;">
   				<div style ="float:left; width:100%;  padding-right:10px; text-align:center; ">
					<H1 style = "font-family: Arial, Helvetica, sans-serif;" > Timesheet Declined:<br /><br />
					A representative will be 
					contacting you shortly.</H1>
   				</div>
			</div>
			</BODY>
			</HTML>';
		}

	IF ( $sNextStep == "Save" ) {	
           		echo' <p>Thank you for letting us represent you:</p>
			<Center>
			<H1 style=" font-family: Arial, Helvetica, sans-serif;"> Your Timesheet has been saved</H1>
			</Center> ';

	}
	echo '</div></div>';
	// ECHO $row['DeclineDate'];
	// exit();

	IF ( $sNextStep == "Approve" && $row['ApproveDate'] = "0000-00-00 00:00:00") { 
			include "manatal_write_2.php"	;	
	}
	
	IF ( $sNextStep == "Approve" && $row['ApproveDate'] !== "0000-00-00 00:00:00") { 
			
			echo' <Center><p><h3>Timesheet has already been processed:<h3></p>
			</Center> ';
	}

	} Else {

	echo 'Timesheet already sent' ;
	
} ?>
