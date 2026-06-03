<?php
session_start();


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once dirname(__DIR__) . '/PHPMailer/PHPMailer.php';
require_once dirname(__DIR__) . '/PHPMailer/Exception.php';
require_once dirname(__DIR__) . '/PHPMailer/SMTP.php';


require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';
$link = db();

// Make mysqli throw exceptions so failures are visible
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// ------------------ 1) Load admin (optional) ----------------------------
$stmt = $link->prepare("SELECT user_pass, email FROM ic_sales WHERE Admin = 'admin' LIMIT 1");
$stmt->execute();
$adminRow = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($adminRow) {
    $_SESSION['user_pass']  = $adminRow['user_pass'];
    $_SESSION['admin_name'] = $adminRow['email'];
}

// ------------------ 2) Identify user (contact or candidate) -------------
$email = $_SESSION['user'] ?? null;

if (!$email) {
    header("Location: /portal/manatal_create_new_talent_password.php/?r=missing_user");
    exit;
}

$is_contact   = false;
$is_resource  = false;
$portal_name  = '';
$company_name = '';
$first_name   = '';
$contact_id   = null;

// see if they have ever been on an assignment
    $stmt = $link->prepare(
        "SELECT candidate, candidate_name 
         FROM ic_matches 
         WHERE candidate_email = ? 
         ORDER BY created_at DESC 
         LIMIT 1"
    );
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $is_resource  = true;
        $portal_name  = $row['candidate_name'];
        $first_name   = explode(' ', $portal_name)[0] ?? '';
        $contact_id   = (string)$row['candidate']; // candidate id
        $_SESSION['first_name'] = $first_name;
    }
    $stmt->close();


// Require a candidate (per your original logic)
if (!$is_resource) {
    header("Location: /portal/manatal_talent_portal_signin.php/?r=not_found");
    exit;
}

// ------------------ 3) Generate selector/token safely -------------------

try {
    $selector = bin2hex(random_bytes(8));    // 16 hex chars
    $token    = random_bytes(32);            // 32 raw bytes
} catch (Throwable $e) {
    header("Location: /portal/manatal_create_new_talent_password.php/?r=error");
    exit;
}

// ------------------ 4) Close any open tickets for this contact ----------
$stmt = $link->prepare("
    UPDATE ic_password_reset_tickets
    SET closed = 1, closed_at = NOW(), close_reason = 'new reset ticket created'
    WHERE contact_id = ? AND closed = 0
");
$stmt->bind_param('s', $contact_id);
$stmt->execute();
$stmt->close();

// ------------------ 5) Create a new reset ticket ------------------------
$stmt = $link->prepare("
    INSERT INTO ic_password_reset_tickets
        (contact_email, contact_id, selector, token, created_at, expires_at)
    VALUES
        (?, ?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 3 HOUR))
");

$token_hex = bin2hex($token); // store hex of token (you’ll hash the validator later when verifying)
$stmt->bind_param('ssss', $email, $contact_id, $selector, $token_hex);
$stmt->execute();
$stmt->close();

// ------------------ 6) Build reset URL ---------------------------------
$base   = 'https://' . $_SERVER['SERVER_NAME'];
$params = http_build_query([
    'selector'  => $selector,
    'validator' => bin2hex($token),
]);
$url = "{$base}/portal/manatal_create_talent_portal_password2.php/?{$params}";


// ------------------ 7) Send email (PHPMailer) ---------------------------



// try {
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
				$mail->setFrom('exchange@icreatives.com', 'icreatives accounting');
				$mail->addReplyTo('andreaa@icreatives.com', 'icreatives');

    // Recipients
    $mail->setFrom('exchange@icreatives.com', 'icreatives');
    $mail->addAddress($email);
    $mail->addBCC('stevenc@icreatives.com');
    $mail->addBCC('password_reset@blindemail.com');

    // Content
    $mail->Subject = 'icreatives - Password Reset';
    $message = sprintf(
        '<p>We received a password reset request. If you did not make this request, you can ignore this email.</p>
         <p>Here is your password reset link:</p>
         <p><a href="%1$s">%1$s</a></p>
         <p>Thanks!</p>',
        htmlspecialchars($url, ENT_QUOTES, 'UTF-8')
    );
    $mail->msgHTML($message);

    $mail->send();

    // Redirect to success
    header("Location: /portal/manatal_create_new_talent_password.php/?r=success&user=" . urlencode($email));
    exit;
	/*
} catch (Throwable $e) {
    // Redirect to error (don’t echo; headers must still work)
    header("Location: /portal/manatal_create_new_talent_password.php/?r=error&user=" . urlencode($email));
    exit;
}
*/

?>