<style>
  table.inv { table-layout: fixed; width: 100%; border-collapse: collapse; }
  table.inv th, table.inv td { padding: 6px; }
  /* 600px email column */
  td.col-email, th.col-email { width:600px; word-break:break-word; white-space:normal; }

  /* zebra striping for body rows */
  table.inv tbody tr:nth-child(even) { background-color: #f9f9f9; }
  table.inv tbody tr:nth-child(odd)  { background-color: #ffffff; }
  table.inv th { background-color: #e9e9e2; }
</style>
<style>
  table.inv {
    border-collapse: collapse;
    width: 100%;
  }
  table.inv tr:nth-child(even) {
    background-color: #f9f9f9; /* light gray */
  }
  table.inv tr:nth-child(odd) {
    background-color: #ffffff; /* white */
  }
  table.inv th {
    background-color: #e9e9e2; /* header color */
  }
</style>


<script>
function openPopup(url) {
    var width = 800;
    var height = 850;
    var left = (screen.width - width) / 2;
    var top = (screen.height - height) / 2;
    var popup = window.open(url, 'PopupWindow', 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top);
    if (popup) popup.focus();
}
</script>

<?php include 'manatal_header.php'; ?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
echo "<h1>MANATAL SEND INVOICES</h1>";

require_once  dirname(__DIR__) . '/vendor/autoload.php';
// Include your database connection code here
require_once __DIR__ . '/../db/db.php';
$link = db(); 
require_once __DIR__.'/dompdf/autoload.inc.php';

function encrypt_string($plaintext) {
    $key = "4b14783a4b5848cd43b9fc7e4cf0fb6f";
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
    return base64_encode($iv.$hmac.$ciphertext_raw);
}

// PHP8-safe currency formatter (replaces deprecated money_format)
function format_currency($amount) {
    return '$' . number_format((float)$amount, 2);
}

function calculateInterest($invoiceDate, $invoiceAmount, $termsInDays) {
    $interestRate = 0.18; // 18%
    $todayDate = date('Y-m-d');
    $invoiceTimestamp = strtotime($invoiceDate);
    $todayTimestamp = strtotime($todayDate);
    $daysOverdue = max(0, floor(($todayTimestamp - $invoiceTimestamp - $termsInDays * 24 * 3600) / (24 * 3600)));
    $interest = $invoiceAmount * $interestRate * $daysOverdue / 365;
    $totalAmount = $invoiceAmount + $interest;
    if ($interest == 0) { $totalAmount = 1.05 * $totalAmount; }
    return ['totalAmount'=>$totalAmount,'daysOverdue'=>$daysOverdue,'interest'=>$interest];
}


use Dompdf\Dompdf;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once dirname(__DIR__) . '/PHPMailer/PHPMailer.php';
require_once dirname(__DIR__) . '/PHPMailer/Exception.php';
require_once dirname(__DIR__) . '/PHPMailer/SMTP.php';



// ---------- Timesheet helpers (as in your original) ----------
Function HrComp($lsInHr,$lsOutHr){
    if ($lsInHr >= 13) { $lsInHr = intval($lsInHr -12);
    } elseif ($lsInHr == 0 AND $lsOutHr <> 0) { $lsInHR = 12;
    } else { $lsInHr = intval($lsInHr); }
    return $lsInHr;
}

Function TotalHrs($i) {
    global $row2;
    $TotalHrs = $row2['OUTHR'. $i] - $row2['INHR' . $i] - $row2['BREAK' . $i];
    return $TotalHrs;
}
Function GrandTotal() {
    $lsGT = 0;
    for ($i = 1; $i <= 7; $i++) { $lsGT = $lsGT + TotalHrs($i); }
    return $lsGT;
}

Function AmPmCompIn($lsHr) { return ($lsHr >= 12) ? "PM" : "AM"; }
Function AmPmCompOut($lsHr) { return ($lsHr >= 12 || $lsHr == 0) ? "PM" : "AM"; }

function truncate($value, $precision) {
    $multiplier = pow(10, $precision);
    $value = (int)($value * $multiplier);
    return $value / $multiplier;
}
/*
Function MinComp($lsHr) {
    $lsMin = ($lsHr - floor($lsHr)) * 60;
    switch ($lsMin) {
        case 0:  return "00";
        case 15: return "15";
        case 30: return "30";
        case 45: return "45";
        default: return "00";
    }
}
*/
function MinComp($lsHr) {
    $fraction = $lsHr - floor($lsHr);

    // Always round UP to the next minute
    $lsMin = (int) ceil($fraction * 60 - 0.000001);

    // Handle edge case (e.g., 8.999999 → 60)
    if ($lsMin == 60) {
        $lsMin = 0;
    }

    return str_pad((string)$lsMin, 2, '0', STR_PAD_LEFT);
}

// ------------------------------------------------------------

// ========== NORMALIZE INPUTS with action routing ==========
$action = isset($_POST['action']) ? $_POST['action'] : '';

$default_startinvoice = 0;
$default_endinvoice   = 0;
$default_company_name = '';
$default_email_redir  = '';
$default_show_paid    = false;

if ($action === 'filter') {
    // Explicit Clear Filters
    if (!empty($_POST['reset'])) {
        $startinvoice   = $default_startinvoice;
        $endinvoice     = $default_endinvoice;
        $company_name   = $default_company_name;
        $email_redirect = $default_email_redir;
        $show_paid      = $default_show_paid;
    } else {
        // Standard filter submit
        $startinvoice   = isset($_POST['startinvoice']) ? (int)$_POST['startinvoice'] : $default_startinvoice;
        $endinvoice     = isset($_POST['endinvoice'])   ? (int)$_POST['endinvoice']   : $default_endinvoice;
        $company_name   = isset($_POST['company_name']) ? trim($_POST['company_name']) : $default_company_name;
        $email_redirect = isset($_POST['email_redirect']) ? trim($_POST['email_redirect']) : $default_email_redir;
        $show_paid      = !empty($_POST['show_paid']);

        // If truly blank, treat as reset
        if ($startinvoice === 0 && $endinvoice === 0 && $company_name === '' && $email_redirect === '' && $show_paid === false) {
            $startinvoice   = $default_startinvoice;
            $endinvoice     = $default_endinvoice;
            $company_name   = $default_company_name;
            $email_redirect = $default_email_redir;
            $show_paid      = $default_show_paid;
        }
    }

} elseif ($action === 'email') {
    // Preserve current view when emailing
    $startinvoice   = isset($_POST['startinvoice']) ? (int)$_POST['startinvoice'] : $default_startinvoice;
    $endinvoice     = isset($_POST['endinvoice'])   ? (int)$_POST['endinvoice']   : $default_endinvoice;
    $company_name   = isset($_POST['company_name']) ? trim($_POST['company_name']) : $default_company_name;
    $email_redirect = isset($_POST['email_redirect']) ? trim($_POST['email_redirect']) : $default_email_redir;
    $show_paid      = !empty($_POST['show_paid']);

} else {
    // First load / anything else
    $startinvoice   = $default_startinvoice;
    $endinvoice     = $default_endinvoice;
    $company_name   = $default_company_name;
    $email_redirect = $default_email_redir;
    $show_paid      = $default_show_paid;
}
// ==========================================================

// ---------- Outstanding total ----------
$sum = mysqli_query($link,"SELECT SUM(Hours * billrate) AS unpaid FROM ic_timesheets WHERE (NOT void OR void IS NULL OR void = '') AND paid_amount = 0");
$rowS = mysqli_fetch_array($sum);
$unpaid = $rowS['unpaid'] ?? 0;
echo "<b>Total Outstanding: $</b>" . number_format((float)$unpaid,2) . "<p>";

// ---------- Build base query ----------
$strSQL = "SELECT 'TIMEACT' as '!TIMEACT' "
        . ", wt.first_name as FIRST "
        . ", wt.last_name as LAST "
        . ", wt.Employee_ID as EMPID "
        . ", wt.paid_amount as PAID "
        . ", wt.billrate as BILLRATE "
        . ", wt.payrate as PAYRATE "
        . ", wt.Hours as HOURS "
        . ", c.organization as CLIENT "
        . ", c.company_name as COMPANY "
        . ", oj.po_number as PO "
        . ", wt.title as ITEM "
        . ", wt.AssignmentNumber as PROJ "
        . ", wt.AssignmentNumber as xORDER "
        . ", wt.AcctEmail as APCCEMAIL "
        . ", c.email as APEMAIL "
        . ", c.full_name as APNAME "
        . ", c.full_name_on_invoice "
        . ", c.address1 "
        . ", c.address2 "
        . ", c.postalcode "
        . ", c.city "
        . ", c.state "
        . ", c.waive_interest "
        . ", c.waive_late_fee "
        . ", c.vendor_number "
        . ", c.terms "
        . ", oj.ap_email as APOVER "
        . ", wt.Primary_Contact_Email as PEMAIL "
        . ", wt.Second_Contact_Email as SEMAIL "
        . ", wt.Unique_id as UNUMB "
        . ", '40' as DURATION "
        . ", '1' as BILLINGSTATUS "
        . ", 'CONTR' as PITEM "
        . ", '0' as BITEM "
        . ", 'Yes' as Contract ";

for ($i = 1; $i <= 7; $i++) {
    $strSQL .= ", wt.TimeInHr{$i} as INHR{$i} ";
    $strSQL .= ", wt.TimeOutHr{$i} as OUTHR{$i} ";
    $strSQL .= ", wt.Break{$i} as BREAK{$i} ";
}

$strSQL .= ", wt.Continuing as CONT "
         .  ", wt.ApproveDate as APPROVE "
         .  ", wt.SentDate as SENT "
         .  ", wt.DeclineDate as DECLINE "
         .  ", wt.Weekending as WKEND "
         .  ", wt.invoice_number as INVNUM "
         .  ", wt.invoice_date as INVDATE "
         .  ", wt.invoice_type as TYPE "
         .  ", wt.Signature as ESIG "
         .  ", wt.CustomerIpAddr as IP "
         .  ", COALESCE (SUM(wt.billrate * wt.Hours),0)  as AMOUNT "
         .  "FROM ic_timesheets wt "
		 .	"LEFT JOIN ic_matches oj ON oj.candidate = wt.employee_id AND oj.job = wt.AssignmentNumber "
		 .	"LEFT JOIN ic_company c ON c.organization = CAST(wt.company_id AS UNSIGNED) ";
	
		 
		//  . "LEFT JOIN ic_matches oj ON (oj.candidate = wt.employee_id AND oj.job = wt.AssignmentNumber) " 
		//  . "LEFT JOIN ic_company c ON (oj.organization = c.organization) ";

// WHERE block
if ($startinvoice > 0) {
    $strSQL .= " WHERE billrate <> 0 AND wt.void = 0 AND wt.invoice_number >= {$startinvoice} AND wt.invoice_number IS NOT NULL ";
} else {
    $strSQL .= " WHERE billrate <> 0 AND wt.void = 0 AND wt.invoice_number > 0 AND wt.invoice_number IS NOT NULL ";
}
/*
if (!$show_paid) {
    $strSQL .= " AND (wt.paid_amount = 0 OR wt.paid_amount IS NULL) ";
}
*/
if ($endinvoice > 0) {
    $strSQL .= " AND wt.invoice_number <= {$endinvoice} ";
}

if ($company_name !== '') {
    $company_esc = mysqli_real_escape_string($link, $company_name);
    $strSQL .= " AND c.company_name LIKE '%{$company_esc}%' ";
}

$strSQL2 = $strSQL . " GROUP BY wt.invoice_number ";
if (!$show_paid) {
	    $strSQL2 .= " HAVING COALESCE(MAX(wt.paid_amount), 0) < ROUND(SUM(wt.billrate * wt.Hours), 2) ";
}

$strSQL2 .= " ORDER BY INVNUM DESC, wt.first_name ASC LIMIT 45 ";

// Query
//echo $strSQL2;
$result = mysqli_query($link,$strSQL2);
if (!$result) { die('Query failed: ' . mysqli_error($link)); }

// Prevent notice if $nextinvoice was never set
$nextinvoice = isset($nextinvoice) ? $nextinvoice : 0;
$invnum = $nextinvoice - 1;
?>


<!-- Filter Form -->
<form method="post">
  <input type="hidden" name="action" value="filter">
  Start Invoice #: <input type="number" id="startinvoice" name="startinvoice" value="<?php echo (int)$startinvoice; ?>">
  End Invoice #: <input type="number" id="endinvoice" name="endinvoice" value="<?php echo (int)$endinvoice; ?>">
  Company Name: <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($company_name); ?>">
  Email Override: <input type="email" id="email_redirect" name="email_redirect" value="<?php echo htmlspecialchars($email_redirect); ?>">
  <?php
    echo 'Show Paid: <input type="checkbox" id="show_paid" name="show_paid" value="1" ' . ($show_paid ? 'checked' : '') . '>';
  ?>
  <p>
    <input type="submit" value="Filter Invoices">
    <button type="submit" name="reset" value="1">Clear Filters</button>
  </p>
</form>

<form method="post">
  <input type="hidden" name="action" value="email">
<?php
echo '<br>';
echo '<table class="inv">';
echo '<colgroup>
  <col> <!-- checkbox -->
  <col style="width:200px;"> <!-- A/P Name -->
  <col style="width:400px;"> <!-- A/P Email -->
  <col style="width:300px;"> <!-- Client -->
  <col> <!-- Inv Amt -->
  <col> <!-- Paid Amt -->
  <col> <!-- Invoice Date -->
  <col> <!-- Job ID -->
  <col> <!-- Inv No -->
  <col> <!-- PO Number -->
</colgroup>';

echo "<thead><tr bgcolor='#ffffff'><th bgcolor='#ffffff'><input type='checkbox' id='check_all'></th><th><p>Check All</p></th></tr></thead>";
echo '<tr align="center">
<th> </th>
<th>A/P Name</th>
<th>A/P Email</th>
<th>Client</th>
<th>Inv Amt</th>
<th>Paid Amt</th>
<th>Invoice Date</th>
<th>Job ID</th>
<th>Inv No</th>
<th>PO Number</th>
</tr>';

while ($row = mysqli_fetch_array($result)) {

    $approved_date = strtotime($row['APPROVE']);
    $week_ending   = strtotime($row['WKEND']);

    if(!empty($row['APOVER'])) { $ap_email = $row['APOVER']; }
    else { $ap_email = $row['APEMAIL']; }

    echo '<tr align="center">';
    echo '<td>';
    if (!is_null($row['APEMAIL'])) {
        echo '<input type="checkbox" name="recipients[]" value="' . htmlspecialchars($row['INVNUM']) . '">';
    }
    echo '</td>';

    echo '<td ALIGN="LEFT"><a target="_blank" href="https://port.icreatives.com/api/company.php?id=' . htmlspecialchars(($row['CLIENT'] ?? '')) . '">' . htmlspecialchars($row['APNAME']) . '</a></td>';

    echo '<td class="col-email">';
    if ($email_redirect === '') {
        if (!empty($ap_email)) {
            echo htmlspecialchars($ap_email);
        } else {
            echo '<font color="red"><b>A/P MISSING</b></font>';
        }
    } else {
        echo '<font color="red"><b>'. htmlspecialchars($email_redirect) .'</b></font>';
    }
    echo "</td>";

    echo '<td ALIGN="LEFT">';
    if (!is_null($row['CLIENT'])) {
        echo htmlspecialchars($row['COMPANY']);
    } else {
        echo '<font color="red"><b>RE-OPEN CLIENT</b></font>';
    }
    echo "</td>";

    echo "<td align='right'>" . format_currency($row['AMOUNT']) . "</td>";
    echo "<td align='right'>" . format_currency($row['PAID']) . "</td>";
    echo "<td>" . date("m/d/Y",strtotime($row['INVDATE'])) . '</td>';
    echo "<td><a target='_blank' href='https://app.manatal.com/jobs/" . htmlspecialchars($row['PROJ']) . "'>" . htmlspecialchars($row['PROJ']) . "</a></td>";

    $url = '"https://port.icreatives.com/api/customer/view_invoice.php?invnum='.encrypt_string($row['INVNUM']).'"';
    echo "<td><a href='javascript:void(0);' onclick='openPopup(".$url.");'>". htmlspecialchars($row['INVNUM']) . "</a></td>";

    echo "<td>" . htmlspecialchars(($row['PO'] ?? '')) . "</td>";
    echo '</tr>';

    $invnum = $invnum + 1;
}

echo '</table><p>';
?>

  <!-- Preserve current view for the email step -->
  <input type="hidden" name="email_redirect" value="<?php echo htmlspecialchars($email_redirect); ?>">
  <input type="hidden" name="show_paid" value="<?php echo $show_paid ? '1' : ''; ?>">
  <input type="hidden" name="startinvoice" value="<?php echo (int)$startinvoice; ?>">
  <input type="hidden" name="endinvoice" value="<?php echo (int)$endinvoice; ?>">
  <input type="hidden" name="company_name" value="<?php echo htmlspecialchars($company_name); ?>">

  <input type="submit" value="Email Checked Invoices">
</form>

<?php
// ---------------- Email send block (only when emailing) ----------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $action === 'email' && !empty($_POST['recipients'])) {

    $recipients = $_POST['recipients'];
    $strSQL_N = str_replace(", COALESCE (SUM(wt.billrate * wt.Hours),0)  as AMOUNT ","",$strSQL);

    for($x=0; $x<count($recipients); $x++) {

        $invno = mysqli_real_escape_string($link, $recipients[$x]);
        $query2 = $strSQL_N . " AND wt.invoice_number = '". $invno ."' ORDER BY WKEND ASC, FIRST ASC";

        $result2 = mysqli_query($link,$query2);
        if (!$result2) { die('Query failed: ' . mysqli_error($link)); }

        $row3 = mysqli_fetch_array($result2);
        if(!empty($row3['APOVER'])) { $ap_email = $row3['APOVER']; }
        else { $ap_email = $row3['APEMAIL']; }

        $invoice_number = $row3['INVNUM'];

       include('manatal_invoice_inc.php');
	   // echo $invoice;
        $invoice = str_replace('url("https://port.icreatives.com/webtime/email/images/assets/background.gif")',"url('/webtime/email/images/assets/background.gif')",$invoice);

        $sScreen = "";
        $result6 = mysqli_query($link,$query2);
        while ($row6 = mysqli_fetch_array($result6)) {
            if($row6['TYPE'] !== 'e' && $row6['TYPE'] !== 'f') {
                include('manatal_timesheet_inc.php');
                $invoice = $invoice . $sScreen;
            }
        }

        $pdf = new Dompdf();
        if ($invoice) {
            $pdf->loadHtml($invoice);
            $pdf->setPaper('letter', 'portrait');
            $pdf->set_option('isHtml5ParserEnabled', true);
            $pdf->set_option('isRemoteEnabled', true);
            $pdf->render();
            $pdfContent = $pdf->output();

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

            if ($email_redirect === '') {
                $emailArray = explode(',', $ap_email);
                foreach ($emailArray as $email) {
                    $email = trim($email);
                    if ($email !== '') { $mail->addAddress($email, 'Accounts Payable'); }
                }
            } else {
                $mail->addAddress($email_redirect, 'Accounts Payable');
            }

            $mail->addBcc("andreaa@icreatives.com");
            $mail->addBcc("invoice@blindemail.com");
            $mail->addBcc("stevenc@icreatives.com");

            $mail->Subject = 'icreatives invoice ' . $row3['INVNUM'] . ' ';
            $mail->Body = 'Dear '. htmlspecialchars($row3["COMPANY"]) .',
            <p>Attached please find a multi-page Acrobat formatted invoice.
            <p>If there is anything else you may need, or have any questions about this invoice, please do not hesitate to contact us.
            <p>Thank you so much for your business.
            <p><p>Sincerely,
            <p><p><span style="FONT-FAMILY:arial,sans-serif;FONT-SIZE:10pt">the icreatives team<br>...</span>'; // (rest unchanged)

            $mail->AltBody = 'Please find the attached PDF Invoice.';
            $mail->addStringAttachment($pdfContent, $row3['INVNUM'].'.pdf', 'base64', 'application/pdf');

            sleep(3); // throttle
            if ($mail->send()) {
                if ($email_redirect === '') {
                    echo 'Email sent successfully to: '. htmlspecialchars($ap_email) . "<br>";
                } else {
                    echo 'Email sent successfully to: '. htmlspecialchars($email_redirect) . "<br>";
                }
            } else {
                echo 'Email could not be sent.';
            }
        }
    }
}
?>

<script>
// Check All behavior
var checkAll = document.getElementById('check_all');
if (checkAll) {
    checkAll.addEventListener('click', function() {
        var checkboxes = document.getElementsByName('recipients[]');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = checkAll.checked;
        }
    });
}
</script>
