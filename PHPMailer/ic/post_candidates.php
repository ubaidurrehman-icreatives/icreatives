<?php
session_start();
if(!isset($_SESSION['recruiter_id'])) {
  session_regenerate_id();
  header("Location: login.php");
  return;
}

require('./db.php');
$conn = db_connect($_SESSION['recruiter_id'], $_SESSION['password']);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$orderID = $_POST['orderID'];
$customerID = $_POST['customerID'];
$divisionID = $_POST['divisionID'];
$message = $_POST['msg'];
$comments = $_POST['comments'];
$email = $_POST['emailaddress'];
$xtremail = $_POST['xtremail'];
$candidates = $_POST['candidateID'];
$isPrimaryResume = $_POST['isPrimaryResume'];
for($i = 0; $i < sizeof($isPrimaryResume); $i++) {
  if($isPrimaryResume == "1") {
    $isPrimaryResume[$i] = TRUE;
  } else {
    $isPrimaryResume[$i] = FALSE;
  }
}
$include = $_POST['include'];
for($i = 0; $i < sizeof($include); $i++) {
  if($include[$i] == "yes") {
    array_splice($include, $i+1, 1);
  }
}
$weights = $_POST['weight'];

// insert new submital
$query = "INSERT INTO ic_submitals(order_id, customer_id, division_id, recruiter_id, message)
          VALUES(?,?,?,?,?);
          SELECT SCOPE_IDENTITY() AS submital_id";
$pstmt = odbc_prepare($conn,$query);
odbc_execute($pstmt,array($orderID, $customerID, $divisionID, $_SESSION['recruiter_id'], $message));
$row = odbc_fetch_array($pstmt);
$submital = $row['submital_id'];

for($i = 0; $i < sizeof($candidates); $i++) {
  if($include[$i] == "yes") {
    // save candidate resume
    if($_FILES['resume']['tmp_name'][$i] != "") {
      if($isPrimaryResume[$i]) {
        move_uploaded_file($_FILES['resume']['tmp_name'][$i], '../resumes/'.$candidates[$i].'.pdf');
        echo "successfully uploaded";
      } else {
        move_uploaded_file($_FILES['resume']['tmp_name'][$i], '../resumes/custom/'.$candidates[$i].'-'.$divisionID.'.pdf');
        echo "successfully uploaded";
      }
    }

    $query = "INSERT INTO ic_candidates(employee_id, submital_id, weight, ic_comments, DisplayCustomResume) VALUES (?,?,?,?,?)";
    $stmt = odbc_prepare($conn, $query);
    odbc_execute($stmt, array($candidates[$i], $submital, $weights[$i], $comments[$i], !$isPrimaryResume[$i]));
  }
}

require_once("../PHPMailer/PHPMailer.php");
require_once("../PHPMailer/Exception.php");
require_once("../PHPMailer/SMTP.php");

$mail = new PHPMailer(true);

echo "Address: ".$email."<br>";

try {
  // // Server settings
  // $mail->SMTPDebug = 3;
  $mail->isSMTP();
  $mail->Host = 'smtp.1and1.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'exchange@icreatives.com';
  $mail->Password = 'Call1888icreate!';
  $mail->SMTPSecure = 'tls';
  $mail->Port = 25;

  // Recipients
  $mail->setFrom($_SESSION['recruiter_id'].'@icreatives.com','Contact Form');
  $mail->addAddress($email);
  if($xtremail != "") {
    $mail->addCC($xtremail);
  }

  // Content
  $mail->Subject = 'You\'ve got new candidates!';
  $mail_message = "
  <html>
  <body>
  <p>".nl2br($message)."</p>
  <p><a href='http://".$_SERVER['SERVER_NAME']."/portal/customer/order_candidates.php?orderID=$orderID&m=1&sub=".$submital."'>Here</a> are your new candidates</p>

  </body>
  </html>
  ";
  $mail->MsgHTML($mail_message);
  $mail->send();

  // header("Location: approve.php");
} catch (Exception $e) {
  echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
