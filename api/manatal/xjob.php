<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Form</title>
    <style>
        .popup-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .btn {
            background-color: blue;
            color: white;
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
	    <style>
        body {
            font-family: Arial, sans-serif;
			font-size:14px;
        }
        table {
            width: 90%;
        }

        table tr td:first-child {
            width: 40%;
        }

        table tr td:last-child {
            width: 60%;
        }

        input[type="number"], input[type="text"], input[type="email"], input[type="tel"], input[type="password"], input[type="checkbox"], input[type="date"] {
            width: 100%;
            box-sizing: border-box;
        }
    </style>

</head>
<body>

<?php
// Database connection details
$servername = "localhost";
$username = "re0nm8";
$password = "50h8r6WNvB!ozVY2";
$dbname = "ck3b2t";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve request variables
$id = $_REQUEST['id'];
$title = $_REQUEST['title'];
$company_name = $_REQUEST['company_name'];
$company = $_REQUEST['company'];

// retreive mantatl job custom fields
require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/'.$id.'/', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);
echo $responseStr = $response->getBody();
//echo	$match = json_decode($responseStr, true);
exit();

// fix JSON string
// Decode the JSON string into an associative array
$data = json_decode($requestBody, true);


// Check if the 'custom_fields' section needs to be fixed
$needsFixing = is_array($data['custom_fields']) && count($data['custom_fields']) > 0 && is_array($data['custom_fields'][0]);

// If 'custom_fields' needs fixing, merge the fields into a single associative array
// if ($needsFixing) {
	// Create a new associative array for custom fields
	$customFields = array();
	foreach ($data['custom_fields'] as $field) {
		// Merge each field into the custom fields array
		$customFields += $field;
	}
	// Replace the 'custom_fields' array with the new associative array
	$data['custom_fields'] = $customFields;
 // }
 

$contact = $data;
// end fix JSON string



// $contact = json_decode($requestBody, true);


// echo $requestBody;


// Fetch data from ic_jobs table
$sql = "SELECT * FROM ic_jobs WHERE company='$company'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch existing data
    $row = $result->fetch_assoc();
    $timeapproveremail = $row['timeapproveremail'];
    $timeapproveremail_b = $row['timeapproveremail_b'];
    $ap_email = $row['ap_email'];
    $ponumber = $row['ponumber'];
    $poamount = $row['poamount'];
    $poenddate = $row['poenddate'];
    $portalusers = $row['portalusers'];
    $openorclosed = $row['openorclosed'];
    $ponote = $row['ponote'];
} else {
    // Initialize with default values if no record found
    $timeapproveremail = '';
    $timeapproveremail_b = '';
    $ap_email = '';
    $ponumber = '';
    $poamount = '0.00';
    $poenddate = '0000-00-00';
    $portalusers = '';
    $openorclosed = 'Open';
    $ponote = '';
}
?>

<div class="popup-form">
    <form action="job.php" method="post">
        <table>
            <tr>
                <td>Id:</td>
                <td><?php echo $id; ?></td>
            </tr>
		    <tr>
                <td>Company:</td>
                <td><?php echo $company; ?></td>
            </tr>
            <tr>
                <td>Company Name:</td>
                <td><?php echo $company_name; ?></td>
            </tr>

            <tr>
                <td>Title:</td>
                <td><?php echo $title; ?></td>
            </tr>
            <tr>
                <td>Time Approver Email:</td>
                <td><input type="email" name="timeapproveremail" value="<?php echo $timeapproveremail; ?>"></td>
            </tr>
            <tr>
                <td>Time Approver Email B:</td>
                <td><input type="email" name="timeapproveremail_b" value="<?php echo $timeapproveremail_b; ?>"></td>
            </tr>
            <tr>
                <td>AP Invoice Email (comma delimited):</td>
                <td><input type="text" name="ap_email" value="<?php echo $ap_email; ?>"></td>
            </tr>
            <tr>
                <td>PO Number:</td>
                <td><input type="text" name="ponumber" value="<?php echo $ponumber; ?>"></td>
            </tr>
            <tr>
                <td>PO Amount:</td>
                <td><input type="text" name="poamount" value="<?php echo $poamount; ?>"></td>
            </tr>
            <tr>
                <td>PO End Date:</td>
                <td><input type="date" name="poenddate" value="<?php echo $poenddate; ?>"></td>
            </tr>
            <tr>
                <td>Portal Users:</td>
                <td><input type="text" name="portalusers" value="<?php echo $portalusers; ?>"></td>
            </tr>
            <tr>
                <td>Open or Closed:</td>
                <td><input type="text" name="openorclosed" value="<?php echo $openorclosed; ?>"></td>
            </tr>
            <tr>
                <td>PO Note:</td>
                <td><textarea name="ponote"><?php echo $ponote; ?></textarea></td>
            </tr>
        </table>
        <button type="submit" class="btn" name="save">Save</button>
        <button type="button" class="btn" onclick="window.close();">Cancel</button>
    </form>
</div>

</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $timeapproveremail = $_POST['timeapproveremail'];
    $timeapproveremail_b = $_POST['timeapproveremail_b'];
    $ap_email = $_POST['ap_email'];
    $ponumber = $_POST['ponumber'];
    $poamount = $_POST['poamount'];
    $poenddate = $_POST['poenddate'];
    $portalusers = $_POST['portalusers'];
    $openorclosed = $_POST['openorclosed'];
    $ponote = $_POST['ponote'];

    // Insert or update the record
    $sql = "INSERT INTO ic_jobs (id, title, timeapproveremail, timeapproveremail_b, ap_email, ponumber, poamount, poenddate, portalusers, openorclosed, ponote, company, company_name) 
            VALUES ('$id', '$title', '$timeapproveremail', '$timeapproveremail_b', '$ap_email', '$ponumber', '$poamount', '$poenddate', '$portalusers', '$openorclosed', '$ponote', '$company', '$company_name')
            ON DUPLICATE KEY UPDATE 
                timeapproveremail=VALUES(timeapproveremail),
                timeapproveremail_b=VALUES(timeapproveremail_b),
                ap_email=VALUES(ap_email),
                ponumber=VALUES(ponumber),
                poamount=VALUES(poamount),
                poenddate=VALUES(poenddate),
                portalusers=VALUES(portalusers),
                openorclosed=VALUES(openorclosed),
                ponote=VALUES(ponote)";

    if ($conn->query($sql) === TRUE) {
        echo "Record saved successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();

    // Close the popup
    echo "<script>window.close();</script>";
}
?>
