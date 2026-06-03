<?php
 session_start();

 $candidate_arr = $_SESSION['candidate_arr'];
?>
<HTML>
<HEAD>
	<meta charset="utf-8" />
	 <?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
</HEAD>
<BODY>

<?php
require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';
$conn = db();     

global $html;
global $page2;

 // print_r($_POST);
 // exit();
// $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/empact/ApplicationResumes';
// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

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
	global 	$candidate_arr;
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
				$mail->IsSMTP(); // telling the class to use SMTP
				// $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
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
		// Set sender and recipient addresses
			$candidate_arr = $_SESSION['candidate_arr'];
		$resumeName = str_replace(" ","-",$candidate_arr['full_name']).".pdf";
		$mail->addBCC('stevenc@icreatives.com');
		$mail->addBCC('nataliap@icreatives.com');
		$mail->setFrom($_POST['recruiter_email'],$_POST['recruiter_name']); // fix once manatal configures our email
        $mail->addAddress($manatalEmail);
		$mail->addBCC($_POST['recruiter_email'],$_POST['recruiter_name']);
        $mail->Subject = 'Candidate Resume Upload';
		$mail->Body =' ';
		/*$mail->Body = 'A candidate filled out our longform, The attached document was sent to Manatal for prossesing skills.<p />
		               Once you are notified by Manatal by email the processing is complete, please "View Profile" 
					   and merge with the existing Candidate by clicking duplicate at the top of the screen. <p />
					   Be sure to keep the old record. If you make a mistake you will need to resave the Match assignment information for the new Candidate.';
		*/
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
global  $candidate_arr;
    // Basic info extraction
 // Basic info extraction

	$candidate_arr = $_SESSION['candidate_arr'];
    $full_name= $formData['full_name'] ?? '';
    $email = $formData['email'] ?? '';
    $phone = $formData['phone_number'] ?? '';
    $address = $formData['address'] ?? '';
    $postal = $formData['postalcode'] ?? '';
    $company = $formData['current_company'] ?? '';
    $position = $formData['current_position'] ?? '';
    $degree = $formData['latest_degree'] ?? '';
    $university = $formData['latest_university'] ?? '';

    // Candidate details section
    $infoHtml[] = "Resume - <h1>$full_name</h1>";
    $infoHtml[] = "<p>$email<br>$phone<br>$address" . ($postal ? ", $postal" : "") . "</p>";

    if ($company)     $infoHtml[] = "<p><strong>Current Company:</strong> $company</p>";
    if ($position)    $infoHtml[] = "<p><strong>Current Position:</strong> $position</p>";
    if ($degree)      $infoHtml[] = "<p><strong>Latest Degree:</strong> $degree</p>";
    if ($university)  $infoHtml[] = "<p><strong>Latest University:</strong> $university</p>";
	
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
    'DEV' => 'Web Development' // ✅ <-- add this
];


    // Group skill codes
    foreach ($formData as $key => $value) {
        if (empty($value)) continue;
        if (stripos($key, 'reference') !== false) continue;
        if (in_array($value, ['--Pick one--', '--Select--', '--'])) continue;

        if (strpos($key, '|') !== false) {
            [$group, $skill] = explode('|', $key, 2);
            $skillsGrouped[$group][] = str_replace('_', ' ', $skill);
        }
    }

    if (!empty($skillsGrouped)) {
        $infoHtml[] = "<h3>Skills</h3>";
        foreach ($skillsGrouped as $group => $skills) {
            $label = $skill_categories[$group] ?? $group;
			$infoHtml[] = "<strong>$label:</strong><ul>";
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
	
	$page2 = "<!DOCTYPE html><html><head><meta charset='UTF-8'><style>
        body { font-family: sans-serif; line-height: 1.4; }
        h2 { margin-bottom: 0; }
        ul { margin-top: 0; }
    </style></head><body>";

	
	$page2 .= "<div style='padding-top:50px; page-break-before: always; font-family: sans-serif;'>";
	$page2 .= "<div style='width:95%; border: 2px solid #b22625; border-radius: 4px; padding: 10px 10px 10px 20px;'>";
    $page2 .= "<h2>Skill Proficiency: ".trim(strtok($candidate_arr['full_name'], ' '))."</h2>";

$skills_by_category = [];

// Collect numeric skill values between 9 and 30
foreach ($formData as $key => $value) {
    if (preg_match('/^([A-Z]{3})\\|(.*)$/', $key, $matches) && is_numeric($value)) {
        $prefix = $matches[1];
        $skill = str_replace('_', ' ', $matches[2]);
        $score = (int) $value;
        if ($score >= 9 && $score <= 30) {
            $skills_by_category[$prefix][] = ['name' => $skill, 'score' => $score];
        }
    }
}

// Output each category in defined order
foreach ($skill_categories as $prefix => $label) {
    if (!isset($skills_by_category[$prefix])) continue;

    $page2 .= '<div style="width:90%;"><h3>' . $label . '</h3>';
    $page2 .= '<div style="display: flex; flex-wrap: wrap; justify-content: space-between;">';

    foreach ($skills_by_category[$prefix] as $item) {
        $skill = htmlspecialchars($item['name']);
        $score = $item['score'];
        $percent = (($score - 8) * 4.75); // Scale 10-20 to 0-100

        $page2 .= "<div style='flex: 0 0 48%; margin-bottom: 15px; box-sizing: border-box;'>";
        $page2 .= "<div style='margin-bottom:5px;'>$skill</div>";
        $page2 .= "<div style='width:100%; background:#eee; height:10px; border-radius:5px;'>
            <div style='width:{$percent}%; background:#b22625; height:10px; border-radius:5px;'></div>
        </div></div>";
    }

    $page2 .= '</div></div>'; // Close skill flex container and category div
}
$page2 .= "</div></div></div>";
$page2 .= "</body></html>";

// $html .= $page2;



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

$candidate_id = $_POST['candidate_id'];

$id = mysqli_real_escape_string($conn, $candidate_id);
$page2 = mysqli_real_escape_string($conn, $page2);

$stmt = "
    INSERT INTO ic_candidate_self_eval (id, html)
    VALUES ('$candidate_id', '$page2')
    ON DUPLICATE KEY UPDATE html = VALUES(html)
";

// Run the query
if (mysqli_query($conn, $stmt)) {
   //  echo "✅ Inserted or updated successfully.";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}

// echo "<br>Email = ".$candidate_arr['email'];
// end of storing html in database

// update self eval link:

 $candidate_arr['custom_fields']['link_d'] = "https://".$_SERVER['HTTP_HOST']."/longform/displaylongform.php?CID=" . ($candidate_arr['id'] ?? '');


// echo "Candidate id = ".$candidate_arr['id'];

$client = new \GuzzleHttp\Client([ 'timeout' => 30, 'connect_timeout' => 10]);
		// json_encode($candidate_arr);

		$response = $client->request('PATCH', 'https://api.manatal.com/open/v3/candidates/'.$candidate_arr['id'].'/', [
		'body' => ''.json_encode($candidate_arr).'',
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
		'content-type' => 'application/json',
		],
		]);





// Close the connection
// mysqli_close($conn);









// if ($uploadOk !== 0) {
?>

<div style="text-align: center;"><img class="size-full wp-image-7060" src="/longform/images/cropped_jumpers.jpg" alt="Thank you" width="500" height="515" /></div>
<div style="text-align: center; font-size: 30px; color: #b22625;"><b>You did it!</b></div>
<div style="text-align: center; font-size: 30px; color: #b22625; padding: 5px 0 25px 0;"><b>Thank you. Fingers crossed on that position!</b></div>

</body>
</html>
<?php // } ?>