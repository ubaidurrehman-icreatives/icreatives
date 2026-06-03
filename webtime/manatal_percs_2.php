<html>
<head>


<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
<meta name="viewport" content="width=device-width, initial-scale=1">

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

<style>
 body{margin:0px; padding:0px; vertical-align:top; font-family:Arial, Verdana, Geneva, sans-serif;}
</style>
  <!-- Bootstrap CSS, font-awesome custom CSS -->
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_red.css">
  <link rel="stylesheet" href="/portal/styles.css">
  

<link href="/webtime/css/style.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Lato|Rokkitt' rel='stylesheet' type='text/css' />
</head>
<body onload="top.scrollTo(0,0)" bgcolor="#ffffff">
<a name="top">&nbsp;</a>
<div class="container-fluid" style="padding-left:0; padding-right:0; padding-bottom:500px; margin:0 auto;">



<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
$sScreen = "";
foreach ($_POST as $key => $value) {
	$sScreen = $sScreen . $key . " : "  . filter_var($value,FILTER_SANITIZE_STRING) . "\r\n"."\r\n";
}


$sScreen = "\r\n" . "\r\n" . "JOB ORDER from $ts" . "\r\n" . "\r\n" ."\r\n" . "\r\n". 
"Job Order: https://app.manatal.com/jobs/". $_REQUEST['ORDERID'] . "\r\n" . "\r\n" . $sScreen;

$sScreen = "\r\n" . "\r\n" . "Talent from $ts" . "\r\n" . "\r\n" ."\r\n" . "\r\n". 
"Candidate: https://app.manatal.com/candidates/". $_REQUEST['EMPID'] . "\r\n" . "\r\n" . $sScreen;


require_once dirname(__DIR__) . '/PHPMailer/PHPMailer.php';
require_once dirname(__DIR__) . '/PHPMailer/Exception.php';
require_once dirname(__DIR__) . '/PHPMailer/SMTP.php';

/*
	$mail             = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = "smtp.1and1.com"; // SMTP server
	// $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
	// 1 = errors and messages
	// 2 = messages only
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	// $mail->Host       = "smtpout.secureserver.net"; // sets the SMTP server
	$mail->Host       = "smtp.ionos.com"; // sets the SMTP server"; // sets the SMTP server
	$mail->Username   = "exchange@icreatives.co"; // SMTP account username
	$mail->Password   = "Call1888icreate!";        // SMTP account password
	$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
	$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
	$mail->FromName   = "icreatives";
	
	*/
				$mail             = new PHPMailer();
				$mail->IsSMTP(); // telling the class to use SMTP
				// $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
				// 1 = errors and messages
				// 2 = messages only
				$mail->SMTPAuth   = true;                  // enable SMTP authentication
				// $mail->Host       = "smtp.1and1.com"; // sets the SMTP server
				$mail->Host       = 'smtp.office365.com';
				$mail->Username   = "exchange@icreatives.com"; // SMTP account username
				$mail->Password   = "Call1888icreate!";        // SMTP account password
				$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
				$mail->Port       = 587;  // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above		
				$mail->isHTML(true);                             // Set email format to HTML
				$mail->CharSet = "UTF-8";

	$mail->AddAddress("contact_form@icreatives.com","");
	$mail->AddCC("stevenc@icreatives.com","");
	$mail->AddBCC("PERC@blindemail.com","");
		$mail->FromName   = "icreatives";
	$mail->From =  "contact_form@icreatives.com";
	$mail->Subject  =   "++ PERCS Form ++";
	// if ( StrToUpper($html) == "P") {
		$mail->IsHTML(false); 
	// 	} else {
	//	$mail->IsHTML(true); 
	// } 
	$mail->Body = $sScreen ;

	if(!$mail->Send()) {
 		echo "Mailer Error: " . $mail->ErrorInfo;
	}

//compute average score by dividing by 7
$sScore = ( $_REQUEST["Quality"] + $_REQUEST["Dependability"] + $_REQUEST["Attendance"] + $_REQUEST["Teamwork"] + $_REQUEST["Quantity"] + $_REQUEST["Initiative"] + $_REQUEST["Personality"] ) / 7;

// don't forget to update PERC_AssignmentMerit.Date_Sent

// Employee_ID = request.QueryString("Employee_ID")	

$sSkip = True ;// so that we can add Movie Ticket later w2e will keep the email code

require_once __DIR__ . '/../db/db.php';
$link = db();   
// change cutomer rating someshere in the future based on score

// $strSQL = "SELECT MAX(Merit_ID) as MAXMERITID FROM PERC_AssignmentMerit";


$StrSQL = "UPDATE ic_timesheets SET PERCdate = NOW()  WHERE Unique_ID = '" . $_REQUEST["Unique_ID"] ."' ";
		$result = mysqli_query($link,$StrSQL);



IF (!$sSkip) { // use this for movie tickets later
/*
		' & "If you do not receive an approved notification by " _
		' & "Monday morning, please download and print our 'Un-Green' PDF fax timesheet and have it signed and fax."& vbCrlf & vbCrlf _



		sBody = "Dear " & rsOrder("First") & "," & vbCrlf & vbCrlf _
		& "You have received this email because we want you to join us in our continued greening effort. " & vbCrlf & vbCrlf _
		& "We are very conscious about our environmental footprint. We recycle almost all our waste, " _
		& "our company cars are hybrids... so printing out timesheets for faxing seems to be morally wrong. "  & vbCrlf & vbCrlf _
		& "The link below will enable you to record and save your time on projects on a daily basis. " & vbCrlf & vbCrlf _
		& "On Fridays, or when the assignment is over, please click on the 'Approve & Send' button. "_
		& "This will send an email out for our customer's approval. " & vbCrlf & vbCrlf _
		& "Once the customer approves, you will receive a notification for your records. You may log in at any time to see the status of you timesheets. "  & vbCrlf & vbCrlf _

		& "Thank you for letting us represent you." & vbCrlf & vbCrlf _	
		
		& "https://www.icreatives.com/webtime/NewTalentAcct.asp?sRegCode="& rsOrder("RegCode") & vbCrlf & vbCrlf _	
		
		
		& "Sincerely,"& vbCrlf & vbCrlf _
		& "the i creatives family" & vbCrlf & vbCrlf
	
		
			IF ISNULL(rsOrder("AltMail")) THEN
				sEmail = rsOrder("EmpMail")	
				'	sEmail = "junk2@tempart.com"	
			ELSE
				sEmail = rsOrder("AltMail")	
			END IF
'			Dim objCDO
			Set objCDO = Server.CreateObject("cdo.message")

			objCDO.To = sEmail
			objCDO.Bcc = "New_Talent_Account@BlindEmail.com"
			objCDO.From = "New_Talent_Account@icreatives.com"
			objCDO.Subject =  "i creatives Online-Timesheet Link"			
			objCDO.TEXTBody = sBody
			objCDO.Send
			Set objCDO = Nothing	
*/		
}
?>


</div>
<center>
<div style="clear:all; display: flex; justify-content: center; margin-top: 50px;">
  <div style="text-align: center; max-width: 800px;">
    <div style="padding: 0 20px;">
      <div style="height: 20px;"></div>	   
	  <a name="top">&nbsp;</a>
      <span  class="redtxt" style="font-family: Arial; font-size: 110px; letter-spacing: -4px;">thank you</span><br>
      yes, we are that excited to hear from you...
      <br><br>
      <img src="/webtime/images/cropped_jumpers.jpg" alt="Jumpers"><br><br>
      Your feedback is the most effective way to improve our service and reward<br>
      those who perform to our standards. All information is confidential.<br><br>
      The <i>icreatives</i> team.
    </div>
  </div>
</div>
</center>




</body>
</html>