<?php

$datetime_1 = date("Y-m-d H:i:s");
ini_set('max_execution_time', 10000);
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());


require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
$client = new \GuzzleHttp\Client();

		
		$query2 = "SELECT * from ic_matches";

		$SQL2 = mysqli_query($link,$query2 );
		$row2 = mysqli_fetch_array($SQL2);
		echo "company Name = ".$row2['first_name'];


		while ($row2 = mysqli_fetch_array($SQL2)) {
			
			// Job info

			$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/'.$row2['job'].'/', [
			'headers' => [
			'Authorization' => $token,
			'accept' => 'application/json',
			],
		]);

		$response->getBody();

		$responseStr = $response->getBody();
		$job = json_decode($responseStr, true);
		// echo $hash = $job['hash'];


				$strSQL = "UPDATE ic_matches SET hash = '". $job['hash'] . "' 
				where id = '". $row2['id']."' ";

				echo $strSQL ;
				$resMySel = mysqli_query($link,$strSQL);
				
			// End Insert

		}
$datetime_2 = date("Y-m-d H:i:s"); 
 
$from_time = strtotime($datetime_1); 
$to_time = strtotime($datetime_2); 
echo "<P>";
echo $diff_minutes = round(abs($from_time - $to_time) / 60,2). " minutes";
Echo "<br>Done";

?>