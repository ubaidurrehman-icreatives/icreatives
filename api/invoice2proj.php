<?php 
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
        $result = curl_exec($ch); // execute
	    // echo $result;             //show response
	curl_close($ch);
	return $result;
};
//    inv_list = "WEEK-MONTH-POID-WLMRT";

Function change_inv_template($proj_name,$inv_template) {
	
	$inv_change = true;
		
		// echo "proj_name = ". $proj_name . " inv_template = ". $inv_template . "-<br> ";
		
		if ( strpos($proj_name,'WEEK') !== false  && $inv_template !== 'WEEK' &&  $inv_template !== NULL ) {
			
			$proj_name = str_replace("WEEK", $inv_template,$proj_name, );
				
		} elseif (strpos($proj_name,'MONTH') !== false && $inv_template !== 'MONTH' &&  $inv_template !== NULL ){
			
				$proj_name = str_replace("MONTH", $inv_template,$proj_name, );
				
		} elseif (strpos($proj_name,'POID') !== false && $inv_template !== 'POID' &&  $inv_template !== NULL ){
			
				$proj_name = str_replace("POID", $inv_template,$proj_name, );
				
		} elseif (strpos($proj_name,'WLMRT') !== false && $inv_template !== 'WLMRT' &&  $inv_template !== NULL ) {
			
				$proj_name = str_replace("WLMRT", $inv_template,$proj_name, );
				
				
		} elseif ( strpos($proj_name,'WEEK') == false && strpos($proj_name,'MONTH') == false && strpos($proj_name,'POID') == false  && strpos($proj_name,'WLMRT') == false &&  $inv_template == NULL ) {
			
			$proj_name = $proj_name . " WEEK";
	
		} elseif ( strpos($proj_name,'WEEK') == false && strpos($proj_name,'MONTH') == false && strpos($proj_name,'POID') == false  && strpos($proj_name,'WLMRT') == false &&  $inv_template !== NULL ) {
			
			$proj_name = $proj_name . " " . $inv_template;
								
		
		} elseif ($inv_template !== NULL ){
			if (strpos($proj_name,$inv_template) !== false) {
					// echo "match";
					$inv_change = false;
			}
			
		} else	{	
				
			// $proj_name = $proj_name . ' ' . $inv_template;
			$inv_change = false;
		}
		// echo "proj_name= " .$proj_name. "<br>";
		return array($proj_name,$inv_change) ;		
}

$url = 'https://api.tracker-rms.com/WebAPI/XmlHttpLogonUser.aspx';
$xmldata = '<?xml version="1.0"?>
				<TrackerRMS>
					<WebAPI>
						<LogonUser>
							<UserCredentials>
								<Username>stevenc@icreatives.com</Username>
								<Password>Agile1Soft!</Password>
							</UserCredentials>
						</LogonUser>
					</WebAPI>
				</TrackerRMS>';


$result = sendcurl($xmldata,$url);

// echo $xmldata;

$keypos = strpos($result,"<SecurityToken>");

$key = SubStr($result,$keypos+15,64);

Function inv_code_from_drop($drop) {
// echo "Drop = " . $drop . "<br>";
if (strpos($drop,"WEEK") !== false ) {
			$inv_code = "WEEK";
}elseif (strpos($drop,"MONTH") !== false ) {
			$inv_code = "MONTH";
}elseif (strpos($drop,"POID") !== false ) {
			$inv_code = "POID";
}elseif (strpos($drop,"WLMRT") !== false ) {
			$inv_code = "WLMRT";
}else{ $inv_code = NULL; }

return $inv_code;
}

$url = 'https://evoapi.tracker-rms.com/api/widget/getRecords';
$jsondata = '{
	"trackerrms": {
		"getRecords": {
			"credentials": {
				"username": "stevenc@icreatives.com",
				"password": "Agile1Soft!",
				"oauthtoken": "",
				"apikey": "NAYpepDHctXB4atSW7Mp"
			},
                     "instructions": {
                           "recordtype": "P",
                           "recordid": "",
                           "state": "active",
						   "publishedlocation": "",
                           "searchtext": "",
                           "onlymyrecords": false,
                           "numrecords": 10,
                           "pagenum": 0,
                           "sortfield": "lastupdateddatetime",
                           "sortdir": "desc",
                           "updatedbefore": "",
                           "updatedafter": "",
						   "includecustomfields": true
                     }
              }
       }
}';
	
// echo $url;s
// echo $jsondata;

$json_result = sendcurl($jsondata,$url);
$jobs = json_decode($json_result,true);

// echo $json_result;



// Check if each job matches both region and title
foreach ($jobs["results"] as $result) {
	// echo "<br>";
	// echo $result["id"] . "<br>";
	// echo $result["name"] . "<br>";
	// echo "pro_inv = " . $result["customfields"][0]["value"] . "<br>";
	$inv_change = false;
	$pro_name = $result["name"] ;
	// print "Pro_Name= " . $pro_name . "<br>";
	$pro_id = $result["id"];
	// $pro_code = inv_code_from_drop($result["customfields"] [0]["value"]);
	
	$client_id = $result["details"]["clientid"];
	
	// Start Finding pro_code
	// echo "Client ID = " . $client_id . "<br>";
	
	$url = 'https://evoapi.tracker-rms.com/api/widget/getRecords';
	$jsondata = '{
	"trackerrms": {
		"getRecords": {
			"credentials": {
				"username": "stevenc@icreatives.com",
				"password": "Agile1Soft!",
				"oauthtoken": "",
				"apikey": "NAYpepDHctXB4atSW7Mp"
			},
                     "instructions": {
                           "recordtype": "C",
                           "recordid": "' . $client_id . '",
                           "state": "active",
						   "publishedlocation": "",
                           "searchtext": "",
                           "onlymyrecords": false,
                           "numrecords": 1,
                           "pagenum": 0,
                           "sortfield": "lastupdateddatetime",
                           "sortdir": "desc",
                           "updatedbefore": "",
                           "updatedafter": "",
						   "includecustomfields": true
                     }
              }
       }
}';
	
	// echo $jsondata;

	$json_result = sendcurl($jsondata,$url);


	// echo $json_result ."<br>";


	$company = json_decode($json_result,true);


	// $pro_code = inv_code_from_drop($result["customfields"] [184]["value"]);
	$fields = $company["results"][0]["customfields"];

	$NoMultiple = "No";
	$pro_code = "";
	foreach($fields as $field) {
		if($field["id"] == 184) {
			if($field["value"]=="Yes"){ $NoMultiple = "Yes";}
		}
		if($field["id"] == 185) {
			$pro_code = $field["value"];
		}
	}

	$pro_code = inv_code_from_drop($pro_code);
	// echo "pro_code = " . inv_code_from_drop($pro_code);
	

	// end finding pro_code
	

	// print "pro_code = " .  $pro_code . "<br>";
	
	// Start Checking & replacing customer fields for change in invoice template
	
	list($new_pro_name,$inv_change) = change_inv_template($pro_name,$pro_code);
	$new_pro_name = substr($new_pro_name,6);
	$new_pro_name = str_replace("&", "+",$new_pro_name );
	
	if ($NoMultiple == "Yes" && strpos($new_pro_name,'1PER') == false ) {
	    $new_pro_name = $new_pro_name . " 1PER";
		$inv_change = true;

	} else if ($NoMultiple !== "Yes" && strpos($new_pro_name,'1PER') !== false ) {
		$new_pro_name = str_replace(" 1PER", "", $new_pro_name );
		$inv_change = true;

	}

	
	
	// echo "New Name= " . $new_pro_name . "<br>";	
	
	$pro_id = $result["id"];
	
	if ($inv_change === true) {
		echo "<br>";
		echo "New Name= " . $new_pro_name . "<br>";
		echo "inv_change = " .	$inv_change ."<BR>";
	
		$url = 'https://api.tracker-rms.com/WebAPI/XmlHttpUpdateRecord.aspx';
		$xmldata = '<?xml version="1.0"?>
			<TrackerRMS> 
				<WebAPI> 
					<UpdateRecord> 
						<UserCredentials>
							<SecurityToken>' . $key. '</SecurityToken>
							<Username>stevenc@icreatives.com</Username>
							<Password>Agile1Soft!</Password>
						</UserCredentials>
						<RecordType>Projects</RecordType> 
						<RecordId>' . $pro_id . '</RecordId> 
						<UpdateFields> 
							<UpdateField name="projectname">' . $new_pro_name . '</UpdateField> 
						</UpdateFields> 
					</UpdateRecord>
				</WebAPI> 
			</TrackerRMS>';
		
		
		// echo $url ;
//		echo  htmlentities($xmldata);


$result = sendcurl($xmldata,$url);
// echo $result . "<br>";
		
	}

	
	// end invoice template change
	
	
	}
echo "Done updating"		;
?>
