<?php
session_start();

?>
<<html>
<head>

	  <style>
    body {
      background-color: #E9E9E0 !important;
    }
	    div {
      background-color: #E9E9E0 !important;
    }
  </style>
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

<?php 
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
require_once __DIR__ . '/../db/db.php';
$link = db();

// Make mysqli throw exceptions so failures are visible
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// ---- Input -------------------------------------------------------------
$selector  = $_REQUEST['selector']  ?? null;
$validator = $_REQUEST['validator'] ?? null;
$password  = $_REQUEST['password']  ?? null;
$confirm   = $_REQUEST['confirm']   ?? null;

if (!$selector || !$validator) {
    header("Location: /portal/manatal_create_client_password.php/?r=e");
    exit;
}
if (!$password || !$confirm) {
    header("Location: /portal/manatal_create_client_password.php/?selector={$selector}&validator={$validator}&r=f");
    exit;
}
if ($password !== $confirm) {
    header("Location: /portal/manatal_create_client_password.php/?selector={$selector}&validator={$validator}&r=m");
    exit;
}
if (!preg_match('/(?=.*[\W0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}/', $password)) {
    header("Location: /portal/manatal_create_client_password.php/?selector={$selector}&validator={$validator}&r=r");
    exit;
}

// ---- Fetch password reset ticket --------------------------------------
$stmt = $link->prepare("
    SELECT id, selector, token, contact_email, contact_id, sender
    FROM ic_password_reset_tickets
    WHERE selector = ? AND closed = 0 AND expires_at >= NOW()
    LIMIT 1
");
$stmt->bind_param('s', $selector);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$ticket) {
    header("Location: /portal/manatal_create_client_password.php/?r=e");
    exit;
}

// ---- Validate the validator/token (constant-time compare) --------------
$calc = hash('sha256', hex2bin($validator));  // hash(userToken)
if (!hash_equals($ticket['token'], $calc)) {  // compare to stored hash
    header("Location: /portal/manatal_create_client_password.php/?r=e");
    exit;
}

// ---- Prepare values ----------------------------------------------------
$userEmail = trim($ticket['contact_email']);
$contactID = (int)$ticket['contact_id'];
$sender    = $ticket['sender'] ?? '';

// ---- Optional sender existence check (ic_sales) ------------------------
if ($sender) {
    $stmt = $link->prepare("SELECT 1 FROM ic_sales WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $sender);
    $stmt->execute();
    $stmt->store_result();
    $sender_exists = ($stmt->num_rows === 1);
    $stmt->close();
    // If you need to enforce this, uncomment:
    // if (!$sender_exists) { header("Location: /portal/manatal_create_client_password.php/?r=e"); exit; }
}

// ---- Encrypt password (Base64 text -> safe for VARCHAR/TEXT) -----------
$key = "4b14783a4b5848cd43b9fc7e4cf0fb6f";            // consider moving to env/secret config
$cipher = "AES-128-CBC";
$ivlen  = openssl_cipher_iv_length($cipher);
$iv     = openssl_random_pseudo_bytes($ivlen);
$ciphertext_raw = openssl_encrypt($password, $cipher, $key, OPENSSL_RAW_DATA, $iv);
$hmac   = hash_hmac('sha256', $ciphertext_raw, $key, true);
$encrypted_password = base64_encode($iv . $hmac . $ciphertext_raw);

// Optional: ensure sql_safe_updates won't block (harmless if already 0)
$link->query("SET SESSION sql_safe_updates = 0");

// ---- Update contact password -------------------------------------------
$stmt = $link->prepare("UPDATE ic_contacts SET encrypted_password = ? WHERE email = ? LIMIT 1");
$stmt->bind_param('ss', $encrypted_password, $userEmail);
$stmt->execute();
$updated = $stmt->affected_rows; // 1 if changed, 0 if same value or no match
$stmt->close();

if ($updated < 0) { // Should not happen with exceptions enabled, but safe-guard
    header("Location: /portal/manatal_create_client_password.php/?selector={$selector}&validator={$validator}&r=e");
    exit;
}

// ---- Close the ticket ---------------------------------------------------
$now  = date('Y-m-d H:i:s');
$stmt = $link->prepare("
    UPDATE ic_password_reset_tickets
    SET closed = 1, closed_at = ?, close_reason = 'password created successfully'
    WHERE contact_id = ? AND selector = ? LIMIT 1
");
$stmt->bind_param('sis', $now, $contactID, $selector);
$stmt->execute();
$stmt->close();

// ---- Fetch contact/org from Manatal + write notes (timeouts!) ----------
$orgId      = null;
$full_name  = null;
$company    = null;

try {
    $client = new \GuzzleHttp\Client([
        'timeout' => 5.0,
        'connect_timeout' => 3.0,
        'http_errors' => false,
    ]);

    // Contact
    $resp = $client->request('GET', "https://api.manatal.com/open/v3/contacts/{$contactID}/", [
        'headers' => [
            'Authorization' => $token,
            'accept' => 'application/json',
        ],
    ]);
    if ($resp->getStatusCode() === 200) {
        $contact_arr = json_decode((string)$resp->getBody(), true);
        $orgId     = $contact_arr['organization'] ?? null;
        $full_name = $contact_arr['full_name'] ?? '';
    }

    // Organization
    if ($orgId) {
        $resp = $client->request('GET', "https://api.manatal.com/open/v3/organizations/{$orgId}/", [
            'headers' => [
                'Authorization' => $token,
                'accept' => 'application/json',
            ],
        ]);
        if ($resp->getStatusCode() === 200) {
            $company_arr = json_decode((string)$resp->getBody(), true);
            $company = $company_arr['name'] ?? '';
        }
    }

    // If first successful password create, log EULA notes and flag
    $stmt = $link->prepare("
        SELECT 1 FROM ic_password_reset_tickets
        WHERE contact_id = ? AND closed = 1 AND close_reason = 'password created successfully'
        LIMIT 1
    ");
    $stmt->bind_param('i', $contactID);
    $stmt->execute();
    $stmt->store_result();
    $ticket_count = $stmt->num_rows;
    $stmt->close();


    if ($ticket_count >= 1 && $orgId) {
        $termsPath = $_SERVER['DOCUMENT_ROOT'] . '/portal/t_and_c.txt';
        $terms     = is_readable($termsPath) ? file_get_contents($termsPath) : '';
        // keep safe, remove odd chars
        $terms     = preg_replace('/[^a-zA-Z0-9_\-#;&() \r\n]/', ' ', $terms);

        $note = "EULA Contract signed by: {$full_name} for company {$company} on date: " . date('Y-m-d')
              . " - ip address: " . ($_SERVER['REMOTE_ADDR'] ?? '')
              . " terms: " . $terms;

        // Contact note
        $client->request('POST', "https://api.manatal.com/open/v3/contacts/{$contactID}/notes/", [
            'json' => ['info' => $note],
            'headers' => [
                'Authorization' => $token,
                'accept' => 'application/json',
            ],
        ]);

        // Mark contact eulasigned
        $client->request('PATCH', "https://api.manatal.com/open/v3/contacts/{$contactID}/", [
            'json' => ['custom_fields' => ['eulasigned' => true], 'full_name' => $full_name],
            'headers' => [
                'Authorization' => $token,
                'accept' => 'application/json',
            ],
        ]);

        // Org note
        $client->request('POST', "https://api.manatal.com/open/v3/organizations/{$orgId}/notes/", [
            'json' => ['info' => $note],
            'headers' => [
                'Authorization' => $token,
                'accept' => 'application/json',
            ],
        ]);

        // (Optional) ensure org custom_fields stays an object if empty
        // If you need to PATCH org custom_fields based on the earlier fetch, you can:
        if (isset($company_arr) && is_array($company_arr)) {
            $customFields = $company_arr['custom_fields'] ?? [];
            if ($customFields === [] || $customFields === null) {
                $customFields = (object)[]; // becomes {} in JSON
            }
            $client->request('PATCH', "https://api.manatal.com/open/v3/organizations/{$orgId}/", [
                'json' => ['custom_fields' => $customFields, 'external_id' => $company],
                'headers' => [
                    'Authorization' => $token,
                    'accept' => 'application/json',
                ],
            ]);
        }
require_once dirname(__DIR__) . '/PHPMailer/PHPMailer.php';
require_once dirname(__DIR__) . '/PHPMailer/Exception.php';
require_once dirname(__DIR__) . '/PHPMailer/SMTP.php';

        // Email the recruiter (only if sender present)
        if (!empty($sender)) {
            // Use PHPMailer via Composer autoload
             $mail = new PHPMailer(true);
            try {
				/*
                $mail->isSMTP();
                $mail->Host       = 'smtp.1and1.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'exchange@icreatives.co';
                $mail->Password   = 'Call1888icreate!';
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;
                $mail->SMTPAutoTLS = true;
                $mail->Timeout     = 30;
				*/
				
				
				$mail->IsSMTP(); // telling the class to use SMTP
				// $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
				// 1 = errors and messages
				// 2 = messages only
				$mail->SMTPAuth   = true;                  // enable SMTP authentication
				// $mail->Host       = "smtp.1and1.com"; // sets the SMTP server
				$mail->Host       = 'smtp.office365.com';
				$mail->Username   = "exchange@icreatives.com"; // SMTP account username
				$mail->Password   = "Call1888icreate!";        // SMTP account password
				$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `  PHPMailer::ENCRYPTION_SMTPS` encouraged
				$mail->Port       = 587;  // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above		
				$mail->isHTML(true);                             // Set email format to HTML
				$mail->CharSet = "UTF-8";



                $mail->setFrom('exchange@icreatives.com', 'icreatives');
                $mail->addAddress($sender);
                $mail->addBCC('jobcomp@blindemail.com');
                $mail->addBCC('stevenc@icreatives.com');

                $mail->Subject = "icreatives - Customer Signed EULA: {$full_name}";
                $mail->msgHTML(nl2br(htmlentities($note)));
                $mail->send();
            } catch (\Throwable $e) {
                // Log and continue; do not block the flow
                // error_log("Mailer error: " . $e->getMessage());
            }
        }
    }
} catch (\Throwable $e) {
    // Log and continue; do not block the flow
    // error_log("Manatal flow error: " . $e->getMessage());
}

// ---- Log the user out cleanly ------------------------------------------
session_regenerate_id(true);
session_destroy();

// ---- All good; show success page ---------------------------------------
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Password Created</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <style>
    body, div { background-color: #E9E9E0 !important; }
  </style>
  <link rel="stylesheet" href="/portal/bootstrap-4.3.1-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_red.css">
  <link rel="stylesheet" href="/portal/styles.css">
</head>
<body>
  <div style="padding-top: 40px;"></div>
  <div class="container custom">
    <div class="row my-5">
      <div class="col">
        <div class="row" style="margin-top: 200px; margin-bottom:200px;">
          <form action="https://www.icreatives.com/portal-login//" method="get" class="w-100 text-center">
            <input type="hidden" name="user" value="<?php echo htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8'); ?>">
            <h3>Password created successfully.</h3>
            <button type="submit" class="btn btn-primary mt-3">Login here</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

