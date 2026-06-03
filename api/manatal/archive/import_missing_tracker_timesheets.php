<?php
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

// $last_name = "Chinchilla";


$query = "
SELECT 
		wte.first_name,
		wte.last_name,
		wte.Batch_ID,
		wte.Unique_id,
		wte.title,
		wte.company_name,
		ts.company_id,
		wte.billrate,
		wte.payrate,
		ts.Employee_ID,
		wte.Email,
		ts.Assignment_ID,
		ts.AssignmentNumber,
		wte.BillingProfile,
		wte.WeekEnding,
		wte.SentDate,
		wte.ApproveDate,
		wte.DeclineDate,
		wte.PERCdate,
		wte.CustomerIpAddr,
		wte.EmployeeIpAddr,
		wte.Signature,
		wte.Primary_Contact_Email,
		wte.Second_Contact_Email,
		wte.Continuing,
		wte.Hours,
		wte.ExportDate,
		wte.Branch_ID,
		wte.Reminders,
		wte.EmpEmail,
		wte.SuperEmail,
		wte.AcctEmail,
		wte.LastEdit,
		wte.TimeInHr1,
		wte.TimeOutHr1,
		wte.Break1,
		wte.TimeInHr2,
		wte.TimeOutHr2,
		wte.Break2,
		wte.TimeInHr3,
		wte.TimeOutHr3,
		wte.Break3,
		wte.TimeInHr4,
		wte.TimeOutHr4,
		wte.Break4,
		wte.TimeInHr5,
		wte.TimeOutHr5,
		wte.Break5,
		wte.TimeInHr6,
		wte.TimeOutHr6,
		wte.Break6,
		wte.TimeInHr7,
		wte.TimeOutHr7,
		wte.Break7,
		ts.invoice_number,
		ts.invoice_date,
		ts.paid_date,
		ts.invoice_amount,
		ts.po_number,
		ts.invoice_export,
		ts.paid_amount

FROM ic_webtime_exp wte
JOIN ic_timesheets ts ON 
ts.first_name = wte.first_name and 
ts.last_name = wte.last_name and 
ts.title = wte.title and
ts.company_name = wte.company_name";
echo $query;
echo "<p>";
$result = mysqli_query($link,$query);
$count = 0;
$invnum = 999;
while ($row = mysqli_fetch_array($result)) {
	$invnum = $invnum + 1;
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
		'0', 
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
		'" . $row['Primary_Contact_Email'] . "', 
		'" . $row['Second_Contact_Email'] . "' , 
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
		" . $invnum. ",  
		'2000-01-01',  	
		'2000-01-01', 
		" . $row['Hours'] * $row['payrate']. ",  
		'" . $row['po_number']. "',  
		'" . $row['invoice_export']. "', 
		1)" ;

		// echo $row['candidate_email']."<br>";
		$query2 = str_replace("''", "NULL", $query2);
	// exit();	
	$result2 = mysqli_query($link,$query2);	
		$count = $count+1;
	
	Echo "<br>" .$count." ".$row['WeekEnding']."<br>";
	
}
		

	?>
		

