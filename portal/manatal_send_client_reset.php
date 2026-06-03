<?php 
session_start();

 $contactID = $_SESSION['contactID'];
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
 ?>
 <html>
<head>
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
  </head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

 <!-- Bootstrap CSS, font-awesome custom CSS -->
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_red.css">
  <link rel="stylesheet" href="/portal/styles.css">
<div style="padding-top: 40px;"> </div>
  <div class="container custom" >
<?php
require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';
$link = db();   


// Add use statements at the beginning of the file


require_once dirname(__DIR__) . '/PHPMailer/PHPMailer.php';
require_once dirname(__DIR__) . '/PHPMailer/Exception.php';
require_once dirname(__DIR__) . '/PHPMailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "SELECT user_pass, email FROM ic_sales WHERE display_name = '".$_REQUEST['sender']."';";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $sender_email = $row['email'];

    $email = $_REQUEST['user'];
    $user = $_REQUEST['user'];
    $new = $_REQUEST['new'];
    $sender = $_REQUEST['sender'];
    $query = "SELECT * FROM ic_contacts WHERE email = '".$email."'";
    $result = mysqli_query($link, $query);

    if ($row_count = mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        $full_name = $row['fullname'];
        list($first_name, $last_name) = explode(" ", $row['full_name']);
        $contact_email = $email;
        $contactID = $row['id'];
    } else {
        header("Location: /portal/manatal_client_portal_login.php/?r=recognize");
        exit;
    }

    try {
        $selector = bin2hex(random_bytes(8));
        $token = random_bytes(32);
    } catch (Exception $e) {
        header("Location: /portal/manatal_send_client_reset.php/?r=error");
        exit;
    }

    $query = "UPDATE ic_password_reset_tickets SET closed = 1, closed_at = NOW(), close_reason = 'new reset ticket created' WHERE contact_id = '".$contactID."' AND closed = 0";
    $result = mysqli_query($link, $query);

    $sql = "INSERT INTO ic_password_reset_tickets (contact_id, contact_email, sender, selector, token, created_at, expires_at) VALUES (?, ?, ?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 3 HOUR))";
    $token_val = hash('sha256', $token);

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, 'sssss', $contactID, $email, $sender_email, $selector, $token_val);
        echo mysqli_stmt_execute($stmt);
    } else {
        echo "ERROR: Could not prepare query: $sql. " . mysqli_error($link);
        exit();
    }

    $url = sprintf('%s/portal/manatal_reset_client_password.php/?%s', "https://".$_SERVER['SERVER_NAME'], http_build_query([
        'selector' => $selector,
        'validator' => bin2hex($token)
    ]));

    $mail = new PHPMailer(true);
    try {
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
				$mail->addReplyTo('exchange@icreatives.com', 'icreatives');

        $mail->setFrom('exchange@icreatives.com', 'icreatives');
        $mail->addAddress($email);
        $mail->addBCC('jobcomp2@blindemail.com');
        $mail->addBCC('stevenc@icreatives.com');

        $mail->Subject = 'icreatives - Password Reset';

        $message = "
            <p>We received a password reset request. The link to reset your password is below.
            If you did not make this request, you can ignore this email</p>
            <p>Here is your password reset link:</br>".
            sprintf('<a href="%s">%s</a></p>', $url, $url).
            "<p>Thanks!</p>";
        $mail->MsgHTML($message);
        $mail->send();
        ?>

        <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.less.css">
        <link rel="stylesheet" href="/portal/styles.css">

        <div class="container custom">
            <div class="row my-5">
                <div class="col">
                    <h1 style="color:#b22655;">Hello <?php echo $first_name ?>!</h1>
                    <input type="hidden" name="user" value="<?php echo $_REQUEST['user']; ?>">
                    <?php if ($_REQUEST['new'] == 1) { ?>
                        <p><strong>Looks like we would like you to visit our talent evaluation portal.</strong></p>
                    <?php } else { ?>
                        <p><strong>Looks like you requested a password reset.</strong></p>
                    <?php } ?>
                    <p>
                        Before you get started, we need to do just a bit of setup.<br>
                        We sent you an email to create a password, which should arrive in your inbox shortly.
                    </p>
                    <p><strong>Please check your inbox</strong> (or spam/junk folders)</p>
                    <p>If the emailed link cannot be found, please contact your icreatives representative.</p>
                    <p>Also please ask your IT department to <strong>place <span style="color:#b22655;"> *.icreatives.com </span> on their email server white list.</strong></p>
                </div>
            </div>
        </div>
        <?php
    } catch (Exception $e) {
        header("Location: /portal/manatal_reset_client_password.php/?r=error");
        exit();
    }
} else {
?>
<?php // if ($_REQUEST['new'] == 1) { ?>
<div style="padding-top:50px;"> </div>
<div class="row justify-content-center">
  <form class="form-login" id="login" action="" method="post">
        <h1 style="color:#b22655;">Create New Password</h1>
        <hr/>
        <div class="form-group">
            <label for="email">Please enter your email address to verify your email address</label>
            <input type="email" class="form-control" id="user" name="user" value="<?php if(isset($user)) echo $user ?>" placeholder="email" required>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col">
					<button type="submit" name="submit" class="btn btn-primary">Send Password Link</button>
                </div>
            </div>
        </div>
    </form>
</div>
                    <?php } ?>
</div>
</body>
</html>