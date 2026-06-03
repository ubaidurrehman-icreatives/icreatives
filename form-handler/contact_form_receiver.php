<?php
/* ================================
   icreatives portal form handler
   ================================ */

// --- PHPMailer (namespaced) ---
require_once dirname(__DIR__)."/PHPMailer/PHPMailer.php";
require_once dirname(__DIR__)."/PHPMailer/Exception.php";
require_once dirname(__DIR__)."/PHPMailer/SMTP.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// --- Error visibility (TURN OFF IN PROD) ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ---------- INPUTS ----------
$job          = strtoupper($_REQUEST['job'] ?? '');
$femail       = strtolower(trim($_REQUEST['email'] ?? ''));
$company      = trim($_REQUEST['company'] ?? '');
$worktype     = $_REQUEST['worktype'] ?? '';
$user         = $_REQUEST['user'] ?? 'Customer';
$utm_source   = $_REQUEST['utm_source'] ?? '';
$utm_medium   = $_REQUEST['utm_medium'] ?? '';
$utm_campaign = $_REQUEST['utm_campaign'] ?? '';
$ip_address   = $_SERVER['REMOTE_ADDR'] ?? '';
$host         = $_SERVER['SERVER_NAME'] ?? 'icreatives.com';
$pageurl      = $_POST['pageurl'] ?? '';
$pagetitle    = $_POST['pagetitle'] ?? '';
$referrer     = $_POST['referrer'] ?? '';

// Reject malformed email immediately
if (!filter_var($femail, FILTER_VALIDATE_EMAIL)) {
  header("Location: https://www.icreatives.com/must-be-spam");
  exit;
}

// Extract email domain
$domain = '';
if (($at = strrpos($femail, '@')) !== false) {
  $domain = strtolower(substr($femail, $at + 1));
}

// ---------- CONFIG ----------
$SPAM_REDIRECT = 'https://www.icreatives.com/must-be-spam';

// Public/personal email domains
$publicDomains = [
  'gmail.com','googlemail.com','outlook.com','hotmail.com','live.com','msn.com',
  'yahoo.com','aol.com','icloud.com','me.com','mac.com','mail.com',
  'protonmail.com','pm.me','zoho.com','gmx.com','yandex.com','qq.com',
  'rr.com','rcn.com'
];

// Hard spam fragments
$badEmailFrags = [
  'boost','cashbenefit','postai.com','vwealth.com','course-fitness.com',
  'reachoutcapital.com','reachoutcapital.biz','yournewsecretweapon.com',
  '.xyz','.ru','.cz'
];

$badJobWords = [
  'WORKING CAPITAL','BACKPACK','CREDIT','FUNDING','PORN',
  'CLEANING','CRYPTO',' SEO ','COINBOOM','SEX'
];

$badContentWords = [
  'porn','sex','viagra','casino','gambling','escort','adult','nude',
  'crypto','loan','funding','backlink','seo service',
  'guest post','link building','buy backlinks','paid links',
  'increase domain authority','카지노','poker'
];

// Candidate/business intent keywords
$candidateKeywords = [
  'resume', 'résumé', 'cv', 'portfolio', 'reel', 'demo reel',
  'looking for work', 'seeking employment', 'seeking opportunity',
  'job seeker', 'apply', 'application', 'available for work',
  'open to work', 'need a job', 'employment opportunity'
];

$businessKeywords = [
  'looking to hire', 'need to hire', 'hiring', 'open role', 'open position',
  'need a designer', 'need creative talent', 'staffing help',
  'temp to hire', 'freelance support', 'contract role', 'fill a role',
  'need talent', 'recruiting help', 'build our team'
];

// ---------- SPAM FILTER (hard block) ----------
$blocked = false;

foreach ($badEmailFrags as $frag) {
  if (stripos($femail, $frag) !== false) {
    $blocked = true;
    break;
  }
}

if (!$blocked && stripos($company, 'google') !== false) {
  $blocked = true;
}

if (!$blocked) {
  foreach ($badJobWords as $w) {
    if (strpos($job, $w) !== false) {
      $blocked = true;
      break;
    }
  }
}

if (!$blocked) {
  $contentToScan = strtolower(
    ($_REQUEST['wholename'] ?? '') . ' ' .
    ($_REQUEST['title'] ?? '') . ' ' .
    ($_REQUEST['company'] ?? '') . ' ' .
    ($_REQUEST['job'] ?? '') . ' ' .
    ($_REQUEST['moreval'] ?? '')
  );

  foreach ($badContentWords as $w) {
    if (strpos($contentToScan, $w) !== false) {
      $blocked = true;
      break;
    }
  }
}

if ($blocked) {
  header("Location: {$SPAM_REDIRECT}");
  exit;
}

// ---------- LEAD CLASSIFICATION ----------
$isPublicEmail = ($domain && in_array($domain, $publicDomains, true));

$titleField   = strtolower(trim($_REQUEST['title'] ?? ''));
$jobField     = strtolower(trim($_REQUEST['job'] ?? ''));
$moreField    = strtolower(trim($_REQUEST['moreval'] ?? ''));
$sourceField  = strtolower(trim($_REQUEST['recruitesource'] ?? ''));
$combinedText = $titleField . ' ' . $jobField . ' ' . $moreField . ' ' . $sourceField;

$candidateScore = 0;
$businessScore  = 0;

// Main rule: public email = candidate
if ($isPublicEmail) {
  $candidateScore += 5;
} else {
  $businessScore += 3;
}

// Candidate intent keywords can override company-domain assumptions
foreach ($candidateKeywords as $kw) {
  if (strpos($combinedText, $kw) !== false) {
    $candidateScore += 2;
  }
}

// Business intent keywords
foreach ($businessKeywords as $kw) {
  if (strpos($combinedText, $kw) !== false) {
    $businessScore += 2;
  }
}

// Final decision: ties go to candidate, per your preference
$isCandidateLead = ($candidateScore >= $businessScore);

// ---------- MAP / SANITIZE ----------
$firstname      = $_REQUEST['firstname'] ?? '';
$lastname       = isset($_REQUEST['wholename']) ? strip_tags($_REQUEST['wholename']) : '';
$email          = isset($_REQUEST['email']) ? strip_tags($_REQUEST['email']) : '';
$phone          = isset($_REQUEST['phonenumber']) ? strip_tags($_REQUEST['phonenumber']) : '';
$comment        = isset($_REQUEST['job']) ? strip_tags($_REQUEST['job']) : '';
$worktype       = isset($_REQUEST['worktype']) ? strip_tags($_REQUEST['worktype']) : '';
$positionInfo   = $_REQUEST['recruitesource'] ?? '';
$temporary      = $_REQUEST['worktemporary'] ?? '';
$fulltime       = $_REQUEST['workposition'] ?? '';
$contract       = $_REQUEST['contractpositions'] ?? '';
$s_more         = isset($_REQUEST['moreval']) ? str_replace(";", "<br>", $_REQUEST['moreval']) : '';

// ---------- EMAIL CONTENT ----------
$subject        = "++ Contact Us ++ " . date("YmdHis");
$subjectconfirm = "ACCEPT LEAD " . date("YmdHis");
$WebLinkBase    = "https://{$host}/sales?VarChar=";

$message = "
<html><head><title>++ Client Contact Us ++</title></head><body>
<p>Hi,<br>Please find the information below.<br><br>
Name: ".($_REQUEST['wholename'] ?? '')."<br>
Title: ".($_REQUEST['title'] ?? '')."<br>
Phone: ".($_REQUEST['phonenumber'] ?? '')."<br>
Company: ".($_REQUEST['company'] ?? '')."<br>
City: ".($_REQUEST['city'] ?? '')."<br>
Image: ".($_REQUEST['refertoimage'] ?? '')."<br>
Email: ".($_REQUEST['email'] ?? '')."<br>
Source: ".($_REQUEST['recruitesource'] ?? '')."<br>
Page URL: ".($_POST['pageurl'] ?? '')."<br>
Page Title: ".($_POST['pagetitle'] ?? '')."<br>
Referrer: ".($_POST['referrer'] ?? '')."<br>
Industry: ".($_REQUEST['industry'] ?? '')."<br>
utm_source: {$utm_source}<br>
utm_medium: {$utm_medium}<br>
utm_campaign: {$utm_campaign}<br>
IP Address: {$ip_address}<br>
{$s_more}<br>
Comment: ".($_REQUEST['job'] ?? '')."<br><br>
Thank you,<br>the icreative team
</p></body></html>";

// ---------- MAILER FACTORY ----------
$makeMailer = function(): PHPMailer {
  $m = new PHPMailer(true);
  $m->isSMTP();
  $m->SMTPAuth   = true;
  $m->Host       = "smtp.1and1.com";
  $m->Username   = "exchange@icreatives.co";
  $m->Password   = "Call1888icreate!";
  $m->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $m->Port       = 587;
  $m->setFrom('exchange@icreatives.co', 'Contact Form');
  $m->addReplyTo('exchange@icreatives.co', 'icreatives');

  $dkimKey = $_SERVER['DOCUMENT_ROOT'] . '/dkim_keys/ionos-icreatives-co-dkim-private-key.key';
  if (is_readable($dkimKey)) {
    $m->DKIM_domain     = 'icreatives.co';
    $m->DKIM_selector   = 'performa';
    $m->DKIM_private    = $dkimKey;
    $m->DKIM_passphrase = '';
    $m->DKIM_identity   = 'exchange@icreatives.co';
  }

  return $m;
};

// ---------- CANDIDATE EMAIL ROUTE ----------
if ($isCandidateLead) {
  try {
    $mail = $makeMailer();
    $mail->Subject = "++ Candidate Contact ++ " . date("YmdHis");
    $mail->msgHTML($message);
    $mail->addAddress("candidate_form@icreatives.com", "Candidate Form");
    $mail->send();
  } catch (Exception $e) {
    // Optional: error_log("PHPMailer candidate error: ".$e->getMessage());
  }

  if ($user === "Talent") {
    header("Location: https://www.icreatives.com/thank-you-talent");
  } else {
    header("Location: https://www.icreatives.com/thank-you");
  }
  exit;
}

// ---------- DB CONNECTION ----------
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once __DIR__ . '/../db/db.php';
$link = db();

// ---------- INSERT ----------
$stmt = $link->prepare("
  INSERT INTO ic_contact_form
  (LogDate, FirstName, lastname, Company, email, Phone, Comment, worktype, PositionInfo, Temporary, FullTime, Contract, Recruiter, ip_address, url, pagetitle, referrer)
  VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'OPEN', ?, ?, ?, ?)
");

$stmt->bind_param(
  "sssssssssssssss",
  $firstname,
  $lastname,
  $company,
  $email,
  $phone,
  $comment,
  $worktype,
  $positionInfo,
  $temporary,
  $fulltime,
  $contract,
  $ip_address,
  $pageurl,
  $pagetitle,
  $referrer
);

$stmt->execute();
$stmt->close();
$form_id = (int)($link->insert_id ?? 0);

// ---------- RECENT LIST ----------
$List = "";
$resRecent = $link->query("SELECT recruiter, lastname, Company FROM ic_contact_form WHERE recruiter <> 'SPAM' ORDER BY Number DESC LIMIT 3");
while ($r = $resRecent->fetch_assoc()) {
  $List .= $r['recruiter'].' '.$r['Company']."<br>";
}

// ---------- SALES RECIPIENTS ----------
$resSales = $link->query("SELECT name, email, mobile FROM ic_sales WHERE active = 'Y'");

// ---------- SEND CONFIRM TO EACH ACTIVE SALES CONTACT ----------
while ($row = $resSales->fetch_assoc()) {
  try {
    $webLink = $WebLinkBase . rawurlencode($row['name']."-".$form_id."-".$row['email']);

    $ConfirmMessage = "
    <html><head><title>++ ACCEPT LEAD ++</title></head><body>
    {$List}<br>click below to accept<br>
    <a href=\"{$webLink}\">{$webLink}</a><br><br>
    Name: ".($_REQUEST['wholename'] ?? '')."<br>
    Title: ".($_REQUEST['title'] ?? '')."<br>
    Company: ".($_REQUEST['company'] ?? '')."<br>
    Email: ".($_REQUEST['email'] ?? '')."<br>
    Work Type: ".($_REQUEST['worktype'] ?? '')."<br>
    Phone: ".($_REQUEST['phonenumber'] ?? '')."<br>
    Page URL: ".($_POST['pageurl'] ?? '')."<br>
    Page Title: ".($_POST['pagetitle'] ?? '')."<br>
    Referrer: ".($_POST['referrer'] ?? '')."<br>
    utm_source: {$utm_source}<br>
    utm_medium: {$utm_medium}<br>
    utm_campaign: {$utm_campaign}<br>
    Comment: ".($_REQUEST['job'] ?? '')."<br><br>
    </body></html>";

    $mail = $makeMailer();
    $mail->Subject = $subjectconfirm;
    $mail->msgHTML($ConfirmMessage);

    if (!empty($row['email'])) {
      $mail->addAddress($row['email'], $row['name']);
    }

    if (!empty($row['mobile']) && filter_var($row['mobile'], FILTER_VALIDATE_EMAIL)) {
      $mail->addAddress($row['mobile'], $row['name']);
    }

    $mail->send();
  } catch (Exception $e) {
    // Optional: error_log("PHPMailer sales error: ".$e->getMessage());
  }
}

// ---------- SEND COPY TO contact_form@ ----------
try {
  $mail = $makeMailer();
  $mail->Subject = $subject;
  $mail->msgHTML($message);
  $mail->addAddress("contact_form@icreatives.co", "Contact Form");
  $mail->send();
} catch (Exception $e) {
  // Optional: error_log("PHPMailer cf error: ".$e->getMessage());
}

// ---------- FINAL REDIRECT ----------
if ($user === "Talent") {
  header("Location: https://www.icreatives.com/thank-you-talent");
} else {
  header("Location: https://www.icreatives.com/thank-you");
}
exit;