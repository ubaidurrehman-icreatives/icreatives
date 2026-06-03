<?php
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

// $last_name = "Chinchilla";


$query = "
SELECT *
FROM ic_eempact_timesheets";
echo $query;

$result = mysqli_query($link,$query);
$count = 0;

while ($row = mysqli_fetch_array($result)) {
	
	// Find match
	list($Primary_Contact_Email, $Second_Contact_Email) = explode(",",$row['SuperEmail']);
	
	echo $Primary_Contact_Email;

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
		TimeInHr1,
		TimeOutHr1,
		Break1,
		TimeInHr2,
		TimeOutHr2,
		Break2,
		TimeInHr3,
		TimeOutHr3,
		Break3,
		TimeInHr4,
		TimeOutHr4,
		Break4,
		TimeInHr5,
		TimeOutHr5,
		Break5,
		TimeInHr6,
		TimeOutHr6,
		Break6,
		TimeInHr7,
		TimeOutHr7,
		Break7,
		invoice_number,
		invoice_date,
		paid_date,
		invoice_amount,
		po_number,
		invoice_export,
		paid_amount
		) VALUES (
		'" . $row['first_name'] . "', 
		'" . $row['last_name'] . "', 
		'" . $row['Batch_ID'] . "', 
		'" . $row['Unique_id'] . "', 
		'" . $row['title'] . "', 
		'" . addslashes($row['company_name']) . "', 
		'" . $row['company_id'] . "', 
		" . $row['billrate'] . " , 
		" . $row['payrate'] . " , 
		'" . $row['Employee_ID'] . "', 
		'" . $row['EmpEmail'] . "', 
		'" . $row['Assignment_ID'] . "', 
		'" . $row['AssignmentNumber'] . "', 
		'" . $row['BillingProfile'] . "', 
		'" . $row['WeekEnding'] . "', 
		'" . $row['SentDate'] . "', 
		'" . $row['ApproveDate'] . "', 
		'" . $row['DeclineDate'] . "', 
		'" . $row['PERCdate'] . "', 
		'" . $row['CustomerIpAddr'] . "', 
		'" . $row['EmployeeIpAddr'] . "', 
		'" . $row['Signature'] . "', 
		'" . $Primary_Contact_Email . "', 
		'" . $Second_Contact_Email . "' , 
		" . $row['Continuing'] . " , 
		" . $row['Hours'] . " , 		
		'" . $row['ExportDate'] . "', 
		'" . $row['Branch_ID'] . "', 
		'" . $row['Reminders'] . "', 
		'" . $row['EmpEmail'] . "', 
		'" . $row['SuperEmail']. "', 
		'" . $row['AcctEmail'] . "', 
		'" . $row['LastEdit'] . "', 
		" . $row['TimeInHr1'] . " ,
		" . $row['TimeOutHr1'] . " ,
		" . $row['Break1'] . " ,
		" . $row['TimeInHr2'] . " ,
		" . $row['TimeOutHr2']. " ,
		" . $row['Break2'] . " ,
		" . $row['TimeInHr3'] . " ,
		" . $row['TimeOutHr3'] . " ,
		" . $row['Break3'] . " ,
		" . $row['TimeInHr4'] . " ,
		" . $row['TimeOutHr4'] . " ,
		" . $row['Break4'] . " ,
		" . $row['TimeInHr5'] . " ,
		" . $row['TimeOutHr5'] . " ,
		" . $row['Break5'] . " ,
		" . $row['TimeInHr6'] . " ,
		" . $row['TimeOutHr6'] . " ,
		" . $row['Break6'] . " ,
		" . $row['TimeInHr7'] . " ,
		" . $row['TimeOutHr7'] . " ,
		" . $row['Break7']. ", 
		" . $row['invoice_number']. ",  
		'" . $row['invoice_date']. "',  	
		'" . $row['paid_date']. "', 
		" . $row['invoice_amount']. ",  
		'" . $row['po_number']. "',  
		'" . $row['invoice_export']. "', 
		" . $row['paid_amount']. ")" ;

		// echo $row['candidate_email']."<br>";
		 echo $query2 = str_replace("''", "NULL", $query2);
	// exit();	
	$result2 = mysqli_query($link,$query2);	
		$count = $count+1;
	
	Echo "<br>" .$count." ".$row['WeekEnding']."<br>";
	
}
		

	?>
		

