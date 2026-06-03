<?php include 'manatal_header.php'; ?>
<?php	

echo " <div style='width:100%; padding:30px 30px 30px 100px;'>";
echo "<h1>MANATAL CREATE FULL-TIME INVOICE</h1>";
// Connect to the MySQL database

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2', 'ck3b2t') or die("Error: " . mysqli_error());

if (!$link) {
    die('Connection failed: ' . mysqli_connect_error());
}

// find invoice info

function Contact_Info($link, $organization, $job) {
   $f_query = "
    select * from ic_contacts 
    where organization = '" . $organization . "' ";

    $SQL = mysqli_query($link, $f_query);
    if (!$SQL) {
        die('Query failed: ' . mysqli_error($link));
    }
    $row0 = mysqli_fetch_array($SQL);
    
    require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
    $client = new \GuzzleHttp\Client();

    $response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/' . $job . '/', [
    'headers' => [
        'Authorization' => $token,
        'accept' => 'application/json',
    ],
    ]);

    $response->getBody();
    $responseStr = $response->getBody();
    $job = json_decode($responseStr, true);
    $invoice_override = $job['custom_fields']['apinvoiceemailcommadelimited'];

    if (strpos($invoice_override, "@")) {
        $email = $invoice_override;
    } else {
        $email = $row0['email'];
    }
    list($email1, $email2) = explode(",", $email);

    $infoArray = array(
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
    );
    return $infoArray;
}

// find last invoice number
$rowSQL = mysqli_query($link, "SELECT MAX(invoice_number) AS max FROM ic_timesheets WHERE void = 0;");
$row = mysqli_fetch_array($rowSQL);

$nextinvoice = $row['max'] + 1;

echo "Next Invoice number is: " . $nextinvoice . "<p>";

$invnum = $nextinvoice - 1;
echo '<form id="match_form" method="post">';
echo 'Please Enter Match Number #:  
<input type="text" id="match_id" name="match_id">';
echo '<input type="hidden" name="nextinvoice" value="' . $nextinvoice . '">';
echo '<input type="submit" value="Submit">';
echo '</form>';
echo '<br>';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['match_id']) && isset($_POST['nextinvoice'])) {
    $query = "
    SELECT * FROM ic_matches WHERE id = '" . $_REQUEST['match_id'] . "'"; 
    $result = $link->query($query);
    $row = $result->fetch_assoc();
    echo "<h2> Company: " . $row['company_name'] . "</h2>";
    echo "<h2> Name: " . $row['candidate_name'] . "</h2>";
    $match_id = $_REQUEST['match_id'];
    $job_name = "Placement fee for: " . $row['candidate_name']; 
    $ap_arr = Contact_Info($link, $row['organization'], $row['job']);
    $email1 = $ap_arr['email1'];
    $email2 = $ap_arr['email2'];
    $date = date('Y-m-d', time());
    setlocale(LC_MONETARY, "en_US");
    $MyNewRandomNum = (trim(date("Y") . date("m") . date("d")) . date("h") . date("m") . date("s")) . intval(rand());

?>
    <form id="update_form" method="post">
    Email: <?php echo $email1 . " " . $email2; ?><p>
    Invoice Number: <input type='text' style='width:200px;' name='invoice_number' value='<?php echo $nextinvoice; ?>' required><p>
    Week Ending Date: <input type='date' name='WeekEnding' value='<?php echo $date; ?>'><p>
    Salary: $<?php echo money_format('%i', $row['salary']) ?> - Fee Percent: - <?php echo $row['fee_percent'] ?>% - Terms: <?php echo $row['terms'] ?><p>
    Invoice Amount: <input type='text' style='width:200px;' name='bill_rate' value='<?php echo $row['salary'] * ($row['fee_percent'] / 100); ?>' required><p>
    Description: <input type='text' style='width:400px;' name='job_name' value='<?php echo $job_name; ?>' required><p>
    Terms: <input type='text' style='width:200px;' name='terms' value='<?php echo $row['terms']; ?>' required><p>
    Invoice Date: <input type='date' style='width:200px;' name='invoice_date' value='<?php echo $date; ?>' required><p>
    <p>
        <input type='hidden' name='Hours' value='1'>
        <input type='hidden' name='salary' value='<?php echo $row['salary']; ?>'>
        <input type='hidden' name='match_id' value='<?php echo $match_id; ?>'>
        <input type='hidden' name='nextinvoice' value='<?php echo $nextinvoice; ?>'>
   <input type='submit' value='Save Changes'>
   </form>
   </div>
<?php 
}

// insert record
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_REQUEST['bill_rate'] > 0) {
    list($first_name, $middle_name, $last_name) = explode(' ', $row['candidate_name']);
    $last_name = $middle_name . " " . $last_name;

    // Prepare SQL statement with placeholders
    $strSQL = "INSERT INTO ic_timesheets (
        Unique_id,
        company_name,
        company_id,
        billrate, 
        Employee_ID,
        Email, 
        first_name, 
        Last_name, 
        title,
        Primary_Contact_Email, 
        Second_Contact_Email, 
        BillingProfile, 
        Hours, 
        AssignmentNumber, 
        WeekEnding, 
        Assignment_ID, 
        SentDate,
        ApproveDate,
        ExportDate,
        terms,
        invoice_template,
        invoice_amount,
        invoice_export,    
        invoice_date,
        invoice_number,
        invoice_type
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW(), ?, ?, ?, NOW(), ?, ?, ?)";

    // Prepare and bind parameters
    if ($stmt = $link->prepare($strSQL)) {
       $terms = $_REQUEST['terms'];
	   $hours = 1;
	   $invoice_type = "f";
$stmt->bind_param(
    'sssissssssssisssisisss',
    $MyNewRandomNum,
    $row['company_name'],
    $row['organization'],
    $_REQUEST['bill_rate'],
    $row['candidate'],
    $row['candidate_email'],
    $first_name,
    $last_name,
    $_REQUEST['job_name'],
    $email1,
    $email2,
    $row0['invoice_template'],
    $hours,
    $row['job'],
    $_REQUEST['WeekEnding'],
    $row['id'],
    $terms,
    $row['invoice_template'],
    $_REQUEST['bill_rate'],
    $_REQUEST['invoice_date'],
    $_REQUEST['invoice_number'],
    $invoice_type
);


        // Execute the statement
        if ($stmt->execute()) {
            echo "Record inserted successfully!";
        } else {
            echo "Error: " . htmlspecialchars($stmt->error);
			exit();
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Prepare failed: " . htmlspecialchars($link->error);
    }

    echo "<p>INVOICE " . $_REQUEST['invoice_number'] . " CREATED";
    
    // create QuickBooks import file:
    
    // Define the CSV file name and path
    $invoicefile = 'selected_invoices.csv';
   
    $invoicepath = '' . $invoicefile;

    // Open the CSV file for writing
    $file = fopen($invoicepath, 'w');

    // make header
    $text = '"*InvoiceNo","*Customer","Email","*InvoiceDate","*DueDate","Terms","Location","Memo","Item","ItemDescription","ItemQuantity","ItemRate","*ItemAmount","Taxable","TaxRate","Service Date"'. PHP_EOL;
    fwrite($file, $text);

    // write formula for duedate here
    $due_date =  date('m/d/Y', strtotime($_REQUEST['invoice_date'].' + '.$_REQUEST['terms'].' days'));
    
    $text = '"'.$_REQUEST['invoice_number'].'","'.
        $row['company_name'].'","'.
        $ap_arr['email1'].'","'.
        date('m/d/Y',strtotime($_REQUEST['invoice_date'])).'","'.
        $due_date.'","Net '.
        $_REQUEST['terms'] .'","","'.
        $row['PO'].'","","'.
        $_REQUEST['job_name'].'","1","'.
        $_REQUEST['bill_rate'].'","'.
        $_REQUEST['bill_rate'].'","N","0%","'.
        $due_date.'"'. PHP_EOL;
    fwrite($file, $text);

    fclose($file);
    $invoice_download_link = 'https://' . $_SERVER['HTTP_HOST'] . '/mngr/' . $invoicepath;
    echo '<BR><a href="'.$invoice_download_link.'">Download Invoices CSV File </a>';
}   
?>
