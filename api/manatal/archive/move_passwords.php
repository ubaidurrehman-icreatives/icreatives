<?php
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2', 'ck3b2t') or die("Error: " . mysqli_error());

require "/var/www/vhosts/icreatives.com/httpdocs/portal/random_compat/vendor/autoload.php";
Function sendcurl($jsondata,$url) {
	    $ch = curl_init();
        if (!$ch) {
            die("Couldn't initialize a cURL handle");
        }
	    curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);
        curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
        $t_result = curl_exec($ch); // execute
	    // echo $result;             //show response
	curl_close($ch);
	return $t_result;
};

$query = "
SELECT
    wt.Employee_ID AS wt_emp_id, m.candidate_name as m_full_name,
    MAX(ts.Employee_ID) AS ts_emp_id,
    CONCAT(COALESCE(MAX(ts.first_name), ''), ' ', COALESCE(MAX(ts.last_name), '')) AS ts_full_name,
    MAX(m.job_name) AS job_name
FROM ic_webtime wt
LEFT JOIN ic_timesheets ts ON wt.EmpEmail = ts.Email
LEFT JOIN ic_matches m ON ts.Employee_ID = m.candidate
WHERE ts.Employee_ID IS NOT NULL
GROUP BY wt.EmpEmail;
";

// echo $query;

$result = mysqli_query($link,$query);	
$count = 0;
while ($row = mysqli_fetch_array($result)) {
	$url = 'https://evoapi.tracker-rms.com/api/widget/getRecords';
	$jsondata = '{
	"trackerrms": {
		"getRecords": {
			"credentials": {
				"username": "stevenc@icreatives.com",
				"password": "Agile1Soft!"
			},
                     "instructions": {
                           "recordtype": "R",
                           "recordid": '.$row["wt_emp_id"].',
                           "state": "",
						   "publishedlocation": "",
                           "searchtext": "steven.mccohen@blindemail.com",
                           "onlymyrecords": false,
                           "numrecords": 5,
                           "pagenum": 0,
                           "sortfield": "lastupdateddatetime",
                           "sortdir": "desc",
                           "updatedbefore": "2024-01-01 12:00:00",
                           "updatedafter": "2021-01-01 12:00:00",
						   "includecustomfields": true
                     }
              }
       }
	}';
	
	// echo $url;
	// echo $jsondata;

	$result2 = sendcurl($jsondata,$url);

	// echo "Tracker Password = ";

	$custom_arr = json_decode($result2,true);

	$password = $custom_arr['results'][0]['customfields'][67]['value'];
	echo $m_full_name = $row['m_full_name'];

	// Now update Manatal Candidates
	echo $row['ts_full_name']."    ".$row["wt_emp_id"]."    ".$row["ts_emp_id"]."    ".$password."<br>";


	$client = new \GuzzleHttp\Client();
	$response = $client->request('PATCH', 'https://api.manatal.com/open/v3/candidates/'.$row["ts_emp_id"], [
	'body' => '{"custom_fields":{"password":"'.$password.'"},"full_name":"'.$m_full_name.'"}',
	'headers' => [
    'Authorization' => $token,
    'accept' => 'application/json',
    'content-type' => 'application/json',
	],
	]);

echo	$response->getBody();

}
Echo "DONE";
?>
