<!DOCTYPE html>
<html>
<head>
    <title>Popup Form</title>
	<!--
	  <link href="https://app.manatal.com/assets/css/vendors~index.2528a81b.css" rel="stylesheet"><link href="https://app.manatal.com/assets/css/index.dfb43d4a.css" rel="stylesheet">	
	-->
<script type="text/javascript">
 
       function timedMsg() {
            var t = setTimeout("document.getElementById('myMsg').style.display='none';", 4000);
        }

        function closeWindowAfterSubmit() {
            setTimeout(function () {
                window.close();
            }, 2000);
        }
</script>
	<style>
		/* Style for the buttons */
		.button {
			display: inline-block;
			padding: 7px 14px;
			border: none;
			background-color: #1976D2;
			color: white;
			font-size: 12px;
			cursor: pointer;
			transition: background-color 0.3s;
		}

		/* Hover style for the buttons */
		.button:hover {
			background-color: #418DDA;
		}
	</style>
	<!-- clipboard code -->
<script>
function copyToClipboard(text) {
    var textarea = document.createElement("textarea");
    textarea.value = text;
    textarea.style.position = "fixed";
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand("copy");
    document.body.removeChild(textarea);
    // Show confirmation message
    var notification = document.createElement("div");
    notification.textContent = "Match Number copied to clipboard: " + text;
    notification.style.position = "fixed";
    notification.style.top = "50px"; // Adjust as needed
    notification.style.left = "50%";
    notification.style.transform = "translateX(-50%)";
    notification.style.background = "#89CFF0";
    notification.style.padding = "10px";
    notification.style.borderRadius = "5px";
    document.body.appendChild(notification);
    // Set timeout to remove the notification after 2 seconds (2000 milliseconds)
    setTimeout(function() {
        document.body.removeChild(notification);
    }, 2000);
}
</script>
<style>
/* Example styling for the icon */
.icon {
    width: 20px;
    height: 20px;
    background: url('clipboard_icon.png') no-repeat;
    background-size: cover;
    cursor: pointer;
}
</style>
</head>
<body>
<?php

function generateDropdownOptions($userNames,$x) {

    $options = '';

    foreach ($userNames as $userName) {
		$selected = '';
		// echo "X = ".$x."-".$userName['display_name'];
		if ($x == $userName['display_name']){$selected = "selected";}
		$options .= '<option value="' . htmlspecialchars($userName['display_name']) . '"'. $selected .'>' . htmlspecialchars($userName['display_name']) . '</option>'. PHP_EOL;
	}
    return $options;
}


$sucess = false;
$matchId = $_GET['match_id'];
$match_id = $_REQUEST['match_id'];
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());
require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.manatal.com/open/v3/matches/'.$matchId.'/', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);

$response->getBody();

	$responseStr = $response->getBody();
	$match = json_decode($responseStr, true);
	
	$job_id= $match['job'];
	$candidate_id = $match['candidate'];	
	
// test if an Accounts Payable Contact has be setTimeout
// Define the query
$sql = "SELECT * FROM ic_company WHERE organization = '".$match['organization']."'";

// Execute the query
$result = $link->query($sql);

// Check if the query returned any results
// Check if the query returned any results
if ($result->num_rows == 0) {
    // No matching records found, output JavaScript to show custom alert and close window
    echo "<script type='text/javascript'>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = document.createElement('div');
                modal.style.position = 'fixed';
                modal.style.left = '50%';
                modal.style.top = '50%';
                modal.style.transform = 'translate(-50%, -50%)';
                modal.style.padding = '20px';
                modal.style.backgroundColor = '#89CFF0';
                modal.style.border = '1px solid #ccc';
                modal.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
                modal.style.width = '300px';
                modal.style.textAlign = 'center';
                modal.style.zIndex = '1000';

                var message = document.createElement('p');
                message.innerText = 'A/P person not set';
                message.style.margin = '0';
                message.style.padding = '10px 0';

                var button = document.createElement('button');
                button.innerText = 'Close';
                button.onclick = function() {
                    document.body.removeChild(modal);
                    window.close();
                };

                modal.appendChild(message);
                modal.appendChild(button);

                document.body.appendChild(modal);
            });
          </script>";
		  exit();
}
	
// Candidate Info 

$response = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/'.$match['candidate'].'/', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);

$response->getBody();

	$responseStr = $response->getBody();
	$candidate = json_decode($responseStr, true);
	
// Job info

$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/'.$job_id.'/', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);

$response->getBody();

	$responseStr = $response->getBody();
	$job = json_decode($responseStr, true);
	$hash = $job['hash'];
	$poamount = $job['custom_fields']['poamount'];
	$ponumber = $job['custom_fields']['ponumber'];
	$timeapproveremail = $job['custom_fields']['timeapproveremail'];
	$timeapproveremail_b = $job['custom_fields']['timeapproveremail_b'];
	// calculate how much of the PO has been spent

        $query = "SELECT SUM(ROUND(ts.hours * ts.billrate,2)) AS spent FROM ic_timesheets ts  
                  JOIN ic_matches m ON ts.AssignmentNumber = m.job AND ts.Employee_ID = m.candidate 
                  WHERE TRIM(m.po_number) = '". trim($ponumber). "' AND (ts.void <> 1) ";

        $sum = mysqli_query($link, $query);
        $rowS = mysqli_fetch_array($sum);
        $spent = $rowS['spent'];
// end PO spent amount	

	
	
// Company name

$response = $client->request('GET', 'https://api.manatal.com/open/v3/organizations/'.$match['organization'].'/', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);


$response->getBody();

	$responseStr = $response->getBody();
	$organization = json_decode($responseStr, true);
	// $customer_name = $organization['name'];

	
//	echo "<p style='font-family: Arial; font-size:17px;'><strong>". $candidate['full_name']."</strong></p>";


// End Candidate Info

// Start List of Users for Dropdown

$response = $client->request('GET', 'https://api.manatal.com/open/v3/users/', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);

$response->getBody();

	$responseStr = $response->getBody();
	$users = json_decode($responseStr, true);
	
	// echo "<br>". $users['results'][1]['full_name'];

// End List of Users for Dropdown
	
// echo "is it a post? ". $_SERVER["REQUEST_METHOD"];


	  if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST["match_id"];
		$match_id = $_POST["match_id"];
		$hash = $_POST["hash"];
		$share = $_POST["share"];
        $bill_rate = $_POST["bill_rate"];
        $pay_rate = $_POST["pay_rate"];
		$po_number = $_POST["po_number"];
		$start_date = $_POST["start_date"];
		$end_date = $_POST["end_date"];
		$salary= $_POST["salary"];
		$fee_percent = $_POST["fee_percent"];
		$owner_1_name = $_POST["owner_1_name"];
		if ( ($_POST["owner_1_percent"] == 0 || is_null($_POST["owner_1_percent"]))  && ($_POST["owner_2_percent"] == 0 || is_null($_POST["owner_2_percent"]))  && ($_POST["owner_3_percent"] == 0 || is_null($_POST["owner_3_percent"]) ) ) {
		  	$owner_1_percent = 100.00;
		  } else {
			$owner_1_percent = $_POST["owner_1_percent"];
		  }
		$owner_2_name = $_POST["owner_2_name"];
		$owner_2_percent = $_POST["owner_2_percent"];
		$owner_3_name = $_POST["owner_3_name"];
		$owner_3_percent = $_POST["owner_3_percent"];
		$candidate_id = $_POST["candidate_id"];
		$candidate_name = $_POST['candidate_name'];
		$notes = $_POST["notes"];
		$closed = $_POST["closed"];
		if($closed !== $pre_closed && $closed) {
			$closed_date = date('Y-m-d');
		} else {
			$closed_date = "0000-00-00";
		}	

		$mass_email= $_POST["mass_email"];
		$mass_text= $_POST["mass_text"];
		$full_time= $_POST["full_time"];
		if(isset($job['custom_fields']['poenddate']) && !is_null($job['custom_fields']['poenddate']) ) {
			$po_end_date = $job['custom_fields']['poenddate'];
		} else {
			$po_end_date = "0000-00-00";
		}
		
		// Calculate the expires_at date (today + 90 days)
		$expires_at = date('Y-m-d', strtotime('+90 days'));
		
		
		
        // Insert data into the database
		
		$sql = "INSERT INTO ic_matches (
		id, 
		external_id, 
		hash,
		share,
		owner, 
		organization,
		job,
		candidate,
		candidate_name,
		candidate_email,
		pay_group,
		file_number,
		department,
		creator,
		stage_id,
		stage_name,
		is_active,
		po_number,
		po_amount,
		po_end_date,
		po_note,
		ap_email,
		timeapproveremail,
		timeapproveremail_b,
		start_date,
		end_date,
		bill_rate,
		pay_rate,
		salary,
		fee_percent,
		owner_1_name,
		owner_1_percent,
		owner_2_name,
		owner_2_percent,
		owner_3_name,
		owner_3_percent,
		closed,
		closed_date,
		expires_at,
		notes,
		company_name,
		job_name,
		mass_email,
		full_time,
		portal_users,
		mass_text) 
		VALUES ('". 
		$match['id']."', '".
		$match['external_id']."', '".
		$job['hash']."', '".
		$share."', '".
		$match['owner']."', '".
		$match['organization']."', '".
		addslashes($match['job'])."', '".
		addslashes($match['candidate'])."', '".
		addslashes($candidate['full_name'])."', '".
		$candidate['email']."', '".
		$candidate['custom_fields']['paygroup']."', '".
		$candidate['custom_fields']['adpnumber']."', '".
		$candidate['custom_fields']['department']."', '".
		$match['creator']."', '".
		$match['stage']['id']."', '".
		addslashes($match['stage']['name'])."', ".
		$match['is_active'].", '".
		addslashes($job['custom_fields']['ponumber'])."', ".	
		$job['custom_fields']['poamount'].", '".
		$po_end_date ."', '".
		addslashes($job['custom_fields']['ponote'])."', '".
		addslashes($job['custom_fields']['apinvoiceemailcommadelimited'])."', '".		
		addslashes($job['custom_fields']['timeapproveremail'])."', '".		
		addslashes($job['custom_fields']['timeapproveremail_b'])."', '".		
		$start_date."', '".
		$end_date."', ".
		$bill_rate.",".
		$pay_rate.", '".
		addslashes($salary)."', ".	
		$fee_percent.", '".
		$owner_1_name."', ".
		$owner_1_percent.", '".
		$owner_2_name."', ".
		$owner_2_percent.", '".
		$owner_3_name."', ".
		$owner_3_percent.", ".
		$closed.", '".
		$closed_date."', '".
		$expires_at."', '".
		addslashes($notes)."', '".
		addslashes($organization['name'])."', '".
		addslashes($job['position_name'])."', ".
		$mass_email.",".
		$full_time.",'".
		implode(', ',(array)$job['custom_fields']['portalusers'])."',".
		$mass_text.")		
		
	ON DUPLICATE KEY UPDATE 
    external_id = VALUES(external_id),
	hash = VALUES(hash),
	share = VALUES(share),
    owner = VALUES(owner),
    organization = VALUES(organization),
    job = VALUES(job),
    candidate = VALUES(candidate),
	candidate_name = VALUES(candidate_name),
    candidate_email = VALUES(candidate_email),
	pay_group = VALUES(pay_group),
	file_number = VALUES(file_number),
	department = VALUES(department),
    creator = VALUES(creator),
    stage_id = VALUES(stage_id),
    stage_name = VALUES(stage_name),
    is_active = VALUES(is_active),
    po_number = VALUES(po_number),
	po_amount = VALUES(po_amount),
	po_end_date = VALUES(po_end_date),
	po_note = VALUES(po_note),
	ap_email = VALUES(ap_email),
	timeapproveremail = VALUES(timeapproveremail),
	timeapproveremail_b = VALUES(timeapproveremail_b),
    start_date = VALUES(start_date),
    end_date = VALUES(end_date),
    bill_rate = VALUES(bill_rate),
    pay_rate = VALUES(pay_rate),
    salary = VALUES(salary),
    fee_percent = VALUES(fee_percent),
    owner_1_name = VALUES(owner_1_name),
    owner_1_percent = VALUES(owner_1_percent),
    owner_2_name = VALUES(owner_2_name),
    owner_2_percent = VALUES(owner_2_percent),
    owner_3_name = VALUES(owner_3_name),
	owner_3_percent = VALUES(owner_3_percent),
	closed = VALUES(closed), 
	closed_date = VALUES(closed_date), 
	expires_at = VALUES(expires_at), 
    notes = VALUES(notes),
	company_name = VALUES(company_name),
	job_name = VALUES(job_name),
	mass_email = VALUES(mass_email),
	full_time = VALUES(full_time),
	portal_users = VALUES(portal_users),
	mass_text = VALUES(mass_text)";
		
		$sql = str_replace(", ,",",0,",$sql);
		$sql = str_replace("''","NULL",$sql);
		$sql = str_replace(" = ,"," = NULL,",$sql);
		
	
// echo "<br><br>SQL = " . $sql . "<br>";
// exit();
		$sucess = false;
        if ($link->query($sql) === TRUE) {
			$sucess = true;
				//echo "<br>Saved";
        } else {
            echo "Error: " . $sql . "<br>" . $link->error;
        }
		
		echo '<script language="JavaScript" type="text/javascript">timedMsg()</script>';
		echo '	<script language="JavaScript" type="text/javascript">window.close();</script>";}';	
    }
	
	$query = "select * from ic_matches where id = '".$match_id."'";
	// echo $query;
	$SQLr = mysqli_query($link,$query );
	$row = mysqli_fetch_array($SQLr);
	$pre_closed = $row['closed'];
	// echo "pre-closed = ".$pre_closed;
	
	if ($row['declined']) {
	echo '<div style="color:#b22625; float:left; font-family: Arial; font-size:14px;"<br><strong>Dropped by Customer</strong> </div>';
	}
	?>
    <div style="float:left; font-family: Arial; font-size:14px;"><strong><?php echo $candidate['full_name']?></strong> (Match ID: <?php echo $matchId; ?>) &nbsp;&nbsp;</div>
	<div style="float:left;" class="icon" onclick="copyToClipboard('<?php echo $matchId; ?>')"></div>

	<div style="height:11px; clear:both; font-family: Arial; font-size:10px;"><br><strong>Position:</strong> <?php echo $job['position_name'] ?></div>
    <div style="height:11px; padding:bottom:20px; font-family: Arial; font-size:10px;"><br><strong>Company:</strong> <?php echo $organization['name']; ?></div>
	<p>
	<!-- <form action="/api/manatal/match.php/?match_id=<?php echo $matchId; ?>" method="post" onsubmit="closeWindowAfterSubmit()"> -->

    <form action= "/api/manatal/match.php/?match_id=<?php echo $matchId; ?>" method= "post">
        <!-- Form inputs go here -->
        <input type= "hidden" name= "match_id" value= "<?php echo $matchId; ?>">
		<input type= "hidden" name= "owner" value= "<?php echo $match['owner']; ?>">
		<input type= "hidden" name= "candidate" value= "<?php echo $match['candidate']; ?>">
		<input type= "hidden" name= "candidate_id" value= "<?php echo $candidate['full_name']; ?>">
		<input type= "hidden" name= "hash" value= "<?php echo $job['hash']; ?>">
		<input type= "hidden" name= "candidate_email= "<?php echo $candidate['candidate_email']; ?>">
		<input type= "hidden" name= "candidate_name= "<?php echo $candidate['full_name']; ?>">
		<input type= "hidden" name= "portal_users= "<?php echo $job['custom_fields']['portalusers']; ?>">
		<input type= "hidden" name= "creator"<?php echo $match['creator']; ?>">
		<table style="font-family: Arial; font-size:14px;">
		<tr>
		
		<td>Last Viewed:</td>
			<td>
			<?php 
			
			echo $row['last_viewed_date']. " - Count = ".$row['view_count']; ?>
			</td>
		</tr>
		<tr>
		<?php echo "<td>Customer Rating:</td><td>".str_repeat("&#9733",$row['rating']).str_repeat("&#9734;",(5-$row['rating']))."</td>"; ?>
		</tr>
		<tr>
		<td>PO Number: </td><td><?php echo $job['custom_fields']['ponumber']; ?><br></td>
		</tr>
		<tr>
		<td>PO Amount </td><td>$<?php echo number_format($job['custom_fields']['poamount'],2); ?><br></td>
		</tr>
		<tr>
		<td>PO Spent </td><?php 
		if ($spent > ($poamount*.90)) {
			echo "<td style='color: red;'>$" . number_format($spent, 2) . "</td>";
		} else {
			echo "<td>$" . number_format($spent, 2) . "</td>";
		}
		?>
		<br>
		</td>
		</tr>


		<!-- 
		<input type= "text" name= "po_number" value= "<?php 
			if(empty($row['po_number'])){
				echo $job['custom_fields']['ponumber']; 
			}else{
			echo $row['po_number'];} ?>"><br></td>
		-->

		<tr>
		<td>Pay Rate:</td><td><input type="number" step="0.01" name="pay_rate" data-type="currency" value="<?php echo ($row['pay_rate'] === null) ? "0.00" : $row['pay_rate']; ?>"><br></td>
		</tr>
		<tr>
        <td>Bill Rate:</td><td><input type="number" step="0.01" name="bill_rate" data-type="currency" value="<?php echo ($row['bill_rate'] === null) ? "0.00" : $row['bill_rate']; ?>"><br></td>
		</tr>
		<tr>
		<td>Salary:</td><td><input type= "money" name= "salary" value= "<?php echo $row['salary'] ?>"><br></td>
		</tr>
		<tr>
		<td>Fee Percent:</td><td><input type= "number" step=0.01 name= "fee_percent" value= "<?php echo $row['fee_percent'] ?>"><br></td>
		</tr>
		<tr>
		<td>Timesheet Status:</td><td>
		<select name= "closed">
			<option value = false <?php if($row['closed'] == 0){ echo "selected";}?>>Active</option>
			<option value = true  <?php if($row['closed'] == 1){ echo "selected";}?>>Not Active</option>
		</select>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Bill Full_time:
		<input type="hidden" name="full_time" value="0">
		<input type="checkbox" name="full_time" value="1" <?php echo ($row['full_time'] == 1) ? 'checked' : ''; ?>>
		</td>
		</tr>
		<tr>
		<td>Portal Share</td>
		<td><input type="hidden" name="share" value="0">
			<input type="checkbox" name="share" value="1" <?php echo ($row['share'] == 1) ? 'checked' : ''; ?>>
			<?php echo "Expires: ". $row['expires_at']; ?>

		</tr>	
		<tr>

		<td>Owner 1 Name:</td><td>
			<?php $dropdownOptions = generateDropdownOptions($users['results'],$row['owner_1_name']); ?>
			<select name= "owner_1_name" required>
				<option value= "" <?php if($row['owner_1_name']== ""){echo "selected";}?>></option>		
				<?php echo $dropdownOptions ; ?>
			</select><br>
		</td>
		</tr>
		<tr>
		<td>Owner 1 Percent:</td><td><input type= "number" step=0.01 name= "owner_1_percent" value= "<?php echo $row['owner_1_percent'] ?>"><br></td>
				</tr>
		<td>Owner 2 Name:</td><td>
			<?php $dropdownOptions = generateDropdownOptions($users['results'],$row['owner_2_name']); ?>
			<select name= "owner_2_name">
				<option value= "" <?php if($row['owner_2_name']== ""){echo "selected";}?>></option>		
				<?php echo $dropdownOptions ; ?>
			</select><br>
		</td>
		</tr>
		<tr>
		<td>Owner 2 Percent:</td>
		<td><input type= "number" step=0.01 name= "owner_2_percent" value= "<?php echo $row['owner_2_percent'] ?>"><br></td>
		</tr>
		<td>Owner 3 Name:</td><td>
			<?php $dropdownOptions = generateDropdownOptions($users['results'],$row['owner_3_name']); ?>
			<select name= "owner_3_name">
				<option value= "" <?php if($row['owner_3_name']== ""){echo "selected";}?>></option>		
				<?php echo $dropdownOptions ; ?>
			</select><br>
		</td>
		</tr>
		<tr>
		<td>Owner 3 Percent:</td><td><input type= "number" step=0.01 name= "owner_3_percent" value= "<?php echo $row['owner_3_percent'] ?>"><br></td>
				</tr>
		<tr>
		<td>Start Date:</td><td><input type="date" name="start_date" value="<?php if($row['start_date'] == null){echo "";}else{ echo date("Y-m-d", strtotime($row['start_date']));} ?>" ><br></td>
		</tr>
		<tr>
		<td>End Date:</td><td><input type="date" name="end_date" value="<?php if($row['end_date'] == null){echo "";}else{ echo date("Y-m-d", strtotime($row['end_date']));} ?>" ><br></td>
		</tr>
		<tr>
		<td>Mass Email:</td><td>
		<select name= "mass_email">
			<option value = false <?php if($row['mass_email'] == 0){ echo "selected";}?>>Clear</option>
			<option value = true  <?php if($row['mass_email'] == 1){ echo "selected";}?>>Sent</option>
		</select>
		</tr>
		<tr>
		<td>Mass Text:</td><td>
		<select name= "mass_text">
			<option value = false <?php if($row['mass_text'] == 0){ echo "selected";}?>>Clear</option>
			<option value = true  <?php if($row['mass_text'] == 1){ echo "selected";}?>>Sent</option>
		</select>
		</tr>
		<tr>
		<td></td><td></td>
		</tr>
		<tr>
        <td><input type= "submit" value= "SAVE" class="button">
		<?php if($sucess){echo "<a id='myMsg'><span style='color:red;'>Saved</span></a>";} ?>
		<script language="JavaScript" type="text/javascript">timedMsg()</script>

		</td>
		<td align="right"><input type="button" class="button" name="cancelvalue" value="CANCEL" onClick="window.close();"> 
		</td>
		</tr>
				<tr>
		<!-- 
		<td>Notes:</td><td>
		
		<textarea name= "notes" rows= "10" cols= "25" ><?php echo $row['notes'] ?></textarea>
			
		<br></td>
		-->
		<td>Customer Comments:</td><td>
		
		<?php echo $row['customer_comments'] ?>
			
		</td>
		
		</tr>

		</table>
    </form>
	<p><br><p>
</body>
</html>
