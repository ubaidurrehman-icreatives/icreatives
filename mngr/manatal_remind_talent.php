<?php include 'manatal_header.php'; ?>
<h1>MANATAL REMIND TALENT</h1>
<?php
require_once __DIR__ . '/../db/db.php';
$link = db(); 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

	require_once dirname(__DIR__) . '/PHPMailer/PHPMailer.php';
	require_once dirname(__DIR__) . '/PHPMailer/Exception.php';
	require_once dirname(__DIR__) . '/PHPMailer/SMTP.php';
	// require_once dirname(__DIR__) . '/PHPMailer/class-phpmailer.php');
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <label for="selectedDate">Select a Friday:</label>
  <input type="date" id="selectedDate" name="selectedDate" required>
  <button type="submit">Submit</button>
</form>

<script>
// Initialize the date picker
$(document).ready(function() {
  $('#selectedDate').datepicker({
    format: 'yy-mm-dd',
    autoclose: true,
    beforeShowDay: function(date) {
      // Disable non-Friday dates
      return [date.getDay() == 5, ''];
    },
    onSelect: function(dateText) {
      // Fill out the field with the selected date
      $('#selectedDate').val(dateText);
    }
  });
});
</script>

<?php

// echo "selected date = ". $_REQUEST['selectedDate'];

$lastFriday = date('Y-m-d', strtotime('last friday'));

$lastFriday = $_REQUEST['selectedDate'];
if (empty($lastFriday)) { $lastFriday = date('Y-m-d H:i:s', strtotime('last friday')); }


echo "Results for week ending: ". $lastFriday;

 $query = "
SELECT *
FROM ic_matches m
LEFT JOIN ic_timesheets ts ON m.candidate = ts.Employee_ID
WHERE (ts.void IS NULL OR NOT ts.void)  AND m.is_active = 1
  AND (m.closed_date > DATE_SUB(CURDATE(), INTERVAL 15 DAY) OR m.closed_date = '0000-00-00')  
  AND m.pay_rate > 0  
  AND (
    m.candidate NOT IN (
        SELECT ts.Employee_ID
        FROM ic_timesheets ts
        JOIN ic_matches m ON m.job = ts.AssignmentNumber AND m.candidate = ts.Employee_ID
        WHERE NOT ts.void  AND m.is_active = 1 
          AND (m.closed_date > DATE_SUB(CURDATE(), INTERVAL 15 DAY) OR m.closed_date = '0000-00-00') 
          AND ts.WeekEnding = '". $lastFriday ."'
    )
	    OR (
      (ts.void IS NULL OR NOT ts.void) AND m.is_active = 1 
      AND (m.closed_date > DATE_SUB(CURDATE(), INTERVAL 15 DAY) OR m.closed_date = '0000-00-00') 
      AND ts.WeekEnding = '". $lastFriday ."' 
      AND ts.SentDate = '0000-00-00 00:00:00'
    )

)
GROUP BY m.candidate;";


// echo  $query;
$result = mysqli_query($link,$query);



// Execute a MySQL query to fetch data from the database
// $query = "SELECT email FROM users";
// $result = mysqli_query($conn, $query);

// Display the results in a HTML table with checkboxes
echo "<form method='post'>";
echo "<table>";
echo "<thead><tr><th><input type='checkbox' id='check_all'></th>

<th>Name</th>
<th>Customer</th>
<th>Role</th>
<th>Email</th>
<th>Order ID</th>

</tr></thead>";
echo "<tbody>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td><input type='checkbox' name='email[]' value='" . $row['candidate_email'] . "'></td>";
    echo "<td>" . $row['candidate_name']. "</td>";
	echo "<td>" . $row['company_name'] . "</td>";
	echo "<td>" . $row['job_name'] . "</td>";
	echo "<td>" . $row['candidate_email'] . "</td>";
	echo "<td><a target = '_blank' href= 'https://app.manatal.com/jobs/".$row['job']."'>" . $row['job'] . "</a></td>"; 
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
echo "<input type='submit' name='submit' value='Send Email'>";
echo "</form>";

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Get the selected email addresses from the form
    $emails = $_POST['email'];
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
	// $mail->isHTML(true);      
	// DKIM Setup
				$mail->DKIM_domain = 'icreatives.com';
				$mail->DKIM_selector = 'performa';
				$mail->DKIM_private = $_SERVER['DOCUMENT_ROOT'] . '/dkim_keys/ionos-icreatives-dkim-private-key.key'; // Replace with actual path
				$mail->DKIM_passphrase = ''; // If your key has a passphrase, enter it here
				$mail->DKIM_identity = 'exchange@icreatives.com'; // Typically same as From

            if ($email_redirect === '') {
                $emailArray = explode(',', $ap_email);
                foreach ($emailArray as $email) {
                    $email = trim($email);
                    if ($email !== '') { $mail->addAddress($email, 'Accounts Payable'); }
                }
            } else {
                $mail->addAddress($email_redirect, 'Accounts Payable');
            }

	
 	// Set sender and recipient addresses
	$mail->setFrom('exchange@icreatives.com', 'icreatives accounting');
	$mail->addReplyTo('andreaa@icreatives.com', 'icreatives');
    $mail->Subject = '++ eTimesheet Reminder for Week Ending ' . date('m/d/Y',strtotime($lastFriday)). '  ++';
	
	
	$sScreen = "Dear XXX, " . PHP_EOL . PHP_EOL; 

	$sScreen = $sScreen . "This automated letter (sorry it is so impersonal) is to inform you that your electronic timesheet for week ending " . date('m/d/Y',strtotime($lastFriday)) . " is missing. " . PHP_EOL . PHP_EOL; 

	$sScreen = $sScreen . "If you worked last week, please reply to this email and submit an online timesheet immediately." . PHP_EOL . PHP_EOL; 

	$sScreen = $sScreen . "https://www.icreatives.com/portal-login/" . PHP_EOL . PHP_EOL; 

	$sScreen = $sScreen . "If you did not work last week, please let us know why." . PHP_EOL . PHP_EOL; 

	$sScreen = $sScreen . "If you are on a long term project, please let me know your status." . PHP_EOL . PHP_EOL;  


	$sScreen = $sScreen . "Thank you in advance." . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL; 


	$sScreen = $sScreen . " Andrea Amenta" . PHP_EOL ; 
	$sScreen = $sScreen . " i creatives | staffing" . PHP_EOL ; 
	$sScreen = $sScreen . " 'the art of selection' " . PHP_EOL ; 
	$sScreen = $sScreen . " •     •     •     •    •    • " . PHP_EOL ; 
	$sScreen = $sScreen . " 1.888.icreate" . PHP_EOL ; 
	$sScreen = $sScreen . " http://icreatives.com" . PHP_EOL ; 

	$mail->Body = $sScreen;

    // Loop through the selected email addresses and add them as recipients
    foreach ($emails as $email) {
		$mail->clearAllRecipients( );
		$strSQL = "select candidate_name,candidate_email from ic_matches where candidate_email = '".$email."'";
		$ans = mysqli_query($link,$strSQL);
		$row2 = mysqli_fetch_assoc($ans);

		$mail->Body = str_replace("XXX", $row2['candidate_name'], $sScreen);

		$mail->addBCC("remind_talent@blindemail.com");
		$mail->addBCC("stevenc@icreatives.com");
		$mail->addAddress($email, $row2['candidate_name']);

    // Send the email
	
	echo "<br>".$email;

    if ($mail->send()) {
        // Display a success message
        echo "Emails sent successfully.";
    } else {
        // Display an error message
        echo "Error sending emails: " . $mail->ErrorInfo;
    }

	}
}
?>

<script>
// Add a "Check All" checkbox to select all checkboxes at once
var checkAll = document.getElementById('check_all');
checkAll.addEventListener('click', function() {
    var checkboxes = document.getElementsByName('email[]');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = checkAll.checked;
    }
});
</script>
