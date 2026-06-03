<HTML>
<HEAD>
	<meta charset="utf-8" />
</HEAD>
<BODY>

<?php
// include OWNER_USER on eempact email upload
$RMail = $_POST['RMail'];   

// echo "RMAIL = " . $RMail . "<BR>";

// xxxx();

include $_SERVER['DOCUMENT_ROOT']."/wp-content/themes/porto-child/db5.php";

$strSQL = "SELECT InternetSMTPEmail, Branch_ID, User_ID from CFG_UserProfile where InternetSMTPEmail = '" .$RMail . "'";   
 	$resMySel = odbc_exec($conn,$strSQL); 

	$resMySel = odbc_exec($conn,$strSQL);
	$row = odbc_fetch_array($resMySel);

	IF($row > 0){
		$sEOF = "Flase";
	}
	$OWNER_USER = $row["User_ID"];
	// echo "<BR>User_Owner= " . $OWNER_USER . "<BR>";
	// echo $resMySel;



// Upload the file
  

$target_dir = "C:/Inetpub/vhosts/beta.icreatives.com/httpdocs/empact/ApplicationResumes/";
$target_file = $target_dir . basename($_FILES["FILE1"]["name"]);
// echo "target file: ". $target_file . "<br>";
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "pdf" && $imageFileType != "docx" && $imageFileType != "doc" && $imageFileType != "rtf") {
    echo "Sorry, only DOC, DOCX & PDF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["FILE1"]["tmp_name"], $target_file)) {
        // echo "The file ". basename( $_FILES["FILE1"]["name"]). " has been uploaded.";
    } else {
	echo basename($_FILES["FILE1"]["name"]). " has NOT been uploaded.";
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<table>
<?php 
$msgbody = "";
$codes=file_get_contents ('EnTake_Fields.txt');
// CHECK TO SEE IF THE VARIABLE IS IN THE LIST OF APPROVED FIELDS IN EnTake_Fields.txt;
    foreach ($_POST as $key => $value) {
	if (strpos($codes, $key) > 0 and !empty($value) ) {
		// echo "<tr>";
        	// echo "<td>";
		$msgbody = $msgbody .$key. ":".CHR(9).$value. CHR(13) ;
		// echo "Found";
		// echo "</td>";
	        // echo "<td>";
	        // echo $key;
	        // echo "</td>";
        	// echo "<td>";
	        // echo $value;
        	// echo "</td>";
	        // echo "</tr>";
	}
    }
reset($_POST);
	foreach ($_POST as $key => $value) {

		if (substr($key,0,6) == "SKILL_" and !empty($value) ) {
			$msgbody = $msgbody .$key. ":".CHR(9).$value. CHR(13) ;
		}
	}
reset($_POST);


// We place the references in the conviction detail field because that was the only place left in eempact to store it.
$x = 1; //do this for 3 references;
$CONVICTION_DETAILS = PHP_EOL;
while ($x <= 3){
	$CONVICTION_DETAILS = $CONVICTION_DETAILS . "Ref ". strval($x) . ": ";
	foreach ($_POST as $key => $value) {
	 	if (substr($key,0,10) == "REFERENCE". strval($x) and !empty($value) ) {
			$CONVICTION_DETAILS = $CONVICTION_DETAILS . $value." ";
		}
	}
	$CONVICTION_DETAILS = $CONVICTION_DETAILS . PHP_EOL;
	$x = $x + 1;
}

 // echo $CONVICTION_DETAILS ;
	$msgbody = $msgbody . "CONVICTION_DETAILS:".CHR(9).$CONVICTION_DETAILS. CHR(13) ;


	$msgbodyend = "";
	foreach ($_POST as $key => $value) {
		if (substr($key,5,11) == "_JOB_DUTIES" and !empty($value) ) {
				       // 0123456789012345
			$msgbodyend = $msgbodyend .$key. ":".CHR(9).$value. CHR(13) ;
		} 
	}

?>

</table>

<?php

// echo $msgbody;

	

$betabody = "";
$betabody = $betabody . "FIRST_NAME:". CHR(9). rtrim($_POST["First"]) . CHR(13);
$betabody = $betabody . "LAST_NAME:". CHR(9). rtrim($_POST["Last"]) . CHR(13);
$betabody = $betabody . "OWNER_USER:". CHR(9). rtrim($OWNER_USER) . CHR(13);
$betabody = $betabody . "SKILL_S". rtrim($_POST["State"]) .":". CHR(9). "10" . CHR(13);
$betabody = $betabody . "EM_USER_DEF1:" . CHR(9) . "NEVER INTERVIEWED" . CHR(13);
$betabody = $betabody . "EM_USER_DEF2:" . CHR(9) . "enTake "  . date('h:i:sa') . CHR(13);
$betabody = $betabody . $msgbody;
$betabody = $betabody . $msgbodyend;
if ($imageFileType != "doc") {
	$betabody = $betabody . "APPL_COMMENT:" . CHR(13) . $content . "   ". date('h:i:sa') . CHR(13) ;
}


$betabody = "*******************************************************************************".CHR(13).$betabody;

// echo $betabody;

require_once($_SERVER['DOCUMENT_ROOT']."/wp-includes/class-phpmailer.php");
require_once($_SERVER['DOCUMENT_ROOT']."/wp-includes/phpmailer/phpmailer.php");
require_once($_SERVER['DOCUMENT_ROOT']."/wp-includes/phpmailer/Exception.php");
require_once($_SERVER['DOCUMENT_ROOT']."/wp-includes/phpmailer/SMTP.php");


// send PLAIN TEXT email TO ENTAKE not easy btw


$mail             = new PHPMailer();
$mail->ContentType = 'text/plain'; 
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "smtp.1and1.com"; // SMTP server
// $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "smtp.1and1.com"; // sets the SMTP server
$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
$mail->Username   = "exchange@icreatives.com"; // SMTP account username
$mail->Password   = "Call1888icreate!";        // SMTP account password
$mail->From = "ic.entake@icreative.com";
$mail->FromName = '';
$mail->Subject    =  "ENTAKE:EMPACT_001_PROD_PDI";
$mail->Body    = $betabody;
// $mail->MsgHTML("");
$mail->AddAddress("ic.entake@icreative.com", "entake mail");
$mail->AddAddress("ic2.entake@icreative.com", "entake mail");
$mail->AddAddress("stevenc@icreatives.com", "entake mail");
// $mail->AddAddress("stevenc@icreatives.com");
if ($imageFileType == "doc"){
	$mail->AddAttachment($target_file);
}
if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;

}else{

// echo "Target file: " . $target_file;

if (file_exists($target_file)) { 
	unlink ($target_file); 
// 	echo "<BR>File Deleted<BR>";
} 

if ($uploadOk !== 0) {
?>

<div style="text-align: center;"><img class="size-full wp-image-7060" src="/wp-content/uploads/2021/01/cropped_jumpers.jpg" alt="Thank you" width="500" height="515" /></div>
<div style="text-align: center; font-size: 30px; color: #b22625;"><b>Thank you!</b></div>
<div style="text-align: center; font-size: 30px; color: #b22625; padding: 5px 0 25px 0;"><b>You did it! Fingers crossed on that position!</b></div>
<?php }

}

// $recruiter_mail = $RMail;

// send reference to recruiter;

$mail             = new PHPMailer();

if ($RMail == "") {
   // if we are missing the recruiter email, send to everyone. 
   $mail->AddAddress("contact_Form@icreatives.com", "unknown reference check");
   // $mail->AddAddress("stevenc@icreatives.com", "reference check unknown recruiter");
} else {
   	$mail->AddAddress($RMail);
   	$mail->AddAddress("stevenc@icreatives.com", "reference check");
}
$mail->ContentType = 'text/plain'; 
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "smtp.1and1.com"; // SMTP server
// $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "smtp.1and1.com"; // sets the SMTP server
$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
$mail->Username   = "exchange@icreatives.com"; // SMTP account username
$mail->Password   = "Call1888icreate!";        // SMTP account password
$mail->From = "ic.entake@icreative.com";
$mail->FromName = '';
$mail->Subject    =  "REFERENCES FOR: " . rtrim($_POST["First"]) . " " . rtrim($_POST["Last"]);
$mail->Body    = $CONVICTION_DETAILS;
// $mail->MsgHTML("");

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
}
?>

</body>
</html>
