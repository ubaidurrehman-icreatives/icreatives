<?php




// MySQL Connect
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

//MS SQL Connect
$connection_string = 'DRIVER={ODBC Driver 17 for SQL Server};SERVER=5de1f42.online-server.cloud,1433;DATABASE=EMPACT_001_PROD_PDI';

$user = 'sa';
$pass = 'ic3eempact!';

$conn = odbc_connect($connection_string, $user, $pass );

if (!$conn) {
    die("MSSQL Connection failed: " . odbc_errors());
}

echo $msquery = "select * from AR_INVOICE_DETAIL 
FULL OUTER JOIN AR_PAYMENT ON SUBSTRING(AR_INVOICE_DETAIL.DocumentKey, 4, LEN(AR_INVOICE_DETAIL.DocumentKey)) = SUBSTRING(AR_PAYMENT.document_id, 4, LEN(AR_PAYMENT.document_id)) 
FULL OUTER JOIN EmployeeMaster ON AR_INVOICE_DETAIL.EmployeeKey = EmployeeMaster.Employee_ID 
FULL OUTER JOIN AR_INVOICE ON AR_INVOICE_DETAIL.DocumentKey = AR_INVOICE.DocumentKey 
where EarningsCode = 'OTHNON' and AR_INVOICE_DETAIL.VoidDate IS NULL ";

$result = odbc_exec($conn,$msquery);



// $result = mysqli_query($link,$query);
// $count = 0;

while ($row = odbc_fetch_array($result)) {

	
	// create unique id
	// Create a DateTime object from the MSSQL timestamp
	$mssqlDateTime = new DateTime($row['create_timestamp']);
	// Format the DateTime object as 'YYYYMMDDHHMM'
	$formattedDateTime = $mssqlDateTime->format('YmdHi');
	
	$MyNewRandomNum = $formattedDateTime. intval(rand()) ;

	$query2 = "insert into ic_timesheets 
		(first_name,
		last_name,
		Batch_ID,
		Unique_id,
		title,
		company_name,
		company_id,
		billrate,
		payrate,
		Employee_ID,
		Email,
		Assignment_ID,
		AssignmentNumber,
		BillingProfile,
		WeekEnding,
		SentDate,
		ApproveDate,
		DeclineDate,
		PERCdate,
		CustomerIpAddr,
		EmployeeIpAddr,
		Signature,
		Primary_Contact_Email,
		Second_Contact_Email,
		Continuing,
		Hours,
		ExportDate,
		Branch_ID,
		Reminders,
		EmpEmail,
		SuperEmail,
		AcctEmail,
		LastEdit,
		invoice_number,
		invoice_date,
		paid_date,
		invoice_amount,
		po_number,
		invoice_type,
		invoice_export,
		paid_amount
		) VALUES (
		'" . addslashes($row['EMNameFirst']) . "', 
		'" . addslashes($row['EMNameLast']) . "', 
		'" . $row['BatchKey'] . "', 
		'" . $MyNewRandomNum . "', 
		'" . $row['JOBDescription'] . "', 
		'" . addslashes($row['DVName']) . "', 
		'" . $row['DivisionKey'] . "', 
		" . $row['UnitRate'] . " , 
		0, 
		'" . $row['EmployeeKey'] . "', 
		'', 
		'" . $row['AssignmentKey'] . "', 
		'" . $row['OrderKey'] . "', 
		'', 
		'" . $row['WeekEnding'] . "', 
		'" . $row['DATEACCOUNTING'] . "', 
		'" . $row['DATEACCOUNTING'] . "', 
		'0000-00-00', 
		'0000-00-00', 
		'', 
		'', 
		'', 
		'" . $Primary_Contact_Email . "', 
		'" . $Second_Contact_Email . "' , 
		0, 
		1, 		
		'" . $row['DATEACCOUNTING'] . "', 
		'" . $row['Branch_ID'] . "', 
		'', 
		'" . $row['InternetSMTPEmail'] . "', 
		'" . $row['SuperEmail']. "', 
		'" . $row['BPInternetSMTPEmail'] . "', 
		'" . $row['DATEACCOUNTING'] . "',  
		" .  intval(substr($row['DocumentKey'],4)). ",  
		'" . $row['PrintDate']. "', 
		'" . $row['document_date']. "', 
		" . $row['ARAmount']. ",  
		'" . $row['OAPurchaseOrder']. "', 
		'f', 
		'" . $row['PrintDate']. "',  		
		" . $row['ARAmount']. ")" ; 

		// echo $row['candidate_email']."<br>";
		 // echo $query2 = str_replace("''", "NULL", $query2);
		 // echo $query2;
		// exit();	
		$result2 = mysqli_query($link,$query2);	
		$count = $count+1;
	
	Echo "<br>" .$count." ".$row['DocumentKey']."<br>";
	
}
		
	?>
		

