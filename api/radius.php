
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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once  dirname(__DIR__) . '/vendor/autoload.php';
require_once  dirname(__DIR__) . '/db/token.php';
function haversine($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 3958.8; // Earth's radius in miles

    $latDelta = deg2rad($lat2 - $lat1);
    $lonDelta = deg2rad($lon2 - $lon1);

    $a = sin($latDelta / 2) * sin($latDelta / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($lonDelta / 2) * sin($lonDelta / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earthRadius * $c;
}

function getCoordinates($zipcode) {
    $url = "https://api.zippopotam.us/us/$zipcode";
    $response = file_get_contents($url);
    if ($response === FALSE) {
        return null;
    }
    $data = json_decode($response, true);
    if (isset($data['places'][0])) {
        return [
            'lat' => $data['places'][0]['latitude'],
            'lng' => $data['places'][0]['longitude']
        ];
    }
    return null;
}



$radius = $_REQUEST['radius'] ?? 0;

$job_id = $_REQUEST['id'];

?>

<div class="popup-form">
    <form action="radius.php" method="post">
        <table>
            <tr>
                <td>Id:</td>
                <td><?php echo $job_id; ?></td>
            </tr>
 <tr>
                
                <td colspan=2>Drop all applicants farther than</td>
            </tr>
            <tr>
                <td>Radius:</td>
     
                <td>
                    <select name="radius">
                        <option value="">--pick--</option>
                        <option value="50">50 miles</option>
                        <option value="50">75 miles</option>
                        <option value="50">100 miles</option>
                    </select>
                </td>
            </tr>
        </table>
		<input type="hidden" name="id" value = "<?php echo $job_id; ?>">
        <button type="submit" class="btn" name="save">Save</button>
        <button type="button" class="btn" onclick="window.close();">Cancel</button>
    </form>
<?php

// echo "xxx-".$_SERVER['REQUEST_METHOD'];



if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($radius) && $radius > 0) {

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/'.$job_id.'/', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);
$response = $response->getBody();
$job = json_decode($response, true);
if(isset($job['custom_fields']['realzipcode'])){
	$jobZip = $job['custom_fields']['realzipcode'];
} else {
	echo "Missing job Zipcode";
	exit();
}
$jobCoordinates = getCoordinates($jobZip);
    if (!$jobCoordinates) {
        die("Invalid job zip code.");
		exit();
    }

    $jobLat = $jobCoordinates['lat'];
    $jobLng = $jobCoordinates['lng'];


$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.manatal.com/open/v3/jobs/'.$job_id.'/matches/', [
  'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
  ],
]);
$response = $response->getBody();
$matches = json_decode($response, true);
$count = 0;
for($x=0; $x<count($matches['results']); $x++) {
	
	// echo $matches['results'][$x]['job_pipeline_stage']['name']."vvv";
	$match_id = $matches['results'][$x]['id'];
	if($matches['results'][$x]['job_pipeline_stage']['name'] == "New Candidates"){
			// echo "ZWZ";
		$candidate = $matches['results'][$x]['candidate'];
		$client = new \GuzzleHttp\Client();
		$response = $client->request('GET', 'https://api.manatal.com/open/v3/candidates/'.$candidate.'/', [
			'headers' => [
			'Authorization' => $token,
			'accept' => 'application/json',
			],
		]);

		$response = $response->getBody();
		$candidate_info = json_decode($response, true);
					$candidate_zipcode = $candidate_info['custom_fields']['postalcode'] ?? '';
			// echo $candidate_zipcode . " xxxx  " . $joZip;
		
		if(isset($candidate_info['custom_fields']['postalcode'])){
			$candidate_zipcode = $candidate_info['custom_fields']['postalcode'];
			

			// echo $candidate_zipcode . "   " . $joZip;
			
			 $candidateCoordinates = getCoordinates($candidate_zipcode);
            if ($candidateCoordinates) {
                $distance = haversine(
                    $jobLat,
                    $jobLng,
                    $candidateCoordinates['lat'],
                    $candidateCoordinates['lng']
                );
                if ($distance > $radius) {
                    // echo "Distance is: ".$distance." Move " .$candidate." to Drop<br>";
							// echo "XXX".$candidate_info['candidate_location'];

					$client = new \GuzzleHttp\Client();

					$response = $client->request('PATCH', 'https://api.manatal.com/open/v3/matches/'.$match_id.'/', [
					'body' => '{"stage":{"id":1572370}}',
					'headers' => [
					'Authorization' => $token,
					'accept' => 'application/json',
					'content-type' => 'application/json',
					],
				]);

				$response->getBody();
				$count = $count + 1;
			
				} else { 
				
					// echo $distance."<br>";
                }
			}
		}
	}		
			
}			
			
			
	echo $count." candidates over ".$radius. " miles away were moved to dropped";	
}	

?>
<p>
<b>Please remember to refresh the job page<b>
</div>

</body>
</html>