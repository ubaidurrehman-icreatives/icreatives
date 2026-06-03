<?php session_start();

 
$contactID = $_SESSION['contactID'];
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
?>

<HTML>
<HEAD>
	<meta charset="utf-8" />
	<?php // require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
</HEAD>
<BODY>

<?php

 print_r($_POST);
 // exit();
// $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/empact/ApplicationResumes';
// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once("/var/www/vhosts/icreatives.com/httpdocs/wp-includes/PHPMailer/PHPMailer.php");
require_once("/var/www/vhosts/icreatives.com/httpdocs/wp-includes/PHPMailer/Exception.php");
require_once("/var/www/vhosts/icreatives.com/httpdocs/wp-includes/PHPMailer/SMTP.php");
require_once("/var/www/vhosts/icreatives.com/httpdocs/wp-includes/class-phpmailer.php");

require "/var/www/vhosts/icreatives.com/httpdocs/mngr/dompdf/autoload.inc.php";
use Dompdf\Dompdf;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use setasign\Fpdi\PdfReader\PdfReader as PdfReaderAlias;

function sendManatalPdfResume(array $formData, string $manatalEmail): bool {
    $html = generateFormattedHtmlResume($formData);

    // Generate PDF in memory
    
    // Check if candidate_id is provided
    if (!empty($formData['candidate_id'])) {
        $candidateId = $formData['candidate_id'];
        $client = new \GuzzleHttp\Client();

        try {

            // Step 1: Fetch candidate resume URL from Manatal
            $response = $client->request('GET', "https://api.manatal.com/open/v3/candidates/$candidateId", [
			'headers' => [
				'Authorization' => 'Token 9f9202437c4d8a7e2f65dd7a5e309feefe6ad1f5',
				'accept' => 'application/json',
			],
			]);

            $data = json_decode($response->getBody(), true);
            $resumeUrl = $data['resume'] ?? '';

            if ($resumeUrl && str_ends_with(strtolower($resumeUrl), '.pdf')) {
                // Step 2: Download original PDF resume
                $originalResume = file_get_contents($resumeUrl);
                file_put_contents('/tmp/original_resume.pdf', $originalResume);

                // Step 3: Generate new resume content (page 2)
                $dompdf = new Dompdf();
                $dompdf->loadHtml(generateFormattedHtmlResume($formData));
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                file_put_contents('/tmp/page2.pdf', $dompdf->output());

                // Step 4: Merge using FPDI
                $pdf = new Fpdi();
                $pageCount = $pdf->setSourceFile('/tmp/original_resume.pdf');
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tpl = $pdf->importPage($i);
                    $pdf->addPage();
                    $pdf->useTemplate($tpl);
                }
                $tpl2 = $pdf->setSourceFile('/tmp/page2.pdf');
                $pdf->addPage();
                $pdf->useTemplate($pdf->importPage(1));
                $pdf->Output('/tmp/final_resume.pdf', 'F');

                // Attach merged PDF
                $mail->addAttachment('/tmp/final_resume.pdf', 'resume.pdf');
                // Step 5: Upload merged resume back to Manatal
 // Step 5: Upload merged resume back to Manatal (as base64 JSON)
				try {
					$base64Resume = base64_encode(file_get_contents('/tmp/final_resume.pdf'));

					$uploadResponse = $client->request('POST', "https://api.manatal.com/open/v3/candidates/$candidateId/resume/", [
						'headers' => [
						'Authorization' => 'Token 9f9202437c4d8a7e2f65dd7a5e309feefe6ad1f5',
						'accept' => 'application/json',
						'content-type' => 'application/json',
					],
					'body' => json_encode([
						'resume_file' => $base64Resume
					])
					]);

					error_log("✅ Resume successfully uploaded to Manatal.");
				} catch (Exception $uploadEx) {
					error_log("❌ Failed to upload resume to Manatal: " . $uploadEx->getMessage());
				}

                return $mail->send();
            }
        } catch (Exception $e) {
            error_log("Resume fetch/merge failed: " . $e->getMessage());
        }
    }

    // Fallback: generate just the one-page Dompdf resume
$dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $pdfContent = $dompdf->output(); // Binary content

    // Email the PDF
    $mail = new PHPMailer(true);
    try {
		$mail = new PHPMailer();
	
				$mail->IsSMTP(); // telling the class to use SMTP
				$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
				// 1 = errors and messages
				// 2 = messages only
				$mail->SMTPAuth   = true;                  // enable SMTP authentication
				$mail->Host       = "smtp.1and1.com"; // sets the SMTP server
				$mail->Username   = "exchange@icreatives.com"; // SMTP account username
				$mail->Password   = "Call1888icreate!";        // SMTP account password
				$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
				$mail->Port       = 587;  // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above		
				$mail->isHTML(true);                             // Set email format to HTML
				$mail->CharSet = "UTF-8";
				// DKIM Setup
				$mail->DKIM_domain = 'icreatives.com';
				$mail->DKIM_selector = 'performa';
				$mail->DKIM_private = $_SERVER['DOCUMENT_ROOT'] . '/dkim_keys/ionos-icreatives-dkim-private-key.key'; // Replace with actual path
				$mail->DKIM_passphrase = ''; // If your key has a passphrase, enter it here
				$mail->DKIM_identity = 'exchange@icreatives.com'; // Typically same as From

							// $mail->AddReplyTo($from);
							
        // You can switch to SMTP here for reliability
		// Set sender and recipient addresses
		$mail->addBCC('stevenc@icreatives.com');
		$mail->setFrom($_POST['recruiter_email'],$_POST['recruiter_name']); // fix once manatal configures our email
        $mail->addAddress($manatalEmail);
		$mail->addBCC($_POST['recruiter_email'],$_POST['recruiter_name']);
        $mail->Subject = 'Candidate Resume Upload';
        $mail->Body = 'Attached is the candidate resume.';

  // Attach PDF in memory
        $mail->addStringAttachment($pdfContent, 'resume.pdf', 'base64', 'application/pdf');

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: " . $mail->ErrorInfo);
        return false;
    }
}

function generateFormattedHtmlResume(array $formData): string {
    $skillsGrouped = [];
    $infoHtml = [];

    // Basic info extraction
 // Basic info extraction
$name = $formData['full_name'] ?? '';
if (empty($name)) {
    $name = trim(($formData['first_name'] ?? '') . ' ' . ($formData['last_name'] ?? ''));
}
if (empty($name)) {
    $name = $formData['name'] ?? '';
}
    $email = $formData['email'] ?? '';
    $phone = $formData['phone_number'] ?? '';
    $address = $formData['address'] ?? '';
    $postal = $formData['postalcode'] ?? '';
    $company = $formData['current_company'] ?? '';
    $position = $formData['current_position'] ?? '';
    $degree = $formData['latest_degree'] ?? '';
    $university = $formData['latest_university'] ?? '';

    // Candidate details section
    $infoHtml[] = "<h2>$name</h2>";
    $infoHtml[] = "<p>$email<br>$phone<br>$address" . ($postal ? ", $postal" : "") . "</p>";

    if ($company)     $infoHtml[] = "<p><strong>Current Company:</strong> $company</p>";
    if ($position)    $infoHtml[] = "<p><strong>Current Position:</strong> $position</p>";
    if ($degree)      $infoHtml[] = "<p><strong>Latest Degree:</strong> $degree</p>";
    if ($university)  $infoHtml[] = "<p><strong>Latest University:</strong> $university</p>";

    // Group skill codes
	//
// Collect numeric skill values using new 0–100 scale only
	foreach ($formData as $key => $value) {
    if (!preg_match('/^([A-Z]{3})\\|(.*)$/', $key, $m)) continue;
    if ($value === '' || $value === null || !is_numeric($value)) continue;

    $prefix = $m[1];
    $skill  = str_replace('_', ' ', $m[2]);
    $score  = (int)$value;

    // Only accept new 0–100 range
    if ($score >= 0 && $score <= 100) {
        $skills_by_category[$prefix][] = ['name' => $skill, 'score' => $score];
    }
	}

	//

    if (!empty($skillsGrouped)) {
        $infoHtml[] = "<h3>Skills</h3>";
        foreach ($skillsGrouped as $group => $skills) {
            $infoHtml[] = "<strong>$group:</strong><ul>";
            foreach ($skills as $skill) {
                $infoHtml[] = "<li>" . htmlspecialchars($skill) . "</li>";
            }
            $infoHtml[] = "</ul>";
        }
    }

    // Wrap in minimal HTML
    $html = "<!DOCTYPE html><html><head><meta charset='UTF-8'><style>
        body { font-family: sans-serif; line-height: 1.4; }
        h2 { margin-bottom: 0; }
        ul { margin-top: 0; }
    </style></head><body>";
    $html .= implode("\n", $infoHtml);
    $html .= "</body></html>";
	
	$page2 = "<div style='page-break-before: always; font-family: sans-serif;'>
    <h2>Skill Proficiency</h2>";
$skill_categories = [
    'GDD' => 'Graphic Design Disciplines',
    'GDS' => 'Graphic Design Software',
    '3DS' => '3D Software',
    'PRS' => 'Presentation Software',
    'UIX' => 'UI & UX Software',
    'ADV' => 'Advanced Web',
    'VID' => 'Video Editing',
    'ASR' => 'Account & Studio Roles',
    'MMS' => 'Marketing Skills',
    'AMS' => 'Account Management Skills',
    'MBS' => 'Media Buying Skills',
    'LNG' => 'Languages',
    'COS' => 'Operating Systems',
];
$skills_by_category = [];

// Collect numeric skill values between 10 and 20
foreach ($formData as $key => $value) {
    if (preg_match('/^([A-Z]{3})\\|(.*)$/', $key, $matches) && is_numeric($value)) {
        $prefix = $matches[1];
        $skill = str_replace('_', ' ', $matches[2]);
        $score = (int) $value;
        if ($score >= 0 && $score <= 100) {
            $skills_by_category[$prefix][] = ['name' => $skill, 'score' => $score];
        }
    }
}

// Output each category in defined order
foreach ($skill_categories as $prefix => $label) {
    if (empty($skills_by_category[$prefix])) continue;

    $page2 .= "<h3>$label</h3>";
    foreach ($skills_by_category[$prefix] as $item) {
        $skill   = htmlspecialchars($item['name']);
        $percent = max(0, min(100, (int)$item['score'])); // 0–100 only

        $page2 .= "<div style='margin-bottom:5px;'>$skill</div>";
        $page2 .= "<div style='width:100%; background:#eee; height:10px; border-radius:5px; overflow:hidden;'>
                     <div style='width:{$percent}%; background:#b22625; height:10px;'></div>
                   </div><br>";
    }
}

$page2 .= "</div>";
$html .= $page2;


    return $html;
}

// === EXAMPLE USAGE ===
$formData = $_POST; // Replace with test array if needed
$manatalEmail = 'icreatives+candidate+QX86WV4Y@mail.manatal.com';


if (sendManatalPdfResume($formData, $manatalEmail)) {
    echo "PDF resume sent to Manatal!";
} else {
    echo "Failed to send resume.";
}

if ($uploadOk !== 0) {
?>

<div style="text-align: center;"><img class="size-full wp-image-7060" src="/wp-content/uploads/2021/01/cropped_jumpers.jpg" alt="Thank you" width="500" height="515" /></div>
<div style="text-align: center; font-size: 30px; color: #b22625;"><b>You did it!</b></div>
<div style="text-align: center; font-size: 30px; color: #b22625; padding: 5px 0 25px 0;"><b>Thank you. Fingers crossed on that position!</b></div>
 
</body>
</html>
<?php } ?>