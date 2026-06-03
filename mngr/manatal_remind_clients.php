<?php include 'manatal_header.php'; ?>
<h1>MANATAL REMIND CLIENTS</h1>
  
<?php
 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../db/db.php';
$link = db(); 
function convertTime($dec)
{
    // start by converting to seconds
    $seconds = ($dec * 3600);
    // we're given hours, so let's get those the easy way
    $hours = floor($dec);
    // since we've "calculated" hours, let's remove them from the seconds variable
    $seconds -= $hours * 3600;
    // calculate minutes left
    $minutes = floor($seconds / 60);
    // remove those from seconds as well
    $seconds -= $minutes * 60;
    // return the time formatted HH:MM:SS
    return lz($hours).":".lz($minutes);
}

Function HrComp($lsInHr,$lsOutHr){
If ($lsInHr >= 13) {
   $lsInHr = intval($lsInHr -12);
} ElseIf ($lsInHr == 0 AND $lsOutHr <> 0) {
	$lsInHR = 12;
} Else {
   $lsInHr = intval($lsInHr);
}
return $lsInHr;
}

Function TotalHrs($i) {

$TotalHrs = $row2['OUTHR'. $i] - $row2['INHR' . $i] - $row2['BREAK' . $i];

return $TotalHrs;
}
Function GrandTotal() {
	$lsGt = 0;
for ($i = 1; $i <= 7; $i++) {
	$lsGT = $lsGT + TotalHrs($i);
}
$GrandTotal = $lsGT;

return $GrandTotal;
}


Function AmPmCompIn($lsHr) { // need two because out should default to PM for new record
If ($lsHr >= 12) {
        $AmPmCompIn = "PM";
} Else {
        $AmPmCompIn = "AM";
}
return $AmPmCompIn;
}

Function AmPmCompOut($lsHr) {
If ($lsHr >= 12 || $lsHr == 0) {
        $AmPmCompOut = "PM";
} Else {
        $AmPmCompOut = "AM";
}
return $AmPmCompOut;
}

function truncate($value, $precision) {
    $multiplier = pow(10, $precision);
    $value = (int)($value * $multiplier);
    return $value / $multiplier;
}

Function MinComp($lsHr) {

$lsMin = ($lsHr - floor($lsHr)) * 60;

Switch ($lsMin) {

Case 0:
$MinComp = "00";
break;        
Case 15:
$MinComp = "15";
break; 
Case 30:
$MinComp = "30";
break; 
Case 45:
$MinComp = "45";
break; 
default:
$MinComp = "00";
break; 
}
return $MinComp;
}


$strSQL = "SELECT 'TIMEACT' as '!TIMEACT' ";
$strSQL = $strSQL . ", wt.first_name as FIRST ";
$strSQL = $strSQL . ", wt.last_name as LAST ";
$strSQL = $strSQL . ", REPLACE(oj.company_name,' ','_') as JOB ";
$strSQL = $strSQL . ", REPLACE(wt.title,' ','_') as ITEM ";
$strSQL = $strSQL . ", wt.AssignmentNumber as PROJ ";
$strSQL = $strSQL . ", wt.AssignmentNumber as xORDER ";
$strSQL = $strSQL . ", oj.po_number as PO ";
// $strSQL = $strSQL . ", oj.Primary_Contact_ID as ORDERTAKENID ";
// $strSQL = $strSQL . ", oj.Second_Contact_ID as REPORTID ";
// $strSQL = $strSQL . ", oj.Primary_Contact_First as PFIRST "; 
// $strSQL = $strSQL . ", oj.Primary_Contact_Last as PLAST "; 
$strSQL = $strSQL . ", wt.Primary_Contact_Email as email ";
// $strSQL = $strSQL . ", oj.Second_Contact_First as SFIRST ";
// $strSQL = $strSQL . ", oj.Second_Contact_Last as SLAST ";
$strSQL = $strSQL . ", wt.Second_Contact_Email as semail  ";
$strSQL = $strSQL . ", wt.Unique_id as UNUMB  ";
$strSQL = $strSQL . ", '40' as DURATION ";
$strSQL = $strSQL . ", '1' as BILLINGSTATUS ";
$strSQL = $strSQL . ", 'CONTR' as PITEM ";
$strSQL = $strSQL . ", '0' as BITEM ";
$strSQL = $strSQL . ", 'Yes' as Contract "; 

for ($i = 1; $i <= 7; $i++) {

 	$strSQL = $strSQL . ", wt.TimeInHr" . $i . " as INHR" . $i . " ";
	$strSQL = $strSQL . ", wt.TimeOutHr" . $i . " as OUTHR" . $i . " ";
	$strSQL = $strSQL . ", wt.Break" . $i . " as BREAK" . $i . " ";
}
$strSQL = $strSQL . ", wt.Continuing as CONT " ;
$strSQL = $strSQL . ", wt.ApproveDate as APPROVE " ;
$strSQL = $strSQL . ", wt.SentDate as SENT " ;
$strSQL = $strSQL . ", wt.DeclineDate as DECLINE " ;
$strSQL = $strSQL . ", wt.Weekending as WKEND " ;
$strSQL = $strSQL . "from ic_timesheets wt ";

$strSQL = $strSQL . "JOIN ic_matches oj ON (oj.candidate = wt.employee_id AND oj.job = wt.AssignmentNumber) 
WHERE NOT wt.void AND (invoice_number = 0 OR invoice_number is NULL) AND wt.invoice_type = 't' AND 	
wt.SentDate <> '0000-00-00 00:00:00' AND 
wt.ApproveDate = '0000-00-00 00:00:00' AND  
wt.ExportDate = '0000-00-00 00:00:00'";


// echo $strSQL;
// AND oj.Primary_Contact_First = ";

// Include the PHPMailer library
// Load PHPMailer
require_once dirname(__DIR__) . '/PHPMailer/PHPMailer.php';
require_once dirname(__DIR__) . '/PHPMailer/Exception.php';
require_once dirname(__DIR__) . '/PHPMailer/SMTP.php';

// Connect to the MySQL database

// Query the database for the list of recipients
$result = mysqli_query($link,$strSQL);	

// Start the HTML form
echo '<form method="post">';

// Output the table headers
echo '<table>';
echo "<thead><tr><th><input type='checkbox' id='check_all'></th><th>Check All</th></tr></thead>";

echo '<tr>
<th> </th>
<th>Talent</th>
<th>Client</th>
<th>Position</th>
<th>Week Ending</th>
<th>Contact 1</th>
<th>Contact Email</th>
<th>Contact2 Email</th>
<th>Date Sent</th>
<th>Job ID</th>
<th>Declined</th>
</tr>';

// Loop through the results and output each row
while ($row = mysqli_fetch_array($result)) {
	$sent_date = strtotime( $row['SENT']);
	$week_ending = strtotime( $row['WKEND']);
    echo '<tr>';
    echo '<td>';
	$sent_date = date("Y-m-d H:i", strtotime($row['SENT']));
	$decline_date = date("Y-m-d H:i", strtotime($row['DECLINE']));
	
	
	if($row['APPROVE'] == "0000-00-00 00:00:00" && $decline_date < $sent_date && !is_null($row['JOB']) ) {
		echo '<input type="checkbox" name="recipients[]" value="' . $row['UNUMB'] . '">';
		}
		echo '</td>';
	// echo '<input type="hidden" name="WKEND[]" value="' . $row['WKEND'] . '">';
		echo '<td>' . $row['FIRST'] . ' ' .$row['LAST'] . '</td>';
		echo '<td>' . $row['ITEM'] .  '</td>';
		echo "<td>" ;
		if (!is_null($row['JOB'])) {
			echo $row['JOB'] ;
		} else {
			echo '<font color="red"><b>RE-OPEN JOB</b></font>';
		}
		echo "</td>";
		echo "<td>" . date("m/d/Y",$week_ending) . '</td>';
		echo "<td>". ($row["PFIRST"] ?? '')." ".($row["PLAST"] ?? '')."</td>";
		echo "<td>" . $row['email'] . "</td>";
		echo "<td>" . $row['semail'] . "</td>";
		echo "<td>" . date("m/d/Y",strtotime( $row['SENT'])) . '</td>';
		echo "<td>" . $row['PROJ'] . "</td>";
		echo "<td>";

		if($row['DECLINE'] !== "0000-00-00 00:00:00") {echo "DECLINED";}

		echo "</td>";

		echo '</tr>';
}

// Close the table and add the submit button
echo '</table>';
echo '<input type="submit" value="Send Email">';
echo '</form>';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the selected recipients
    $recipients = $_POST['recipients'];
	$WKEND = $_POST['WKEND'] ?? '0000-00-00'; 

    // Loop through the recipients and send an email to each one

	
	for($i=0; $i<count($recipients); $i++) {
		
		
		// foreach ($recipients as $recipient) {
        // Create a new PHPMailer instance
	$mail             = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = "smtp.1and1.com"; // SMTP server
	// $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
	// 1 = errors and messages
	// 2 = messages only
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Host       = "smtp.1and1.com"; // sets the SMTP server
	$mail->Username   = "exchange@icreatives.com"; // SMTP account username
	$mail->Password   = "Call1888icreate!";        // SMTP account password
	$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
	$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
	$mail->CharSet = "UTF-8";
	$mail->isHTML(true);      
	// DKIM Setup
				$mail->DKIM_domain = 'icreatives.com';
				$mail->DKIM_selector = 'performa';
				$mail->DKIM_private = $_SERVER['DOCUMENT_ROOT'] . '/dkim_keys/ionos-icreatives-dkim-private-key.key'; // Replace with actual path
				$mail->DKIM_passphrase = ''; // If your key has a passphrase, enter it here
				$mail->DKIM_identity = 'exchange@icreatives.com'; // Typically same as From

				// Set sender and recipient addresses
				$mail->setFrom('exchange@icreatives.com', 'icreatives');
				$mail->addReplyTo('andreaa@icreatives.com', 'icreatives');
				$mail->AddBCC("stevenc@icreatives.com");
										
		$query2 = $strSQL . " AND wt.Unique_id = '". $recipients[$i]."'";

		$result2 = mysqli_query($link,$query2);
		$row2 = mysqli_fetch_array($result2);
				
		$mail->clearAllRecipients( );
		
        $mail->addAddress($row2['email']);
		$mail->addAddress($row2['semail']);
		// $mail->AddCC("ysmith@icreatives.com");
		$mail->AddBCC("stevenc@icreatives.com");

        // Set the subject
        $mail->Subject = '+++ Timesheet for '. $row2["FIRST"]. ' Week Ending '. date("m/d/Y", strtotime($row2["WKEND"])). ' +++';

        // Set the body
		include "manatal_eapprovetxt.php";
		$sScreen = Str_Replace("|","'",$sScreen);
        $mail->Body = $sScreen;
		
		$sScreen;

        // Send the email
		
        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo "Email Sent to ". $row2['email']." at ". $row2['JOB']. " Week: " .  date("m/d/Y", strtotime($row2["WKEND"])). "<br>";
        }
		
    }
}

?>
<script>
// Add a "Check All" checkbox to select all checkboxes at once
var checkAll = document.getElementById('check_all');
checkAll.addEventListener('click', function() {
    var checkboxes = document.getElementsByName('recipients[]');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = checkAll.checked;
    }
});
</script>
