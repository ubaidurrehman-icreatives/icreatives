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

        input[type="number"], input[type="text"], input[type="email"], input[type="tel"], input[type="checkbox"], input[type="date"], select {
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body style="background-color: #f9f9f9;">
<!-- https://www.icreatives.com/api/candidate.php?id=58031587?tab=more&full_name=bill%20gate -->

<?php
session_start();

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
use GuzzleHttp\Exception\ClientException;
$data = $_SESSION['data'];
$id = $_SESSION['id'];
$_SESSION['full_name'] = $_REQUEST['full_name'];
$full_name = $_SESSION['full_name'];

require_once __DIR__ . '/../db/db.php';
$link = db();  
require_once  dirname(__DIR__) . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
// Retrieve request variables
$id = $_REQUEST['id'];

	// Fix flaw in candidate chrome extension
$id = str_replace("?tab=more","",$id);
$full_name = $_REQUEST['full_name'];


// retreive mantatl job custom fields

$client =  new \GuzzleHttp\Client(['timeout' => 5.0,'connect_timeout' => 3.0]);

// Load match
try {
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/'.$id.'/', [
	'headers' => [
    'Authorization' => 'Token 92e3967b096dc33e0f09df8c0a927ec0437d8942',
    'accept' => 'application/json',
	],
	]);
	
} catch (\GuzzleHttp\Exception\ClientException $e) {
    $body = (string) $e->getResponse()->getBody();
    $json = json_decode($body, true);
    $wait = 60;
    if (isset($json['detail']) && preg_match('/available in (\d+) seconds/', $json['detail'], $matches)) {
        $wait = $matches[1];
    }
    echo "<p> </p><p>The server is busy. Please close this window, wait {$wait} seconds and try again</p>";
    exit();
}
	

$responseStr = $response->getBody();
$data = json_decode($responseStr, true);
$_SESSION['data'] = $data;
$_SESSION['id'] = $id;

$_SESSION['email'] = $data['email'];

$overtime = isset($data['custom_fields']['overtime']) ? $data['custom_fields']['overtime'] : null;
// $password = isset($data['custom_fields']['password']) ? $data['custom_fields']['password'] : null;
$paygroup = isset($data['custom_fields']['paygroup']) ? $data['custom_fields']['paygroup'] : null;
$adpnumber = isset($data['custom_fields']['adpnumber']) ? $data['custom_fields']['adpnumber'] : null;
$department = isset($data['custom_fields']['department']) ? $data['custom_fields']['department'] : null;
$drug = isset($data['custom_fields']['drug']) ? $data['custom_fields']['drug'] : null;
$background = isset($data['custom_fields']['background']) ? $data['custom_fields']['background'] : null;

?>
<div class="popup-form">
    <form action="candidate.php" method="post">
        <table>
            <tr>
                <td>Id:</td>
                <td><?php echo $id; ?></td>
            </tr>
		    <tr>
                <td>Name:</td>
                <td><?php echo $data['full_name']; ?></td>
            </tr>
	    <tr>
		<td>Overtime:</td>
		<td><input type="checkbox" name="overtime" value=true <?php echo ($overtime == true) ? 'checked' : ''; ?>></td>
	    </tr>
		<!--
           <tr>
                <td>Password:</td>
                <td><input type="text" name="password"  value="<?php echo $password; ?>"></td>
            </tr>
			-->
             <tr>
                <td>ADP Paygroup:</td>
                <td><input type="text" name="paygroup" value="<?php echo $paygroup; ?>"></td>
            </tr>
            <tr>
                <td>APD Department:</td>
                <td><input type="text" name="department" value="<?php echo $department; ?>"></td>
            </tr>
            <tr>
                <td>ADP Number:</td>
                <td><input type="text" name="adpnumber" value="<?php echo $adpnumber; ?>"></td>
            </tr>
			 <tr>
                <td>Background Check:</td>
                <td><input type="date" name="background" value="<?php echo $background; ?>"></td>
            </tr>
			<tr>
                <td>Drug Test:</td>
                <td><input type="date" name="drug" value="<?php echo $drug; ?>"></td>
            </tr>
			<tr>
				<td>Update Email Address on Timesheets:</td>
				<td><input type="checkbox" name="update_email"></td>
			</tr>
         </table>
        <button type="submit" class="btn" name="save">Save</button>
	<button type="button" class="btn" onclick="window.close();">Cancel</button>
	<?php $paygroup = isset($data['custom_fields']['paygroup']) ? $data['custom_fields']['paygroup'] : null; ?>

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
    $overtime = $_POST['overtime'];
    // $password = $_POST['password'];
    $paygroup = $_POST['paygroup'];
    $adpnumber = $_POST['adpnumber'];
    $department = $_POST['department'];
	$full_name = $data['full_name'];
  
if (isset($_POST['overtime']) && $_POST['overtime'] <> "") {
    $data['custom_fields']['overtime'] = $_POST['overtime'];
} else {
    if(isset($data['custom_fields']['overtime'])){unset($data['custom_fields']['overtime']);}
}
/*
if (isset($_POST['password']) && $_POST['password'] <> "") {
    $data['custom_fields']['password'] = $_POST['password'];
} else {
    if(isset($data['custom_fields']['password'])){unset($data['custom_fields']['password']);}
}
*/
if (isset($_POST['paygroup']) && $_POST['paygroup'] <> "") {
    $data['custom_fields']['paygroup'] =$_POST['paygroup'];
} else {
    if(isset($data['custom_fields']['paygroup'])){unset($data['custom_fields']['paygroup']);}
}
if (isset($_POST['adpnumber']) && $_POST['adpnumber'] <> "") {
    $data['custom_fields']['adpnumber'] = $_POST['adpnumber'];
} else {
    if(isset($data['custom_fields']['adpnumber'])){unset($data['custom_fields']['adpnumber']);}
}
if (isset($_POST['department']) && $_POST['department'] <> "") {
    $data['custom_fields']['department'] = $_POST['department'];
} else {
    if(isset($data['custom_fields']['department'])){unset($data['custom_fields']['department']);}
}

if (isset($_POST['link']) && $_POST['link'] <> "") {
    $data['custom_fields']['link'] = $_POST['link'];
}
if (isset($_POST['link_b']) && $_POST['link_b'] <> "") {
    $data['custom_fields']['link_b'] = $_POST['link_b'];
}
if (isset($_POST['link_c']) && $_POST['link_c'] <> "") {
    $data['custom_fields']['link_c'] = $_POST['link_c'];
}
if (isset($_POST['link_d']) && $_POST['link_d'] <> "") {
    $data['custom_fields']['link_d'] = $_POST['link_d'];
}
if (isset($_POST['linkname']) && $_POST['linkname'] <> "") {
    $data['custom_fields']['linkname'] = $_POST['linkname'];
}
if (isset($_POST['linkname_b']) && $_POST['linkname_b'] <> "") {
    $data['custom_fields']['linkname_b'] = $_POST['linkname_b'];
}
if (isset($_POST['linkname_c']) && $_POST['linkname_c'] <> "") {
    $data['custom_fields']['linkname_c'] = $_POST['linkname_c'];
}
if (isset($_POST['linkname_d']) && $_POST['linkname_d'] <> "") {
    $data['custom_fields']['linkname_d'] = $_POST['linkname_d'];
}
if (isset($_POST['drug']) && $_POST['drug'] !== '' && $_POST['drug'] !== '0001-01-01') {
    $d = DateTime::createFromFormat('Y-m-d', $_POST['drug']);
    if ($d && $d->format('Y-m-d') === $_POST['drug']) {
        $data['custom_fields']['drug'] = $_POST['drug'];
    }
} else {
    $data['custom_fields']['drug'] = null; // or omit this key if your API prefers
}
if (isset($_POST['background']) && $_POST['background'] !== '' && $_POST['background'] !== '0001-01-01') {
    $d = DateTime::createFromFormat('Y-m-d', $_POST['background']);
    if ($d && $d->format('Y-m-d') === $_POST['background']) {
        $data['custom_fields']['background'] = $_POST['background'];
    }
} else {
    $data['custom_fields']['background'] = null; // or omit this key if your API prefers
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

		$client =  new \GuzzleHttp\Client(['timeout' => 5.0,'connect_timeout' => 3.0]);

		// Load match
		try {


		$response = $client->request('PATCH', 'https://api.manatal.com/open/v3/candidates/'.$id.'/', [
		'body' => '{"custom_fields":'.$customFields.',"full_name":"'.$full_name.'"}',
		'headers' => [
		'Authorization' => 'Token 92e3967b096dc33e0f09df8c0a927ec0437d8942',
		'accept' => 'application/json',
		'content-type' => 'application/json',
		],
		]);	
		// echo $response->getBody();
		} catch (\GuzzleHttp\Exception\ClientException $e) {
			$body = (string) $e->getResponse()->getBody();
			$json = json_decode($body, true);
			$wait = 60;
			if (isset($json['detail']) && preg_match('/available in (\d+) seconds/', $json['detail'], $matches)) {
				$wait = $matches[1];
			}
			echo "<p> </p><p>The server is busy. Please close this window, wait {$wait} seconds and try again</p>";
			exit();
		}
	
	// update the ADP paygroup for the candidate on all matches where candidate is working
	

	$query = "UPDATE ic_matches SET 
		pay_group = '". $paygroup . "', 
		file_number = '". $adpnumber. "', 
		department = '". $department. "' 
		WHERE candidate = '". $id . "' AND is_active = 1 ";

	$result = mysqli_query($link,$query);	
	
	$email = $_SESSION['email'];
	if ( isset($email) && !empty($email) && isset( $_REQUEST['update_email']) && $_REQUEST['update_email'] ) {
	    echo $query = "UPDATE ic_timesheets 
         SET Email = '{$email}' 
       WHERE Employee_ID = '{$id}' ";
	   $result = mysqli_query($link,$query);
	   echo $query = "UPDATE ic_matches 
         SET candidate_email = '{$email}' 
       WHERE candidate = '{$id}' ";
	   $result = mysqli_query($link,$query);
exit();
	  
	   }

    // Close the popup
    echo "<script>window.close();</script>"; 
}
?>
