<?php include 'manatal_header.php'; ?>
<?php
/*
 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
echo "<div style='width:100%; padding:30px 30px 30px 100px;'>";
echo "<h1>MANATAL CREATE EXPENSE INVOICE</h1>";

require_once  dirname(__DIR__) . '/vendor/autoload.php';
global $token;
require_once  dirname(__DIR__) . '/db/token.php';
// Connect to the MySQL database
require_once dirname(__DIR__). '/db/db.php';
$link = db(); 
	
// Function to find invoice info
function Contact_Info($link, $organization, $job) {
	global $token;
	global $row0;
    $f_query = "SELECT * FROM ic_company WHERE organization = '" . $organization . "'";
    $result = mysqli_query($link, $f_query);
    if (!$result) {
        die('Query failed: ' . mysqli_error($link));
    }
    $row0 = mysqli_fetch_array($result);

    if (!$row0) {
        return false;
    }

    $client = new \GuzzleHttp\Client([ 'timeout' => 30, 'connect_timeout' => 10]);
    $response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/'.$job.'/', [
        'headers' => [
		'Authorization' => $token,
            'accept' => 'application/json',
        ],
    ]);

    $responseStr = $response->getBody();
    $jobData = json_decode($responseStr, true);
    $invoice_override = $jobData['custom_fields']['apinvoiceemailcommadelimited'];

    $email = strpos($invoice_override, "@") !== false ? $invoice_override : $row0['email'];
    list($email1, $email2) = explode(",", $email);

    return [
        'full_name' => $row0['full_name'],
        'terms' => $row0['terms'],
        'address1' => $row0['address1'],
        'address2' => $row0['address2'],
        'email' => $row0['email'],
        'email1' => $email1,
        'email2' => $email2,
        'city' => $row0['city'],
        'state' => $row0['state'],
        'postalcode' => $row0['postalcode'],
        'country' => $row0['country'],
        'created_at' => $row0['created_at'],
        'one_invoice_per_candidate' => $row0['one_invoice_per_candidate'],
        'full_name_on_invoice' => $row0['full_name_on_invoice'],
        'accountspayable' => $row0['accountspayable']
    ];
}

// Find last invoice number
$rowSQL = mysqli_query($link, "SELECT MAX(invoice_number) AS max FROM `ic_timesheets` WHERE void = 0;");
$row = mysqli_fetch_array($rowSQL);
$nextinvoice = $row['max'] + 1;

echo "Next Invoice number is: " . $nextinvoice . "<p>";

echo '<form id="match_form" method="post">';
echo 'Please Enter Match Number #: <input type="text" id="match_id" name="match_id">';
echo '<input type="hidden" name="nextinvoice" value="' . $nextinvoice . '">';
echo '<input type="submit" value="Submit">';
echo '</form>';
echo '<br>';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['match_id']) && isset($_POST['nextinvoice'])) {
    $query = "SELECT * FROM ic_matches WHERE id = '" . $_POST['match_id'] . "'";
    $result = mysqli_query($link, $query);
    if (!$result) {
        die('Query failed: ' . mysqli_error($link));
    }
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        die("No match found for the given ID.");
    }

    echo "<h2> Company: " . $row['company_name'] . "</h2>";
    echo "<h2> Name: " . $row['candidate_name'] . "</h2>";
    echo "<h2> PO No: " . $row['po_number'] . "</h2>";
    $match_id = $_POST['match_id'];
    $job_name = "Placement fee for: " . $row['candidate_name'];
    $ap_arr = Contact_Info($link, $row['organization'], $row['job']);

    if (!$ap_arr) {
        die("No accounts payable contact found for the given organization.");
    }

    $email1 = $ap_arr['email1'];
    $email2 = $ap_arr['email2'];
    $date = date('Y-m-d');
    $MyNewRandomNum = (Trim(date("Y") . date("m") . date("d")) . date("h") . date("m") . date("s")) . intval(rand());

    ?>

    <form id="update_form" method="post">
        Email: <?php echo $email1 . " " . $email2; ?><p>
        Invoice Number: <input type="text" style="width:200px;" name="invoice_number" value="<?php echo $nextinvoice; ?>" required><p>
        Week Ending Date: <input type="date" name="WeekEnding" value="<?php echo $date; ?>"><p>
        Cost Amount: <input type="number" step="0.01" style="width:200px;" name="pay_rate" required><p>
        Invoice Amount: <input type="number" step="0.01" style="width:200px;" name="bill_rate" required><p>
        Description: <input type="text" style="width:400px;" name="job_name" required><p>
        Terms: <input type="text" style="width:200px;" name="terms" value="<?php echo $ap_arr['terms']; ?>" required><p>
        Invoice Date: <input type="date" style="width:200px;" name="invoice_date" value="<?php echo $date; ?>" required><p>

        <p>
        <input type="hidden" name="Hours" value="1">
        <input type="hidden" name="salary" value="<?php echo ($_REQUEST['salary'] ?? 0); ?>">
        <input type="hidden" name="match_id" value="<?php echo $_POST['match_id']; ?>">
        <input type="hidden" name="nextinvoice" value="<?php echo $nextinvoice; ?>">
        <input type="hidden" name="candidate_name" value="<?php echo $row['candidate_name']; ?>">
        <input type="submit" value="Save Changes">
    </form>
    </div>
    <?php
}

// Insert record
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_REQUEST['bill_rate']) && $_REQUEST['bill_rate'] !== 0) {
    $row = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM ic_matches WHERE id = '" . $_REQUEST['match_id'] . "'"));
    $name = trim($row['candidate_name'] ?? '');
		$parts = preg_split('/\s+/', $name);
		$first_name = $parts[0] ?? '';
		$last_name  = $parts[count($parts) - 1] ?? '';
		$middle_name = '';
		if (count($parts) > 2) {
			$middle_name = implode(' ', array_slice($parts, 1, -1));
		}
    $last_name = $middle_name . " " . $last_name;
    $Hours = 1;

    // Construct the SQL statement directly
    $strSQL = "INSERT INTO ic_timesheets (
        Unique_id, company_name, company_id, billrate, payrate, Employee_ID, Email, 
        first_name, Last_name, title, Primary_Contact_Email, Second_Contact_Email, 
        BillingProfile, Hours, AssignmentNumber, WeekEnding, Assignment_ID, SentDate, 
        ApproveDate, ExportDate, terms, invoice_template, invoice_amount, invoice_export, 
        invoice_date, invoice_number, invoice_type
    ) VALUES (
        '$MyNewRandomNum',
        '".$row['company_name']."',
        '".$row['organization']."',
        '".$_REQUEST['bill_rate']."',
        '".$_REQUEST['pay_rate']."',
        '".$row['candidate']."',
        '',
        '".$first_name."',
        '".$last_name."',
        '".$_REQUEST['job_name']."',
        '".$email1."',
        '".$email2."',
        '".($row0['invoice_template'] ?? '')."',
        '$Hours',
        '".$row['job']."',
        '".$_REQUEST['WeekEnding']."',
        '".$row['id']."',
        NOW(),
        NOW(),
        NOW(),
        '".$_REQUEST['terms']."',
        '".$row['invoice_template']."',
        '".$_REQUEST['bill_rate']."',
	NOW(),
        '".$_REQUEST['invoice_date']."',
        '".$_REQUEST['invoice_number']."',
        'e'
    )";

    // echo "Debugging SQL: " . $strSQL . "<br>"; // Print SQL statement for debugging

    // Execute the statement
    if (mysqli_query($link, $strSQL)) {
        echo "Record inserted successfully!";
    } else {
        echo "Error: " . mysqli_error($link);
    }

    echo "<p>INVOICE ". $_REQUEST['invoice_number'] . " CREATED";

    // Create QuickBooks import file
    $invoicefile = 'selected_invoices.csv';
    $invoicepath = '' . $invoicefile;

    // Open the CSV file for writing
    $file = fopen($invoicepath, 'w');

    // Open file for appending
    $file = fopen($invoicefile, "a");

    // Make header
    $text = '"*InvoiceNo","*Customer","Email","*InvoiceDate","*DueDate","Terms","Location","Memo","Item","ItemDescription","ItemQuantity","ItemRate","*ItemAmount","Taxable","TaxRate","Service Date"' . PHP_EOL;
    fwrite($file, $text);

    // Note: You must turn on "Custom transaction numbers" in Accounts and Settings or your invoice numbers will be replaced by standard QuickBooks invoice numbers.
    $invoicefile = "invoice.txt";
    $invnum = $nextinvoice;

    // Write formula for duedate here
    $due_date =  date('m/d/Y', strtotime($_REQUEST['invoice_date'].' + '.$ap_arr['terms'].' days'));
    $text = '"'.$_REQUEST['invoice_number'].'","'.
        $row['company_name'].'","'.
        $ap_arr['email'].'","'.
        date('m/d/Y',strtotime($_REQUEST['invoice_date'])).'","'.
        $due_date.'","Net '.
        $_REQUEST['terms'] .'","","'.
        $row['po_number'].'","","'.
        $_REQUEST['job_name'].'","1","'.
        $_REQUEST['bill_rate'].'","'.
        $_REQUEST['bill_rate'].'","N","0%","'.
        date('m/d/Y',strtotime($_REQUEST['invoice_date'])).'"' . PHP_EOL;

    fwrite($file, $text);
    fclose($file);

    $invoice_download_link = 'https://' . $_SERVER['HTTP_HOST'] . '/mngr/' . $invoicepath;
    echo '<br><a href="' . $invoice_download_link . '">Download Invoices CSV File</a>';
}
?>
