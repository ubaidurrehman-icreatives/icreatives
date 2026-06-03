<?php

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




// fix invoices and matches for first 99 matches

// $response = $client->request('GET', 'https://api.manatal.com/open/v3/matches/?stage__in=946505%2C142142&created_at__gte=2023-01-01&page='. $page_num .'&page_size=99', [

while ($page_num > 0 ) {
		$response = $client->request('GET', 'https://api.manatal.com/open/v3/matches/?stage__in=946505&page='. $page_num .'&page_size=99', [		'headers' => [
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
		// $job_arr =  tracker_job($match_arr['results'][$x]['job']) ;
		// $candidate_arr =  tracker_candidate($match_arr['results'][$x]['candidate']);
		// $company_arr =  tracker_company($match_arr['results'][$x]['organization']);
		
		$query2 = "SELECT * from ic_timesheets where 
		AssignmentNumber = '".$match_arr['results'][$x]['job']."' AND 
		Employee_ID = '".$match_arr['results'][$x]['candidate']."' LIMIT 1";

		$SQL2 = mysqli_query($link,$query2 );
		$row2 = mysqli_fetch_array($SQL2);
		echo "company Name = ".$row2['first_name'];


		while ($row2 = mysqli_fetch_array($SQL2)) {

				// Insert data into the database

				$strSQL = "UPDATE ic_timesheets SET Assignment_ID = '". $match_arr['results'][$x]['id'] . "' 
				where company_id = '". $match_arr['results'][$x]['organization']. "' AND 
				AssignmentNumber = '".$match_arr['results'][$x]['job']."' AND
				Employee_ID = '".$match_arr['results'][$x]['candidate']."' ";

				echo $strSQL ;
				$resMySel = mysqli_query($link,$strSQL);
				
			// End Insert

		}

	
		echo "<br><br><br>Page Number = ".$page_num."  ";	
			// exit(); // just do one page please
		If (is_null($match_arr['next']) || $page_num > 65) {
			$page_num = 0;
			exit();
		} else { 
			$page_num = $page_num + 1;
		
		}
				/*
		} else {
			$page_num = $page_num + 1;
			// echo "Page Number = ".$page_num ."<br>";
			sleep(60);
			if ($page_num > $first_page+5) {exit();}
				$from_time = strtotime($datetime_1); 
				$to_time = strtotime($datetime_2); 
				echo "<P>";
				echo $diff_minutes = round(abs($from_time - $to_time) / 60,2). " minutes";
				Echo "<br>Done";
				exit();
			}
*/

	}

		sleep(10);
}
$datetime_2 = date("Y-m-d H:i:s"); 
 
$from_time = strtotime($datetime_1); 
$to_time = strtotime($datetime_2); 
echo "<P>";
echo $diff_minutes = round(abs($from_time - $to_time) / 60,2). " minutes";
Echo "<br>Done";

?>