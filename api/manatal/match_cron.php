<?php

// Buu and Tai, we need to fix this so if we go over the API limit, we can run the next page

// since it runs as a cron, we had to do a lot if "isset()" tests to avoid errors
$datetime_1 = date("Y-m-d H:i:s");
ini_set('max_execution_time', 10000);

$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

// everything updated within a certain time
$after =  date("Y-m-d",strtotime('today - 1 days'));
echo "updates after ".$after;

$page_num = 1;
$count = 100;

require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$client = new \GuzzleHttp\Client();

	$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/?updated_at__gte='.$after.'&page='. $page_num .'&page_size=100', [
	'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
	],
	]);

	$responseStr = $response->getBody();
	$jobs = json_decode($responseStr, true);
	
	echo "<br>count= ". $jobs['count']."<BR>";
	
	// keep track of count to see if we should add a page
				echo "<br>next= ".$jobs['next']."<br>"; 
	If (is_null($jobs['next'])) {
			echo "<br>next= ".$jobs['next']; 
	}

 	echo "<br> Page Count = ";
	echo $page_count = count($jobs['results']);
	
	// position_name
	for($x=0; $x<count($jobs['results']); $x++) {
		//  echo $jobs['results'][$x]['custom_fields']['portalusers'];
		if(isset($jobs['results'][$x]['custom_fields']['portalusers'])){
			$portalusers_array = implode(', ',(array)$jobs['results'][$x]['custom_fields']['portalusers']);
		} else {
			$portalusers_array = "";
		}
		if(isset($jobs['results'][$x]['custom_fields']['ponumber'])){
			$po_number = $jobs['results'][$x]['custom_fields']['ponumber'];
		} else {
			$po_number = "";
		}
		if(isset($jobs['results'][$x]['custom_fields']['apinvoiceemailcommadelimited'])){
			$ap_email = $jobs['results'][$x]['custom_fields']['apinvoiceemailcommadelimited'];
		} else {
			$ap_email = "";
		}
		if(isset($jobs['results'][$x]['custom_fields']['poamount'])){
			$po_amount = $jobs['results'][$x]['custom_fields']['poamount'];
		} else {
			$po_amount = "";
		}
		if(isset($jobs['results'][$x]['custom_fields']['poenddate'])){
			$po_end_date = $jobs['results'][$x]['custom_fields']['poenddate'];
		} else {
			$po_end_date = "0000-00-00";
		}
		$position_name = $jobs['results'][$x]['position_name'];
		$organization = $jobs['results'][$x]['organization'];
		
		$query = "UPDATE ic_matches set 
			job_name = '". $position_name ."',
			portal_users = '". $portalusers_array ."', 
			po_amount = '". $po_amount ."', 
			po_end_date = '". $po_end_date ."', 
			po_number = '". $po_number ."', 
			ap_email = '". $ap_email ."' 
			WHERE job = '" . $jobs['results'][$x]['id'] . "'";
		echo $query."<br><br>";
		$result = mysqli_query($link,$query );
		
		if (isset($po_number) && trim($po_number) <> "" && !empty(trim($po_number))) {
			$query = "UPDATE ic_matches set  
			po_amount = '". $po_amount ."' 
			WHERE organization = '".$organization."' AND (TRIM(po_number) = '" . trim($po_number)."') AND po_number <> '' AND po_number IS NOT NULL";
			echo $query."<br><br>";
			$result = mysqli_query($link,$query );	
		}
		if(isset($jobs['results'][$x]['custom_fields']['openorclosed']) && $jobs['results'][$x]['custom_fields']['openorclosed'] == "Closed"){
			// $openorclosed = 1;
			$query = "UPDATE ic_matches set closed = 1 WHERE job = '" . $jobs['results'][$x]['id'] . "'";
			echo $query."<br><br>";
			$result = mysqli_query($link,$query );
			
			// update closed date if closed
			$query = "UPDATE ic_matches set closed_date = NOW() WHERE closed = 1 AND closed_date = '0000-00-00' AND job = '" . $jobs['results'][$x]['id'] . "'";
			echo $query."<br><br>";
			$result = mysqli_query($link,$query );
		} 
		if(isset($jobs['results'][$x]['custom_fields']['openorclosed']) && $jobs['results'][$x]['custom_fields']['openorclosed'] == "Open"){
			// $openorclosed = 1;
			$query = "UPDATE ic_matches set closed = 0 WHERE job = '" . $jobs['results'][$x]['id'] . "'";
			echo $query."<br><br>";
			$result = mysqli_query($link,$query );
			
			// update closed date if open
			$query = "UPDATE ic_matches set closed_date = '0000-00-00' AND job = '" . $jobs['results'][$x]['id'] . "'";
			echo $query."<br><br>";
			$result = mysqli_query($link,$query );
		} 

	}


// Un-Share Candidates Dropped by recruiter (should probably delete them in the future)
// everything updated within a certain time

$page_num = 1;
$count = 100;

$client = new \GuzzleHttp\Client();

	$response = $client->request('GET', 'https://api.manatal.com/open/v3/matches/?dropped_at__gte='.$after.'&page='. $page_num .'&page_size=100', [
	'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
	],
	]);


	$responseStr = $response->getBody();
	$drops = json_decode($responseStr, true);
	
	echo "<br>drop count= ". $drops['count']."<BR>";
	
	// keep track of count to see if we should add a page
	echo "<br>next= ".$drops['next']."<br>"; 
	If (is_null($drops['next'])) {
			echo "<br>next= ".$drops['next']; 
	}

 	echo "<br> Page Count = ";
	echo $page_count = count($drops['results']);
	echo "<br>";
	
	// position_name
	for($x=0; $x<count($drops['results']); $x++) {
		$query = "UPDATE ic_matches set 
			is_active = 0, share = 0 WHERE id = '" . $drops['results'][$x]['id'] . "'";
		echo $query."<br><br>";
		$result = mysqli_query($link,$query );
	}

$datetime_2 = date("Y-m-d H:i:s"); 
 
 
$from_time = strtotime($datetime_1); 
$to_time = strtotime($datetime_2); 
echo "<P>";
echo $diff_minutes = round(abs($from_time - $to_time) / 60,2). " minutes";
Echo "<br>Done";

?>