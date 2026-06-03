<?php
session_start();
$candidate_arr = $_SESSION['candidate_arr'];
?>
<HTML>
<HEAD>
	<meta charset="utf-8" />
</HEAD>
<BODY>

<?php

global $html;
global $page2;

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';
$conn = db();

require_once dirname(__DIR__) . '/PHPMailer/PHPMailer.php';
require_once dirname(__DIR__) . '/PHPMailer/Exception.php';
require_once dirname(__DIR__) . '/PHPMailer/SMTP.php';
require_once dirname(__DIR__) . '/mngr/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use GuzzleHttp\Client;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use setasign\Fpdi\PdfReader\PdfReader as PdfReaderAlias;

function sendManatalPdfResume(array $formData, string $manatalEmail): bool {
	global $html;
	global $page2;
	global $candidate_arr;

    $html = generateFormattedHtmlResume($formData);

    // Generate PDF in memory
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $pdfContent = $dompdf->output(); // Binary content

    // Email the PDF
    $mail = new PHPMailer(true);
    try {
		$mail = new PHPMailer();
		$mail->IsSMTP();
		// $mail->SMTPDebug = 2;
		$mail->SMTPAuth   = true;
		$mail->Host       = "smtp.1and1.com";
		$mail->Username   = "exchange@icreatives.com";
		$mail->Password   = "Call1888icreate!";
		$mail->SMTPSecure = 'tls';
		$mail->Port       = 587;
		$mail->isHTML(true);
		$mail->CharSet = "UTF-8";
		// DKIM Setup
		$mail->DKIM_domain = 'icreatives.com';
		$mail->DKIM_selector = 'performa';
		$mail->DKIM_private = $_SERVER['DOCUMENT_ROOT'] . '/dkim_keys/ionos-icreatives-dkim-private-key.key';
		$mail->DKIM_passphrase = '';
		$mail->DKIM_identity = 'exchange@icreatives.com';

		$resumeName = str_replace(" ","-",$formData['full_name']).".pdf";
		$mail->addBCC('stevenc@icreatives.com');
		$mail->addBCC('nataliap@icreatives.com');
		$mail->setFrom($_POST['recruiter_email'],$_POST['recruiter_name']);
        $mail->addAddress($manatalEmail);
		$mail->addBCC($_POST['recruiter_email'],$_POST['recruiter_name']);
        $mail->Subject = 'Candidate Resume Upload';
		$mail->Body =' ';

        // Attach PDF in memory
        $mail->addStringAttachment($pdfContent, $resumeName, 'base64', 'application/pdf');
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
    global $html;
    global $page2;
    global $candidate_arr;

    // Basic info
    $full_name = $formData['full_name'] ?? '';
    $email     = $formData['email'] ?? '';
    $phone     = $formData['phone_number'] ?? '';
    $address   = $formData['address'] ?? '';
    $postal    = $formData['postalcode'] ?? '';
    $company   = $formData['current_company'] ?? '';
    $position  = $formData['current_position'] ?? '';
    $degree    = $formData['latest_degree'] ?? '';
    $university= $formData['latest_university'] ?? '';

    // Candidate details (Page 1)
    $infoHtml[] = "Resume - <h1>".htmlspecialchars($full_name)."</h1>";
    $infoHtml[] = "<p>".htmlspecialchars($email)."<br>".htmlspecialchars($phone)."<br>"
                . htmlspecialchars($address) . ($postal ? ", " . htmlspecialchars($postal) : "") . "</p>";

    if ($company)    $infoHtml[] = "<p><strong>Current Company:</strong> ".htmlspecialchars($company)."</p>";
    if ($position)   $infoHtml[] = "<p><strong>Current Position:</strong> ".htmlspecialchars($position)."</p>";
    if ($degree)     $infoHtml[] = "<p><strong>Latest Degree:</strong> ".htmlspecialchars($degree)."</p>";
    if ($university) $infoHtml[] = "<p><strong>Latest University:</strong> ".htmlspecialchars($university)."</p>";

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
        'DEV' => 'Web Development'
    ];

    // Group non-numeric skill labels (for page 1 list)
 // Build a readable Skills list for Page 1
$skillsGrouped = []; // group => [skill => skill]

foreach ($formData as $key => $value) {
    // Only fields like "GDD|Print_Designer"
    if (strpos($key, '|') === false) continue;

    // Skip reference fields
    if (stripos($key, 'reference') !== false) continue;

    // Split into group + skill
    [$group, $skill] = explode('|', $key, 2);

    // Normalize / clean labels
    $group = strtoupper(trim($group));
    $skillLabel = str_replace('_', ' ', trim($skill));

    // Normalize value for checks
    $v = is_string($value) ? trim($value) : $value;

    // Ignore explicit placeholders / empty strings
    if ($v === '' || in_array($v, ['--Pick one--', '--Select--', '--'], true)) continue;

    // If the field exists and isn't an explicit placeholder, we count it
    // (numeric scores OR checkbox/text both included)
    if (!isset($skillsGrouped[$group])) {
        $skillsGrouped[$group] = [];
    }
    $skillsGrouped[$group][$skillLabel] = $skillLabel; // de-dup by using key
}

if (!empty($skillsGrouped)) {
    $infoHtml[] = "<h3>Skills</h3>";
    foreach ($skillsGrouped as $group => $skills) {
        $label = $skill_categories[$group] ?? $group;
        $infoHtml[] = "<strong>" . htmlspecialchars($label) . ":</strong><ul>";
        foreach ($skills as $skillLabel) {
            $infoHtml[] = "<li>" . htmlspecialchars($skillLabel) . "</li>";
        }
        $infoHtml[] = "</ul>";
    }
}

    // Page 1 HTML
    $html = "<!DOCTYPE html><html><head><meta charset='UTF-8'><style>
        body { font-family: Arial, Helvetica, sans-serif; line-height: 1.4; color:#222; margin:24px; }
        h2 { margin-bottom: 0; }
        ul { margin-top: 0; }
    </style></head><body>";
    $html .= implode("\n", $infoHtml);
    $html .= "</body></html>";

    // Page 2 styles (with grid for 2-per-line)
    $page2 = "<!DOCTYPE html><html><head><meta charset='UTF-8'>"
        . "<style>
            html,body{height:100%}
            body{
              margin:0;display:flex;justify-content:center;
              background:#f7f7f7;font-family:Arial,Helvetica,sans-serif;color:#222
            }
            .main-container{
              width:100%;max-width:800px;background:rgba(255,255,255,0.9);
              padding:30px;box-shadow:0 8px 24px rgba(0,0,0,0.08);
              box-sizing:border-box;border-radius:10px
            }
            .card{border:2px solid #b22625;border-radius:6px;padding:16px 16px 8px 20px;background:#fff}
            h2{margin:0 0 12px 0}
            h3{margin:10px 0}
            .grid{display:flex;flex-wrap:wrap;justify-content:space-between;gap:14px 0}
            .item{flex:0 0 48%;box-sizing:border-box}
            .label{margin-bottom:6px}
            .bar{
              width:100%;height:12px;border-radius:6px;background:#d7d7d7;
              box-shadow:inset 0 0 0 1px rgba(0,0,0,0.08);overflow:hidden
            }
            .bar-fill{height:100%;background:#b22625;border-radius:6px;transition:width .4s ease}
        </style></head><body>";

    // Begin Page 2 content
    $page2 .= "<div style='padding-top:50px; page-break-before: always; font-family: sans-serif;'>";
    $page2 .= "<div style='width:95%; border: 2px solid #b22625; border-radius: 4px; padding: 10px 10px 10px 20px;'>";
    $page2 .= "<h2>Skill Proficiency: ".htmlspecialchars(trim(strtok($candidate_arr['full_name'] ?? '', ' ')))."</h2>";

    $skills_by_category = [];

    // Collect numeric skill values (assumes values are 0-100 already; if 10-20 scale, adjust mapping here)
    foreach ($formData as $key => $value) {
        if (preg_match('/^([A-Z]{3})\\|(.*)$/', $key, $matches) && is_numeric($value)) {
            $prefix = $matches[1];
            $skill  = str_replace('_', ' ', $matches[2]);
            $score  = (int) $value;
            $skills_by_category[$prefix][] = ['name' => $skill, 'score' => $score];
        }
    }

    // Output each category in defined order
    foreach ($skill_categories as $prefix => $label) {
        if (empty($skills_by_category[$prefix])) continue;

        $page2 .= '<div style="width:90%; padding-top:10px;"><h3>' . htmlspecialchars($label) . '</h3>';


        // ⬇️ Wrap items in .grid to enforce two per line
        $page2 .= '<div class="grid">';

        foreach ($skills_by_category[$prefix] as $item) {
            $skill   = htmlspecialchars($item['name']);
            $score   = (int)$item['score'];
            $percent = max(0, min(100, $score)); // clamp to 0-100

$page2 .= "<div class='item' style='margin-left:10px;'>";
$page2 .=   "<div class='label'>$skill</div>";
$page2 .=   "<div class='bar'><div class='bar-fill' style='width:{$percent}%'></div></div>";
$page2 .= "</div>";

        }

        $page2 .= '</div>'; // close .grid
        $page2 .= '</div>'; // close width wrapper
    }

    $page2 .= "</div></div>";
    $page2 .= "</body></html>";

    return $html;
}

// === EXAMPLE USAGE ===
$formData = $_POST; // Replace with test array if needed
$manatalEmail = 'icreatives+candidate@mail.manatal.com';

if (sendManatalPdfResume($formData, $manatalEmail)) {
    // echo "PDF resume sent to Manatal!";
} else {
    echo "Failed to send resume.";
}

// ---------- Store Page 2 HTML ----------
$candidate_id = $_POST['candidate_id'] ?? '';
$candidate_id = mysqli_real_escape_string($conn, $candidate_id); // FIX: escape the correct var
$page2        = mysqli_real_escape_string($conn, $page2);

$stmt = "
    INSERT INTO ic_candidate_self_eval (id, html)
    VALUES ('$candidate_id', '$page2')
    ON DUPLICATE KEY UPDATE html = VALUES(html)
";

if (mysqli_query($conn, $stmt)) {
   // echo "✅ Inserted or updated successfully.";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}

mysqli_close($conn);

// Success UI (optional)
if (!isset($uploadOk)) { $uploadOk = 1; }
if ($uploadOk !== 0) {
?>
<div style="text-align: center;"><img class="size-full wp-image-7060" src="/longform/images/cropped_jumpers.png" alt="Thank you" width="500" height="515" /></div>
<div style="text-align: center; font-size: 30px; color: #b22625;"><b>You did it!</b></div>
<div style="text-align: center; font-size: 30px; color: #b22625; padding: 5px 0 25px 0;"><b>Thank you. Fingers crossed on that position!</b></div>
<?php } ?>

</body>
</html>
