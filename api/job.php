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
			height: 100%;
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
		textarea {
			width: 98%;
		}
    </style>
    <style>
        body {
            font-family: Arial, sans-serif;
			font-size:14px;
			background-color: #f9f9f9;
        }
        table {
            width: 100%;
        }

        table tr td:first-child {
            width: 40%;
        }

        table tr td:last-child {
            width: 60%;
        }

        input[type="number"], input[type="text"], input[type="email"], input[type="tel"], input[type="password"], input[type="checkbox"], input[type="date"], select {
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body style="background-color: #f9f9f9;">

<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// error_reporting(E_ERROR | E_PARSE);
*/
?>

<?php
session_start();
$data = $_SESSION['data'];
$id = $_SESSION['id'];

require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
// Retrieve request variables
$id = $_REQUEST['id'];
$title = $_REQUEST['title'];
$position_name = $_REQUEST['title'];
$company_name = $_REQUEST['company_name'];
$company = $_REQUEST['company'];

// retreive mantatl job custom fields
$client = new \GuzzleHttp\Client();


$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/'.$id.'/', [
  'headers' => [
        'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);
$responseStr = $response->getBody();
$data = json_decode($responseStr, true);

$old_po = $data['custom_fields']['ponumber'];
$company =  $data['organization'];
$_SESSION['data'] = $data;
$_SESSION['id'] = $id;
$_SESSION['old_po'] = $old_po;
$_SESSION['company'] = $company;
  


// Check if the 'custom_fields' section needs to be fixed
$needsFixing = is_array($data['custom_fields']) && count($data['custom_fields']) > 0 && is_array($data['custom_fields'][0]);

// If 'custom_fields' needs fixing, merge the fields into a single associative array
if ($needsFixing) {
	// Create a new associative array for custom fields
	$customFields = array();
	foreach ($data['custom_fields'] as $field) {
		// Merge each field into the custom fields array
		$customFields += $field;
	}
	// Replace the 'custom_fields' array with the new associative array
	$data['custom_fields'] = $customFields;
}


$timeapproveremail = isset($data['custom_fields']['timeapproveremail']) ? $data['custom_fields']['timeapproveremail'] : null;
$timeapproveremail_b = isset($data['custom_fields']['timeapproveremail_b']) ? $data['custom_fields']['timeapproveremail_b'] : null;
$apinvoiceemailcommadelimited = isset($data['custom_fields']['apinvoiceemailcommadelimited']) ? $data['custom_fields']['apinvoiceemailcommadelimited'] : null;
$ponumber = isset($data['custom_fields']['ponumber']) ? $data['custom_fields']['ponumber'] : null;
$old_ponumber = isset($data['custom_fields']['ponumber']) ? $data['custom_fields']['ponumber'] : null;
$poamount = isset($data['custom_fields']['poamount']) ? $data['custom_fields']['poamount'] : null;
$poenddate = isset($data['custom_fields']['poenddate']) ? $data['custom_fields']['poenddate'] : null;
$portalusers = isset($data['custom_fields']['portalusers']) ? $data['custom_fields']['portalusers'] : null;
$openorclosed = isset($data['custom_fields']['openorclosed']) ? $data['custom_fields']['openorclosed'] : null;
$ponote = isset($data['custom_fields']['ponote']) ? $data['custom_fields']['ponote'] : null;

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
			<!--
            <tr>
                <td>Company Name:</td>
                <td><?php echo $company_name; ?></td>
            </tr>
			-->
            <tr>
                <td>Title:</td>
                <td><?php echo $title; ?></td>
            </tr>
            <tr>
                <td>Time Approver Email 1:</td>
                <td><input type="email" name="timeapproveremail" value="<?php echo $timeapproveremail; ?>"></td>
            </tr>
            <tr>
                <td>Time Approver Email 2:</td>
                <td><input type="email" name="timeapproveremail_b" value="<?php echo $timeapproveremail_b; ?>"></td>
            </tr>
            <tr>
                <td>AP Invoice Override (email comma delimited):</td>
                <td><input type="text" name="apinvoiceemailcommadelimited" value="<?php echo $apinvoiceemailcommadelimited; ?>"></td>
            </tr>
            <tr>
                <td>PO Number:</td>
                <td><input type="text" name="ponumber" value="<?php echo $ponumber; ?>"></td>
            </tr>
            <tr>
                <td>PO Amount:</td>
                <td><input type="number" name="poamount" value="<?php echo $poamount; ?>"></td>
            </tr>
            <tr>
                <td>PO End Date:</td>
                <td><input type="date" name="poenddate" value="<?php echo $poenddate; ?>"></td>
            </tr>
			<!-- delete portaluser for TempBack
            <tr>
                <td>Portal Users:</td>
                <td><input type="text" name="portalusers" value="<?php echo $portalusers; ?>"></td>
            </tr>
			 -->
            <tr>
                <td>Open or Closed:</td>
                <td>
                    <select name="openorclosed">
                        <option value="Open" <?php echo ($openorclosed == 'Open') ? 'selected' : ''; ?>>Open</option>
                        <option value="Closed" <?php echo ($openorclosed == 'Closed') ? 'selected' : ''; ?>>Closed</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>PO Note:</td>
                <td><textarea name="ponote"><?php echo $ponote; ?></textarea></td>
            </tr>
        </table>
	<input type = "hidden" name = "old_openorclosed" value = "<?php echo $old_openorclosed; ?>">
	<input type = "hidden" name = "company" value = "<?echo $_REQUEST['company']; ?>">
	<input type = "hidden" name = "old_ponumber" value = "<?echo $ponumber; ?>">
        <button type="submit" class="btn" name="save">Save</button>
        <button type="button" class="btn" onclick="window.close();">Cancel</button>
    </form>
</div>

</body>
</html>

<?php
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = $_SESSION['data'];
	// echo "XXX";
	$id = $_SESSION['id'];
    // Retrieve form data
    $timeapproveremail = $_POST['timeapproveremail'];
    $timeapproveremail_b = $_POST['timeapproveremail_b'];
    $ap_email = $_POST['ap_email'];
    $ponumber = $_POST['ponumber'];
    $poamount = $_POST['poamount'];
    $poenddate = $_POST['poenddate'];
    // $portalusers = $_POST['portalusers'];
    $openorclosed = $_POST['openorclosed'];
    $ponote = $_POST['ponote'];

if (isset($_POST['timeapproveremail']) && $_POST['timeapproveremail'] <> "") {
    $data['custom_fields']['timeapproveremail'] = $_POST['timeapproveremail'];
} else {
    if(isset($data['custom_fields']['timeapproveremail'])){unset($data['custom_fields']['timeapproveremail']);}
}
if (isset($_POST['timeapproveremail_b']) && $_POST['timeapproveremail_b'] <> "") {
    $data['custom_fields']['timeapproveremail_b'] = $_POST['timeapproveremail_b'];
} else {
    if(isset($data['custom_fields']['timeapproveremail_b'])){unset($data['custom_fields']['timeapproveremail_b']);}
}
if (isset($_POST['apinvoiceemailcommadelimited']) && $_POST['apinvoiceemailcommadelimited'] <> "") {
    $data['custom_fields']['apinvoiceemailcommadelimited'] =$_POST['apinvoiceemailcommadelimited'];
} else {
    if(isset($data['custom_fields']['apinvoiceemailcommadelimited'])){unset($data['custom_fields']['apinvoiceemailcommadelimited']);}
}
if (isset($_POST['ponumber']) && $_POST['ponumber'] <> "") {
    $data['custom_fields']['ponumber'] = $_POST['ponumber'];
} else {
    if(isset($data['custom_fields']['ponumber'])){unset($data['custom_fields']['ponumber']);}
}
if (isset($_POST['poamount']) && $_POST['poamount'] <> "") {
    $data['custom_fields']['poamount'] = $_POST['poamount'];
} else {
    if(isset($data['custom_fields']['poamount'])){unset($data['custom_fields']['poamount']);}
}
if (isset($_POST['poenddate']) && $_POST['poenddate'] <> "") {
    $data['custom_fields']['poenddate'] = $_POST['poenddate'];
} else {
    if(isset($data['custom_fields']['poenddate'])){unset($data['custom_fields']['poenddate']);}
}
/* delete portaluser for TempBack
if (isset($portalusers) && $portalusers <> "") {
    $data['custom_fields']['portalusers'] = $portalusers;
} else {
    if(isset($data['custom_fields']['portalusers'])){unset($data['custom_fields']['portalusers']);}
}
*/

if (isset($_POST['openorclosed']) && $_POST['openorclosed'] <> "") {
    $data['custom_fields']['openorclosed'] = $_POST['openorclosed'];
}
if (isset($_POST['ponote']) && $_POST['ponote'] <> "") {
    $data['custom_fields']['ponote'] = $_POST['ponote'];
} else {
    if(isset($data['custom_fields']['ponote'])){unset($data['custom_fields']['ponote']);}
}
if (isset($data['custom_fields']['skill'])) {
    unset($data['custom_fields']['skill']);
}
if (isset($data['custom_fields']['publishonicreativeswebpage']) ) {
    unset($data['custom_fields']['publishonicreativeswebpage']);
}
if (isset($data['custom_fields']['publishonicreativeswebpage_b']) ) {
    unset($data['custom_fields']['publishonicreativeswebpage_b']);
}

if (isset($data['custom_fields']['timesheetapprovalsemail']) ) {
    unset($data['custom_fields']['timesheetapprovalsemail']);
}
if (isset($data['custom_fields']['timesheetapprovalsemail']) ) {
    unset($data['custom_fields']['timesheetapprovalsemail']);
}
    
//    $data['custom_fields']['publishonicreativeswebpage'] = true;
//    $data['custom_fields']['publishonicreativeswebpage_b'] = "xxx";

	// Extract "custom_fields"
		$customFields = json_encode($data['custom_fields']);
		$position_name = $data['position_name'];
		// If we don't do this, we will never access the record without patching the custom fields
		if ($customFields == "[]") {
			$customFields= "{}";
		}

		$client = new \GuzzleHttp\Client();

		$response = $client->request('PATCH', 'https://api.manatal.com/open/v3/jobs/'.$id.'/', [
		'body' => '{"custom_fields":'.$customFields.',"position_name":"'.$position_name.'"}',
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
		'content-type' => 'application/json',
		],
		]);	
		
if (isset($data['custom_fields']['openorclosed']) && $data['custom_fields']['openorclosed'] == "Open") {
   $old_openorclosed = "Open";
} else {
   $old_openorclosed = "Closed";
}
		

// update all match records with that job nub with new po information
require_once __DIR__ . '/../db/db.php';
$conn = db();   
// update all match records with po information

// echo $_POST['openorclosed'] . "xxx". $_POST['old_openorclosed'];
// exit();

	if(isset($_POST['openorclosed']) && ($_POST['openorclosed'] == "Closed")) {
		$sql = "UPDATE ic_matches SET closed = 1 where job = '".$id."'";
		$result = $conn->query($sql);
	}
	if(!isset($_POST['openorclosed']) || ($_POST['openorclosed'] == "Open")) {
			$sql = "UPDATE ic_matches SET closed = 0, closed_date = '0000-00-00' where job = '".$id."'";
		$result = $conn->query($sql);
	}

if($_POST['openorclosed'] !== $_POST['old_openorclosed']) {
 
	if($_POST['openorclosed'] == "Open" && $_POST['old_openorclosed'] == "Closed") {
		echo '<script>alert("You have re-opened a job, remember to re-open any active assignments from the match record(s).")</script>'; 	
	}
	
	if($_POST['openorclosed'] == "Closed") {
		$sql = "UPDATE ic_matches SET closed = 1, closed_date = NOW(), deactive_date = NOW(), share = 0 where job = '".$id."'";
		$result = $conn->query($sql);
	}
}

// First update job
$sql = "UPDATE ic_matches SET po_number = '".$_POST['ponumber']."', 
	po_amount = '".$_POST['poamount']."', 
	po_end_date = '".$_POST['poenddate']."',
	po_note = '".$_POST['ponote']."', 
	portal_users= '".implode(', ',(array)$data['custom_fields']['portalusers'])."', 
	ap_email  = '".$_POST['apinvoiceemailcommadelimited']."' ,
	timeapproveremail  = '".$_POST['timeapproveremail']."' ,
	timeapproveremail_b  = '".$_POST['timeapproveremail_b']."' 
	WHERE organization = '".$_SESSION['company']."' AND job = '".$id."'";

$result = $conn->query($sql);

// decided this was a bad idea so commented below
// now update all jobs with the old ponumber with new, unless po is removed from job
/*
$sql = "UPDATE ic_matches SET po_number = '".$_POST['ponumber']."', 
	po_amount = '".$_POST['poamount']."', 
	po_end_date = '".$_POST['poenddate']."',
	po_note = '".$_POST['ponote']."' 

	WHERE organization = '".$_SESSION['company']."' AND po_number <> '' AND po_number = '".$_SESSION['old_po']."'";


$result = $conn->query($sql);
*/
// echo $response->getBody();
    // Close the popup
    echo "<script>window.close();</script>";
}
?>
