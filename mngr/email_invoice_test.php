<?php
// echo "server doc root= ".$_SERVER['DOCUMENT_ROOT'];
require_once __DIR__.'/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/PHPMailer.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/Exception.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/PHPMailer/SMTP.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-includes/class-phpmailer.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdf = new Dompdf();
    $url = isset($_POST['url']) ? $_POST['url'] : '';

    if ($url) {
       $htmlContent = file_get_contents($url);
        $pdf->loadHtml($htmlContent);
        $pdf->setPaper('letter', 'portrait');
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->render();

        // Create a PDF string
        $pdfContent = $pdf->output();

        // Create a new PHPMailer instance
        $mailer = new PHPMailer();
        
        // Configure SMTP or other mail settings
		$mail = new PHPMailer();
		$mail->IsSMTP(); // telling the class to use SMTP
		// $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
		// 1 = errors and messages
		// 2 = messages only
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->Host       = "smtp.1and1.com"; // sets the SMTP server
		$mail->Username   = "exchange@icreatives.co"; // SMTP account username
		$mail->Password   = "Call1888icreate!";        // SMTP account password
		$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
		$mail->Port       = 587;  // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above		
		$mail->isHTML(true);                             // Set email format to HTML
		$mail->CharSet = "UTF-8";


        // Set sender and recipient addresses
        $mail->setFrom('stevenc@icreatives.com', 'icreatives');
        $mail->addAddress('steven@cohen.email', 'steven cohen');

        // Set email subject and body
        $mail->Subject = 'Generated PDF';
        $mail->Body = 'Please find the attached PDF.';
        $mail->AltBody = 'Please find the attached PDF.';

        // Add the PDF as an attachment
        $mail->addStringAttachment($pdfContent, 'web_page.pdf', 'base64', 'application/pdf');

        // Send the email
        if ($mail->send()) {
            echo 'Email sent successfully.';
        } else {
            echo 'Email could not be sent.';
        }
    } else {
        $error = 'Please provide a URL.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generate PDF from URL and Send Email</title>
</head>
<body>
    <h1>Generate PDF from URL and Send Email</h1>
    <form method="post">
        <label for="url">Enter URL:</label>
        <input type="url" name="url" required>
        <button type="submit">Generate PDF and Send Email</button>
    </form>
    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
</body>
</html>
