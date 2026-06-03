<?php
session_start();

?>
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

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// ------------------ Input & basic validation ----------------------------
$selector  = $_REQUEST['selector']  ?? null;
$validator = $_REQUEST['validator'] ?? null;     // hex token from URL
$password  = $_REQUEST['password']  ?? null;
$confirm   = $_REQUEST['confirm']   ?? null;

if (!$selector || !$validator) {
    header("Location: /portal/manatal_create_new_talent_password.php?r=e");
    exit;
}
if (!$password || !$confirm) {
    header("Location: /portal/manatal_create_talent_portal_password2.php?selector={$selector}&validator={$validator}&r=f");
    exit;
}
if ($password !== $confirm) {
    header("Location: /portal/manatal_create_talent_portal_password2.php?selector={$selector}&validator={$validator}&r=m");
    exit;
}
if (!preg_match('/(?=.*[\W0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}/', $password)) {
    header("Location: /portal/manatal_create_talent_portal_password2.php?selector={$selector}&validator={$validator}&r=r");
    exit;
}

// ------------------ Fetch ticket (unexpired & open) ---------------------
$stmt = $link->prepare("
    SELECT id, token, contact_email, contact_id
    FROM ic_password_reset_tickets
    WHERE selector = ? AND closed = 0 AND expires_at >= NOW()
    LIMIT 1
");
$stmt->bind_param('s', $selector);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$ticket) {
    header("Location: /portal/manatal_create_new_talent_password.php?r=e");
    exit;
}

$stored_token_hex = $ticket['token'];        // stored as HEX of random token
$userEmail        = $ticket['contact_email'];
$contactID        = (string)$ticket['contact_id'];
$ticketID         = (int)$ticket['id'];
$_SESSION['user'] = $userEmail;

// ------------------ Verify validator matches stored token ---------------
if (!hash_equals($stored_token_hex, $validator)) {
    header("Location: /portal/manatal_create_new_talent_password.php?r=e");
    exit;
}

// ------------------ Update Manatal candidate custom field ---------------
try {
    $client = new \GuzzleHttp\Client([
        'timeout' => 5.0,
        'connect_timeout' => 3.0,
        'http_errors' => false,
    ]);

    // Fetch candidate for full_name & existing custom_fields
    $resp = $client->request('GET', "https://api.manatal.com/open/v3/candidates/{$contactID}/", [
        'headers' => [
            'Authorization' => $token,
            'accept' => 'application/json',
        ],
    ]);

    if ($resp->getStatusCode() !== 200) {
        header("Location: /portal/manatal_create_new_talent_password.php?selector={$selector}&validator={$validator}&r=e");
        exit;
    }

    $candidate = json_decode((string)$resp->getBody(), true) ?: [];
    $full_name = $candidate['full_name'] ?? '';
    $custom    = $candidate['custom_fields'] ?? [];

    // ***** plaintext per your request *****
    $custom['password'] = $password;

    // Ensure custom_fields serializes to an object if empty
    if (empty($custom)) { $custom = (object)[]; }

    $resp = $client->request('PATCH', "https://api.manatal.com/open/v3/candidates/{$contactID}/", [
        'json' => [
            'custom_fields' => $custom,
            'full_name'     => $full_name,
        ],
        'headers' => [
            'Authorization' => $token,
            'accept'        => 'application/json',
        ],
    ]);

    if ($resp->getStatusCode() >= 400) {
        header("Location: /portal/manatal_create_new_talent_password.php?selector={$selector}&validator={$validator}&r=e");
        exit;
    }
} catch (\Throwable $e) {
    // error_log("Manatal error: " . $e->getMessage());
    header("Location: /portal/manatal_create_new_talent_password.php?selector={$selector}&validator={$validator}&r=e");
    exit;
}

// ------------------ Close the reset ticket ------------------------------
$stmt = $link->prepare("
    UPDATE ic_password_reset_tickets
    SET closed = 1, closed_at = NOW(), close_reason = 'password created successfully'
    WHERE id = ? LIMIT 1
");
$stmt->bind_param('i', $ticketID);
$stmt->execute();
$stmt->close();

// ------------------ Log the user out cleanly ----------------------------
session_regenerate_id(true);
session_destroy();

// ------------------ Render success page ---------------------------------
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Password Created</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php require_once  dirname(__DIR__) . '/db/portal-bkgrnd.css'; ?>
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
          <form action="/portal/manatal_talent_portal_signin.php/" method="get" class="w-100 text-center">
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
