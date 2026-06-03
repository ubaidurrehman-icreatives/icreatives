<HTML>
<STYLE>

.round-button {
	width:15%;
}
.round-button-circle {
	width: 100%;
	height:0;
	padding-bottom: 100%;
    border-radius: 50%;
	border:10px solid #cfdcec;
    overflow:hidden;
    
    background: #4679BD; 
    box-shadow: 0 0 3px gray;
}
.round-button-circle:hover {
	background:#30588e;
}
.round-button a {
    display:block;
	float:left;
	width:100%;
	padding-top:50%;
    padding-bottom:50%;
	line-height:1em;
	margin-top:-0.5em;
    
	text-align:center;
	color:#e2eaf3;
    font-family:Verdana;
    font-size:1.2em;
    font-weight:bold;
    text-decoration:none;
}
</STYLE>
<BODY>

<?php

global $wpdb;
$missed = "F";
$VarChar = $_GET['VarChar'];
$Accept = $_GET['Accept'];

// Echo "Accept: ".$Accept."<BR>";

	$link = mysql_connect('localhost', 'wordpress_d', 'm1vHl09_WF') or die("Error: " . mysql_error());
	mysql_select_db('wordpress_b');

// is it from us?

list($rep, $id, $repemail)  = explode("-", $VarChar);
$strSQL = "SELECT name from ic_sales WHERE name= '". $rep . "' "   ;
$result = mysql_query($strSQL);
$num_rows = mysql_num_rows($result);

If ($num_rows > 0){

// see if anyopne claimed it


list($rep, $id, $repemail)  = explode("-", $VarChar);
	$strSQL = "SELECT recruiter, firstname, lastname, company, phone from ic_contact_form WHERE NUMBER= '". $id . "' "   ;
	$result = mysql_query($strSQL);
	while ($row = mysql_fetch_array($result)) {
	   if($row['recruiter'] != "OPEN") {
      		echo "<h1>". $row['recruiter'] . " Already Accepted</h1>" ;
		$VarChar = "";
		$Accept = "";
		$missed = "T";
	   }
	}



if ($VarChar !="" and is_null($Accept) AND mysql_num_rows($result) <> 0 ) {

	Echo "<div class='round-button'><div class='round-button-circle'><a href='/sales?VarChar=". $VarChar . "&Accept=Yes' class='round-button'>Accept</a></div></div>";

	Echo "<div class='round-button'><div class='round-button-circle'><a href='/sales?VarChar=". $VarChar . "&Accept=Spam' class='round-button'>Spam</a></div></div>";

}

if ($VarChar !="" and $Accept !="") {


list($rep, $id, $repemail)  = explode("-", $VarChar);

	If ($Accept == "Spam"){
		$rep = "SPAM";
	}


  	//Continue

	// add to form



	// SQL queries

	$strSQL = "UPDATE ic_contact_form SET Accepted = now(), Recruiter = '". $rep . "' WHERE NUMBER= '". $id . "'"   ;
	mysql_query($strSQL) or die ('Error updating database: '.mysql_error());

	If ($rep <> "SPAM"){

	$strSQL = "SELECT firstname, lastname, company, phone from ic_contact_form WHERE NUMBER= '". $id . "'"   ;
//	echo $strSQL;

	$result = mysql_query($strSQL);
	while ($row = mysql_fetch_array($result)) {
	   if($row['number'] = $id) {

		$message = $rep . " has this one.
		Name: ".$row['firstname']." ".$row['lastname']."
		Company: ".$row['company']."
		Phone:".$row['phone']. "\r\n" ;
	   }
	}

	// Send  confirmation


		$s_more=str_replace(";","<br>",$_REQUEST['moreval']);
		$to = "contact_form@icreatives.com";
		$subject = "++ Contact Us ++ " . date("Ymdhs");
// ***********************************
// require_once('..\PHPMailerAutoload.php');
require_once($_SERVER['DOCUMENT_ROOT']."/wp-includes/class-phpmailer.php");
//require_once('class.phpmailer.php');
// require_once('phpmailer-fe.php');

$mail             = new PHPMailer();

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "smtp.1and1.com"; // SMTP server
// $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "smtp.1and1.com"; // sets the SMTP server
$mail->Port       = 587;                    // set the SMTP port for the GMAIL server
$mail->Username   = "exchange@icreatives.com"; // SMTP account username
$mail->Password   = "Call1888icreate!";        // SMTP account password



$mail->From = $repemail;
$mail->FromName = '';
// $mail->AddReplyTo("name@yourdomain.com","First Last");
$mail->Subject    = $subject;
$mail->MsgHTML($message);
$mail->AddAddress("contact_form@icreatives.com", "Contact Form");

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
}
    

// ***********************************



	}


echo "<h1>".$rep. ", you have this one!<P></h1>";
	
}

if ($VarChar !="" OR $missed = "T" ) {	

/// show last 3 items

	$strSQL = "SELECT number,recruiter,LastName , Company,LogDate,Accepted from ic_contact_form where recruiter <> 'SPAM' ORDER BY Number DESC limit 10";
	$result = mysql_query($strSQL);

// echo "<BR>".$strSQL."<BR>;


echo "Last ten non-spam leads, most recent first:<br>";

echo "<table  style='width:100%'>";

	while ($row = mysql_fetch_array($result)) {
		Echo "<tr>";
		Echo "<td>".$row['number']."</td>";
		Echo "<td>".$row['recruiter']."</td>";
		Echo "<td>".$row['LastName']."</td>";
		Echo "<td>".$row['Company']."</td>";
		Echo "<td>".$row['LogDate']."</td>";
		Echo "<td>".$row['Accepted']."</td>";
		Echo "</tr>";
	}

Echo "</table>";

}
}
?>
</BODY>
</HTML>





