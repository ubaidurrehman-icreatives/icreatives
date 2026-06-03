<?php include 'trms_header.php'; ?>
<?php
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

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

$query = "SELECT * FROM ic_candidate_open_jobs

LEFT JOIN ic_webtime

ON ic_candidate_open_jobs.resource_id = ic_webtime.Employee_ID

WHERE (ic_webtime.Employee_ID NOT IN (

               SELECT Employee_ID

               FROM ic_webtime

               WHERE WeekEnding = '". $lastFriday ."'

)) OR ( WeekEnding = '". $lastFriday ."' AND SentDate = '0000-00-00 00:00:00') GROUP BY ic_candidate_open_jobs.resource_id";

// echo $query;
$result = mysqli_query($link,$query);


// Include the PHPMailer library
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/PHPMailer.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/Exception.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/SMTP.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/class-phpmailer.php");



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
    echo "<td><input type='checkbox' name='email[]' value='" . $row['email'] . "'></td>";
    echo "<td>" . $row['first_name'] . " " . $row['last_name']. "</td>";
	echo "<td>" . $row['customer_name'] . "</td>";
	echo "<td>" . $row['title'] . "</td>";
	echo "<td>" . $row['email'] . "</td>";
	echo "<td>" . $row['order_id'] . "</td>"; 
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
	$mail->Host       = "smtpout.secureserver.net"; // sets the SMTP server
	$mail->Username   = "exchange@icreatives.co"; // SMTP account username
	$mail->Password   = "Call1888icreate!";        // SMTP account password
	$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
	$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    // Set the email parameters
    $mail->setFrom('ysmith@icreatives.com', 'icreatives');
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
		$strSQL = "select first_name,last_name from ic_candidate_open_jobs where email = '".$email."'";
		$ans = mysqli_query($link,$strSQL);
		$row2 = mysqli_fetch_assoc($ans);

		$mail->Body = str_replace("XXX", $row2['first_name'], $sScreen);

		$mail->addBCC("remind_talent@blindemail.com");
		$mail->addAddress($email, $row2['first_name']." ".$row2['last_name']);

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
