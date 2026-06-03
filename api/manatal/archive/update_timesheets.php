<?php
$link = mysqli_connect('localhost', 're0nm8', '50h8r6WNvB!ozVY2','ck3b2t') or die("Error: " . mysqli_error());

// $last_name = "Chinchilla";


$query = "
SELECT 
    wt.Unique_id as wt_Unique_id,
    wt.billrate as wt_billrate,
    wt.payrate as wt_payrate,
    wt.BillingProfile as wt_BillingProfile,
    wt.WeekEnding as wt_WeekEnding,
    wt.SentDate as wt_SentDate,
    wt.ApproveDate as wt_ApproveDate,
    wt.DeclineDate as wt_DeclineDate,
    wt.PERCdate as wt_PERCdate,
    wt.first_time as wt_first_time,
    wt.CustomerIpAddr as wt_CustomerIpAddr,
    wt.EmployeeIpAddr as wt_EmployeeIpAddr,
    wt.Signature as wt_Signature,
    wt.Primary_Contact_Email as wt_Primary_Contact_Email,
    wt.Second_Contact_Email as wt_Second_Contact_Email,
    wt.Continuing as wt_Continuing,
    wt.Hours as wt_Hours,
    wt.ExportDate as wt_ExportDate,
    wt.Branch_ID as wt_Branch_ID,
    wt.Reminders as wt_Reminders,
    wt.EmpEmail as wt_EmpEmail,
    wt.SuperEmail as wt_SuperEmail,
    wt.AcctEmail as wt_AcctEmail,
    wt.LastEdit as wt_LastEdit,
    wt.TimeInHr1 as wt_TimeInHr1,
    wt.TimeOutHr1 as wt_TimeOutHr1,
    wt.Break1 as wt_Break1,
    wt.TimeInHr2 as wt_TimeInHr2,
    wt.TimeOutHr2 as wt_TimeOutHr2,
    wt.Break2 as wt_Break2,
    wt.TimeInHr3 as wt_TimeInHr3,
    wt.TimeOutHr3 as wt_TimeOutHr3,
    wt.Break3 as wt_Break3,
    wt.TimeInHr4 as wt_TimeInHr4,
    wt.TimeOutHr4 as wt_TimeOutHr4,
    wt.Break4 as wt_Break4,
    wt.TimeInHr5 as wt_TimeInHr5,
    wt.TimeOutHr5 as wt_TimeOutHr5,
    wt.Break5 as wt_Break5,
    wt.TimeInHr6 as wt_TimeInHr6,
    wt.TimeOutHr6 as wt_TimeOutHr6,
    wt.Break6 as wt_Break6,
    wt.TimeInHr7 as wt_TimeInHr7,
    wt.TimeOutHr7 as wt_TimeOutHr7,
    wt.Break7 as wt_Break7  
	FROM ic_webtime wt
right JOIN ic_timesheets ts ON wt.Unique_id = ts.Unique_id

;";

echo $query;

$result = mysqli_query($link,$query);

$result = mysqli_query($link, $query);
if (!$result) {
    die('Query Error: ' . mysqli_error($link));
}



$count = 0;

while ($row = mysqli_fetch_array($result)) {
	
	// Find match
	
$query2 = "UPDATE ic_timesheets SET 
    billrate = " . $row['wt_billrate'] . ", 
    payrate = " . $row['wt_payrate'] . ", 
    BillingProfile = '" . $row['wt_BillingProfile'] . "', 
    WeekEnding = '" . $row['wt_WeekEnding'] . "', 
    SentDate = '" . $row['wt_SentDate'] . "', 
    ApproveDate = '" . $row['wt_ApproveDate'] . "', 
    DeclineDate = '" . $row['wt_DeclineDate'] . "', 
    PERCdate = '" . $row['wt_PERCdate'] . "', 
    first_time = '" . $row['wt_first_time'] . "', 
    CustomerIpAddr = '" . $row['wt_CustomerIpAddr'] . "', 
    EmployeeIpAddr = '" . $row['wt_EmployeeIpAddr'] . "', 
    Signature = '" . $row['wt_Signature'] . "', 
    Primary_Contact_Email = '" . $row['wt_Primary_Contact_Email'] . "', 
    Second_Contact_Email = '" . $row['wt_Second_Contact_Email'] . "', 
    Continuing = '" . $row['wt_Continuing'] . "', 
    Hours = " . $row['wt_Hours'] . ", 
    ExportDate = '" . $row['wt_ExportDate'] . "', 
    Branch_ID = '" . $row['wt_Branch_ID'] . "', 
    Reminders = '" . $row['wt_Reminders'] . "', 
    EmpEmail = '" . $row['wt_EmpEmail'] . "', 
    SuperEmail = '" . $row['wt_SuperEmail'] . "', 
    AcctEmail = '" . $row['wt_AcctEmail'] . "', 
    LastEdit = " . $row['wt_LastEdit'] . "', 
    TimeInHr1 = " . $row['wt_TimeInHr1'] . "', 
    TimeOutHr1 = " . $row['wt_TimeOutHr1'] . ", 
    Break1 = " . $row['wt_Break1'] . ", 
    TimeInHr2 = " . $row['wt_TimeInHr2'] . ", 
    TimeOutHr2 = " . $row['wt_TimeOutHr2'] . ", 
    Break2 = " . $row['wt_Break2'] . ", 
    TimeInHr3 = " . $row['wt_TimeInHr3'] . ", 
    TimeOutHr3 = " . $row['wt_TimeOutHr3'] . ", 
    Break3 = " . $row['wt_Break3'] . ", 
    TimeInHr4 = " . $row['wt_TimeInHr4'] . ", 
    TimeOutHr4 = " . $row['wt_TimeOutHr4'] . ", 
    Break4 = " . $row['wt_Break4'] . ", 
    TimeInHr5 = " . $row['wt_TimeInHr5'] . ", 
    TimeOutHr5 = " . $row['wt_TimeOutHr5'] . ", 
    Break5 = " . $row['wt_Break5'] . ", 
    TimeInHr6 = " . $row['wt_TimeInHr6'] . ", 
    TimeOutHr6 = " . $row['wt_TimeOutHr6'] . ", 
    Break6 = " . $row['wt_Break6'] . ", 
    TimeInHr7 = " . $row['wt_TimeInHr7'] . ", 
    TimeOutHr7 = " . $row['wt_TimeOutHr7'] . ", 
    Break7 = " . $row['wt_Break7'] . "
	 WHERE Unique_id = ". $row['wt_Unique_id'] . "
	;";

	echo $query2;
		// echo "Email = " . $row['wt_candidate_email']."<br>";
	
		$result2 = mysqli_query($link,$query2);	
		$count = $count+1;
	
	Echo "<br>" .$count." ".$row['wt_WeekEnding']."<br>";
	
}
		

	?>
		

