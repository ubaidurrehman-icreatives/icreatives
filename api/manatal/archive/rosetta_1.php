

<?php

ob_clean();
$datetime_1 = date("Y-m-d H:i:s");
ini_set('max_execution_time', 10000);
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

$page_num = $_REQUEST['page'];
$first_page = $_REQUEST['page'];
$count = 10;

if(empty($page_num)) { 
	echo "MISSING PAGE NUMBER https://www.icreatives.com/api/manatal/rosetta_1.php?page=XXXX";
	exit();
}

require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$client = new \GuzzleHttp\Client();
// Global $client;
// 1385366 walmart packaging



function tracker_job($id) {
	// require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";

	global $client;

	$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/'.$id.'/', [
	'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
	],
	]);
	$response->getBody();

	$responseStr = $response->getBody();
	$job_arr = json_decode($responseStr, true);
	// $t_job = $job_arr['custom_fields']['trackerid'];
	 return $job_arr;
}
// 60944756
function tracker_candidate($id) {
	// require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
	global $client;


	$response = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/'.$id.'/', [

	'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
	],
	]);
	$response->getBody();

	$responseStr = $response->getBody();
	$candidate_arr = json_decode($responseStr, true);
	// $t_candidate = $candidate_arr['custom_fields']['trackerid'];
	 return $candidate_arr;
}

//1952777
function tracker_company($id) {
	require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";

	$client = new \GuzzleHttp\Client();

	$response = $client->request('GET', 'https://api.manatal.com/open/v3/organizations/'.$id.'/', [


	'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
	],
	]);
	$response->getBody();

	$responseStr = $response->getBody();
	$company_arr = json_decode($responseStr, true);
	// $t_company = $company_arr['custom_fields']['trackerid'];
	 return $company_arr;
}

// build invoices and matches for first 50 matches


while ($page_num > 0 ) {
	$response = $client->request('GET', 'https://api.manatal.com/open/v3/matches/?stage__in=946505%2C142142&created_at__gte=2023-01-01&page='. $page_num .'&page_size=25', [
		'headers' => [
		'Authorization' => $token,
		'accept' => 'application/json',
	],
	]);	
	
	$response->getBody();

	$responseStr = $response->getBody();
	$match_arr = json_decode($responseStr, true);
	
	
	echo "<br>count= ". $match_arr['count']."<BR>";
	

	
	// keep track of count to see if we should add a page
	// echo "<br>next= ".$match_arr['next']."<br>"; 
	If (is_null($match_arr['next'])) {
			// echo "<br>next= ".$match_arr['next']; 
	}

 	echo "<br>";
	$page_count = $match_arr['count'];	

	
	for($x=0; $x<count($match_arr['results']); $x++) {
		
		$match_arr['results'][$x]['id'] . "<br>";
		// GET info
		$job_arr =  tracker_job($match_arr['results'][$x]['job']) ;
		$candidate_arr =  tracker_candidate($match_arr['results'][$x]['candidate']);
		$company_arr =  tracker_company($match_arr['results'][$x]['organization']);
		
		echo $tt_query = "
			SELECT * FROM ic_webtime 
			WHERE 	Employee_ID = ". $candidate_arr['custom_fields']['trackerid'] . " 
			AND 	Assignment_ID = ".  $job_arr['custom_fields']['trackerid'];		
		
		$tt_SQL = mysqli_query($link,$tt_query );
		$q = 0;
		while($tt_row = mysqli_fetch_array($tt_SQL)){			
			
			// First Build Timesheet from old invoice info
			$q = $q + 1;
			// Insert data into the database

			$strSQL = "INSERT INTO ic_timesheets " ;
			$strSQL = $strSQL . " ( 
			company_name, 
			company_id, 
			billrate, 
			payrate, 
			Employee_ID, 
			first_name, 
			Last_name, 
			title, 
			Primary_Contact_Email, 
			Second_Contact_Email, 
			Hours, 
			EmployeeIpAddr, 
			CustomerIpAddr,
			Signature,
			WeekEnding, 
			Continuing, 
			Unique_id, 
			SuperEmail, 
			Assignment_ID, 
			AssignmentNumber, 
			Reminders, 
			EmpEmail, 
			AcctEmail,
			SentDate, 
			ApproveDate, 
			DeclineDate" ;       		
			for ($i = 1; $i <= 7; $i++) {
        		$strSQL = $strSQL . ", TimeInHr" . $i . ", " ;       
        		$strSQL = $strSQL . "TimeOutHr" . $i . ", " ;
				$strSQL = $strSQL . "Break" . $i . " " ;
			}
			
			list($first_name,$last_name) = explode(" ",$candidate_arr['full_name']);
			
			$strSQL = $strSQL . ") VALUES  ('" .
			$company_arr['name']."', '". 
			$match_arr['results'][$x]['organization']."', '".
			$tt_row["billrate"]."', '".
			$tt_row["payrate"] ."', '" . 
			$match_arr['results'][$x]['candidate'] . "', '"  . 
			$first_name. "', '"  .
			$last_name . "', '"  . 
			$tt_row['title']. "', '"  .
			$tt_row['Primary_Contact_Email'] . "', '"  . 
			$tt_row['Second_Contact_Email']. "', "  . 
			$tt_row['Hours'] . ", '" . 
			$tt_row['EmployeeIpAddr'] . "', '" . 
			$tt_row['CustomerIpAddr'] . "', '" . 
			$tt_row['Signature'] . "', '" . 
			$tt_row['WeekEnding'] . "', " .
			$tt_row['Continuing'] . ", '" . 
			$tt_row['Unique_id']  . "', '" . 
			$tt_row['SuperEmail']  . "', '" . 
			$match_arr['results'][$x]['id']. "', '" . 
			$match_arr['results'][$x]['job']. "', 0, '" . 
			$tt_row['EmpEmail'] . "', '" .  
			$tt_row['AcctEmail'] . "', '" .
			$tt_row['SentDate'] ."', '". 
			$tt_row['ApproveDate']  . "', '". 
			$tt_row['DeclineDate']  . "'";

			for ($i = 1; $i <= 7; $i++) {
				$strSQL = $strSQL . ", " . $tt_row["TimeInHr" . $i]  ;
				$strSQL = $strSQL . ", " . $tt_row["TimeOutHr" . $i] ;
				$strSQL = $strSQL . ", " . $tt_row["Break" . $i];

			}
			$strSQL = $strSQL . ") " ;
			
			$strSQL ;
			// exit();

			$resMySel = mysqli_query($link,$strSQL);
			
			
			// End Insert
			$billrate = $tt_row['billrate'];
			$payrate =  $tt_row['payrate'];
			
					// time_nanosleep(0, 500000000); // 1/2 second
		}
		
		// Last Build Match from old invoice info
		if ($q > 0 ) {
		$m_SQL = "INSERT INTO ic_matches (
		id, 
		owner, 
		organization,
		company_name,
		job,
		candidate,
		candidate_name,
		candidate_email,
		creator,
		stage_id,
		stage_name,
		is_active,
		po_number,
		start_date,
		end_date,
		bill_rate,
		pay_rate,
		closed,
		job_name)
		VALUES ('". 		
		$match_arr['results'][$x]['id']."', '".
		$match_arr['results'][$x]['owner']."', '".
		$match_arr['results'][$x]['organization']."', '". 
		$company_arr['name']."', '".
		$match_arr['results'][$x]['job']."', '".
		$match_arr['results'][$x]['candidate']."', '".
		$candidate_arr['full_name']."', '".
		$candidate_arr['email']."', '".
		$match_arr['results'][$x]['creator']."', '".
		$match_arr['results'][$x]['stage']['id']."', '".
		$match_arr['results'][$x]['stage']['name']."', ".
		$match_arr['results'][$x]['is_active'].", '".
		$job_arr['custom_fields']['ponumber']."', '".
		$job_arr['custom_fields']['startdate']."', '".
		$job_arr['custom_fields']['enddate']."', ".
		$billrate.",".
		$payrate.", ";
		if($job_arr['custom_fields']['openorclosed'] == "Open") {
			$m_SQL = $m_SQL . "0";
		}else{
			$m_SQL = $m_SQL . "1";
		}	
		$m_SQL = $m_SQL .", '".
		$job_arr['position_name']."')	";
		$sucess = false;
        if ($link->query($m_SQL) === TRUE) {
			$sucess = true;
			//echo "<br>Saved";
        } else {
            echo "Error: " . $m_SQL . "<br>" . $conn->error;
        }
		
		}

    }
		
	echo "<br><br><br>Page Number = ".$page_num."<br>";	
	$page_num = $page_num + 1;
		// exit(); // just do one page please
		echo "next api page = ".$match_arr['next']."<br>";
	If (is_null($match_arr['next'])) {
		$page_num = 0;
		exit();
	} 
		// echo "Page Number = ".$page_num ."<br>";
	if ($page_num > $first_page + 5 )  {
		// delete below after test
		$datetime_2 = date("Y-m-d H:i:s"); 
 
		$from_time = strtotime($datetime_1); 
		$to_time = strtotime($datetime_2); 
		echo "<P>";
		echo $diff_minutes = round(abs($from_time - $to_time) / 60,2). " minutes";
		Echo "<br>Done";
		exit();
	}
    sleep(60);
}
$datetime_2 = date("Y-m-d H:i:s"); 
 
$from_time = strtotime($datetime_1); 
$to_time = strtotime($datetime_2); 
echo "<P>";
echo $diff_minutes = round(abs($from_time - $to_time) / 60,2). " minutes";
Echo "<br>Done";

?>